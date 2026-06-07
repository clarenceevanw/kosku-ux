@extends('layouts.ux2.owner')

@section('title', 'Kelola Unit - KosKu')

@push('styles')
<style>
    /* ── ANIMATIONS ──────────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slide-right {
        from { opacity: 0; transform: translateX(-16px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .anim-fade-up { animation: fade-up 0.55s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in { animation: fade-in 0.45s ease both; }
    .anim-slide-r { animation: slide-right 0.5s cubic-bezier(.22,.68,0,1.1) both; }
    .d1{animation-delay:.07s} .d2{animation-delay:.14s}
    .d3{animation-delay:.21s} .d4{animation-delay:.28s}

    /* ── PAGE HEADER ─────────────────────────── */
    .page-header {
        background: linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        margin-bottom: 32px;
    }
    .page-header::before {
        content: ''; position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(189,235,216,0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(189,235,216,0.1) 1px, transparent 1px);
        background-size: 32px 32px; pointer-events: none;
    }
    .back-btn {
        width: 44px; height: 44px; border-radius: 12px;
        background: rgba(255,255,255,0.15);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        transition: background .22s ease, transform .22s ease;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .back-btn:hover {
        background: rgba(255,255,255,0.25);
        transform: translateX(-4px);
    }

    /* ── TABLE CARD ──────────────────────────── */
    .table-card {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        box-shadow: var(--ux2-shadow-soft);
        overflow: hidden;
    }
    .table-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--ux2-line);
    }
    .table-row {
        transition: background .22s ease;
    }
    .table-row:hover {
        background: var(--ux2-panel);
    }
    .tenant-avatar {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px; flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="max-w-[1440px] mx-auto">

    {{-- ════ PAGE HEADER ════ --}}
    <div class="page-header p-lg anim-fade-up">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-md">
            <a href="{{ route('ux2.owner.rooms.index') }}" class="back-btn flex-shrink-0">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">arrow_back</span>
            </a>
            <div>
                <p class="anim-slide-r d1" style="font-size:12px; font-weight:700; color:rgba(255,255,255,0.6); letter-spacing:.07em; text-transform:uppercase; margin-bottom:4px;">Kelola Unit</p>
                <h1 class="font-display-lg text-display-lg anim-fade-up d2" style="color:#fff; line-height:1.1;">
                    {{ $room->type_name }}
                </h1>
                <div class="flex flex-wrap items-center gap-sm mt-3 anim-fade-up d3">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[13px] font-semibold" style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.2);">
                        <span class="material-symbols-outlined text-[16px]">home_work</span>
                        {{ $room->boardingHouse->name }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[13px] font-semibold" style="background:rgba(255,255,255,0.15); color:#fff; border:1px solid rgba(255,255,255,0.2);">
                        <span class="material-symbols-outlined text-[16px]">payments</span>
                        Rp {{ number_format($room->price_per_month, 0, ',', '.') }}/bln
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[13px] font-semibold" style="background:var(--ux2-secondary); color:#fff;">
                        <span class="material-symbols-outlined text-[16px]">group</span>
                        {{ $room->contracts->count() }}/{{ $room->stock }} Terisi
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ════ TENANT LIST TABLE ════ --}}
    <div class="table-card anim-fade-up d2">
        <div class="table-header">
            <h3 class="font-headline-md text-headline-md font-bold" style="color:var(--ux2-ink);">Daftar Penyewa Aktif</h3>
            <p style="font-size:14px; color:var(--ux2-muted); margin-top:2px;">Menampilkan penyewa yang sedang menyewa tipe kamar ini.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[800px]">
                <thead>
                    <tr style="background:var(--ux2-surface); border-bottom:1px solid var(--ux2-line);">
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Penyewa</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Nomor Kontrak</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Mulai Sewa</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Selesai Sewa</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider text-right" style="color:var(--ux2-muted);">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--ux2-line);">
                    @forelse($room->contracts as $contract)
                        <tr class="table-row">
                            <td class="px-md py-md">
                                <div class="flex items-center gap-3">
                                    <div class="tenant-avatar" style="background:var(--ux2-primary-soft); color:var(--ux2-primary);">
                                        {{ strtoupper(substr($contract->tenant->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">{{ $contract->tenant->name }}</p>
                                        <p style="font-size:12px; color:var(--ux2-muted);">{{ $contract->tenant->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-md py-md">
                                <span class="font-label-md text-label-md font-bold" style="color:var(--ux2-primary);">{{ $contract->contract_number ?? '-' }}</span>
                            </td>
                            <td class="px-md py-md font-body-md text-body-md" style="color:var(--ux2-ink);">
                                {{ $contract->start_date ? $contract->start_date->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-md py-md font-body-md text-body-md" style="color:var(--ux2-ink);">
                                {{ $contract->end_date ? $contract->end_date->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-md py-md text-right">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold" style="background:var(--ux2-secondary-soft); color:var(--ux2-secondary);">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:var(--ux2-secondary);"></span>
                                    Aktif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-md py-xl text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 rounded-full flex items-center justify-center mb-sm" style="background:var(--ux2-panel);">
                                        <span class="material-symbols-outlined text-5xl" style="color:var(--ux2-muted);">person_off</span>
                                    </div>
                                    <h3 class="font-headline-md text-headline-md font-bold mb-1" style="color:var(--ux2-ink);">Belum ada penyewa aktif</h3>
                                    <p style="font-size:14px; color:var(--ux2-muted); max-width:300px; line-height:1.6;">Saat ini belum ada penyewa yang menempati tipe kamar ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
