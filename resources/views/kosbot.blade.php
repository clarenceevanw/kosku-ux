@extends('layouts.app')

@section('content')
{{-- Full-height chat layout. The extra class on body needs override via layout.
     We use flex+h-screen by setting explicit heights on the containers. --}}
<div class="flex flex-col" style="height: calc(100vh - 80px); margin-top: 80px;">

    {{-- ── AI Mode Selector ── --}}
    <div class="flex justify-center pt-6 pb-4 bg-white shrink-0 relative z-10 border-b border-gray-100">
        <div class="bg-gray-100 p-1.5 rounded-full flex gap-1 shadow-inner">
            <button id="tab-chat" onclick="switchAITab('chat')" class="px-6 py-2.5 rounded-full text-sm font-bold bg-white text-[#111827] shadow-sm transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">chat</span> KosBot Chat
            </button>
            <button id="tab-price" onclick="switchAITab('price')" class="px-6 py-2.5 rounded-full text-sm font-bold text-gray-500 hover:text-[#111827] transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">price_check</span> AI Price Checker
                <span class="bg-amber-100 text-amber-700 text-[10px] px-2 py-0.5 rounded-full ml-1">BETA</span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         SECTION 1: KOSBOT CHAT
    ═══════════════════════════════════════ --}}
    <div id="section-chat" class="flex-1 flex flex-col overflow-hidden bg-white">
        {{-- Chat Messages Area (scrollable) --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto px-5 md:px-16 py-8 flex flex-col gap-6 scroll-smooth">
            {{-- Timestamp pill --}}
            <div class="flex justify-center">
                <span class="bg-gray-100 text-gray-500 px-4 py-1 rounded-full text-xs font-semibold">
                    {{ now()->translatedFormat('d M Y, H:i') }}
                </span>
            </div>

            {{-- KosBot welcome message --}}
            <div class="flex w-full justify-start gap-4">
                <div class="w-10 h-10 rounded-full bg-[#111827] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[18px]">smart_toy</span>
                </div>
                <div class="bg-white border border-gray-100 text-[#111827] rounded-2xl rounded-tl-sm p-5 max-w-[85%] md:max-w-[60%] shadow-sm text-sm leading-relaxed">
                    <p class="font-bold mb-1">Halo! Saya <span class="text-amber-500">KosBot ✨</span></p>
                    Asisten AI kamu untuk menemukan kos impian. Ceritakan kebutuhanmu — lokasi, budget, fasilitas yang diinginkan — dan saya akan carikan yang terbaik!
                </div>
            </div>

            {{-- Suggestion chips --}}
            <div class="flex justify-start pl-14 gap-2 flex-wrap">
                @php
                    $suggestions = [
                        'Kos putra dekat ITS Surabaya',
                        'Kos AC WiFi budget 1.5jt',
                        'Kos putri Jakarta Selatan',
                        'Studio apartment Bandung',
                    ];
                @endphp
                @foreach($suggestions as $suggestion)
                <button onclick="fillInput('{{ $suggestion }}')"
                        class="px-4 py-2 bg-white border border-gray-200 rounded-full text-xs font-semibold text-gray-600 hover:border-[#111827] hover:text-[#111827] transition-all active:scale-95 shadow-sm">
                    {{ $suggestion }}
                </button>
                @endforeach
            </div>

            {{-- Demo conversation (static example) --}}
            <div class="flex w-full justify-end gap-4">
                <div class="bg-[#111827] text-white rounded-2xl rounded-tr-sm p-5 max-w-[85%] md:max-w-[60%] shadow-md text-sm leading-relaxed">
                    Cari kos putra dekat UK Petra, budget max 1.5jt, ada AC.
                </div>
            </div>

            <div class="flex w-full justify-start gap-4">
                <div class="w-10 h-10 rounded-full bg-[#111827] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[18px]">smart_toy</span>
                </div>
                <div class="flex flex-col gap-3 max-w-[85%] md:max-w-[70%]">
                    <div class="bg-white border border-gray-100 text-[#111827] rounded-2xl rounded-tl-sm p-5 shadow-sm text-sm leading-relaxed">
                        Saya menemukan beberapa kos putra yang cocok dekat UK Petra dengan budget di bawah Rp 1.500.000! Ini rekomendasi terbaik saya:
                    </div>

                    {{-- Kos result card --}}
                    <a href="{{ route('search', ['q' => 'Petra Surabaya']) }}"
                       class="bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100 flex flex-col md:flex-row group cursor-pointer transition-transform hover:-translate-y-1">
                        <div class="h-48 md:h-auto md:w-48 relative shrink-0 bg-gray-100">
                            <img class="w-full h-full object-cover"
                                 src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400"
                                 alt="Kos Petra">
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2 py-0.5 rounded-full text-[10px] font-bold text-[#111827] shadow-sm">
                                Terverifikasi
                            </div>
                        </div>
                        <div class="p-5 flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="font-bold text-[#111827] text-base mb-1">Kos Petra Executive</h3>
                                <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]">location_on</span>
                                    Siwalankerto, Surabaya
                                </p>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-base font-extrabold text-[#0D9488]">Rp 1.200.000<span class="text-xs font-normal text-gray-400">/bln</span></span>
                                <span class="text-[11px] font-bold text-[#111827] bg-gray-100 px-3 py-1 rounded-full">Lihat Detail →</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Typing indicator placeholder --}}
            <div id="typing-indicator" class="flex w-full justify-start gap-4 hidden">
                <div class="w-10 h-10 rounded-full bg-[#111827] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[18px]">smart_toy</span>
                </div>
                <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm p-4 shadow-sm flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0ms"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay:150ms"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-400 animate-bounce" style="animation-delay:300ms"></div>
                </div>
            </div>
        </div>

        {{-- Chat Input Bar (fixed to bottom) --}}
        <div class="px-5 md:px-16 pb-6 bg-white border-t border-gray-100 pt-4 shrink-0">
            <div class="max-w-4xl mx-auto flex items-center gap-3 bg-gray-50 rounded-full p-2 border border-gray-200 shadow-sm focus-within:border-[#111827] focus-within:shadow-md transition-all duration-300">
                <input id="chat-input"
                       class="flex-1 bg-transparent border-none outline-none text-sm text-[#111827] placeholder-gray-400 focus:ring-0 ml-4"
                       placeholder="Tanya KosBot tentang kos idamanmu..."
                       type="text"
                       autocomplete="off">
                <button onclick="sendMessage()"
                        class="w-10 h-10 rounded-full bg-[#111827] text-white flex items-center justify-center hover:opacity-90 transition-opacity shrink-0 shadow-sm active:scale-95">
                    <span class="material-symbols-outlined">arrow_upward</span>
                </button>
            </div>
            <p class="text-center text-[10px] text-gray-400 mt-2">
                KosBot AI dapat membuat kesalahan. Verifikasi informasi penting sebelum mengambil keputusan.
            </p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         SECTION 2: AI PRICE CHECKER
    ═══════════════════════════════════════ --}}
    <div id="section-price" class="flex-1 overflow-y-auto hidden bg-gray-50/50">
        <div class="max-w-3xl mx-auto py-10 px-5">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-100 text-center bg-white">
                    <div class="w-12 h-12 bg-gray-50 text-[#111827] rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <span class="material-symbols-outlined text-[24px]">price_check</span>
                    </div>
                    <h2 class="text-2xl font-bold text-[#111827] tracking-tight mb-2">AI Price Checker</h2>
                    <p class="text-gray-500 text-sm max-w-md mx-auto">Cek apakah harga kos yang kamu temukan wajar atau overprice berdasarkan data pasar Surabaya saat ini.</p>
                </div>
                
                <form class="p-8 space-y-6">
                    {{-- Kota --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#111827] mb-2">Kota / Wilayah (Beta)</label>
                        <select disabled class="w-full h-12 px-4 rounded-xl bg-gray-100 border-gray-200 text-gray-500 text-sm outline-none cursor-not-allowed">
                            <option selected>Surabaya (Beta Only)</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Saat ini model AI hanya dilatih untuk data harga kos di wilayah Surabaya.</p>
                    </div>

                    {{-- Luas Kamar & Fasilitas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-[#111827] mb-2">Luas Kamar (m²)</label>
                            <input type="number" placeholder="Contoh: 12" class="w-full h-12 px-4 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#111827] focus:ring-1 focus:ring-[#111827] text-sm outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#111827] mb-2">Kamar Mandi</label>
                            <select class="w-full h-12 px-4 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#111827] focus:ring-1 focus:ring-[#111827] text-sm outline-none transition-all">
                                <option value="dalam">Kamar Mandi Dalam</option>
                                <option value="luar">Kamar Mandi Luar</option>
                            </select>
                        </div>
                    </div>

                    {{-- Fasilitas Checkboxes --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#111827] mb-3">Fasilitas Utama</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 p-3 rounded-xl border border-gray-200 hover:border-[#111827] transition-all">
                                <input type="checkbox" class="rounded text-[#111827] focus:ring-[#111827]">
                                <span class="text-sm text-gray-700">AC</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 p-3 rounded-xl border border-gray-200 hover:border-[#111827] transition-all">
                                <input type="checkbox" class="rounded text-[#111827] focus:ring-[#111827]">
                                <span class="text-sm text-gray-700">WiFi</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 p-3 rounded-xl border border-gray-200 hover:border-[#111827] transition-all">
                                <input type="checkbox" class="rounded text-[#111827] focus:ring-[#111827]">
                                <span class="text-sm text-gray-700">Kasur & Lemari</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 p-3 rounded-xl border border-gray-200 hover:border-[#111827] transition-all">
                                <input type="checkbox" class="rounded text-[#111827] focus:ring-[#111827]">
                                <span class="text-sm text-gray-700">Water Heater</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 p-3 rounded-xl border border-gray-200 hover:border-[#111827] transition-all">
                                <input type="checkbox" class="rounded text-[#111827] focus:ring-[#111827]">
                                <span class="text-sm text-gray-700">Parkir Motor</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 p-3 rounded-xl border border-gray-200 hover:border-[#111827] transition-all">
                                <input type="checkbox" class="rounded text-[#111827] focus:ring-[#111827]">
                                <span class="text-sm text-gray-700">Parkir Mobil</span>
                            </label>
                        </div>
                    </div>

                    {{-- Harga Sewa --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#111827] mb-2">Harga Sewa per Bulan (Rp)</label>
                        <input type="number" placeholder="Contoh: 1500000" class="w-full h-14 px-4 rounded-xl bg-gray-50 border border-gray-200 focus:border-[#111827] focus:ring-1 focus:ring-[#111827] text-lg font-bold text-[#111827] outline-none transition-all">
                    </div>

                    <button type="button" class="w-full h-14 bg-[#111827] text-white rounded-xl font-bold text-sm hover:bg-opacity-90 active:scale-[0.98] transition-all shadow-lg mt-2 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">analytics</span> Prediksi Kelayakan Harga
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function switchAITab(tab) {
        const chatSection = document.getElementById('section-chat');
        const priceSection = document.getElementById('section-price');
        const chatTabBtn = document.getElementById('tab-chat');
        const priceTabBtn = document.getElementById('tab-price');

        if (tab === 'chat') {
            chatSection.classList.remove('hidden');
            chatSection.classList.add('flex');
            priceSection.classList.add('hidden');
            
            chatTabBtn.classList.add('bg-white', 'text-[#111827]', 'shadow-sm');
            chatTabBtn.classList.remove('text-gray-500', 'hover:text-[#111827]');
            
            priceTabBtn.classList.remove('bg-white', 'text-[#111827]', 'shadow-sm');
            priceTabBtn.classList.add('text-gray-500', 'hover:text-[#111827]');
        } else {
            priceSection.classList.remove('hidden');
            chatSection.classList.add('hidden');
            chatSection.classList.remove('flex');
            
            priceTabBtn.classList.add('bg-white', 'text-[#111827]', 'shadow-sm');
            priceTabBtn.classList.remove('text-gray-500', 'hover:text-[#111827]');
            
            chatTabBtn.classList.remove('bg-white', 'text-[#111827]', 'shadow-sm');
            chatTabBtn.classList.add('text-gray-500', 'hover:text-[#111827]');
        }
    }

    function fillInput(text) {
        document.getElementById('chat-input').value = text;
        document.getElementById('chat-input').focus();
    }

    function sendMessage() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (!text) return;

        const messagesEl = document.getElementById('chat-messages');
        const typingEl   = document.getElementById('typing-indicator');

        // Append user bubble
        const userBubble = document.createElement('div');
        userBubble.className = 'flex w-full justify-end gap-4';
        userBubble.innerHTML = `<div class="bg-[#111827] text-white rounded-2xl rounded-tr-sm p-5 max-w-[85%] md:max-w-[60%] shadow-md text-sm leading-relaxed">${escapeHtml(text)}</div>`;
        messagesEl.insertBefore(userBubble, typingEl);

        // Show typing indicator
        typingEl.classList.remove('hidden');
        input.value = '';

        // Redirect to search with user's query after a brief delay
        setTimeout(() => {
            typingEl.classList.add('hidden');
            const searchUrl = '{{ route('search') }}?q=' + encodeURIComponent(text);
            const botReply = document.createElement('div');
            botReply.className = 'flex w-full justify-start gap-4';
            botReply.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-[#111827] flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[18px]">smart_toy</span>
                </div>
                <div class="bg-white border border-gray-100 text-[#111827] rounded-2xl rounded-tl-sm p-5 max-w-[85%] md:max-w-[60%] shadow-sm text-sm leading-relaxed">
                    Saya sedang mencari kos yang cocok dengan permintaanmu! 
                    <a href="${searchUrl}" class="text-teal-600 font-bold underline hover:text-teal-700">Lihat hasil pencarian →</a>
                </div>`;
            messagesEl.insertBefore(botReply, typingEl);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }, 1200);

        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    // Allow Enter key to send
    document.getElementById('chat-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
</script>
@endsection
