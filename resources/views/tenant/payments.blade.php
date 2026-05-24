@extends('layouts.tenant', ['activeContract' => $activeContract ?? null])

@section('title', 'Tagihan Saya')

@section('content')

{{-- ══════════════════════════════════════════════════════════
     Header
══════════════════════════════════════════════════════════ --}}
<header class="mb-8">
    <h2 class="font-display text-4xl md:text-5xl font-bold text-on-surface tracking-tight">Tagihan Saya</h2>
    <p class="font-body text-base text-on-surface-variant mt-2">Riwayat dan status semua pembayaran Anda.</p>
</header>

{{-- Kos Selector --}}
<x-kos-selector :activeContracts="$allActiveContracts" :selectedContract="$activeContract" />

{{-- ══════════════════════════════════════════════════════════
     Payment History
══════════════════════════════════════════════════════════ --}}
@if($payments->isEmpty())
    <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-16 flex flex-col items-center justify-center text-center shadow-sm">
        <span class="material-symbols-outlined text-on-surface-variant text-7xl mb-6">receipt_long</span>
        <h3 class="font-headline text-xl font-semibold text-on-surface mb-2">Belum Ada Tagihan</h3>
        <p class="font-body text-base text-on-surface-variant max-w-sm">
            @if($activeContract)
                Belum ada tagihan untuk kos ini. Tagihan akan muncul sesuai jadwal pembayaran bulanan.
            @else
                Anda belum memiliki riwayat pembayaran. Tagihan akan muncul setelah Anda memiliki kontrak aktif.
            @endif
        </p>
    </div>
@else
    <div class="space-y-4">
        @foreach($payments as $payment)
            @php
                $statusConfig = match($payment->payment_status->value ?? $payment->payment_status) {
                    'pending'           => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'showButton' => true],
                    'paid_to_escrow'    => ['label' => 'Dalam Escrow',        'class' => 'bg-blue-50 text-blue-700 border-blue-200', 'showButton' => false],
                    'released_to_owner' => ['label' => 'Lunas',               'class' => 'bg-green-50 text-green-700 border-green-200', 'showButton' => false],
                    'cancelled'         => ['label' => 'Dibatalkan',          'class' => 'bg-red-50 text-red-700 border-red-200', 'showButton' => false],
                    default             => ['label' => 'Tidak Diketahui',     'class' => 'bg-gray-50 text-gray-700 border-gray-200', 'showButton' => false],
                };
                
                // Check if overdue
                $isOverdue = $payment->payment_status->value === 'pending' && $payment->due_date && $payment->due_date->isPast();
            @endphp
            <div class="bg-surface-container-lowest border {{ $isOverdue ? 'border-red-300' : 'border-outline-variant/50' }} rounded-2xl p-4 sm:p-6 shadow-sm hover:shadow-md transition-shadow {{ $isOverdue ? 'bg-red-50/30' : '' }}">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-surface-container flex items-center justify-center text-on-surface shrink-0">
                            <span class="material-symbols-outlined text-[20px] sm:text-[24px]">receipt</span>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <p class="font-headline text-sm sm:text-base font-semibold text-on-surface truncate">
                                    {{ $payment->contract->room->boardingHouse->name ?? 'Sewa Kos' }}
                                </p>
                                <span class="bg-primary/10 text-primary px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-bold border border-primary/30 whitespace-nowrap">
                                    Bulan ke-{{ $payment->billing_month }}
                                </span>
                            </div>
                            <p class="font-body text-xs sm:text-sm text-on-surface-variant truncate">
                                {{ $payment->contract->room->type_name ?? '—' }}
                            </p>
                            <p class="font-body text-[10px] sm:text-xs text-on-surface-variant mt-1 hidden sm:block">
                                Periode: {{ $payment->contract->start_date->translatedFormat('d M Y') }} — {{ $payment->contract->end_date->translatedFormat('d M Y') }}
                            </p>
                            <p class="font-label text-[10px] sm:text-xs {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-on-surface-variant' }} mt-1">
                                @if($isOverdue)
                                    <span class="material-symbols-outlined text-[12px] sm:text-[14px] align-middle">warning</span>
                                    Jatuh tempo: {{ $payment->due_date->translatedFormat('d M Y') }} (Terlambat)
                                @else
                                    Jatuh tempo: {{ $payment->due_date->translatedFormat('d M Y') }}
                                @endif
                            </p>
                            <p class="font-label text-[10px] sm:text-xs text-on-surface-variant mt-1 hidden sm:block">
                                No. Kontrak: {{ $payment->contract->contract_number }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:items-end gap-2">
                        <p class="font-headline text-lg sm:text-xl font-bold text-on-surface">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] sm:text-xs font-semibold border self-start sm:self-auto {{ $statusConfig['class'] }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-outline-variant/30 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2 text-on-surface-variant text-[10px] sm:text-xs">
                        @if($payment->payment_method)
                        <span class="material-symbols-outlined text-[14px] sm:text-[16px]">credit_card</span>
                        <span class="font-label">Metode: {{ strtoupper($payment->payment_method) }}</span>
                        <span class="mx-1 sm:mx-2 text-outline-variant hidden sm:inline">·</span>
                        @endif
                        @if($payment->paid_at)
                        <span class="material-symbols-outlined text-[14px] sm:text-[16px]">check_circle</span>
                        <span class="font-label">Dibayar: {{ $payment->paid_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        @else
                        <span class="material-symbols-outlined text-[14px] sm:text-[16px]">schedule</span>
                        <span class="font-label">Dibuat: {{ $payment->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                        @endif
                    </div>
                    @if($statusConfig['showButton'])
                    <a href="{{ route('tenant.payment.checkout', $payment->id) }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 {{ $isOverdue ? 'bg-red-600 hover:bg-red-700' : 'bg-primary hover:bg-primary/90' }} text-on-primary rounded-full font-label text-xs font-semibold transition-colors shadow-sm whitespace-nowrap">
                        <span class="material-symbols-outlined text-[14px] sm:text-[16px]">payment</span>
                        <span class="hidden sm:inline">{{ $isOverdue ? 'Bayar Sekarang (Terlambat)' : 'Bayar Sekarang' }}</span>
                        <span class="sm:hidden">Bayar</span>
                    </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
