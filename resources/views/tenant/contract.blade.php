@extends('layouts.tenant', ['activeContract' => $activeContract])

@section('title', 'Kontrak Digital')

@section('content')

{{-- ══════════════════════════════════════════════════════════
     Header
══════════════════════════════════════════════════════════ --}}
<header class="mb-8">
    <h2 class="font-display text-4xl md:text-5xl font-bold text-on-surface tracking-tight">Kontrak Digital</h2>
    <p class="font-body text-base text-on-surface-variant mt-2">Detail kontrak sewa dan riwayat pembayaran Anda.</p>
</header>

{{-- Kos Selector --}}
<x-kos-selector :activeContracts="$allActiveContracts" :selectedContract="$activeContract" />

@if(!$activeContract || !$activeContract->contract)
    {{-- No Contract Empty State --}}
    <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-16 flex flex-col items-center justify-center text-center shadow-sm">
        <span class="material-symbols-outlined text-on-surface-variant text-7xl mb-6">description</span>
        <h3 class="font-headline text-xl font-semibold text-on-surface mb-2">Belum Ada Kontrak</h3>
        <p class="font-body text-base text-on-surface-variant max-w-sm">
            Anda belum memiliki kontrak sewa yang aktif.
        </p>
    </div>
@else
    @php
        $contract = $activeContract->contract;
        $room     = $activeContract->room;
        $house    = $room?->boardingHouse;
        $owner    = $house?->owner;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ──────────────────────────────────────────────────────
             Contract Summary Card (col-span-8)
        ────────────────────────────────────────────────────── --}}
        <section class="col-span-1 lg:col-span-8 bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 shadow-sm">
            {{-- Contract Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant">No. Kontrak</span>
                        <span class="font-label text-xs font-black text-on-surface bg-surface-container px-3 py-1 rounded-full border border-outline-variant/30">
                            {{ $contract->contract_number }}
                        </span>
                    </div>
                    <h3 class="font-headline text-2xl font-bold text-on-surface">{{ $house?->name }}</h3>
                    <p class="font-body text-base text-on-surface-variant flex items-center gap-1.5 mt-1">
                        <span class="material-symbols-outlined text-[16px]">location_on</span>
                        {{ $house?->address }}, {{ $house?->city }}
                    </p>
                </div>
                @php
                    $statusConf = match($contract->status->value ?? $contract->status) {
                        'active'    => ['label' => 'Aktif',     'class' => 'bg-green-50 text-green-700 border-green-200'],
                        'expired'   => ['label' => 'Berakhir',  'class' => 'bg-gray-100 text-gray-600 border-gray-200'],
                        'cancelled' => ['label' => 'Dibatalkan','class' => 'bg-red-50 text-red-700 border-red-200'],
                        default     => ['label' => '—',         'class' => 'bg-gray-50 text-gray-600 border-gray-200'],
                    };
                @endphp
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border self-start {{ $statusConf['class'] }}">
                    <span class="w-2 h-2 rounded-full bg-current"></span>
                    {{ $statusConf['label'] }}
                </span>
            </div>

            {{-- Contract Details Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pb-8 border-b border-outline-variant/30">
                <div>
                    <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-1">Mulai Sewa</p>
                    <p class="font-body text-base font-semibold text-on-surface">
                        {{ $contract->start_date?->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-1">Berakhir</p>
                    <p class="font-body text-base font-semibold text-on-surface">
                        {{ $contract->end_date?->translatedFormat('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-1">Durasi</p>
                    <p class="font-body text-base font-semibold text-on-surface">{{ $durationMonths }} Bulan</p>
                </div>
                <div>
                    <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-1">Sisa Waktu</p>
                    <p class="font-body text-base font-semibold text-on-surface">{{ $remainingTime ?? '—' }}</p>
                </div>
            </div>

            {{-- Financial Details --}}
            <div class="grid grid-cols-2 gap-6 py-8 border-b border-outline-variant/30">
                <div class="bg-surface-container rounded-xl p-5">
                    <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-2">Biaya Bulanan</p>
                    <p class="font-headline text-2xl font-bold text-on-surface">
                        Rp {{ number_format($contract->monthly_fee, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-surface-container rounded-xl p-5">
                    <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-2">Deposit</p>
                    <p class="font-headline text-2xl font-bold text-on-surface">
                        Rp {{ number_format($contract->deposit_fee, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- E-Signature Timeline --}}
            <div class="pt-8">
                <h4 class="font-headline text-base font-semibold text-on-surface mb-6">Status Tanda Tangan Digital</h4>
                <div class="space-y-4">
                    {{-- Tenant signature --}}
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                                    {{ $contract->tenant_signature_date ? 'bg-green-100 text-green-600' : 'bg-surface-container text-on-surface-variant' }}">
                            <span class="material-symbols-outlined text-[20px]">
                                {{ $contract->tenant_signature_date ? 'verified' : 'pending' }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <p class="font-label text-sm font-semibold text-on-surface">Tanda Tangan Penyewa</p>
                            <p class="font-body text-xs text-on-surface-variant">
                                @if($contract->tenant_signature_date)
                                    Ditandatangani pada {{ $contract->tenant_signature_date->translatedFormat('d M Y, H:i') }} WIB
                                @else
                                    Belum ditandatangani
                                @endif
                            </p>
                        </div>
                        @if($contract->tenant_signature_date)
                            <span class="text-xs font-semibold text-green-600">✓ Selesai</span>
                        @else
                            <span class="text-xs font-semibold text-amber-600">Menunggu</span>
                        @endif
                    </div>

                    {{-- Owner signature --}}
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                                    {{ $contract->owner_signature_date ? 'bg-green-100 text-green-600' : 'bg-surface-container text-on-surface-variant' }}">
                            <span class="material-symbols-outlined text-[20px]">
                                {{ $contract->owner_signature_date ? 'verified' : 'pending' }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <p class="font-label text-sm font-semibold text-on-surface">Tanda Tangan Pemilik</p>
                            <p class="font-body text-xs text-on-surface-variant">
                                @if($contract->owner_signature_date)
                                    Ditandatangani pada {{ $contract->owner_signature_date->translatedFormat('d M Y, H:i') }} WIB
                                @else
                                    Belum ditandatangani
                                @endif
                            </p>
                        </div>
                        @if($contract->owner_signature_date)
                            <span class="text-xs font-semibold text-green-600">✓ Selesai</span>
                        @else
                            <span class="text-xs font-semibold text-amber-600">Menunggu</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Download PDF --}}
            @if($contract->pdf_url)
            <div class="mt-8 pt-6 border-t border-outline-variant/30">
                <a href="{{ $contract->pdf_url }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-full font-label text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Unduh PDF Kontrak
                </a>
            </div>
            @endif
        </section>

        {{-- ──────────────────────────────────────────────────────
             Owner Info + Room Info (col-span-4)
        ────────────────────────────────────────────────────── --}}
        <div class="col-span-1 lg:col-span-4 space-y-6">

            {{-- Room Card --}}
            <section class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                <h4 class="font-headline text-base font-semibold text-on-surface mb-4">Detail Kamar</h4>
                @if($room?->image_url)
                <div class="aspect-video rounded-xl overflow-hidden bg-surface-container mb-4">
                    <img src="{{ $room->image_url }}" alt="{{ $room->type_name }}" class="w-full h-full object-cover">
                </div>
                @endif
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="font-label text-sm text-on-surface-variant">Tipe Kamar</span>
                        <span class="font-label text-sm font-semibold text-on-surface">{{ $room?->type_name }}</span>
                    </div>
                    @if($room?->size)
                    <div class="flex justify-between items-center">
                        <span class="font-label text-sm text-on-surface-variant">Ukuran</span>
                        <span class="font-label text-sm font-semibold text-on-surface">{{ $room->size }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="font-label text-sm text-on-surface-variant">Harga/Bulan</span>
                        <span class="font-label text-sm font-semibold text-on-surface">
                            Rp {{ number_format($room?->price_per_month ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </section>

            {{-- Owner Card --}}
            @if($owner)
            <section class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                <h4 class="font-headline text-base font-semibold text-on-surface mb-4">Pemilik Kos</h4>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-on-primary font-bold text-lg shrink-0">
                        {{ strtoupper(substr($owner->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-label text-sm font-semibold text-on-surface">{{ $owner->name }}</p>
                        <p class="font-body text-xs text-on-surface-variant">{{ $owner->email }}</p>
                    </div>
                </div>
                @if($owner->phone_number)
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $owner->phone_number) }}"
                   target="_blank"
                   class="w-full flex items-center justify-center gap-2 py-3 bg-primary text-on-primary rounded-full font-label text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    Hubungi via WhatsApp
                </a>
                @endif
            </section>
            @endif
        </div>

    </div>
@endif

@endsection
