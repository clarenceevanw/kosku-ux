# KosKu AI Integration Plan

---

## ✅ Plan 1: KosBot Chatbot

### Overview
KosBot adalah asisten AI berbasis Gemini yang dapat melakukan percakapan **multi-turn** dan mencari data kos **dari database lokal KosKu secara real-time** menggunakan fitur **Tool Calling** bawaan Laravel 13. AI tidak sekadar menjawab generik — ia dapat memanggil fungsi pencarian database dan mengembalikan hasil nyata.

### Tech Stack
- **AI Provider:** Google Gemini (`gemini-2.0-flash`) via **`laravel/ai`** (First-party Laravel 13 SDK)
- **Agent System:** Native `php artisan make:agent KosBot` — bukan Prism PHP
- **Memory:** Native — percakapan disimpan otomatis ke database (`agent_conversations` table) via `RemembersConversations` trait
- **Frontend:** Blade + Vanilla JS (`fetch` API, polling atau SSE)
- **Pattern:** Clean Architecture — `KosBotController` → `KosBotAgent` → Gemini

> **Kenapa tidak pakai Prism PHP?**
> Laravel 13 sudah memiliki official first-party AI SDK (`laravel/ai`) yang built-in support untuk Gemini, menyediakan agent system yang jauh lebih terintegrasi dengan framework (native memory, testing fake, queue support), sehingga Prism PHP tidak diperlukan.

---

### Step 1: Install `laravel/ai`

```bash
composer require laravel/ai
php artisan vendor:publish --provider="Laravel\Ai\AiServiceProvider"
php artisan migrate
```

Tambahkan ke `.env`:
```env
GEMINI_API_KEY=your-gemini-api-key
```

Konfigurasi di `config/ai.php`:
```php
'default' => 'gemini',

'providers' => [
    'gemini' => [
        'driver' => 'gemini',
        'key'    => env('GEMINI_API_KEY'),
    ],
],
```

---

### Step 2: Buat KosBot Agent

```bash
php artisan make:agent KosBotAgent
```

File yang dibuat: `app/Ai/Agents/KosBotAgent.php`

Agent ini bertanggung jawab atas:
1. **System Instructions** — kepribadian dan batasan KosBot.
2. **Tools** — fungsi-fungsi PHP yang bisa dipanggil Gemini dari database.
3. **Memory** — via `RemembersConversations` trait, history tersimpan otomatis di DB.

**Tools yang akan diimplementasi (sebagai PHP class):**

| Tool Class | Deskripsi | Input |
|---|---|---|
| `SearchBoardingHouseTool` | Cari kos by keyword & kota | `query: string`, `city?: string` |
| `FilterByBudgetTool` | Filter kos by rentang harga | `min_price?: int`, `max_price?: int`, `city?: string` |
| `GetHouseDetailsTool` | Detail kos by ID | `boarding_house_id: string` |

---

### Step 3: Buat KosBotController (`app/Http/Controllers/KosBotController.php`)

Skinny controller — hanya menerima request dan memanggil agent.

**Endpoint:**
- `POST /api/bot/chat` — Menerima `{ message: string, conversation_id?: string }`.
- Mengembalikan JSON: `{ reply: string, results?: array, conversation_id: string }`.

> **Perbedaan vs Prism:** Tidak perlu kirim `history[]` dari frontend karena memory dikelola di sisi server oleh agent menggunakan `conversation_id`.

---

### Step 4: Daftarkan Route di `routes/api.php`

```php
// KosBot API (public — no auth required)
Route::post('/bot/chat', [KosBotController::class, 'chat'])->name('api.bot.chat');
```

---

### Step 5: Update Frontend `kosbot.blade.php`

Ganti fungsi `sendMessage()` menjadi AJAX call ke endpoint baru.

**Alur frontend (lebih sederhana vs Prism):**
1. User mengirim pesan → append user bubble ke DOM.
2. Tampilkan typing indicator.
3. `fetch('POST /api/bot/chat', { message, conversation_id })`.
4. Simpan `conversation_id` yang dikembalikan server di `localStorage`.
5. Saat response diterima → render reply bubble.
6. Jika `results[]` ada di response → render kartu kos di bawah bubble.

---

### Step 6: System Instructions KosBotAgent

```
Kamu adalah KosBot, asisten AI dari platform KosKu — marketplace kos terpercaya di Indonesia.
Tugasmu adalah membantu pengguna menemukan kos yang cocok dengan kebutuhan mereka.

Aturan:
- Selalu berbahasa Indonesia yang ramah dan santai.
- Jika pengguna menyebutkan lokasi, budget, atau fasilitas, WAJIB gunakan tool untuk mencari dari database.
- JANGAN memberikan rekomendasi kos yang tidak ada di database KosKu.
- Jika tidak ada hasil cocok, informasikan dengan sopan dan tawarkan alternatif pencarian.
- Jawaban teks maksimal 2-3 kalimat, ringkas dan to the point.
- Saat menampilkan kos, cukup sebutkan nama dan harga. Detail akan ditampilkan oleh UI.
```

---

### File-File yang Perlu Dibuat/Dimodifikasi

| File | Aksi |
|---|---|
| `app/Ai/Agents/KosBotAgent.php` | **Buat** via `php artisan make:agent` |
| `app/Ai/Tools/SearchBoardingHouseTool.php` | **Buat baru** — Tool pencarian |
| `app/Ai/Tools/FilterByBudgetTool.php` | **Buat baru** — Tool filter harga |
| `app/Ai/Tools/GetHouseDetailsTool.php` | **Buat baru** — Tool detail kos |
| `app/Http/Controllers/KosBotController.php` | **Buat baru** — Skinny controller |
| `app/Http/Requests/KosBotChatRequest.php` | **Buat baru** — Validasi input |
| `routes/api.php` | **Modifikasi** — Tambah route `/bot/chat` |
| `resources/views/kosbot.blade.php` | **Modifikasi** — Update JS `sendMessage()` |
| `.env` | **Modifikasi** — Tambah `GEMINI_API_KEY` |
| `config/ai.php` | **Buat** via artisan publish |

---

## 2. AI Price Checker (Python Machine Learning Model)
- **Objective:** Allow users to validate if a boarding house price is reasonable based on market data.
- **Status:** Beta (Currently restricted to Surabaya).
- **Tech Stack:** 
  - Backend API: Python (FastAPI or Flask) hosting the trained ML model (e.g., Random Forest or XGBoost).
  - Client: Laravel `Http` facade to consume the Python API.
- **Expected Inputs (Features):**
  - Lokasi/Kecamatan (Surabaya area)
  - Luas Kamar (m2)
  - Fasilitas (AC, WiFi, Kamar Mandi Dalam, Kasur, Lemari, dll)
  - Harga yang ditawarkan (Untuk dibandingkan)
- **Implementation Steps:**
  1. Build the Python API endpoint (`POST /predict`).
  2. Create a form in Laravel (`kosbot.blade.php`) specifically for the Price Checker.
  3. Send data to Laravel controller, which forwards it to the Python API.
  4. Compare the user's input price with the model's predicted price range.
  5. Return an assessment (e.g., "Harga Sangat Wajar", "Terlalu Mahal", "Di Bawah Harga Pasar").
