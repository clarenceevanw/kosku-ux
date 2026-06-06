@extends('layouts.ux2.tenant')

@section('title', 'Dashboard Penghuni - KosKu')

@section('styles')
<style>
    /* ── ANIMATIONS ──────────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in  { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.9); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes slide-up-stagger {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .anim-fade-up  { animation: fade-up  0.55s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in  { animation: fade-in  0.45s ease both; }
    .anim-scale-in { animation: scale-in 0.5s  cubic-bezier(.22,.68,0,1.2) both; }
    .d1 { animation-delay: .06s; } .d2 { animation-delay: .12s; }
    .d3 { animation-delay: .18s; } .d4 { animation-delay: .24s; }
    .d5 { animation-delay: .30s; } .d6 { animation-delay: .36s; }

    /* ── SCROLL REVEAL ───────────────────────── */
    .reveal {
        opacity: 0; transform: translateY(20px);
        transition: opacity .55s cubic-bezier(.22,.68,0,1.1), transform .55s cubic-bezier(.22,.68,0,1.1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .rev-d1 { transition-delay: .08s; }
    .rev-d2 { transition-delay: .18s; }
    .rev-d3 { transition-delay: .28s; }
    .rev-d4 { transition-delay: .38s; }

    /* ── WELCOME BANNER ──────────────────────── */
    .welcome-banner {
        background: linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(189,235,216,0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(189,235,216,0.1) 1px, transparent 1px);
        background-size: 32px 32px;
        pointer-events: none;
    }
    .welcome-orb {
        position: absolute; border-radius: 50%;
        filter: blur(50px); pointer-events: none;
    }
    .welcome-orb-1 {
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(47,143,121,0.4) 0%, transparent 70%);
        top: -60px; right: 5%;
        animation: orb-drift 12s ease-in-out infinite;
    }
    .welcome-orb-2 {
        width: 160px; height: 160px;
        background: radial-gradient(circle, rgba(242,189,94,0.25) 0%, transparent 70%);
        bottom: -40px; right: 30%;
        animation: orb-drift 16s ease-in-out 3s infinite reverse;
    }
    @keyframes orb-drift {
        0%, 100% { transform: translate(0,0); }
        33%       { transform: translate(-15px, 12px); }
        66%       { transform: translate(10px, -10px); }
    }

    /* ── STAT CARDS ──────────────────────────── */
    .stat-card {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 14px;
        padding: 20px;
        box-shadow: var(--ux2-shadow-soft);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 32px rgba(15,42,39,0.1);
    }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }

    /* ── PROPERTY CARD ───────────────────────── */
    .property-card {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--ux2-shadow-soft);
    }
    .property-img {
        position: relative; overflow: hidden;
    }
    .property-img img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .5s ease;
    }
    .property-card:hover .property-img img { transform: scale(1.04); }

    /* ── INFO ROW ────────────────────────────── */
    .info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--ux2-line);
        font-size: 14px;
    }
    .info-row:last-child { border-bottom: none; }

    /* ── BILLING CARD ────────────────────────── */
    .billing-card {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--ux2-shadow-soft);
    }
    .billing-header {
        background: linear-gradient(135deg, var(--ux2-primary), var(--ux2-primary-deep));
        padding: 20px;
    }

    /* ── BILLING URGENT ──────────────────────── */
    @keyframes pulse-urgent {
        0%, 100% { box-shadow: 0 0 0 0 rgba(217,95,85,0.4); }
        50%       { box-shadow: 0 0 0 8px rgba(217,95,85,0); }
    }
    .billing-urgent { animation: pulse-urgent 2s ease-in-out infinite; }

    /* ── KOSBOT CARD ─────────────────────────── */
    .kosbot-card {
        background: linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);
        border-radius: 16px; overflow: hidden;
        position: relative;
        box-shadow: 0 8px 24px rgba(12,38,40,0.18);
    }
    .kosbot-card::before {
        content: '';
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(189,235,216,0.08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(189,235,216,0.08) 1px, transparent 1px);
        background-size: 28px 28px;
    }
    @keyframes bot-float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-8px); }
    }
    .bot-avatar { animation: bot-float 4s ease-in-out infinite; }

    /* ── TICKET ITEM ─────────────────────────── */
    .ticket-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 12px 14px; border-radius: 10px;
        transition: background .2s ease;
    }
    .ticket-item:hover { background: var(--ux2-panel); }

    /* ── KOS SELECTOR ────────────────────────── */
    .kos-selector-card {
        border: 1.5px solid var(--ux2-line);
        border-radius: 12px;
        transition: border-color .22s ease, background .22s ease, transform .22s ease, box-shadow .22s ease;
        overflow: hidden;
    }
    .kos-selector-card:hover { transform: translateY(-2px); box-shadow: var(--ux2-shadow-soft); }
    .kos-selector-card.active {
        border-color: var(--ux2-secondary) !important;
        background: var(--ux2-primary-soft) !important;
        box-shadow: 0 0 0 3px rgba(47,143,121,0.16) !important;
    }

    /* ── SHIMMER BTN ─────────────────────────── */
    @keyframes shimmer { from { left:-80%; } to { left:140%; } }
    .btn-shimmer { position: relative; overflow: hidden; }
    .btn-shimmer::after {
        content: '';
        position: absolute; top:0; left:-80%;
        width:55%; height:100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
        transform: skewX(-18deg);
        animation: shimmer 2.8s ease-in-out 1s infinite;
    }

    /* ── AI INSIGHT ──────────────────────────── */
    @keyframes badge-pulse { 0%,100%{opacity:1} 50%{opacity:.6} }
    .ai-dot {
        width:7px; height:7px; border-radius:50%;
        background:var(--ux2-secondary);
        animation: badge-pulse 2s ease-in-out infinite;
    }
</style>
@endsection

@section('content')
@php
    $contract      = $activeContract;
    $room          = $contract?->room;
    $boardingHouse = $room?->boardingHouse;
@endphp

{{-- ════ KOS SELECTOR (multiple contracts) ════ --}}
@if($allActiveContracts->count() > 1)
<div class="mb-lg anim-fade-up">
    <div class="property-card p-md">
        <div class="flex items-center gap-3 mb-md">
            <div class="stat-icon" style="background:var(--ux2-primary-soft);">
                <span class="material-symbols-outlined" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">home_work</span>
            </div>
            <div>
                <h3 class="font-headline-md text-headline-md font-semibold" style="color:var(--ux2-ink);">Pilih Kos yang Ingin Dilihat</h3>
                <p style="font-size:13px; color:var(--ux2-muted);">Anda memiliki {{ $allActiveContracts->count() }} kos aktif</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($allActiveContracts as $c)
            <a href="?kos={{ $c->id }}"
                class="kos-selector-card flex items-center gap-3 p-sm {{ $activeContract?->id === $c->id ? 'active' : '' }}">
                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0" style="border:1px solid var(--ux2-line);">
                    @if($c->room?->image_url)
                        <img src="{{ $c->room->image_url }}" alt="{{ $c->room->boardingHouse->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="background:var(--ux2-panel);">
                            <span class="material-symbols-outlined" style="color:var(--ux2-muted);">bed</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-label-md text-label-md font-bold truncate" style="color:var(--ux2-ink);">{{ $c->room->boardingHouse->name }}</h4>
                    <p style="font-size:13px; color:var(--ux2-muted);">{{ $c->room->type_name }}</p>
                    <p style="font-size:12px; color:var(--ux2-muted);">{{ $c->room->boardingHouse->city }}</p>
                </div>
                @if($activeContract?->id === $c->id)
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0"
                    style="background:var(--ux2-secondary);">
                    <span class="material-symbols-outlined text-[14px]" style="color:#fff; font-variation-settings:'FILL' 1;">check</span>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ════ WELCOME BANNER ════ --}}
<div class="welcome-banner p-lg mb-lg anim-fade-up">
    <div class="welcome-orb welcome-orb-1"></div>
    <div class="welcome-orb welcome-orb-2"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-md">
        <div>
            <p style="font-size:13px; font-weight:600; color:rgba(255,255,255,0.6); letter-spacing:.05em; text-transform:uppercase; margin-bottom:6px;">Dashboard Penghuni</p>
            <h1 class="font-headline-lg text-headline-lg anim-fade-up d1" style="color:#fff; line-height:1.2;">
                Selamat Datang, {{ $tenant->name ?? 'Penghuni' }}! 👋
            </h1>
            <p class="mt-2 anim-fade-up d2" style="color:rgba(255,255,255,0.7); font-size:14px;">Berikut ringkasan aktivitas kos Anda hari ini.</p>
        </div>
        @if($boardingHouse)
        <div class="flex items-center gap-3 anim-scale-in d3 p-sm rounded-xl"
            style="background:rgba(255,255,255,0.12); backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.2);">
            <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:var(--ux2-secondary-soft);">
                <span class="material-symbols-outlined" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">home_work</span>
            </div>
            <div>
                <p class="font-label-sm text-label-sm" style="color:rgba(255,255,255,0.65);">Kos Aktif</p>
                <p class="font-label-md text-label-md font-bold" style="color:#fff;">{{ $boardingHouse->name }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ════ STAT CARDS ROW ════ --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-md mb-lg">
    {{-- Total Masa Sewa --}}
    <div class="stat-card anim-fade-up d2">
        <div class="flex items-center gap-3 mb-2">
            <div class="stat-icon" style="background:var(--ux2-primary-soft);">
                <span class="material-symbols-outlined" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">calendar_month</span>
            </div>
            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--ux2-muted);">Total Masa Sewa</p>
        </div>
        <p style="font-size:32px; font-weight:800; color:var(--ux2-ink); line-height:1;">
            {{ $durationMonths ?? '—' }}
            <span style="font-size:16px; font-weight:500; color:var(--ux2-muted);">Bln</span>
        </p>
        @if($contract)
        <p style="font-size:12px; color:var(--ux2-muted); margin-top:4px;">Berakhir: {{ $contract->end_date?->translatedFormat('d M Y') ?? '-' }}</p>
        @endif
    </div>

    {{-- Tiket Aktif --}}
    <div class="stat-card anim-fade-up d3">
        <div class="flex items-center gap-3 mb-2">
            <div class="stat-icon" style="background:var(--ux2-coral-soft);">
                <span class="material-symbols-outlined" style="color:var(--ux2-coral); font-variation-settings:'FILL' 1;">report_problem</span>
            </div>
            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--ux2-muted);">Tiket Aktif</p>
        </div>
        <p style="font-size:32px; font-weight:800; line-height:1;"
            style2="color:{{ ($ticketStats['active'] ?? 0) > 0 ? 'var(--ux2-coral)' : 'var(--ux2-ink)' }};"
            class="{{ ($ticketStats['active'] ?? 0) > 0 ? '' : '' }}">
            {{ $ticketStats['active'] ?? 0 }}
        </p>
        <p style="font-size:12px; color:var(--ux2-muted); margin-top:4px;">
            {{ ($ticketStats['active'] ?? 0) > 0 ? 'Sedang ditangani' : 'Tidak ada tiket aktif' }}
        </p>
    </div>

    {{-- Tiket Selesai --}}
    <div class="stat-card anim-fade-up d4">
        <div class="flex items-center gap-3 mb-2">
            <div class="stat-icon" style="background:var(--ux2-secondary-soft);">
                <span class="material-symbols-outlined" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">check_circle</span>
            </div>
            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--ux2-muted);">Tiket Selesai</p>
        </div>
        <p style="font-size:32px; font-weight:800; color:var(--ux2-ink); line-height:1;">{{ $ticketStats['resolved'] ?? 0 }}</p>
        <p style="font-size:12px; color:var(--ux2-muted); margin-top:4px;">Total diselesaikan</p>
    </div>
</div>

{{-- ════ MAIN CONTENT GRID ════ --}}
<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_360px] gap-lg">

    {{-- ── LEFT COLUMN ────────────────────── --}}
    <div class="flex flex-col gap-lg">

        {{-- Property Card --}}
        <div class="property-card reveal anim-fade-up d2">
            @if($contract)
                <div class="flex flex-col md:flex-row">
                    {{-- Image --}}
                    <div class="property-img w-full md:w-2/5 h-52 md:h-auto flex-shrink-0" style="min-height:200px;">
                        @if($room?->image_url)
                            <img src="{{ $room->image_url }}" alt="{{ $boardingHouse?->name ?? 'Kamar kos' }}" />
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background:var(--ux2-panel);">
                                <span class="material-symbols-outlined text-5xl" style="color:var(--ux2-muted);">home_work</span>
                            </div>
                        @endif
                        {{-- Status badge --}}
                        <div class="absolute bottom-3 left-3 flex items-center gap-1 px-3 py-1 rounded-full font-label-sm text-label-sm font-bold"
                            style="background:var(--ux2-secondary); color:#fff;">
                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">check_circle</span> Aktif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 p-md flex flex-col justify-between">
                        <div>
                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-sm mb-md">
                                <div>
                                    <h3 class="font-headline-md text-headline-md font-bold" style="color:var(--ux2-ink);">{{ $boardingHouse?->name ?? 'Belum ada hunian aktif' }}</h3>
                                    <p class="flex items-center gap-1 mt-1" style="font-size:14px; color:var(--ux2-muted);">
                                        <span class="material-symbols-outlined text-[16px]" style="color:var(--ux2-secondary);">location_on</span>
                                        {{ $boardingHouse ? $boardingHouse->city . ', ' . $boardingHouse->province : '-' }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full font-label-sm text-label-sm font-bold flex-shrink-0"
                                    style="background:var(--ux2-panel); color:var(--ux2-muted); border:1px solid var(--ux2-line);">
                                    {{ $room?->type_name ?? '-' }}
                                </span>
                            </div>

                            {{-- Info rows --}}
                            <div class="flex flex-col">
                                <div class="info-row">
                                    <span style="color:var(--ux2-muted);">No. Kontrak</span>
                                    <span class="font-bold" style="color:var(--ux2-ink);">{{ $contract?->contract_number ?? '-' }}</span>
                                </div>
                                <div class="info-row">
                                    <span style="color:var(--ux2-muted);">Masa Sewa Dimulai</span>
                                    <span class="font-bold" style="color:var(--ux2-ink);">{{ $contract?->start_date?->translatedFormat('d M Y') ?? '-' }}</span>
                                </div>
                                <div class="info-row">
                                    <span style="color:var(--ux2-muted);">Masa Sewa Berakhir</span>
                                    <span class="font-bold" style="color:var(--ux2-ink);">{{ $contract?->end_date?->translatedFormat('d M Y') ?? '-' }}</span>
                                </div>
                                <div class="info-row" style="border-bottom:none;">
                                    <span style="color:var(--ux2-muted);">Sisa Waktu Kontrak</span>
                                    <span class="font-bold" style="color:var(--ux2-primary);">{{ $remainingTime ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('ux2.tenant.contract') }}"
                            class="mt-md self-start inline-flex items-center gap-2 font-label-md text-label-md font-bold px-md py-sm rounded-xl transition-colors"
                            style="background:var(--ux2-primary-soft); color:var(--ux2-primary); border:1px solid var(--ux2-secondary-soft);"
                            onmouseover="this.style.background='var(--ux2-secondary-soft)'"
                            onmouseout="this.style.background='var(--ux2-primary-soft)'">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">description</span>
                            Lihat Detail Kos
                        </a>
                    </div>
                </div>
            @else
                {{-- No contract state --}}
                <div class="flex flex-col items-center justify-center py-xl px-md text-center">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mb-md" style="background:var(--ux2-panel);">
                        <span class="material-symbols-outlined text-5xl" style="color:var(--ux2-muted);">home_work</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md font-bold mb-sm" style="color:var(--ux2-ink);">Belum Ada Hunian Aktif</h3>
                    <p style="font-size:14px; color:var(--ux2-muted); max-width:320px; line-height:1.6; margin-bottom:20px;">Anda belum memiliki kontrak hunian yang aktif. Mulai cari kos impianmu sekarang!</p>
                    <a href="{{ route('ux2.search') }}"
                        class="btn-shimmer inline-flex items-center gap-2 px-lg py-sm font-label-md text-label-md font-bold rounded-xl"
                        style="background:var(--ux2-primary); color:#fff;">
                        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">search</span> Cari Kos
                    </a>
                </div>
            @endif
        </div>

        {{-- KosBot AI Card --}}
        <div class="kosbot-card p-lg reveal rev-d1">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-lg">
                <div class="bot-avatar flex-shrink-0">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center"
                        style="background:rgba(255,255,255,0.15); border:2px solid rgba(255,255,255,0.25);">
                        <span class="material-symbols-outlined text-4xl" style="color:#fff; font-variation-settings:'FILL' 1;">smart_toy</span>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 mb-sm px-3 py-1 rounded-full"
                        style="background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2);">
                        <span class="material-symbols-outlined text-[14px]" style="color:var(--ux2-accent); font-variation-settings:'FILL' 1;">auto_awesome</span>
                        <span style="font-size:11px; font-weight:700; color:var(--ux2-accent); text-transform:uppercase; letter-spacing:.06em;">AI Powered</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md font-bold mb-2" style="color:#fff;">Tanya KosBot AI</h3>
                    <p style="font-size:14px; color:rgba(255,255,255,0.7); line-height:1.6; margin-bottom:16px;">
                        Butuh bantuan seputar kos atau ada pertanyaan teknis? KosBot siap membantu 24/7.
                    </p>
                    <a href="{{ route('ux2.bot') }}"
                        class="btn-shimmer inline-flex items-center gap-2 px-lg py-sm font-label-md text-label-md font-bold rounded-xl"
                        style="background:#fff; color:var(--ux2-primary);">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">chat</span>
                        Mulai Chat
                    </a>
                </div>
            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ── RIGHT COLUMN ───────────────────── --}}
    <div class="flex flex-col gap-lg">

        {{-- Billing Card --}}
        <div class="billing-card reveal anim-scale-in d3">
            {{-- Header --}}
            <div class="billing-header">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined" style="color:var(--ux2-accent); font-variation-settings:'FILL' 1;">payments</span>
                    <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:rgba(255,255,255,0.65);">Tagihan Mendatang</p>
                </div>
                @if($upcomingPayment)
                    @php
                        $payStatus = $upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status;
                        $isPending = $payStatus === 'pending';
                        $isUrgent  = $isPending && $daysUntilDue !== null && $daysUntilDue <= 3;
                    @endphp
                    <p style="font-size:11px; color:rgba(255,255,255,0.55); margin-bottom:4px;">
                        {{ $upcomingPayment->contract->room->boardingHouse->name ?? 'Sewa Bulanan' }} (Bulan ke-{{ $upcomingPayment->billing_month }})
                    </p>
                    <p style="font-size:30px; font-weight:800; color:#fff; line-height:1.1;">
                        Rp {{ number_format($upcomingPayment->amount, 0, ',', '.') }}
                    </p>
                    <p class="flex items-center gap-1 mt-2" style="font-size:13px; color:rgba(255,255,255,0.65);">
                        <span class="material-symbols-outlined text-[15px]">schedule</span>
                        Jatuh tempo: {{ $upcomingPayment?->due_date?->translatedFormat('d M Y') ?? '-' }}
                    </p>
                @else
                    <p style="font-size:28px; font-weight:800; color:#fff; line-height:1.1;">Semua Lunas ✓</p>
                    <p style="font-size:13px; color:rgba(255,255,255,0.65); margin-top:4px;">Tidak ada tagihan menunggu.</p>
                @endif
            </div>

            {{-- Body --}}
            <div class="p-md flex flex-col gap-sm">
                @if($upcomingPayment)
                    @php
                        $statusLabel = match($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) {
                            'pending'           => ['label' => 'Menunggu Pembayaran', 'bg' => 'var(--ux2-coral-soft)',    'color' => 'var(--ux2-coral)'],
                            'paid_to_escrow'    => ['label' => 'Dalam Escrow',        'bg' => 'var(--ux2-secondary-soft)', 'color' => 'var(--ux2-primary)'],
                            'released_to_owner' => ['label' => 'Lunas',               'bg' => 'var(--ux2-secondary-soft)', 'color' => 'var(--ux2-primary)'],
                            'cancelled'         => ['label' => 'Dibatalkan',          'bg' => 'var(--ux2-coral-soft)',    'color' => 'var(--ux2-coral)'],
                            default             => ['label' => 'Tidak Diketahui',     'bg' => 'var(--ux2-panel)',          'color' => 'var(--ux2-muted)'],
                        };
                    @endphp

                    {{-- Status chip --}}
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full font-label-sm text-label-sm font-bold"
                            style="background:{{ $statusLabel['bg'] }}; color:{{ $statusLabel['color'] }};">
                            {{ $statusLabel['label'] }}
                        </span>

                        {{-- Urgency warning --}}
                        @if($isUrgent)
                        <span class="billing-urgent px-3 py-1 rounded-full font-label-sm text-label-sm font-bold"
                            style="background:var(--ux2-coral-soft); color:var(--ux2-coral); border:1px solid rgba(217,95,85,0.3);">
                            {{ $daysUntilDue == 0 ? 'Jatuh tempo hari ini!' : "≤ {$daysUntilDue} hari lagi" }}
                        </span>
                        @elseif($daysUntilDue !== null && $isPending)
                        <span style="font-size:12px; color:var(--ux2-muted);">{{ $daysUntilDue }} hari lagi</span>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center py-sm text-center">
                        <span class="material-symbols-outlined text-4xl mb-2" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">check_circle</span>
                        <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">Tidak Ada Tagihan</p>
                        <p style="font-size:13px; color:var(--ux2-muted);">Semua tagihan Anda sudah lunas.</p>
                    </div>
                @endif

                <a href="{{ route('ux2.tenant.payments') }}"
                    class="btn-shimmer w-full py-sm font-label-md text-label-md font-bold rounded-xl flex justify-center items-center gap-2 transition-colors"
                    style="background:var(--ux2-primary); color:#fff;"
                    onmouseover="this.style.background='var(--ux2-primary-deep)'"
                    onmouseout="this.style.background='var(--ux2-primary)'">
                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">
                        {{ $upcomingPayment && ($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) === 'pending' ? 'payment' : 'receipt_long' }}
                    </span>
                    {{ $upcomingPayment && ($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) === 'pending' ? 'Bayar Sekarang' : ($upcomingPayment ? 'Lihat Detail Tagihan' : 'Riwayat Tagihan') }}
                </a>
            </div>
        </div>

        {{-- Recent Tickets --}}
        <div class="property-card reveal rev-d2">
            <div class="flex items-center justify-between p-md" style="border-bottom:1px solid var(--ux2-line);">
                <div class="flex items-center gap-2">
                    <div class="stat-icon" style="background:var(--ux2-coral-soft);">
                        <span class="material-symbols-outlined" style="color:var(--ux2-coral); font-variation-settings:'FILL' 1;">build</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md font-bold" style="color:var(--ux2-ink); font-size:18px;">Status Laporan</h3>
                </div>
                <a href="{{ route('ux2.tenant.tickets') }}" class="font-label-md text-label-md font-bold transition-colors"
                    style="color:var(--ux2-secondary);"
                    onmouseover="this.style.color='var(--ux2-primary)'"
                    onmouseout="this.style.color='var(--ux2-secondary)'">Lihat Semua</a>
            </div>

            <div class="p-sm flex flex-col gap-1">
                @forelse ($recentTickets as $ticket)
                @php
                    $ticketStatus = match($ticket->status->value ?? $ticket->status) {
                        'reported'    => ['label' => 'Dilaporkan', 'color' => 'var(--ux2-muted)',     'bg' => 'var(--ux2-panel)'],
                        'in_progress' => ['label' => 'Diproses',   'color' => 'var(--ux2-primary)',   'bg' => 'var(--ux2-primary-soft)'],
                        'resolved'    => ['label' => 'Selesai',    'color' => 'var(--ux2-secondary)', 'bg' => 'var(--ux2-secondary-soft)'],
                        default       => ['label' => 'Unknown',    'color' => 'var(--ux2-muted)',     'bg' => 'var(--ux2-panel)'],
                    };
                @endphp
                <div class="ticket-item">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                        style="background:var(--ux2-panel);">
                        <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-muted);">build</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="font-label-md text-label-md font-bold truncate" style="color:var(--ux2-ink);">{{ $ticket->title }}</h4>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold flex-shrink-0"
                                style="background:{{ $ticketStatus['bg'] }}; color:{{ $ticketStatus['color'] }};">
                                {{ $ticketStatus['label'] }}
                            </span>
                        </div>
                        <p style="font-size:12px; color:var(--ux2-muted); margin-top:2px;">{{ $ticket->created_at?->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center py-lg text-center">
                    <span class="material-symbols-outlined text-4xl mb-sm" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">check_circle</span>
                    <p class="font-label-md text-label-md font-bold mb-1" style="color:var(--ux2-ink);">Tidak Ada Laporan</p>
                    <p style="font-size:13px; color:var(--ux2-muted);">Belum ada laporan kerusakan.</p>
                </div>
                @endforelse

                {{-- AI Insight --}}
                <div class="flex items-center gap-3 p-sm rounded-xl mt-2"
                    style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
                    <div class="flex flex-col items-center gap-1 flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">tips_and_updates</span>
                        <div class="ai-dot"></div>
                    </div>
                    <p style="font-size:13px; color:var(--ux2-muted); line-height:1.5;">
                        @if(($ticketStats['total'] ?? 0) === 0)
                            Riwayat maintenance rendah. Nikmati kenyamanan maksimal!
                        @elseif(($ticketStats['active'] ?? 0) > 0)
                            Anda memiliki <strong style="color:var(--ux2-primary);">{{ $ticketStats['active'] }}</strong> laporan aktif yang sedang ditangani.
                        @else
                            Semua laporan kerusakan terselesaikan. Terima kasih!
                        @endif
                    </p>
                </div>
            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end main grid --}}

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /* Scroll reveal */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
    document.querySelectorAll('.reveal').forEach(el => ro.observe(el));
});
</script>
@endsection
