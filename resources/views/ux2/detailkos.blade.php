@extends('layouts.ux2.app')

@section('title', ($boardingHouse['name'] ?? 'Detail Kos') . ' - KosKu')

@section('styles')
<style>
    /* ── PAGE-IN ANIMATIONS ───────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.92); }
        to   { opacity: 1; transform: scale(1); }
    }

    .anim-fade-up   { animation: fade-up  0.6s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in   { animation: fade-in  0.5s ease both; }
    .anim-scale-in  { animation: scale-in 0.55s cubic-bezier(.22,.68,0,1.2) both; }

    .delay-1 { animation-delay: .08s; }
    .delay-2 { animation-delay: .18s; }
    .delay-3 { animation-delay: .28s; }
    .delay-4 { animation-delay: .38s; }
    .delay-5 { animation-delay: .48s; }
    .delay-6 { animation-delay: .58s; }

    /* ── SCROLL REVEAL ────────────────────────────── */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s cubic-bezier(.22,.68,0,1.1), transform 0.6s cubic-bezier(.22,.68,0,1.1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-d1 { transition-delay: .1s; }
    .reveal-d2 { transition-delay: .2s; }
    .reveal-d3 { transition-delay: .3s; }
    .reveal-d4 { transition-delay: .4s; }

    /* ── GALLERY ──────────────────────────────────── */
    .gallery-hero {
        position: relative;
        overflow: hidden;
        background: var(--ux2-panel);
    }
    .gallery-hero img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .gallery-hero:hover img { transform: scale(1.04); }
    .gallery-thumb {
        position: relative; overflow: hidden;
        background: var(--ux2-panel); cursor: pointer;
    }
    .gallery-thumb img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }
    .gallery-thumb:hover img { transform: scale(1.09); }
    .gallery-thumb .overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,0.42);
        display: flex; align-items: center; justify-content: center;
        transition: background 0.2s ease;
    }
    .gallery-thumb:hover .overlay { background: rgba(0,0,0,0.28); }

    /* ── FACILITY CARD ────────────────────────────── */
    .facility-card {
        display: flex; flex-direction: column; align-items: center;
        gap: 8px; padding: 16px 12px;
        background: var(--ux2-paper);
        border: 1px solid var(--ux2-line);
        border-radius: 12px;
        transition: border-color 0.22s ease, transform 0.22s ease, box-shadow 0.22s ease;
    }
    .facility-card:hover {
        border-color: var(--ux2-secondary);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(15,42,39,0.1);
    }
    .facility-card .icon-wrap {
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--ux2-primary-soft);
        display: flex; align-items: center; justify-content: center;
    }

    /* ── BOOKING WIDGET ───────────────────────────── */
    .booking-widget {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        box-shadow: var(--ux2-shadow);
        overflow: hidden;
    }
    .booking-widget-header {
        background: linear-gradient(135deg, var(--ux2-primary), var(--ux2-primary-deep));
        padding: 24px;
        color: #fff;
    }
    .room-select-wrap {
        border: 1.5px solid var(--ux2-line);
        border-radius: 10px;
        padding: 12px 14px;
        transition: border-color 0.22s ease, box-shadow 0.22s ease;
        cursor: pointer;
    }
    .room-select-wrap:hover, .room-select-wrap:focus-within {
        border-color: var(--ux2-secondary);
        box-shadow: 0 0 0 3px rgba(47,143,121,0.16);
    }

    /* ── BTN SHIMMER ──────────────────────────────── */
    @keyframes shimmer-slide {
        from { left: -80%; }
        to   { left: 130%; }
    }
    .btn-shimmer { position: relative; overflow: hidden; }
    .btn-shimmer::after {
        content: '';
        position: absolute;
        top: 0; left: -80%;
        width: 60%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
        transform: skewX(-18deg);
        animation: shimmer-slide 2.6s ease-in-out 0.8s infinite;
    }

    /* ── DESCRIPTION EXPAND ───────────────────────── */
    .desc-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: -webkit-line-clamp 0.3s;
    }
    .desc-clamp.expanded {
        -webkit-line-clamp: unset;
    }

    /* ── CHIP ─────────────────────────────────────── */
    .kos-chip {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border-radius: 999px;
        font-size: 12px; font-weight: 700;
    }

    /* ── FLOATING BACK BTN ────────────────────────── */
    @keyframes pulse-back {
        0%, 100% { box-shadow: 0 0 0 0 rgba(20,60,58,0.3); }
        50%       { box-shadow: 0 0 0 6px rgba(20,60,58,0); }
    }
    .back-btn { animation: pulse-back 2.5s ease 2s infinite; }

    /* ── VERIFIED BADGE ───────────────────────────── */
    @keyframes badge-pop {
        from { opacity:0; transform: scale(0.6) rotate(-8deg); }
        to   { opacity:1; transform: scale(1) rotate(0deg); }
    }
    .verified-badge { animation: badge-pop 0.5s cubic-bezier(.22,.68,0,1.4) 0.5s both; }

    /* ── PRICE HIGHLIGHT ──────────────────────────── */
    @keyframes price-in {
        from { opacity:0; transform: translateY(6px); }
        to   { opacity:1; transform: translateY(0); }
    }
    [data-room-price] { transition: opacity 0.2s ease; }
</style>
@endsection

@section('content')
@php
    $rooms        = collect($boardingHouse['rooms'] ?? []);
    $roomImages   = $rooms->pluck('image_url')->filter()->values();
    $primaryImage = $boardingHouse['primary_image'] ?? $roomImages->first();
    $firstRoom    = $rooms->first();
    $displayPrice = $firstRoom['price_formatted'] ?? ($boardingHouse['min_price_formatted'] ?? 'Hubungi pemilik');
@endphp

{{-- ════════════════════════════════════════════════
     HERO GALLERY — full width, cinematic
════════════════════════════════════════════════ --}}
<div class="w-full bg-primary relative anim-fade-in">
    {{-- Grid gallery --}}
    <div class="max-w-[1440px] mx-auto px-0 md:px-margin-desktop">
        <div class="grid grid-cols-4 grid-rows-2 gap-1 h-[300px] md:h-[520px]">

            {{-- Main large image --}}
            <div class="gallery-hero col-span-4 md:col-span-3 row-span-2 anim-scale-in">
                @if ($primaryImage)
                    <img src="{{ $primaryImage }}" alt="{{ $boardingHouse['name'] }}" />
                @else
                    <div class="w-full h-full flex items-center justify-center" style="background:var(--ux2-panel);">
                        <span class="material-symbols-outlined text-7xl" style="color:var(--ux2-muted);">home_work</span>
                    </div>
                @endif
                {{-- Gradient overlay bottom --}}
                <div class="absolute inset-0 pointer-events-none"
                    style="background:linear-gradient(to top, rgba(12,38,40,.55) 0%, transparent 48%);"></div>
                {{-- Photo count badge --}}
                <div class="absolute bottom-md left-md z-10">
                    <span class="kos-chip" style="background:rgba(255,255,255,0.18); backdrop-filter:blur(10px); color:#fff; border:1px solid rgba(255,255,255,0.25);">
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">photo_library</span>
                        {{ $roomImages->count() }} Foto
                    </span>
                </div>
            </div>

            {{-- Thumbnail 1 --}}
            <div class="gallery-thumb col-span-1 row-span-1 hidden md:block anim-scale-in delay-1">
                @if ($roomImages->get(1))
                    <img src="{{ $roomImages->get(1) }}" alt="{{ $boardingHouse['name'] }}" />
                @else
                    <div class="w-full h-full flex items-center justify-center" style="background:var(--ux2-panel-strong);">
                        <span class="material-symbols-outlined" style="color:var(--ux2-muted);">bed</span>
                    </div>
                @endif
            </div>

            {{-- Thumbnail 2 --}}
            <div class="gallery-thumb col-span-1 row-span-1 hidden md:block anim-scale-in delay-2">
                @if ($roomImages->get(2))
                    <img src="{{ $roomImages->get(2) }}" alt="{{ $boardingHouse['name'] }}" />
                @else
                    <div class="w-full h-full flex items-center justify-center" style="background:var(--ux2-panel-strong);">
                        <span class="material-symbols-outlined" style="color:var(--ux2-muted);">meeting_room</span>
                    </div>
                @endif
                <div class="overlay">
                    <span class="font-bold" style="color:#fff; font-size:13px; display:flex; align-items:center; gap:5px;">
                        <span class="material-symbols-outlined" style="font-size:18px;">photo_library</span>
                        {{ $roomImages->count() }} Foto
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Floating back button --}}
    <a href="{{ route('ux2.search') }}"
        class="back-btn absolute top-md left-4 md:left-[calc(40px+0.5rem)] z-30 w-10 h-10 rounded-full flex items-center justify-center"
        style="background:rgba(255,255,255,0.18); backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,0.3); color:#fff; transition: background 0.2s ease;"
        onmouseover="this.style.background='rgba(255,255,255,0.32)'"
        onmouseout="this.style.background='rgba(255,255,255,0.18)'">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>

    {{-- Favourite button --}}
    <button class="absolute top-md right-4 md:right-[calc(40px+0.5rem)] z-30 w-10 h-10 rounded-full flex items-center justify-center"
        style="background:rgba(255,255,255,0.18); backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,0.3); color:#fff; transition: background 0.2s ease;"
        onmouseover="this.style.background='rgba(255,255,255,0.32)'"
        onmouseout="this.style.background='rgba(255,255,255,0.18)'">
        <span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
    </button>
</div>

{{-- ════════════════════════════════════════════════
     MAIN CONTENT AREA
════════════════════════════════════════════════ --}}
<div class="max-w-[1440px] mx-auto px-margin-mobile md:px-margin-desktop py-lg">

    {{-- Breadcrumbs --}}
    <nav class="flex items-center gap-1 text-on-surface-variant font-label-md text-label-md mb-lg anim-fade-up">
        <a class="hover:text-primary transition-colors" href="{{ route('ux2.search') }}">Cari Kos</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <a class="hover:text-primary transition-colors" href="{{ route('ux2.search', ['q' => $boardingHouse['city'] ?? '']) }}">{{ $boardingHouse['city'] ?? 'Lokasi' }}</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span style="color:var(--ux2-primary); font-weight:600;">{{ $boardingHouse['name'] }}</span>
    </nav>

    {{-- Two-column layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_380px] gap-xl items-start">

        {{-- ── LEFT COLUMN ─────────────────────── --}}
        <div class="flex flex-col gap-xl">

            {{-- Property identity block --}}
            <div class="reveal anim-fade-up delay-1">
                {{-- Status chips row --}}
                <div class="flex flex-wrap items-center gap-2 mb-md">
                    <span class="kos-chip" style="background:var(--ux2-secondary-soft); color:var(--ux2-primary);">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1; color:var(--ux2-secondary);">check_circle</span>
                        Tersedia
                    </span>
                    <span class="kos-chip" style="background:var(--ux2-panel); color:var(--ux2-muted); border:1px solid var(--ux2-line);">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1;">person</span>
                        {{ $boardingHouse['gender_label'] }}
                    </span>
                    @if (!empty($boardingHouse['city']))
                    <span class="kos-chip" style="background:var(--ux2-panel); color:var(--ux2-muted); border:1px solid var(--ux2-line);">
                        <span class="material-symbols-outlined text-[13px]">location_city</span>
                        {{ $boardingHouse['city'] }}
                    </span>
                    @endif
                </div>

                <h1 class="font-headline-lg text-headline-lg mb-sm" style="color:var(--ux2-ink); line-height:1.2;">
                    {{ $boardingHouse['name'] }}
                </h1>
                <p class="font-body-md text-body-md flex items-start gap-1" style="color:var(--ux2-muted);">
                    <span class="material-symbols-outlined text-[20px] mt-0.5 flex-shrink-0" style="color:var(--ux2-secondary);">location_on</span>
                    {{ $boardingHouse['address'] }}, {{ $boardingHouse['city'] }}
                </p>
            </div>

            {{-- Divider --}}
            <div style="height:1px; background:var(--ux2-line);"></div>

            {{-- Facilities grid --}}
            <div class="reveal reveal-d1">
                <h2 class="font-headline-md text-headline-md mb-md" style="color:var(--ux2-ink);">Fasilitas Utama</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @forelse ($boardingHouse['facilities'] ?? [] as $facility)
                    <div class="facility-card reveal reveal-d2">
                        <div class="icon-wrap">
                            <span class="material-symbols-outlined" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">{{ $facility['icon'] ?? 'check_circle' }}</span>
                        </div>
                        <span class="font-label-md text-label-md text-center" style="color:var(--ux2-ink);">{{ $facility['name'] }}</span>
                    </div>
                    @empty
                    <p class="col-span-full font-body-md text-body-md" style="color:var(--ux2-muted);">Fasilitas belum tersedia.</p>
                    @endforelse
                </div>
            </div>

            {{-- Divider --}}
            <div style="height:1px; background:var(--ux2-line);"></div>

            {{-- Description --}}
            <div class="reveal reveal-d2">
                <h2 class="font-headline-md text-headline-md mb-md" style="color:var(--ux2-ink);">Deskripsi</h2>
                <div class="relative" style="background:var(--ux2-paper); border:1px solid var(--ux2-line); border-radius:12px; padding:20px;">
                    <p id="desc-text" class="desc-clamp font-body-md text-body-md leading-relaxed" style="color:var(--ux2-muted);">
                        {{ $boardingHouse['description'] }}
                    </p>
                    <button id="desc-toggle"
                        class="mt-3 font-label-md text-label-md font-bold flex items-center gap-1 transition-colors"
                        style="color:var(--ux2-secondary);"
                        onmouseover="this.style.color='var(--ux2-primary)'"
                        onmouseout="this.style.color='var(--ux2-secondary)'">
                        <span id="desc-toggle-text">Baca Selengkapnya</span>
                        <span class="material-symbols-outlined text-[16px]" id="desc-toggle-icon">expand_more</span>
                    </button>
                </div>
            </div>

            {{-- Divider --}}
            <div style="height:1px; background:var(--ux2-line);"></div>

            {{-- Location card --}}
            <div class="reveal reveal-d3">
                <h2 class="font-headline-md text-headline-md mb-md" style="color:var(--ux2-ink);">Lokasi</h2>
                <div class="rounded-xl overflow-hidden border" style="border-color:var(--ux2-line);">
                    {{-- Map placeholder --}}
                    <div class="w-full flex flex-col items-center justify-center py-xl px-md text-center"
                        style="background:linear-gradient(135deg, var(--ux2-primary-soft) 0%, var(--ux2-panel) 100%); min-height:220px;">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-md animate-float"
                            style="background:var(--ux2-primary);">
                            <span class="material-symbols-outlined text-3xl" style="color:#fff; font-variation-settings:'FILL' 1;">location_on</span>
                        </div>
                        <p class="font-label-md text-label-md font-bold mb-1" style="color:var(--ux2-ink);">{{ $boardingHouse['address'] }}</p>
                        <p class="font-body-md text-body-md" style="color:var(--ux2-muted);">{{ $boardingHouse['city'] }}, {{ $boardingHouse['province'] }}</p>
                        @if (!empty($boardingHouse['latitude']) && !empty($boardingHouse['longitude']))
                            <p class="font-label-sm text-label-sm mt-sm" style="color:var(--ux2-muted);">
                                {{ $boardingHouse['latitude'] }}, {{ $boardingHouse['longitude'] }}
                            </p>
                        @endif
                    </div>
                    {{-- Address footer --}}
                    <div class="px-md py-sm flex items-center gap-2" style="background:#fff; border-top:1px solid var(--ux2-line);">
                        <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-secondary);">near_me</span>
                        <span class="font-label-sm text-label-sm" style="color:var(--ux2-muted);">{{ $boardingHouse['city'] }}, {{ $boardingHouse['province'] }}</span>
                    </div>
                </div>
            </div>

        </div>{{-- end left column --}}

        {{-- ── RIGHT COLUMN — Booking Widget ───── --}}
        <div class="lg:sticky lg:top-28 anim-fade-up delay-3">
            <div class="booking-widget">

                {{-- Widget header with price --}}
                <div class="booking-widget-header">
                    <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color:rgba(255,255,255,0.65);">Harga per bulan</p>
                    <p class="mb-sm" style="font-size:32px; font-weight:700; line-height:1.1; color:#fff;">
                        <span data-room-price>{{ $displayPrice }}</span>
                        <span style="font-size:14px; font-weight:400; color:rgba(255,255,255,0.65);">&nbsp;/ bulan</span>
                    </p>
                    {{-- Verified badge --}}
                    <div class="verified-badge inline-flex items-center gap-1 px-3 py-1 rounded-full"
                        style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25);">
                        <span class="material-symbols-outlined text-[14px]" style="color:var(--ux2-accent); font-variation-settings:'FILL' 1;">verified</span>
                        <span style="font-size:11px; font-weight:700; color:#fff; text-transform:uppercase; letter-spacing:.06em;">KosKu Verified</span>
                    </div>
                </div>

                {{-- Widget body --}}
                <div class="p-md flex flex-col gap-md">

                    {{-- Room type selector --}}
                    <div>
                        <label class="block font-label-sm text-label-sm mb-sm" style="color:var(--ux2-muted);">Pilih Tipe Kamar</label>
                        <div class="room-select-wrap">
                            <label class="block font-label-sm text-label-sm mb-1" style="color:var(--ux2-muted); font-size:11px;">Tipe Kamar</label>
                            <select class="w-full bg-transparent border-none p-0 focus:ring-0 font-body-md text-body-md cursor-pointer"
                                style="color:var(--ux2-ink);" data-room-selector>
                                @forelse ($rooms as $room)
                                    <option value="{{ $room['id'] }}" data-price="{{ $room['price_formatted'] }}">
                                        {{ $room['type_name'] }}
                                    </option>
                                @empty
                                    <option data-price="Hubungi pemilik">Hubungi pemilik</option>
                                @endforelse
                            </select>
                        </div>
                    </div>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-col gap-sm">
                        <a class="btn-shimmer w-full font-label-md text-label-md font-bold py-3 rounded-xl flex justify-center items-center gap-2 transition-all active:scale-95"
                            href="{{ route('ux2.booking.show', $boardingHouse['id']) }}"
                            data-booking-link
                            data-booking-base-url="{{ route('ux2.booking.show', $boardingHouse['id']) }}"
                            style="background:var(--ux2-primary); color:#fff;"
                            onmouseover="this.style.background='var(--ux2-primary-deep)'"
                            onmouseout="this.style.background='var(--ux2-primary)'">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">edit_note</span>
                            Ajukan Sewa
                        </a>
                        <button class="w-full font-label-md text-label-md font-bold py-3 rounded-xl flex justify-center items-center gap-2 transition-all active:scale-95"
                            style="background:var(--ux2-paper); color:var(--ux2-primary); border:1.5px solid var(--ux2-primary);"
                            onmouseover="this.style.background='var(--ux2-primary-soft)'"
                            onmouseout="this.style.background='var(--ux2-paper)'">
                            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1;">chat</span>
                            Tanya Pemilik
                        </button>
                    </div>

                    {{-- Verification info --}}
                    <div class="flex items-start gap-3 p-sm rounded-xl"
                        style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
                        <span class="material-symbols-outlined mt-0.5" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1; flex-shrink:0;">verified_user</span>
                        <div>
                            <p class="font-label-md text-label-md font-bold mb-1" style="color:var(--ux2-primary);">KosKu Verified</p>
                            <p class="font-body-md text-body-md" style="font-size:13px; color:var(--ux2-muted); line-height:1.5;">
                                Properti ini telah diverifikasi dan dikelola secara profesional.
                            </p>
                        </div>
                    </div>

                </div>{{-- end widget body --}}
            </div>{{-- end booking-widget --}}

            {{-- Share / contact row below widget --}}
            <div class="flex items-center justify-center gap-sm mt-md">
                <button class="flex items-center gap-1 font-label-sm text-label-sm font-bold px-sm py-xs rounded-lg transition-colors"
                    style="color:var(--ux2-muted); background:var(--ux2-paper); border:1px solid var(--ux2-line);"
                    onmouseover="this.style.background='var(--ux2-panel)'"
                    onmouseout="this.style.background='var(--ux2-paper)'">
                    <span class="material-symbols-outlined text-[16px]">share</span> Bagikan
                </button>
                <button class="flex items-center gap-1 font-label-sm text-label-sm font-bold px-sm py-xs rounded-lg transition-colors"
                    style="color:var(--ux2-muted); background:var(--ux2-paper); border:1px solid var(--ux2-line);"
                    onmouseover="this.style.background='var(--ux2-panel)'"
                    onmouseout="this.style.background='var(--ux2-paper)'">
                    <span class="material-symbols-outlined text-[16px]" data-icon="favorite_border">favorite_border</span> Simpan
                </button>
            </div>

        </div>{{-- end right column --}}
    </div>{{-- end grid --}}
</div>{{-- end main container --}}

{{-- ── SCRIPTS (original logic preserved exactly) ─────── --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    /* ── Original: room selector & price sync ── */
    const roomSelector = document.querySelector('[data-room-selector]');
    const roomPrice    = document.querySelector('[data-room-price]');
    const bookingLink  = document.querySelector('[data-booking-link]');

    if (!roomSelector || !roomPrice) return;

    const syncRoomPrice = () => {
        const selectedRoom = roomSelector.options[roomSelector.selectedIndex];
        roomPrice.textContent = selectedRoom?.dataset.price || 'Hubungi pemilik';

        if (bookingLink && selectedRoom?.value) {
            const bookingUrl = new URL(bookingLink.dataset.bookingBaseUrl, window.location.origin);
            bookingUrl.searchParams.set('room_id', selectedRoom.value);
            bookingLink.href = bookingUrl.toString();
        }
    };

    roomSelector.addEventListener('change', syncRoomPrice);
    syncRoomPrice();

    /* ── New: description expand/collapse ── */
    const descText   = document.getElementById('desc-text');
    const descToggle = document.getElementById('desc-toggle');
    const descLabel  = document.getElementById('desc-toggle-text');
    const descIcon   = document.getElementById('desc-toggle-icon');

    if (descToggle && descText) {
        descToggle.addEventListener('click', () => {
            const expanded = descText.classList.toggle('expanded');
            descLabel.textContent = expanded ? 'Sembunyikan' : 'Baca Selengkapnya';
            descIcon.textContent  = expanded ? 'expand_less' : 'expand_more';
        });
    }

    /* ── New: scroll reveal ── */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
    document.querySelectorAll('.reveal').forEach(el => ro.observe(el));

    /* ── New: favourite toggle ── */
    document.querySelectorAll('[data-icon]').forEach(icon => {
        icon.closest('button')?.addEventListener('click', () => {
            const isFav = icon.textContent.trim() === 'favorite';
            icon.textContent = isFav ? 'favorite_border' : 'favorite';
            icon.style.color = isFav ? '' : '#e53935';
        });
    });
});
</script>
@endsection
