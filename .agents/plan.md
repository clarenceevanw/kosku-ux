# KosKu AI Integration Plan

## 1. KosBot Chatbot (Laravel 13 AI SDK)
- **Objective:** Provide a conversational AI agent to help tenants find boarding houses, get personalized recommendations, and answer FAQs.
- **Tech Stack:** Laravel 13 AI SDK (using OpenAI or Anthropic drivers).
- **Implementation Steps:**
  1. Set up the AI SDK connection in `config/ai.php`.
  2. Create a dedicated `KosBotService` to manage the context and conversation history.
  3. Implement AI Tools/Functions (e.g., `searchBoardingHouse(location, budget, facilities)` so the AI can query the local database and return actual links).
  4. Build an API endpoint (`POST /api/bot/chat`) that streams the AI response back to the Blade frontend for a real-time typing effect.

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
