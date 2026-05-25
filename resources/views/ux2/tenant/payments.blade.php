@extends('layouts.ux2.tenant')

@section('title', 'Tagihan Saya - KosKu')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="font-headline-md text-headline-md font-bold text-primary mb-2">Tagihan & Pembayaran</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Kelola dan bayar tagihan sewa kos Anda di sini.</p>
    </div>

    {{-- Kos Selector --}}
    @if($allActiveContracts->count() > 1)
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="material-symbols-outlined text-primary">home_work</span>
            <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Pilih Kos yang Ingin Dilihat</h3>
        </div>
        <p class="font-body-md text-body-md text-on-surface-variant mb-6">Anda memiliki {{ $allActiveContracts->count() }} kos aktif. Pilih salah satu untuk melihat tagihannya.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($allActiveContracts as $contract)
            <a href="?kos={{ $contract->id }}" 
               class="group relative flex items-center gap-4 p-4 border-2 {{ $activeContract?->id === $contract->id ? 'border-primary bg-primary/5' : 'border-outline-variant hover:border-outline' }} rounded-xl transition-all cursor-pointer">
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
                    @if($contract->room?->image_url)
                    <img src="{{ $contract->room->image_url }}" alt="{{ $contract->room->boardingHouse->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-surface-variant">bed</span>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-label-lg text-label-lg font-semibold text-on-surface truncate">{{ $contract->room->boardingHouse->name }}</h4>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ $contract->room->type_name }}</p>
                    <p class="font-label-md text-label-md text-on-surface-variant mt-1">{{ $contract->room->boardingHouse->city }}</p>
                </div>
                @if($activeContract?->id === $contract->id)
                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-on-primary text-[16px]" style="font-variation-settings: 'FILL' 1;">check</span>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Bills List -->
    @if($payments->isEmpty())
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-16 flex flex-col items-center justify-center text-center shadow-sm">
            <span class="material-symbols-outlined text-on-surface-variant text-7xl mb-6">receipt_long</span>
            <h3 class="font-headline-md text-headline-md font-semibold text-on-surface mb-2">Belum Ada Tagihan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-sm">
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
                        'pending'           => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-error-container text-on-error-container', 'showButton' => true],
                        'paid_to_escrow'    => ['label' => 'Dalam Escrow',        'class' => 'bg-secondary-container text-on-secondary-container', 'showButton' => false],
                        'released_to_owner' => ['label' => 'Lunas',               'class' => 'bg-secondary-container text-on-secondary-container', 'showButton' => false],
                        'cancelled'         => ['label' => 'Dibatalkan',          'class' => 'bg-error-container text-on-error-container', 'showButton' => false],
                        default             => ['label' => 'Tidak Diketahui',     'class' => 'bg-surface-variant text-on-surface-variant', 'showButton' => false],
                    };
                    
                    // Check if overdue
                    $isOverdue = ($payment->payment_status->value ?? $payment->payment_status) === 'pending' && $payment->due_date && \Carbon\Carbon::parse($payment->due_date)->isPast();
                @endphp
                <div class="bg-surface-container-lowest border {{ $isOverdue ? 'border-error border-2' : 'border-outline-variant' }} rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                    @if(($payment->payment_status->value ?? $payment->payment_status) === 'pending')
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-error"></div>
                    @else
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-secondary"></div>
                    @endif
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl {{ ($payment->payment_status->value ?? $payment->payment_status) === 'pending' ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container' }} flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined">{{ ($payment->payment_status->value ?? $payment->payment_status) === 'pending' ? 'payments' : 'check_circle' }}</span>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="font-headline-sm text-headline-sm font-semibold text-primary truncate">
                                        {{ $payment->contract->room->boardingHouse->name ?? 'Sewa Kos' }}
                                    </h3>
                                    <span class="bg-primary-container text-on-primary-container px-2 py-0.5 rounded text-[10px] font-bold border border-primary/20 whitespace-nowrap">
                                        Bulan ke-{{ $payment->billing_month }}
                                    </span>
                                </div>
                                <p class="font-body-md text-body-md text-on-surface-variant truncate">
                                    {{ $payment->contract->room->type_name ?? '—' }}
                                </p>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
                                    Periode: {{ \Carbon\Carbon::parse($payment->contract->start_date)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($payment->contract->end_date)->translatedFormat('d M Y') }}
                                </p>
                                <p class="font-label-sm text-label-sm {{ $isOverdue ? 'text-error font-semibold' : 'text-on-surface-variant' }} mt-1">
                                    @if($isOverdue)
                                        <span class="material-symbols-outlined text-[14px] align-middle">warning</span>
                                        Jatuh tempo: {{ \Carbon\Carbon::parse($payment->due_date)->translatedFormat('d M Y') }} (Terlambat)
                                    @else
                                        Jatuh tempo: {{ \Carbon\Carbon::parse($payment->due_date)->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 border-outline-variant/30 pt-4 sm:pt-0 mt-2 sm:mt-0 gap-2">
                            <p class="font-headline-md text-headline-md font-bold text-primary">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusConfig['class'] }}">
                                {{ $statusConfig['label'] }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-outline-variant/30 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex flex-wrap items-center gap-2 text-on-surface-variant font-label-sm text-label-sm">
                            @if($payment->payment_method)
                            <span class="material-symbols-outlined text-[16px]">credit_card</span>
                            <span>Metode: {{ strtoupper($payment->payment_method) }}</span>
                            <span class="mx-1 sm:mx-2 text-outline-variant hidden sm:inline">·</span>
                            @endif
                            @if($payment->paid_at)
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            <span>Dibayar: {{ \Carbon\Carbon::parse($payment->paid_at)->translatedFormat('d M Y, H:i') }} WIB</span>
                            @else
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            <span>Dibuat: {{ \Carbon\Carbon::parse($payment->created_at)->translatedFormat('d M Y, H:i') }} WIB</span>
                            @endif
                        </div>
                        @if($statusConfig['showButton'])
                        <a href="{{ route('ux2.tenant.payment.checkout', $payment->id) }}" 
                           class="inline-flex items-center justify-center gap-2 px-5 py-2 {{ $isOverdue ? 'bg-error text-on-error hover:bg-opacity-90' : 'bg-primary text-on-primary hover:bg-opacity-90' }} rounded-lg font-label-md text-label-md transition-colors shadow-sm whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px]">payment</span>
                            <span>{{ $isOverdue ? 'Bayar Sekarang (Terlambat)' : 'Bayar Sekarang' }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
