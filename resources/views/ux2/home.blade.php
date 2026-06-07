@extends('layouts.ux2.app')

@section('title', 'Beranda KosKu - Sewa Kos Mudah & Cerdas')

@section('styles')
    <style>
        /* ============================================
           HERO AMBIENT ORBS
        ============================================ */
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(60px);
            opacity: 0;
            animation: orb-appear 1.2s ease forwards;
        }
        .hero-orb-1 {
            width: 340px; height: 340px;
            background: radial-gradient(circle, rgba(47,143,121,0.35) 0%, transparent 70%);
            top: -80px; right: 15%;
            animation: orb-appear 1.2s ease 0.2s forwards, orb-drift1 12s ease-in-out 1.4s infinite;
        }
        .hero-orb-2 {
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(242,189,94,0.25) 0%, transparent 70%);
            bottom: 10%; left: 5%;
            animation: orb-appear 1.2s ease 0.5s forwards, orb-drift2 15s ease-in-out 1.7s infinite;
        }
        .hero-orb-3 {
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(189,235,216,0.2) 0%, transparent 70%);
            top: 40%; right: 5%;
            animation: orb-appear 1.2s ease 0.8s forwards, orb-drift3 10s ease-in-out 2s infinite;
        }
        @keyframes orb-appear {
            to { opacity: 1; }
        }
        @keyframes orb-drift1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(-20px, 18px) scale(1.06); }
            66%       { transform: translate(12px, -14px) scale(0.96); }
        }
        @keyframes orb-drift2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%       { transform: translate(18px, -22px) scale(1.08); }
        }
        @keyframes orb-drift3 {
            0%, 100% { transform: translate(0, 0); }
            50%       { transform: translate(-14px, 16px); }
        }

        /* ============================================
           SCROLL REVEAL — fade-up
        ============================================ */
        .reveal {
            opacity: 0;
            transform: translateY(36px);
            transition: opacity 0.65s cubic-bezier(.22,.68,0,1.2), transform 0.65s cubic-bezier(.22,.68,0,1.2);
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.22s; }
        .reveal-delay-3 { transition-delay: 0.34s; }
        .reveal-delay-4 { transition-delay: 0.46s; }
        .reveal-delay-5 { transition-delay: 0.58s; }

        /* ============================================
           HERO TEXT — fade-slide in on load
        ============================================ */
        @keyframes hero-in {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-animate-1 { animation: hero-in 0.7s cubic-bezier(.22,.68,0,1.1) 0.1s both; }
        .hero-animate-2 { animation: hero-in 0.7s cubic-bezier(.22,.68,0,1.1) 0.28s both; }
        .hero-animate-3 { animation: hero-in 0.7s cubic-bezier(.22,.68,0,1.1) 0.44s both; }
        .hero-animate-4 { animation: hero-in 0.7s cubic-bezier(.22,.68,0,1.1) 0.60s both; }
        .hero-card-in   { animation: hero-in 0.8s cubic-bezier(.22,.68,0,1.1) 0.55s both; }

        /* ============================================
           TYPING CURSOR
        ============================================ */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0; }
        }
        .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 1.1em;
            background: var(--ux2-secondary-soft);
            margin-left: 4px;
            vertical-align: middle;
            border-radius: 2px;
            animation: blink 1s step-start infinite;
        }

        /* ============================================
           PULSING ONLINE DOT
        ============================================ */
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 0 0 rgba(47,143,121,0.55); }
            50%       { box-shadow: 0 0 0 5px rgba(47,143,121,0); }
        }
        .pulse-dot {
            animation: pulse-dot 1.8s ease-in-out infinite;
        }

        /* ============================================
           CHAT BUBBLE TYPING INDICATOR
        ============================================ */
        @keyframes chat-bubble-in {
            from { opacity: 0; transform: translateX(-12px) scale(0.94); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        .chat-bubble-anim {
            animation: chat-bubble-in 0.45s cubic-bezier(.22,.68,0,1.2) both;
        }
        .chat-bubble-anim:nth-child(1) { animation-delay: 0.3s; }
        .chat-bubble-anim:nth-child(2) { animation-delay: 0.9s; }
        .chat-bubble-anim:nth-child(3) { animation-delay: 1.5s; }

        /* Typing dots */
        @keyframes typing-dot {
            0%, 80%, 100% { transform: translateY(0); opacity: 0.4; }
            40%            { transform: translateY(-5px); opacity: 1; }
        }
        .typing-dots span {
            display: inline-block;
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
            margin: 0 1.5px;
            animation: typing-dot 1.4s ease-in-out infinite;
        }
        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

        /* ============================================
           SHIMMER CTA BUTTON
        ============================================ */
        @keyframes shimmer-slide {
            from { left: -80%; }
            to   { left: 130%; }
        }
        .btn-shimmer {
            position: relative;
            overflow: hidden;
        }
        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: 0; left: -80%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.28), transparent);
            transform: skewX(-18deg);
            animation: shimmer-slide 2.8s ease-in-out 1.2s infinite;
        }

        /* ============================================
           CARD HOVER LIFT
        ============================================ */
        .card-lift {
            transition: transform 0.28s cubic-bezier(.22,.68,0,1.2), box-shadow 0.28s ease !important;
        }
        .card-lift:hover {
            transform: translateY(-6px) scale(1.012) !important;
            box-shadow: 0 18px 42px rgba(15,42,39,0.13) !important;
        }

        /* ============================================
           HERO CARD PARALLAX TILT
        ============================================ */
        .tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.12s ease;
            will-change: transform;
        }

        /* ============================================
           FLOATING (existing, enhanced)
        ============================================ */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-10px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-15px); }
        }
        .animate-float-delayed {
            animation: float-delayed 8s ease-in-out infinite;
            animation-delay: 2s;
        }

        /* ============================================
           SECTION DIVIDER WAVE
        ============================================ */
        .wave-divider {
            overflow: hidden;
            line-height: 0;
        }

        /* ============================================
           STAT BADGE COUNTER
        ============================================ */
        @keyframes count-in {
            from { opacity: 0; transform: scale(0.7); }
            to   { opacity: 1; transform: scale(1); }
        }
        .stat-badge {
            animation: count-in 0.55s cubic-bezier(.22,.68,0,1.3) both;
        }
        .stat-badge:nth-child(1) { animation-delay: 0.7s; }
        .stat-badge:nth-child(2) { animation-delay: 0.9s; }
        .stat-badge:nth-child(3) { animation-delay: 1.1s; }

        /* ============================================
           SEARCH FORM FOCUS GLOW
        ============================================ */
        .search-form-wrap {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .search-form-wrap:focus-within {
            box-shadow: 0 0 0 3px rgba(47,143,121,0.28), 0 10px 30px rgba(15,42,39,0.15) !important;
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('content')
    @php
        $heroHouse = collect($recommendations)->first(fn($house) => !empty($house['primary_image']));
        $heroImage = $heroHouse['primary_image'] ?? null;
    @endphp

    <!-- Hero Section -->
    <section
        class="relative min-h-[calc(100vh-140px)] px-margin-mobile md:px-margin-desktop py-xl overflow-hidden bg-primary">
        <!-- Ambient orbs -->
        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>
        @if ($heroImage)
            <img src="{{ $heroImage }}" alt="{{ $heroHouse['name'] ?? 'Hunian kos' }}"
                class="absolute inset-0 w-full h-full object-cover opacity-45" />
        @endif
        <div
            class="absolute inset-0 bg-[linear-gradient(90deg,rgba(12,38,40,.95)_0%,rgba(12,38,40,.76)_48%,rgba(12,38,40,.44)_100%)]">
        </div>

        <div
            class="max-w-[1440px] mx-auto relative z-10 grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_420px] gap-xl items-center">
            <div class="max-w-3xl">
                <div class="ux2-chip mb-md hero-animate-1">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    Kos aktif di Surabaya, Jakarta, Bandung, dan Yogyakarta
                </div>
                <h1 class="font-display-lg text-display-lg text-on-primary mb-md leading-tight hero-animate-2">
                    Cari kos yang nyaman, jelas, dan siap ditempati.
                </h1>
                <p class="font-body-lg text-body-lg text-white/80 max-w-2xl mb-lg hero-animate-3">
                    Lihat pilihan kamar, fasilitas, harga awal, lalu lanjut booking tanpa pindah alur.
                </p>

                <!-- Quick stats -->
                <div class="flex flex-wrap gap-sm mb-lg hero-animate-3">
                    <div class="stat-badge flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 px-sm py-xs rounded-lg">
                        <span class="material-symbols-outlined text-[18px]" style="color: var(--ux2-accent); font-variation-settings:'FILL' 1;">home_work</span>
                        <span class="font-label-sm text-label-sm text-white"><span class="js-counter font-bold" data-target="1200">0</span>+ Kos</span>
                    </div>
                    <div class="stat-badge flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 px-sm py-xs rounded-lg">
                        <span class="material-symbols-outlined text-[18px]" style="color: var(--ux2-secondary-soft); font-variation-settings:'FILL' 1;">location_city</span>
                        <span class="font-label-sm text-label-sm text-white"><span class="font-bold">4</span> Kota</span>
                    </div>
                    <div class="stat-badge flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 px-sm py-xs rounded-lg">
                        <span class="material-symbols-outlined text-[18px]" style="color: var(--ux2-secondary-soft); font-variation-settings:'FILL' 1;">star</span>
                        <span class="font-label-sm text-label-sm text-white"><span class="font-bold">4.8</span> Rating</span>
                    </div>
                </div>

                <form action="{{ route('ux2.search') }}"
                    class="search-form-wrap w-full max-w-3xl ux2-shell-card rounded-[20px] p-sm flex flex-col md:flex-row gap-sm hero-animate-4"
                    method="GET">
                    <div class="flex-1 flex items-center gap-sm px-sm py-xs">
                        <span class="material-symbols-outlined text-secondary">location_on</span>
                        <div class="flex flex-col flex-1 text-left">
                            <label class="font-label-sm text-label-sm text-on-surface-variant">Lokasi atau kampus</label>
                            <input
                                class="w-full bg-transparent border-none p-0 focus:ring-0 font-body-md text-body-md text-on-surface placeholder-outline-variant"
                                name="q" placeholder="Coba: Surabaya, ITS, Senopati" type="text" />
                        </div>
                    </div>
                    <button
                        class="btn-shimmer bg-primary hover:bg-inverse-surface text-on-primary font-label-md text-label-md font-bold px-lg py-md rounded-xl flex items-center justify-center gap-2 transition-all duration-200 active:scale-95">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">search</span>
                        Cari Kos
                    </button>
                </form>
            </div>

            @if ($heroHouse)
                <a href="{{ route('ux2.kos.show', $heroHouse['id']) }}"
                    class="tilt-card hero-card-in hidden lg:block bg-surface-container-lowest/95 border border-white/20 rounded-[20px] overflow-hidden shadow-2xl backdrop-blur-xl">
                    <div class="h-64 overflow-hidden bg-surface-variant">
                        <img src="{{ $heroImage }}" alt="{{ $heroHouse['name'] }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-105" />
                    </div>
                    <div class="p-md">
                        <div class="flex items-center justify-between gap-sm mb-sm">
                            <span class="ux2-chip">{{ $heroHouse['gender_label'] }}</span>
                            <span
                                class="font-label-sm text-label-sm text-on-surface-variant">{{ $heroHouse['city'] }}</span>
                        </div>
                        <h2 class="font-headline-md text-headline-md text-on-surface mb-xs">{{ $heroHouse['name'] }}</h2>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-md">
                            {{ $heroHouse['min_price_formatted'] ?? 'Hubungi pemilik' }}</p>
                        <div class="flex items-center justify-between border-t border-outline-variant pt-sm">
                            <span class="font-label-md text-label-md text-secondary font-bold">Lihat detail</span>
                            <span class="material-symbols-outlined text-secondary">arrow_forward</span>
                        </div>
                    </div>
                </a>
            @endif
        </div>
    </section>
    <!-- KosBot AI Section -->
    <section class="py-xl px-margin-mobile md:px-margin-desktop bg-surface relative -mt-lg z-20">
        <div class="max-w-[1440px] mx-auto">
            <div
                class="reveal ux2-dark-panel rounded-[32px] p-lg md:p-[64px] flex flex-col lg:flex-row items-center gap-xl relative overflow-hidden shadow-[0_10px_40px_rgba(12,38,40,0.18)] border border-white/10">
                <div class="absolute inset-0 opacity-20 pointer-events-none"
                    style="background-image: linear-gradient(rgba(189, 235, 216, 0.12) 1px, transparent 1px), linear-gradient(90deg, rgba(189, 235, 216, 0.12) 1px, transparent 1px); background-size: 36px 36px;">
                </div>
                <!-- Content -->
                <div class="flex-1 z-10">
                    <div
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-sm py-xs rounded-lg mb-md border border-white/20">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; color: var(--ux2-accent);">auto_awesome</span>
                        <span
                            class="font-label-sm text-label-sm font-bold uppercase tracking-widest" style="color: var(--ux2-accent);">Newest Feature!</span>
                    </div>
                    <h2 class="font-display-lg text-display-lg mb-md leading-tight" style="color: #ffffff;">
                        Tanya <span style="color: var(--ux2-secondary-soft);">KosBot AI</span>
                    </h2>
                    <p class="font-body-lg text-body-lg mb-lg max-w-xl" style="color: rgba(255,255,255,0.75);">
                        Berhenti membuang waktu memfilter ribuan daftar. Ceritakan kebutuhan spesifik Anda kepada KosBot,
                        dan biarkan AI kami mencocokkan Anda dengan properti yang tepat dalam hitungan detik.
                    </p>
                    <ul class="flex flex-col gap-sm mb-lg">
                        <li class="flex items-start gap-sm reveal reveal-delay-1">
                            <span class="material-symbols-outlined" style="color: var(--ux2-secondary-soft);">check_circle</span>
                            <span class="font-body-md text-body-md" style="color: rgba(255,255,255,0.85);">Analisis preferensi lokasi dan gaya hidup.</span>
                        </li>
                        <li class="flex items-start gap-sm reveal reveal-delay-2">
                            <span class="material-symbols-outlined" style="color: var(--ux2-secondary-soft);">check_circle</span>
                            <span class="font-body-md text-body-md" style="color: rgba(255,255,255,0.85);">Estimasi biaya komprehensif termasuk utilitas.</span>
                        </li>
                    </ul>
                    <a class="btn-shimmer font-label-md text-label-md font-bold px-lg py-sm rounded-xl inline-flex items-center gap-2 transition-colors"
                        href="{{ route('ux2.bot') }}"
                        style="background-color: var(--ux2-secondary); color: #ffffff;"
                        onmouseover="this.style.backgroundColor='var(--ux2-secondary-soft)'; this.style.color='var(--ux2-primary)';"
                        onmouseout="this.style.backgroundColor='var(--ux2-secondary)'; this.style.color='#ffffff';">
                        Mulai Percakapan <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
                <!-- Abstract Visual Representation of Chat -->
                <div class="flex-1 w-full max-w-md relative z-10 h-[400px]">
                    <div
                        class="absolute inset-0 bg-surface-container-lowest rounded-[24px] shadow-lg border border-outline-variant/20 overflow-hidden flex flex-col p-md">
                        <!-- Header -->
                        <div class="flex items-center gap-sm pb-sm border-b border-outline-variant/20 mb-md">
                            <div class="animate-float w-10 h-10 rounded-full flex items-center justify-center" style="background-color: var(--ux2-primary-soft);">
                                <span class="material-symbols-outlined"
                                    style="font-variation-settings: 'FILL' 1; color: var(--ux2-primary);">smart_toy</span>
                            </div>
                            <div>
                                <div class="font-label-md text-label-md font-bold text-on-surface">KosBot Assistant</div>
                                <div class="font-label-sm text-label-sm flex items-center gap-1" style="color: var(--ux2-secondary);"><span
                                        class="pulse-dot w-2 h-2 rounded-full inline-block" style="background-color: var(--ux2-secondary);"></span> Online</div>
                            </div>
                        </div>
                        <!-- Chat bubbles -->
                        <div class="flex flex-col gap-sm flex-1 overflow-hidden">
                            <div class="chat-bubble-anim self-start px-md py-sm rounded-2xl rounded-tl-sm font-body-sm max-w-[85%] shadow-sm relative"
                                style="background-color: var(--ux2-primary); color: #ffffff; font-size: 13px;">
                                <div class="absolute -left-2 -top-2 w-4 h-4 rounded-full flex items-center justify-center"
                                    style="background-color: var(--ux2-secondary-soft);">
                                    <span class="material-symbols-outlined text-[10px]" style="color: var(--ux2-primary);">bolt</span>
                                </div>
                                Halo! Saya cari kos dekat ITS, budget 1 juta, ada WiFi 🏠
                            </div>
                            <div class="chat-bubble-anim self-end px-md py-sm rounded-2xl rounded-tr-sm font-body-sm max-w-[85%] shadow-sm"
                                style="background-color: var(--ux2-secondary-soft); color: var(--ux2-primary); font-size: 13px;">
                                Saya menemukan 8 kos cocok untuk kamu! Mau saya urutkan berdasarkan jarak atau harga? ✨
                            </div>
                            <div class="chat-bubble-anim self-start px-md py-sm rounded-2xl rounded-tl-sm font-body-sm max-w-[60%] shadow-sm"
                                style="background-color: var(--ux2-panel); color: var(--ux2-muted); font-size: 13px;">
                                <div class="typing-dots" style="color: var(--ux2-secondary);">
                                    <span></span><span></span><span></span>
                                </div>
                            </div>
                        </div>
                        <!-- Input fake -->
                        <div
                            class="mt-auto bg-surface rounded-xl border border-outline-variant/30 p-sm flex items-center justify-between text-outline-variant">
                            <span class="font-body-md text-body-md">Ketik balasan...</span>
                            <span class="material-symbols-outlined" style="color: var(--ux2-secondary);">send</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Bento Grid / Curated Selection -->
    <section class="py-xl px-margin-mobile md:px-margin-desktop bg-background">
        <div class="max-w-[1440px] mx-auto">
            <div class="reveal flex justify-between items-end mb-lg">
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface mb-xs">Kurasi Eksklusif</h2>
                    <p class="font-body-lg text-body-lg text-on-surface-variant">Properti pilihan dengan standar kualitas
                        institusional.</p>
                </div>
                <a class="hidden md:flex items-center gap-1 text-secondary font-label-md text-label-md font-bold hover:text-secondary-fixed transition-colors"
                    href="{{ route('ux2.search') }}">
                    Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                @forelse ($recommendations as $house)
                    <a class="reveal card-lift group rounded-[20px] overflow-hidden bg-surface-container-lowest border border-outline-variant/20 shadow-[0_4px_20px_rgba(15,23,42,0.05)] flex flex-col"
                        href="{{ route('ux2.kos.show', $house['id']) }}">
                        <div class="h-[220px] w-full overflow-hidden relative bg-surface-variant">
                            @if (!empty($house['primary_image']))
                                <img alt="{{ $house['name'] }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                    src="{{ $house['primary_image'] }}" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant"><span
                                        class="material-symbols-outlined text-5xl">home_work</span></div>
                            @endif
                            <div class="absolute top-sm left-sm z-20 flex gap-2">
                                <span
                                    class="bg-surface-container-lowest text-on-surface font-label-sm text-label-sm px-2 py-1 rounded-md shadow-sm">{{ $house['gender_label'] }}</span>
                            </div>
                        </div>
                        <div class="p-md flex flex-col gap-sm">
                            <h3 class="font-headline-md text-headline-md text-on-surface">{{ $house['name'] }}</h3>
                            <div class="flex items-center gap-1 font-body-md text-body-md text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">location_on</span>
                                {{ $house['city'] }}
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($house['facility_preview'] ?? [] as $facility)
                                    <span
                                        class="bg-surface-container px-2 py-1 rounded-md text-on-primary-container font-label-sm text-label-sm">{{ $facility['name'] }}</span>
                                @endforeach
                            </div>
                            <div class="mt-auto pt-sm border-t border-outline-variant/40">
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Mulai dari</span>
                                <p class="font-headline-md text-headline-md text-secondary">
                                    {{ $house['min_price_formatted'] ?? 'Hubungi pemilik' }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div
                        class="md:col-span-3 bg-surface-container-lowest rounded-xl border border-outline-variant p-lg text-center">
                        <span class="material-symbols-outlined text-5xl text-outline mb-2">home_work</span>
                        <h3 class="font-headline-md text-headline-md text-primary">Belum ada rekomendasi kos</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant mt-2">Data rekomendasi akan tampil
                            setelah tersedia dari backend.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
(function () {
    /* ── 1. SCROLL REVEAL ─────────────────────────────── */
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    /* ── 2. NUMBER COUNTER ────────────────────────────── */
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = parseInt(el.dataset.target, 10);
            const duration = 1400;
            const start = performance.now();
            function tick(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out cubic
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target).toLocaleString('id-ID');
                if (progress < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
            counterObserver.unobserve(el);
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.js-counter').forEach(el => counterObserver.observe(el));

    /* ── 3. PARALLAX TILT ON HERO CARD ───────────────── */
    const tiltCards = document.querySelectorAll('.tilt-card');
    tiltCards.forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width  - 0.5; // -0.5 to 0.5
            const y = (e.clientY - rect.top)  / rect.height - 0.5;
            const rotateX = (-y * 10).toFixed(2);
            const rotateY = ( x * 10).toFixed(2);
            card.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) scale(1)';
        });
    });

    /* ── 4. SMOOTH HOVER ON SEARCH BUTTON ────────────── */
    document.querySelectorAll('.btn-shimmer').forEach(btn => {
        btn.addEventListener('mouseenter', () => btn.style.transform = 'translateY(-1px)');
        btn.addEventListener('mouseleave', () => btn.style.transform = 'translateY(0)');
    });
})();
</script>
@endsection
