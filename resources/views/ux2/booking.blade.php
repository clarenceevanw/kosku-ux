@extends('layouts.ux2.app')

@section('title', 'Booking Kos - ' . ($boardingHouse['name'] ?? 'KosKu'))

@section('styles')
<style>
    /* ── PAGE ANIMATIONS ─────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes slide-right {
        from { opacity: 0; transform: translateX(-20px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes scale-pop {
        from { opacity: 0; transform: scale(0.88); }
        to   { opacity: 1; transform: scale(1); }
    }
    .anim-fade-up    { animation: fade-up    0.55s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in    { animation: fade-in    0.4s  ease both; }
    .anim-slide-r    { animation: slide-right 0.5s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-scale-pop  { animation: scale-pop  0.5s cubic-bezier(.22,.68,0,1.2) both; }
    .d1 { animation-delay: .07s; }
    .d2 { animation-delay: .14s; }
    .d3 { animation-delay: .21s; }
    .d4 { animation-delay: .28s; }
    .d5 { animation-delay: .35s; }
    .d6 { animation-delay: .42s; }

    /* ── SCROLL REVEAL ───────────────────────── */
    .reveal {
        opacity: 0; transform: translateY(22px);
        transition: opacity .55s cubic-bezier(.22,.68,0,1.1), transform .55s cubic-bezier(.22,.68,0,1.1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .rev-d1 { transition-delay: .08s; }
    .rev-d2 { transition-delay: .16s; }
    .rev-d3 { transition-delay: .24s; }

    /* ── STEP INDICATOR ──────────────────────── */
    .step-line {
        flex: 1; height: 2px;
        background: var(--ux2-line);
        border-radius: 2px;
        transition: background 0.3s;
    }
    .step-bubble {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px;
        transition: all 0.3s;
        flex-shrink: 0;
    }
    .step-bubble.active {
        background: var(--ux2-primary); color: #fff;
        box-shadow: 0 4px 14px rgba(20,60,58,0.28);
    }
    .step-bubble.done {
        background: var(--ux2-secondary); color: #fff;
    }
    .step-bubble.pending {
        background: var(--ux2-panel); color: var(--ux2-muted);
        border: 2px solid var(--ux2-line);
    }

    /* ── RADIO: ROOM CARD ────────────────────── */
    input[type="radio"]:checked + .room-card {
        border-color: var(--ux2-secondary) !important;
        background-color: var(--ux2-primary-soft) !important;
        box-shadow: 0 0 0 3px rgba(47,143,121,0.16);
    }
    input[type="radio"]:checked + .room-card .radio-dot {
        border-color: var(--ux2-secondary) !important;
        background-color: var(--ux2-secondary) !important;
    }
    input[type="radio"]:checked + .room-card .radio-dot .check-icon {
        display: block;
    }

    /* ── RADIO: DURATION CARD ────────────────── */
    input[type="radio"]:checked + .duration-card {
        border-color: var(--ux2-secondary) !important;
        background: linear-gradient(135deg, var(--ux2-primary-soft), var(--ux2-paper)) !important;
        box-shadow: 0 0 0 3px rgba(47,143,121,0.16);
    }
    input[type="radio"]:checked + .duration-card .dur-number {
        color: var(--ux2-primary) !important;
    }

    .room-card {
        transition: border-color .22s ease, background .22s ease, box-shadow .22s ease, transform .22s ease;
    }
    .room-card:hover { transform: translateX(4px); }

    .duration-card {
        transition: border-color .22s ease, background .22s ease, box-shadow .22s ease, transform .22s ease;
        position: relative;
    }
    .duration-card:hover { transform: translateY(-3px); box-shadow: var(--ux2-shadow-soft); }

    /* ── SECTION CARD ─────────────────────────── */
    .booking-section {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        padding: 28px;
        box-shadow: var(--ux2-shadow-soft);
    }
    .section-title {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 20px; padding-bottom: 16px;
        border-bottom: 1px solid var(--ux2-line);
    }
    .section-num {
        width: 28px; height: 28px; border-radius: 50%;
        background: var(--ux2-primary-soft);
        color: var(--ux2-primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 800;
        flex-shrink: 0;
    }

    /* ── SUMMARY PANEL ───────────────────────── */
    .summary-panel {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--ux2-shadow);
    }
    .summary-header {
        background: linear-gradient(135deg, var(--ux2-primary), var(--ux2-primary-deep));
        padding: 22px 24px; color: #fff;
    }

    /* ── DATE INPUT ──────────────────────────── */
    .date-input-wrap {
        position: relative;
    }
    .date-input-wrap input[type="date"] {
        padding-left: 48px;
        height: 52px;
        background: var(--ux2-paper) !important;
        border: 1.5px solid var(--ux2-line) !important;
        border-radius: 12px !important;
        color: var(--ux2-ink) !important;
        font-size: 15px;
        transition: border-color .22s ease, box-shadow .22s ease;
    }
    .date-input-wrap input[type="date"]:focus {
        border-color: var(--ux2-secondary) !important;
        box-shadow: 0 0 0 3px rgba(47,143,121,0.16) !important;
        outline: none;
    }
    .date-input-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--ux2-secondary); pointer-events: none;
    }

    /* ── SUBMIT SHIMMER ──────────────────────── */
    @keyframes shimmer {
        from { left: -80%; }
        to   { left: 140%; }
    }
    .btn-submit {
        position: relative; overflow: hidden;
        transition: background .22s ease, transform .12s ease, box-shadow .22s ease;
    }
    .btn-submit::after {
        content: '';
        position: absolute; top: 0; left: -80%;
        width: 55%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
        transform: skewX(-18deg);
        animation: shimmer 2.8s ease-in-out 1s infinite;
    }
    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(20,60,58,0.22);
    }
    .btn-submit:active { transform: scale(0.98); }

    /* ── FLASH MESSAGES ──────────────────────── */
    @keyframes flash-slide {
        from { opacity:0; transform: translateY(-10px); }
        to   { opacity:1; transform: translateY(0); }
    }
    .flash-msg { animation: flash-slide 0.35s ease both; }

    /* ── SPINNER ─────────────────────────────── */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .animate-spin { animation: spin 0.8s linear infinite; display: inline-block; }
</style>
@endsection

@section('content')
@php
    $rooms           = collect($boardingHouse['rooms'] ?? []);
    $firstRoom       = $rooms->first();
    $requestedRoomId = old('room_id', request('room_id'));
    $selectedRoomId  = $rooms->contains(fn ($room) => $room['id'] === $requestedRoomId && $room['is_available'])
        ? $requestedRoomId
        : ($rooms->firstWhere('is_available', true)['id'] ?? $firstRoom['id'] ?? null);
@endphp

{{-- ════ HERO BANNER ════ --}}
<div class="w-full anim-fade-in" style="background:linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);">
    <div class="max-w-[1440px] mx-auto px-margin-mobile md:px-margin-desktop py-lg">
        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-1 mb-md anim-slide-r d1" style="font-size:13px; color:rgba(255,255,255,0.65);">
            <a href="{{ route('ux2.search') }}" class="hover:text-white transition-colors" style="color:rgba(255,255,255,0.65);">Cari Kos</a>
            <span class="material-symbols-outlined text-sm" style="color:rgba(255,255,255,0.4);">chevron_right</span>
            <a href="{{ route('ux2.kos.show', $boardingHouse['id']) }}" class="hover:text-white transition-colors" style="color:rgba(255,255,255,0.65);">{{ $boardingHouse['name'] }}</a>
            <span class="material-symbols-outlined text-sm" style="color:rgba(255,255,255,0.4);">chevron_right</span>
            <span style="color:#fff; font-weight:600;">Booking</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-md">
            <div class="anim-fade-up d2">
                <a href="{{ route('ux2.kos.show', $boardingHouse['id']) }}"
                    class="inline-flex items-center gap-1 mb-sm group transition-colors"
                    style="color:rgba(255,255,255,0.65); font-size:13px; font-weight:600;"
                    onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">
                    <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    Kembali ke Detail Kos
                </a>
                <h1 class="font-headline-lg text-headline-lg" style="color:#fff; line-height:1.15;">Booking Kos</h1>
                <p class="mt-1" style="color:rgba(255,255,255,0.7); font-size:14px;">
                    {{ $boardingHouse['name'] }} &mdash; {{ $boardingHouse['address'] }}, {{ $boardingHouse['city'] }}
                </p>
            </div>

            {{-- Step indicator --}}
            <div class="flex items-center gap-2 anim-fade-up d3 md:min-w-[280px]">
                <div class="step-bubble active">1</div>
                <div class="step-line" style="background:rgba(255,255,255,0.3);"></div>
                <div class="step-bubble pending" style="border-color:rgba(255,255,255,0.35); color:rgba(255,255,255,0.6); background:rgba(255,255,255,0.12);">2</div>
                <div class="step-line" style="background:rgba(255,255,255,0.3);"></div>
                <div class="step-bubble pending" style="border-color:rgba(255,255,255,0.35); color:rgba(255,255,255,0.6); background:rgba(255,255,255,0.12);">3</div>
                <div class="ml-2" style="color:rgba(255,255,255,0.7); font-size:12px; font-weight:600; white-space:nowrap;">Pilih &rarr; Bayar &rarr; Selesai</div>
            </div>
        </div>
    </div>
</div>

{{-- ════ MAIN CONTENT ════ --}}
<div class="max-w-[1440px] mx-auto px-margin-mobile md:px-margin-desktop py-lg">

    {{-- Flash messages --}}
    @if(session('error'))
    <div class="flash-msg mb-lg flex items-center gap-3 p-md rounded-xl border"
        style="background:var(--ux2-coral-soft); border-color:rgba(217,95,85,0.3); color:var(--ux2-coral);">
        <span class="material-symbols-outlined flex-shrink-0" style="font-variation-settings:'FILL' 1;">error</span>
        <p class="font-body-md text-body-md">{{ session('error') }}</p>
    </div>
    @endif

    @if(session('success'))
    <div class="flash-msg mb-lg flex items-center gap-3 p-md rounded-xl border"
        style="background:var(--ux2-secondary-soft); border-color:rgba(47,143,121,0.3); color:var(--ux2-primary);">
        <span class="material-symbols-outlined flex-shrink-0" style="font-variation-settings:'FILL' 1; color:var(--ux2-secondary);">check_circle</span>
        <p class="font-body-md text-body-md">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Two-column layout --}}
    <form action="{{ route('ux2.booking.store') }}" method="POST" id="bookingForm">
        @csrf
        <input type="hidden" name="boarding_house_id" value="{{ $boardingHouse['id'] }}">

        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_340px] gap-xl items-start">

            {{-- ══ LEFT: Form sections ══ --}}
            <div class="flex flex-col gap-lg">

                {{-- ① Pilih Kamar --}}
                <div class="booking-section reveal anim-fade-up d2">
                    <div class="section-title">
                        <div class="section-num">1</div>
                        <div>
                            <h2 class="font-headline-md text-headline-md" style="color:var(--ux2-ink); font-size:20px;">Pilih Tipe Kamar</h2>
                            <p style="font-size:12px; color:var(--ux2-muted);">Pilih tipe kamar yang sesuai kebutuhan Anda</p>
                        </div>
                        <span class="material-symbols-outlined ml-auto" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">bed</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        @foreach($boardingHouse['rooms'] as $index => $room)
                        <label class="relative flex items-center gap-4 cursor-pointer {{ !$room['is_available'] ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <input type="radio" name="room_id" value="{{ $room['id'] }}" class="sr-only peer"
                                   data-price="{{ $room['price_per_month'] }}"
                                   data-name="{{ $room['type_name'] }}"
                                   required
                                   {{ $room['id'] === $selectedRoomId ? 'checked' : '' }}
                                   {{ !$room['is_available'] ? 'disabled' : '' }}>
                            <div class="room-card flex-1 flex items-center gap-4 p-md border-2 rounded-xl"
                                style="border-color:var(--ux2-line); background:var(--ux2-paper);">

                                {{-- Room image --}}
                                @if($room['image_url'])
                                <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl overflow-hidden flex-shrink-0" style="border:1px solid var(--ux2-line);">
                                    <img src="{{ $room['image_url'] }}" alt="{{ $room['type_name'] }}" class="w-full h-full object-cover">
                                </div>
                                @else
                                <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background:var(--ux2-panel); border:1px solid var(--ux2-line);">
                                    <span class="material-symbols-outlined text-3xl" style="color:var(--ux2-muted);">meeting_room</span>
                                </div>
                                @endif

                                {{-- Room info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <h3 class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink); font-size:15px;">{{ $room['type_name'] }}</h3>
                                        @if($room['is_available'])
                                            <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm"
                                                style="background:var(--ux2-secondary-soft); color:var(--ux2-primary); font-size:11px;">
                                                {{ $room['stock'] }} tersedia
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm"
                                                style="background:var(--ux2-coral-soft); color:var(--ux2-coral); font-size:11px;">Penuh</span>
                                        @endif
                                    </div>
                                    @if($room['size'])
                                    <p class="font-body-md text-body-md mb-1" style="color:var(--ux2-muted); font-size:13px;">{{ $room['size'] }}</p>
                                    @endif
                                    <p style="font-size:18px; font-weight:700; color:var(--ux2-primary);">
                                        {{ $room['price_formatted'] }}
                                        <span style="font-size:12px; font-weight:400; color:var(--ux2-muted);">/bulan</span>
                                    </p>
                                </div>

                                {{-- Radio dot --}}
                                <div class="radio-dot w-6 h-6 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                                    style="border-color:var(--ux2-line);">
                                    <span class="check-icon material-symbols-outlined text-[16px] hidden" style="color:#fff; font-variation-settings:'FILL' 1;">check</span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    @error('room_id')
                    <div class="mt-3 flex items-center gap-2" style="color:var(--ux2-coral);">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <span class="font-label-md text-label-md">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- ② Durasi Sewa --}}
                <div class="booking-section reveal rev-d1 anim-fade-up d3">
                    <div class="section-title">
                        <div class="section-num">2</div>
                        <div>
                            <h2 class="font-headline-md text-headline-md" style="color:var(--ux2-ink); font-size:20px;">Durasi Sewa</h2>
                            <p style="font-size:12px; color:var(--ux2-muted);">Pilih berapa bulan Anda ingin menyewa</p>
                        </div>
                        <span class="material-symbols-outlined ml-auto" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">calendar_month</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach([1, 3, 6, 12] as $months)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="duration_months" value="{{ $months }}" class="sr-only peer" required {{ $months === 6 ? 'checked' : '' }}>
                            <div class="duration-card p-md border-2 rounded-xl text-center"
                                style="border-color:var(--ux2-line); background:var(--ux2-paper);">
                                <p class="dur-number font-bold" style="font-size:28px; line-height:1; color:var(--ux2-ink);">{{ $months }}</p>
                                <p style="font-size:12px; color:var(--ux2-muted); margin-top:4px;">Bulan</p>
                                @if($months === 6)
                                <span class="absolute -top-2 left-1/2 -translate-x-1/2 px-2 py-0.5 rounded-full font-label-sm text-label-sm"
                                    style="background:var(--ux2-secondary); color:#fff; font-size:10px; white-space:nowrap;">Populer</span>
                                @endif
                                @if($months === 12)
                                <span class="absolute -top-2 left-1/2 -translate-x-1/2 px-2 py-0.5 rounded-full font-label-sm text-label-sm"
                                    style="background:var(--ux2-accent); color:var(--ux2-ink); font-size:10px; white-space:nowrap;">Hemat</span>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>

                    @error('duration_months')
                    <div class="mt-3 flex items-center gap-2" style="color:var(--ux2-coral);">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <span class="font-label-md text-label-md">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- ③ Tanggal Mulai --}}
                <div class="booking-section reveal rev-d2 anim-fade-up d4">
                    <div class="section-title">
                        <div class="section-num">3</div>
                        <div>
                            <h2 class="font-headline-md text-headline-md" style="color:var(--ux2-ink); font-size:20px;">Tanggal Mulai Sewa</h2>
                            <p style="font-size:12px; color:var(--ux2-muted);">Kapan Anda ingin mulai menempati?</p>
                        </div>
                        <span class="material-symbols-outlined ml-auto" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">event</span>
                    </div>

                    <div class="date-input-wrap">
                        <span class="material-symbols-outlined date-input-icon" style="font-variation-settings:'FILL' 1;">calendar_today</span>
                        <input type="date" name="start_date" id="startDateInput"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               value="{{ request('start_date', date('Y-m-d', strtotime('+7 days'))) }}"
                               class="w-full"
                               required>
                    </div>
                    <p class="mt-sm flex items-center gap-1 font-label-sm text-label-sm" style="color:var(--ux2-muted);">
                        <span class="material-symbols-outlined text-[14px]">info</span>
                        Tanggal mulai minimal 1 hari dari sekarang
                    </p>

                    @error('start_date')
                    <div class="mt-3 flex items-center gap-2" style="color:var(--ux2-coral);">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <span class="font-label-md text-label-md">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- CTA row (mobile — shown only below lg) --}}
                <div class="flex flex-col md:flex-row gap-3 lg:hidden reveal rev-d3">
                    <a href="{{ route('ux2.kos.show', $boardingHouse['id']) }}"
                        class="flex-1 font-label-md text-label-md font-bold py-md rounded-xl text-center flex justify-center items-center gap-2 transition-colors"
                        style="background:var(--ux2-panel); color:var(--ux2-ink); border:1px solid var(--ux2-line);"
                        onmouseover="this.style.background='var(--ux2-panel-strong)'"
                        onmouseout="this.style.background='var(--ux2-panel)'">
                        <span class="material-symbols-outlined text-[18px]">close</span> Batal
                    </a>
                    <button type="submit" id="submitBtnMobile"
                        class="btn-submit flex-1 font-label-md text-label-md font-bold py-md rounded-xl flex justify-center items-center gap-2"
                        style="background:var(--ux2-primary); color:#fff;">
                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">check_circle</span>
                        Lanjut ke Pembayaran
                    </button>
                </div>

            </div>{{-- end left --}}

            {{-- ══ RIGHT: Summary panel ══ --}}
            <div class="lg:sticky lg:top-28 flex flex-col gap-md anim-scale-pop d3">

                <div class="summary-panel">
                    {{-- Header --}}
                    <div class="summary-header">
                        <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:rgba(255,255,255,0.6); margin-bottom:6px;">Ringkasan Booking</p>
                        <p class="font-headline-md text-headline-md" style="color:#fff; font-size:18px; font-weight:700; line-height:1.3;">{{ $boardingHouse['name'] }}</p>
                        <p class="mt-1 flex items-center gap-1" style="color:rgba(255,255,255,0.65); font-size:12px;">
                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                            {{ $boardingHouse['city'] }}
                        </p>
                    </div>

                    {{-- Body --}}
                    <div class="p-md flex flex-col gap-md">

                        {{-- Selected room summary --}}
                        <div class="flex flex-col gap-2">
                            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--ux2-muted);">Tipe Kamar Dipilih</p>
                            <div class="flex items-center gap-2 p-sm rounded-xl" style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
                                <span class="material-symbols-outlined flex-shrink-0" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">bed</span>
                                <span id="summaryRoomName" class="font-label-md text-label-md font-bold" style="color:var(--ux2-primary);">—</span>
                            </div>
                        </div>

                        {{-- Duration summary --}}
                        <div class="flex flex-col gap-2">
                            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--ux2-muted);">Durasi Sewa</p>
                            <div class="flex items-center gap-2 p-sm rounded-xl" style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
                                <span class="material-symbols-outlined flex-shrink-0" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">calendar_month</span>
                                <span id="summaryDuration" class="font-label-md text-label-md font-bold" style="color:var(--ux2-primary);">6 Bulan</span>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div style="height:1px; background:var(--ux2-line);"></div>

                        {{-- Price total --}}
                        <div>
                            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--ux2-muted); margin-bottom:6px;">Estimasi Total</p>
                            <p id="summaryTotal" style="font-size:28px; font-weight:800; color:var(--ux2-primary); line-height:1;">Rp —</p>
                            <p style="font-size:12px; color:var(--ux2-muted); margin-top:2px;">*Harga dapat berubah sesuai kamar</p>
                        </div>

                        {{-- CTA (desktop) --}}
                        <div class="flex flex-col gap-sm hidden lg:flex">
                            <button type="submit" id="submitBtn"
                                class="btn-submit w-full font-label-md text-label-md font-bold py-md rounded-xl flex justify-center items-center gap-2"
                                style="background:var(--ux2-primary); color:#fff;">
                                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">check_circle</span>
                                Lanjut ke Pembayaran
                            </button>
                            <a href="{{ route('ux2.kos.show', $boardingHouse['id']) }}"
                                class="w-full font-label-md text-label-md font-bold py-sm rounded-xl flex justify-center items-center gap-2 transition-colors"
                                style="color:var(--ux2-muted); background:var(--ux2-paper); border:1px solid var(--ux2-line); text-align:center;"
                                onmouseover="this.style.background='var(--ux2-panel)'"
                                onmouseout="this.style.background='var(--ux2-paper)'">
                                <span class="material-symbols-outlined text-[16px]">close</span> Batal
                            </a>
                        </div>

                        {{-- Verified badge --}}
                        <div class="flex items-start gap-2 p-sm rounded-xl"
                            style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
                            <span class="material-symbols-outlined mt-0.5 flex-shrink-0" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">verified_user</span>
                            <div>
                                <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-primary);">KosKu Verified</p>
                                <p style="font-size:12px; color:var(--ux2-muted); line-height:1.5; margin-top:2px;">Transaksi aman & terverifikasi</p>
                            </div>
                        </div>

                    </div>{{-- end panel body --}}
                </div>

            </div>{{-- end right --}}

        </div>{{-- end grid --}}
    </form>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Original: prevent double submit ── */
    const form       = document.getElementById('bookingForm');
    const submitBtn  = document.getElementById('submitBtn');
    const submitBtnM = document.getElementById('submitBtnMobile');

    form.addEventListener('submit', function () {
        const spinHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> Memproses...';
        if (submitBtn)  { submitBtn.disabled  = true; submitBtn.innerHTML  = spinHTML; }
        if (submitBtnM) { submitBtnM.disabled = true; submitBtnM.innerHTML = spinHTML; }
    });

    /* ── New: live summary panel ── */
    const roomRadios     = document.querySelectorAll('input[name="room_id"]');
    const durationRadios = document.querySelectorAll('input[name="duration_months"]');
    const summaryRoom    = document.getElementById('summaryRoomName');
    const summaryDur     = document.getElementById('summaryDuration');
    const summaryTotal   = document.getElementById('summaryTotal');

    let currentPrice    = 0;
    let currentDuration = 6;

    function formatRupiah(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function updateSummary() {
        if (summaryTotal) {
            summaryTotal.textContent = currentPrice > 0
                ? formatRupiah(currentPrice * currentDuration)
                : 'Rp —';
        }
    }

    roomRadios.forEach(r => {
        r.addEventListener('change', function () {
            if (summaryRoom) summaryRoom.textContent = this.dataset.name || '—';
            currentPrice = parseInt(this.dataset.price || '0', 10);
            updateSummary();
        });
        if (r.checked) {
            if (summaryRoom) summaryRoom.textContent = r.dataset.name || '—';
            currentPrice = parseInt(r.dataset.price || '0', 10);
        }
    });

    durationRadios.forEach(r => {
        r.addEventListener('change', function () {
            currentDuration = parseInt(this.value, 10);
            if (summaryDur) summaryDur.textContent = currentDuration + ' Bulan';
            updateSummary();
        });
        if (r.checked) {
            currentDuration = parseInt(r.value, 10);
            if (summaryDur) summaryDur.textContent = currentDuration + ' Bulan';
        }
    });

    updateSummary();

    /* ── New: scroll reveal ── */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
    document.querySelectorAll('.reveal').forEach(el => ro.observe(el));
});
</script>
@endsection
