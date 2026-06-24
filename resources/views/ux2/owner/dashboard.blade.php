@extends('layouts.ux2.owner')

@section('title', 'Dashboard Owner - KosKu')

@push('styles')
<style>
    /* ── ANIMATIONS ──────────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.9); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes slide-right {
        from { opacity: 0; transform: translateX(-16px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .anim-fade-up  { animation: fade-up    0.55s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in  { animation: fade-in    0.45s ease both; }
    .anim-scale-in { animation: scale-in   0.5s  cubic-bezier(.22,.68,0,1.2) both; }
    .anim-slide-r  { animation: slide-right 0.5s cubic-bezier(.22,.68,0,1.1) both; }
    .d1{animation-delay:.07s} .d2{animation-delay:.14s}
    .d3{animation-delay:.21s} .d4{animation-delay:.28s}
    .d5{animation-delay:.35s} .d6{animation-delay:.42s}

    /* ── SCROLL REVEAL ───────────────────────── */
    .reveal {
        opacity: 0; transform: translateY(20px);
        transition: opacity .55s cubic-bezier(.22,.68,0,1.1), transform .55s cubic-bezier(.22,.68,0,1.1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .rev-d1{transition-delay:.08s} .rev-d2{transition-delay:.18s}
    .rev-d3{transition-delay:.28s} .rev-d4{transition-delay:.38s}

    /* ── WELCOME BANNER ──────────────────────── */
    .welcome-banner {
        background: linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);
        border-radius: 16px; position: relative; overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(189,235,216,0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(189,235,216,0.1) 1px, transparent 1px);
        background-size: 32px 32px; pointer-events: none;
    }
    .orb {
        position: absolute; border-radius: 50%;
        filter: blur(55px); pointer-events: none;
    }
    @keyframes orb-drift {
        0%,100%{transform:translate(0,0)} 40%{transform:translate(-14px,10px)} 70%{transform:translate(9px,-9px)}
    }
    .orb-1 {
        width:200px;height:200px; top:-60px; right:8%;
        background:radial-gradient(circle,rgba(47,143,121,0.45) 0%,transparent 70%);
        animation: orb-drift 11s ease-in-out infinite;
    }
    .orb-2 {
        width:150px;height:150px; bottom:-40px; right:28%;
        background:radial-gradient(circle,rgba(242,189,94,0.28) 0%,transparent 70%);
        animation: orb-drift 15s ease-in-out 4s infinite reverse;
    }

    /* ── STAT CARDS ──────────────────────────── */
    .stat-card {
        border-radius: 16px; padding: 20px;
        position: relative; overflow: hidden;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(0,0,0,0.12);
    }
    .stat-card-progress {
        height: 6px; border-radius: 3px;
        background: rgba(255,255,255,0.2);
        margin-top: 14px; overflow: hidden;
    }
    .stat-card-bar {
        height: 100%; border-radius: 3px;
        background: rgba(255,255,255,0.85);
        transition: width 1.2s cubic-bezier(.22,.68,0,1);
    }
    @keyframes count-up {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .stat-number { animation: count-up 0.5s ease both; }

    /* ── KOS SELECTOR ────────────────────────── */
    .kos-selector {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 14px;
        padding: 10px 14px;
        display: flex; align-items: center; gap: 10px;
        box-shadow: var(--ux2-shadow-soft);
        transition: border-color .22s ease, box-shadow .22s ease;
    }
    .kos-selector:focus-within {
        border-color: var(--ux2-secondary);
        box-shadow: 0 0 0 3px rgba(47,143,121,0.15);
    }

    /* ── SECTION CARD ────────────────────────── */
    .dash-section {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        box-shadow: var(--ux2-shadow-soft);
        overflow: hidden;
    }
    .dash-section-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--ux2-line);
        display: flex; align-items: center; justify-content: space-between;
    }
    .section-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ── QUICK ACTIONS ───────────────────────── */
    .action-btn {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        padding: 14px 20px; border-radius: 12px;
        font-weight: 700; font-size: 14px;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        position: relative; overflow: hidden;
    }
    .action-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
    .action-btn:active { transform: scale(0.98); }
    @keyframes shimmer { from{left:-80%} to{left:140%} }
    .action-btn::after {
        content:''; position:absolute; top:0; left:-80%;
        width:55%; height:100%;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,0.22),transparent);
        transform:skewX(-18deg);
        animation: shimmer 2.8s ease-in-out 1s infinite;
    }

    /* ── ROOM MAP ────────────────────────────── */
    .room-cell {
        aspect-ratio: 1;
        border-radius: 12px;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        transition: transform .2s ease, box-shadow .2s ease;
        cursor: pointer;
    }
    .room-cell:hover { transform: scale(1.07); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }

    /* ── TICKET CARD ─────────────────────────── */
    .ticket-card {
        background: var(--ux2-paper);
        border: 1px solid var(--ux2-line);
        border-radius: 14px; padding: 18px;
        transition: border-color .22s ease, box-shadow .22s ease, transform .22s ease;
    }
    .ticket-card:hover {
        border-color: var(--ux2-secondary);
        box-shadow: 0 6px 18px rgba(15,42,39,0.1);
        transform: translateY(-2px);
    }

    /* ── CHART SECTION ───────────────────────── */
    .chart-wrap {
        position: relative; height: 260px; width: 100%;
    }

    /* ── LEGEND DOT ──────────────────────────── */
    .legend-dot { width:14px; height:14px; border-radius:4px; flex-shrink:0; }
</style>
@endpush

@section('content')

{{-- ════ WELCOME BANNER ════ --}}
<div class="welcome-banner p-lg mb-lg anim-fade-in">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-end md:justify-between gap-lg">
        <div>
            <p class="anim-slide-r d1" style="font-size:12px; font-weight:700; color:rgba(255,255,255,0.6); letter-spacing:.07em; text-transform:uppercase; margin-bottom:8px;">Owner Dashboard</p>
            <h1 class="font-display-lg text-display-lg anim-fade-up d2" style="color:#fff; line-height:1.1;">
                Selamat datang, {{ auth()->user()->name }}!
            </h1>
            <p class="anim-fade-up d3" style="color:rgba(255,255,255,0.7); font-size:14px; margin-top:6px;">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- Kos Selector in banner --}}
        <div class="anim-scale-in d4">
            <form method="GET" action="{{ route('ux2.owner.dashboard') }}">
                <div class="kos-selector">
                    <span class="material-symbols-outlined flex-shrink-0" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">home_work</span>
                    <select name="kos_id" onchange="this.form.submit()"
                        class="bg-transparent border-none font-label-md text-label-md focus:ring-0 cursor-pointer min-w-[180px]"
                        style="color:var(--ux2-ink);">
                        <option value="">Semua Kos</option>
                        @foreach($boardingHouses as $kos)
                            <option value="{{ $kos->id }}" {{ $selectedKosId == $kos->id ? 'selected' : '' }}>{{ $kos->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- ALERTS --}}
    @php
        $pendingContracts = \App\Models\Contract::whereHas('room.boardingHouse', fn($q) => $q->byOwner(auth()->id()))
            ->where('status', 'pending')->count();
        $pendingPayments = \App\Models\MonthlyPayment::whereHas('contract.room.boardingHouse', fn($q) => $q->byOwner(auth()->id()))
            ->where('payment_status', 'paid_to_escrow')->count();
    @endphp

    @if($pendingContracts > 0 || $pendingPayments > 0)
    <div class="relative z-10 mt-lg anim-fade-up d5 bg-white rounded-2xl p-md shadow-lg border border-white/20">
        <div class="flex items-center gap-2 mb-sm">
            <span class="material-symbols-outlined text-amber-500" style="font-variation-settings:'FILL' 1;">notifications_active</span>
            <h4 class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">Perlu Perhatian Anda</h4>
        </div>
        <div class="space-y-2">
            @if($pendingContracts > 0)
            <div class="flex items-center justify-between p-sm bg-amber-50 rounded-xl border border-amber-200">
                <div class="flex items-center gap-sm">
                    <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-[18px]" style="font-variation-settings:'FILL' 1;">person_add</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-amber-900"><strong>{{ $pendingContracts }}</strong> penyewa menunggu persetujuan</p>
                </div>
                <a href="{{ route('ux2.owner.transactions.index') }}" class="px-4 py-2 bg-amber-500 text-white rounded-lg font-label-sm text-label-sm font-bold hover:bg-amber-600 transition-colors">
                    Lihat
                </a>
            </div>
            @endif
            @if($pendingPayments > 0)
            <div class="flex items-center justify-between p-sm bg-blue-50 rounded-xl border border-blue-200">
                <div class="flex items-center gap-sm">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white text-[18px]" style="font-variation-settings:'FILL' 1;">payments</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-blue-900"><strong>{{ $pendingPayments }}</strong> pembayaran menunggu verifikasi</p>
                </div>
                <a href="{{ route('ux2.owner.transactions.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg font-label-sm text-label-sm font-bold hover:bg-blue-600 transition-colors">
                    Verifikasi
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- ════ STAT CARDS ════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter mb-lg">

    {{-- Kamar Terisi --}}
    @php
        $occupancyPct = $kamarTerisi['total'] > 0 ? round($kamarTerisi['occupied'] / $kamarTerisi['total'] * 100) : 0;
    @endphp
    <div class="stat-card anim-fade-up d1" style="background:linear-gradient(135deg,#6cf8bb,#4edea3);">
        <div class="flex justify-between items-start">
            <div>
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(0,36,24,0.7);margin-bottom:8px;">Kamar Terisi</p>
                <p class="stat-number" style="font-size:38px;font-weight:800;line-height:1;color:#002414;">
                    {{ $kamarTerisi['occupied'] }}
                    <span style="font-size:18px;font-weight:500;opacity:.65;">/ {{ $kamarTerisi['total'] }}</span>
                </p>
            </div>
            <div style="background:rgba(0,36,24,0.12);border-radius:12px;padding:10px;">
                <span class="material-symbols-outlined text-3xl" style="color:#002414;font-variation-settings:'FILL' 1;">bed</span>
            </div>
        </div>
        <div class="stat-card-progress">
            <div class="stat-card-bar js-progress-bar" data-width="{{ $occupancyPct }}%" style="width:0%"></div>
        </div>
        <p style="font-size:12px;color:rgba(0,36,24,0.65);margin-top:6px;">{{ $occupancyPct }}% terisi</p>
    </div>

    {{-- Pemasukan --}}
    <div class="stat-card anim-fade-up d2" style="background:linear-gradient(135deg,#e9ddff,#d0bcff);">
        <div class="flex justify-between items-start">
            <div>
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(35,0,92,0.7);margin-bottom:8px;">Pemasukan Bulan Ini</p>
                <p class="stat-number" style="font-size:32px;font-weight:800;line-height:1;color:#23005c;">
                    Rp {{ number_format($totalPendapatan / 1000000, 1) }}JT
                </p>
                <p style="font-size:12px;color:rgba(35,0,92,0.6);margin-top:4px;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div style="background:rgba(35,0,92,0.1);border-radius:12px;padding:10px;">
                <span class="material-symbols-outlined text-3xl" style="color:#23005c;font-variation-settings:'FILL' 1;">payments</span>
            </div>
        </div>
    </div>

    {{-- Tagihan Tertunggak --}}
    <div class="stat-card anim-fade-up d3" style="background:linear-gradient(135deg,#ffdad6,#ffb4ab);">
        <div class="flex justify-between items-start">
            <div>
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(93,0,7,0.7);margin-bottom:8px;">Tagihan Tertunggak</p>
                <p class="stat-number" style="font-size:38px;font-weight:800;line-height:1;color:#93000a;">{{ $tagihanBelumLunas }}</p>
                <p style="font-size:12px;color:rgba(93,0,7,0.65);margin-top:4px;">Tagihan belum dibayar</p>
            </div>
            <div style="background:rgba(93,0,7,0.1);border-radius:12px;padding:10px;">
                <span class="material-symbols-outlined text-3xl" style="color:#93000a;font-variation-settings:'FILL' 1;">warning</span>
            </div>
        </div>
    </div>

    {{-- Keluhan Aktif --}}
    <div class="stat-card anim-fade-up d4" style="background:linear-gradient(135deg,#dae2fd,#bec6e0);">
        <div class="flex justify-between items-start">
            <div>
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:rgba(19,27,46,0.7);margin-bottom:8px;">Keluhan Aktif</p>
                <p class="stat-number" style="font-size:38px;font-weight:800;line-height:1;color:#131b2e;">{{ $laporanAktif }}</p>
                <p style="font-size:12px;color:rgba(19,27,46,0.6);margin-top:4px;">Tiket perlu ditangani</p>
            </div>
            <div style="background:rgba(19,27,46,0.1);border-radius:12px;padding:10px;">
                <span class="material-symbols-outlined text-3xl" style="color:#131b2e;font-variation-settings:'FILL' 1;">build</span>
            </div>
        </div>
    </div>
</div>

{{-- ════ TWO-COLUMN: Quick Actions + Room Map ════ --}}
<div class="grid grid-cols-1 lg:grid-cols-[280px_minmax(0,1fr)] gap-gutter mb-lg">

    {{-- Quick Actions --}}
    <div class="dash-section reveal anim-fade-up d2">
        <div class="dash-section-header">
            <div class="flex items-center gap-2">
                <div class="section-icon" style="background:var(--ux2-primary-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-primary);font-variation-settings:'FILL' 1;">bolt</span>
                </div>
                <h3 class="font-headline-md text-headline-md font-bold" style="color:var(--ux2-ink);font-size:18px;">Aksi Cepat</h3>
            </div>
        </div>
        <div class="p-md flex flex-col gap-sm">
            <button class="action-btn"
                style="background:var(--ux2-primary); color:#fff;">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings:'FILL' 1;">send</span>
                Kirim Pengingat Tagihan
            </button>
            <button class="action-btn"
                style="background:var(--ux2-paper); color:var(--ux2-ink); border:1.5px solid var(--ux2-line);"
                onmouseover="this.style.background='var(--ux2-panel)'"
                onmouseout="this.style.background='var(--ux2-paper)'">
                <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-secondary);font-variation-settings:'FILL' 1;">payments</span>
                Kelola Keuangan
            </button>

            <div style="height:1px;background:var(--ux2-line);margin:4px 0;"></div>

            {{-- Quick nav links --}}
            <a href="{{ route('ux2.owner.kos.index') }}"
                class="flex items-center gap-3 p-sm rounded-xl transition-colors"
                style="color:var(--ux2-muted);"
                onmouseover="this.style.background='var(--ux2-panel)'"
                onmouseout="this.style.background='transparent'">
                <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-secondary);">home_work</span>
                <span class="font-label-md text-label-md">Kelola Kos</span>
                <span class="material-symbols-outlined text-[16px] ml-auto" style="color:var(--ux2-line);">chevron_right</span>
            </a>
            <a href="{{ route('ux2.owner.rooms.index') }}"
                class="flex items-center gap-3 p-sm rounded-xl transition-colors"
                style="color:var(--ux2-muted);"
                onmouseover="this.style.background='var(--ux2-panel)'"
                onmouseout="this.style.background='transparent'">
                <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-secondary);">bed</span>
                <span class="font-label-md text-label-md">Kelola Kamar</span>
                <span class="material-symbols-outlined text-[16px] ml-auto" style="color:var(--ux2-line);">chevron_right</span>
            </a>
            <a href="{{ route('ux2.owner.transactions.index') }}"
                class="flex items-center gap-3 p-sm rounded-xl transition-colors"
                style="color:var(--ux2-muted);"
                onmouseover="this.style.background='var(--ux2-panel)'"
                onmouseout="this.style.background='transparent'">
                <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-secondary);">receipt_long</span>
                <span class="font-label-md text-label-md">Pemesanan</span>
                <span class="material-symbols-outlined text-[16px] ml-auto" style="color:var(--ux2-line);">chevron_right</span>
            </a>
        </div>
    </div>

    {{-- Room Map --}}
    <div class="dash-section reveal rev-d1">
        <div class="dash-section-header">
            <div class="flex items-center gap-2">
                <div class="section-icon" style="background:var(--ux2-primary-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-primary);font-variation-settings:'FILL' 1;">grid_view</span>
                </div>
                <h3 class="font-headline-md text-headline-md font-bold" style="color:var(--ux2-ink);font-size:18px;">Peta Kamar</h3>
            </div>
            {{-- Legend --}}
            <div class="flex flex-wrap items-center gap-md">
                <div class="flex items-center gap-1">
                    <div class="legend-dot" style="background:#4edea3;"></div>
                    <span class="font-label-sm text-label-sm" style="color:var(--ux2-muted);">Terisi</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="legend-dot" style="background:#ffb4ab;"></div>
                    <span class="font-label-sm text-label-sm" style="color:var(--ux2-muted);">Menunggak</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="legend-dot" style="background:var(--ux2-panel);border:2px dashed var(--ux2-line);"></div>
                    <span class="font-label-sm text-label-sm" style="color:var(--ux2-muted);">Kosong</span>
                </div>
            </div>
        </div>

        <div class="p-md">
            @if(!$selectedKosId)
                <div class="flex flex-col items-center justify-center py-xl rounded-xl text-center"
                    style="background:var(--ux2-panel);border:2px dashed var(--ux2-line);">
                    <span class="material-symbols-outlined text-5xl mb-sm" style="color:var(--ux2-muted);opacity:.4;">home_work</span>
                    <p class="font-headline-md text-headline-md font-bold mb-xs" style="color:var(--ux2-ink);">Pilih Kos Terlebih Dahulu</p>
                    <p style="font-size:14px;color:var(--ux2-muted);max-width:320px;line-height:1.6;">
                        Silakan pilih kos dari dropdown di atas untuk melihat peta kamar secara detail.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-5 md:grid-cols-8 lg:grid-cols-10 gap-sm">
                    @forelse($visualRooms as $room)
                        @if($room['status'] == 'lunas')
                            <div class="room-cell" style="background:linear-gradient(135deg,#6cf8bb,#4edea3);">
                                <span class="material-symbols-outlined" style="color:#002414;font-size:14px;font-variation-settings:'FILL' 1;">person</span>
                                <span class="font-label-sm text-label-sm font-bold" style="color:#002414;font-size:10px;">{{ $room['number'] }}</span>
                            </div>
                        @elseif($room['status'] == 'menunggak')
                            <div class="room-cell" style="background:linear-gradient(135deg,#ffdad6,#ffb4ab);">
                                <span class="material-symbols-outlined" style="color:#93000a;font-size:14px;font-variation-settings:'FILL' 1;">warning</span>
                                <span class="font-label-sm text-label-sm font-bold" style="color:#93000a;font-size:10px;">{{ $room['number'] }}</span>
                            </div>
                        @else
                            <div class="room-cell" style="background:var(--ux2-panel);border:2px dashed var(--ux2-line);">
                                <span class="material-symbols-outlined" style="color:var(--ux2-muted);font-size:14px;opacity:.4;">bed</span>
                                <span class="font-label-sm text-label-sm font-bold" style="color:var(--ux2-muted);font-size:10px;">{{ $room['number'] }}</span>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-full py-lg text-center" style="color:var(--ux2-muted);">Belum ada kamar di kos ini.</div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ════ TWO-COLUMN: Tickets + Chart ════ --}}
<div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_360px] gap-gutter">

    {{-- Recent Tickets --}}
    <div class="dash-section reveal rev-d2">
        <div class="dash-section-header">
            <div class="flex items-center gap-2">
                <div class="section-icon" style="background:var(--ux2-coral-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-coral);font-variation-settings:'FILL' 1;">build</span>
                </div>
                <h3 class="font-headline-md text-headline-md font-bold" style="color:var(--ux2-ink);font-size:18px;">Keluhan Terbaru</h3>
            </div>
            <a href="{{ route('ux2.owner.tickets.index') }}"
                class="font-label-md text-label-md font-bold transition-colors"
                style="color:var(--ux2-secondary);"
                onmouseover="this.style.color='var(--ux2-primary)'"
                onmouseout="this.style.color='var(--ux2-secondary)'">Lihat Semua</a>
        </div>

        <div class="p-md grid grid-cols-1 md:grid-cols-2 gap-sm">
            @forelse($recentTickets as $ticket)
                <div class="ticket-card">
                    <div class="flex justify-between items-start gap-2 mb-sm">
                        <h4 class="font-label-md text-label-md font-bold flex-1"
                            style="color:var(--ux2-ink);">{{ \Illuminate\Support\Str::limit($ticket->title, 50) }}</h4>
                        @if($ticket->priority === 'urgent' || $ticket->priority === 'high')
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold flex-shrink-0"
                                style="background:var(--ux2-coral-soft);color:var(--ux2-coral);">Urgent</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold flex-shrink-0"
                                style="background:var(--ux2-panel);color:var(--ux2-muted);">Normal</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1 mb-xs" style="color:var(--ux2-muted);font-size:12px;">
                        <span class="material-symbols-outlined text-[14px]">home_work</span>
                        {{ $ticket->room->boardingHouse->name ?? 'Unknown' }}
                    </div>
                    <div class="flex items-center gap-1 mb-md" style="color:var(--ux2-muted);font-size:12px;">
                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                        {{ $ticket->created_at->diffForHumans() }}
                    </div>
                    <button class="w-full py-xs px-sm rounded-xl font-label-md text-label-md font-bold transition-colors"
                        style="background:var(--ux2-primary);color:#fff;"
                        onclick="window.location.href='{{ route('ux2.owner.tickets.show', $ticket->id) }}'"
                        onmouseover="this.style.background='var(--ux2-primary-deep)'"
                        onmouseout="this.style.background='var(--ux2-primary)'">
                        Follow Up
                    </button>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-xl text-center">
                    <span class="material-symbols-outlined text-5xl mb-sm" style="color:var(--ux2-muted);opacity:.35;font-variation-settings:'FILL' 1;">task_alt</span>
                    <p class="font-label-md text-label-md font-bold mb-1" style="color:var(--ux2-ink);">Tidak Ada Keluhan</p>
                    <p style="font-size:14px;color:var(--ux2-muted);">Tidak ada keluhan aktif saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Occupancy Chart --}}
    <div class="dash-section reveal rev-d3">
        <div class="dash-section-header">
            <div class="flex items-center gap-2">
                <div class="section-icon" style="background:var(--ux2-primary-soft);">
                    <span class="material-symbols-outlined text-[20px]" style="color:var(--ux2-primary);font-variation-settings:'FILL' 1;">bar_chart</span>
                </div>
                <div>
                    <h3 class="font-headline-md text-headline-md font-bold" style="color:var(--ux2-ink);font-size:18px;">Tingkat Okupansi</h3>
                    <p style="font-size:12px;color:var(--ux2-muted);">3 Bulan Terakhir</p>
                </div>
            </div>
        </div>
        <div class="p-md">
            <div class="chart-wrap">
                <canvas id="occupancyChart"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Original: Chart.js occupancy chart (unchanged) ── */
    const ctx = document.getElementById('occupancyChart').getContext('2d');
    const labels     = {!! json_encode($occupancyTrends['labels']) !!};
    const dataValues = {!! json_encode($occupancyTrends['values']) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tingkat Okupansi (%)',
                data: dataValues,
                borderColor: '#006c49',
                backgroundColor: 'rgba(108, 248, 187, 0.12)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6cf8bb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 9
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: '#e7eeff', drawBorder: false },
                    ticks: { callback: v => v + '%' }
                },
                x: { grid: { display: false, drawBorder: false } }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => 'Okupansi: ' + ctx.parsed.y + '%' }
                }
            }
        }
    });

    /* ── New: scroll reveal ── */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });
    document.querySelectorAll('.reveal').forEach(el => ro.observe(el));

    /* ── New: animate progress bars on enter ── */
    const barObserver = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                const bars = e.target.querySelectorAll('.js-progress-bar');
                bars.forEach(bar => {
                    setTimeout(() => { bar.style.width = bar.dataset.width; }, 120);
                });
                barObserver.unobserve(e.target);
            }
        });
    }, { threshold: 0.5 });
    document.querySelectorAll('.stat-card').forEach(c => barObserver.observe(c));
});
</script>
@endpush
