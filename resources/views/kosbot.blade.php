@extends('layouts.app')

@section('content')
{{-- Full-height chat layout. The extra class on body needs override via layout.
     We use flex+h-screen by setting explicit heights on the containers. --}}
<div class="flex flex-col" style="height: calc(100vh - 80px); margin-top: 80px;">

    {{-- ═══════════════════════════════════════
         Chat Messages Area (scrollable)
    ═══════════════════════════════════════ --}}
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

                {{-- Kos result card (links to real search) --}}
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
                            <div class="flex gap-2 mb-3">
                                <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded-full text-[10px] font-semibold text-slate-500">AC</span>
                                <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded-full text-[10px] font-semibold text-slate-500">WiFi</span>
                                <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded-full text-[10px] font-semibold text-slate-500">KM Dalam</span>
                            </div>
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

    {{-- ═══════════════════════════════════════
         Chat Input Bar (fixed to bottom)
    ═══════════════════════════════════════ --}}
    <div class="px-5 md:px-16 pb-6 bg-white border-t border-gray-100 pt-4 shrink-0">
        <div class="max-w-4xl mx-auto flex items-center gap-3 bg-gray-50 rounded-full p-2 border border-gray-200 shadow-sm focus-within:border-[#111827] focus-within:shadow-md transition-all duration-300">
            <button class="w-10 h-10 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-200 transition-colors shrink-0">
                <span class="material-symbols-outlined">add</span>
            </button>
            <input id="chat-input"
                   class="flex-1 bg-transparent border-none outline-none text-sm text-[#111827] placeholder-gray-400 focus:ring-0"
                   placeholder="Tanya KosBot tentang kos idamanmu..."
                   type="text"
                   autocomplete="off">
            <button class="w-10 h-10 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-200 transition-colors shrink-0">
                <span class="material-symbols-outlined">mic</span>
            </button>
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

<script>
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
