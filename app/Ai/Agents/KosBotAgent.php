<?php

namespace App\Ai\Agents;

use App\Ai\Tools\FilterByBudgetTool;
use App\Ai\Tools\GetHouseDetailsTool;
use App\Ai\Tools\SearchBoardingHouseTool;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

/**
 * KosBotAgent
 *
 * Asisten AI KosKu yang membantu pengguna menemukan kos impian.
 * Menggunakan Gemini (gemini-2.0-flash) sebagai provider.
 *
 * Features:
 * - Multi-turn conversation via RemembersConversations (history tersimpan di DB).
 * - Tool Calling: dapat mencari kos dari database secara real-time.
 * - Bisa digunakan oleh guest (tanpa login) dengan session-based conversation ID.
 */
class KosBotAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    /**
     * System instructions — kepribadian dan batasan KosBot.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Kamu adalah KosBot, asisten AI ramah dari platform KosKu — marketplace kos terpercaya di Indonesia.
Tugasmu adalah membantu pengguna menemukan kos yang cocok dengan kebutuhan mereka.

ATURAN WAJIB:
1. Selalu berkomunikasi dalam Bahasa Indonesia yang santai, ramah, dan natural.
2. Jika pengguna menyebutkan lokasi, kota, budget, atau fasilitas — WAJIB gunakan tool yang tersedia untuk mencari dari database KosKu.
3. DILARANG memberikan rekomendasi kos yang tidak ada di database (jangan mengarang nama kos).
4. Jika tidak ada hasil yang cocok, beritahu dengan sopan dan tawarkan alternatif pencarian yang berbeda.
5. Saat menampilkan hasil kos dari tool, cukup sebutkan nama dan harga. Kartu kos akan ditampilkan otomatis oleh UI.
6. Jawaban teks maksimal 2-3 kalimat — singkat, padat, dan to the point.
7. Gunakan emoji secukupnya agar lebih ramah (🏠 ✨ 📍 💰 dll).

PANDUAN TOOL:
- Gunakan `search_boarding_house` untuk mencari berdasarkan nama/lokasi/kota.
- Gunakan `filter_by_budget` saat user menyebut budget atau harga.
- Gunakan `get_house_details` saat user ingin tahu detail spesifik satu kos.
- Boleh memanggil lebih dari satu tool jika diperlukan.
PROMPT;
    }

    /**
     * Tools yang dapat dipanggil oleh AI.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new SearchBoardingHouseTool,
            new FilterByBudgetTool,
            new GetHouseDetailsTool,
        ];
    }
}
