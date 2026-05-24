@extends('layouts.ux2.tenant')

@section('title', 'Dashboard Penghuni - KosKu')

@section('content')
<header class="mb-8">
    <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg font-semibold text-on-surface">
        Selamat Datang, {{ $tenant->name ?? 'Penghuni' }}!</h2>
    <p class="font-body-md text-body-md text-on-surface-variant mt-2">Berikut adalah ringkasan aktivitas kos Anda hari ini.</p>
</header>

{{-- Kos Selector --}}
@if($allActiveContracts->count() > 1)
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
    <div class="flex items-center gap-4 mb-4">
        <span class="material-symbols-outlined text-primary">home_work</span>
        <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Pilih Kos yang Ingin Dilihat</h3>
    </div>
    <p class="font-body-md text-body-md text-on-surface-variant mb-6">Anda memiliki {{ $allActiveContracts->count() }} kos aktif. Pilih salah satu untuk melihat detailnya.</p>
    
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

@php
    $contract = $activeContract;
    $room = $contract?->room;
    $boardingHouse = $room?->boardingHouse;
@endphp

<!-- Bento Grid Layout -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    <!-- Kos Saya (Property Info) - Spans 8 cols on desktop -->
    <div class="md:col-span-8 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col md:flex-row">
        @if($contract)
            <div class="w-full md:w-2/5 h-48 md:h-auto relative">
                @if ($room?->image_url)
                <img alt="{{ $boardingHouse?->name ?? 'Kamar kos' }}" class="w-full h-full object-cover" src="{{ $room->image_url }}" />
                @else
                <div class="w-full h-full bg-surface-variant flex items-center justify-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl">home_work</span>
                </div>
                @endif
                <div class="absolute bottom-4 left-4 bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full font-label-sm text-label-sm font-semibold flex items-center gap-1 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Aktif
                </div>
            </div>
            <div class="p-6 w-full md:w-3/5 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">{{ $boardingHouse?->name ?? 'Belum ada hunian aktif' }}</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[18px]">location_on</span> {{ $boardingHouse ? $boardingHouse->city . ', ' . $boardingHouse->province : '-' }}
                            </p>
                        </div>
                        <span class="bg-surface-variant text-on-surface-variant px-3 py-1 rounded-full font-label-md text-label-md">{{ $room?->type_name ?? '-' }}</span>
                    </div>
                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between items-center border-b border-outline-variant pb-2">
                            <span class="font-body-md text-body-md text-on-surface-variant">No. Kontrak</span>
                            <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $contract?->contract_number ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-outline-variant pb-2">
                            <span class="font-body-md text-body-md text-on-surface-variant">Masa Sewa Dimulai</span>
                            <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $contract?->start_date?->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-outline-variant pb-2">
                            <span class="font-body-md text-body-md text-on-surface-variant">Masa Sewa Berakhir</span>
                            <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $contract?->end_date?->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-outline-variant pb-2">
                            <span class="font-body-md text-body-md text-on-surface-variant">Sisa Waktu Kontrak</span>
                            <span class="font-label-md text-label-md font-semibold text-on-surface">{{ $remainingTime ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <a class="mt-6 w-full md:w-auto self-end bg-surface text-primary border border-outline px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-variant transition-colors text-center"
                    href="{{ route('ux2.tenant.contract') }}">Lihat Detail Kos</a>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center py-10 px-4 text-center">
                <span class="material-symbols-outlined text-on-surface-variant text-6xl mb-4">home_work</span>
                <h3 class="font-headline-md text-headline-md font-semibold text-on-surface mb-2">Belum Ada Hunian Aktif</h3>
                <p class="font-body-md text-body-md text-on-surface-variant mb-6 max-w-sm">Anda belum memiliki kontrak hunian yang aktif. Mulai cari kos impianmu sekarang!</p>
                <a href="{{ route('ux2.search') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-full font-label-md text-label-md font-semibold hover:bg-opacity-90 transition-opacity">
                    <span class="material-symbols-outlined">search</span> Cari Kos
                </a>
            </div>
        @endif
    </div>

    <!-- Tagihan (Billing) - Spans 4 cols on desktop -->
    <div class="md:col-span-4 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-6 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-secondary text-[28px]">payments</span>
                <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Tagihan Mendatang</h3>
            </div>
            
            @if($upcomingPayment)
                <div class="{{ ($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) === 'pending' ? 'bg-error-container text-on-error-container' : 'bg-surface-container text-on-surface' }} p-4 rounded-lg mb-6">
                    <p class="font-label-sm text-label-sm uppercase tracking-wider mb-1">
                        {{ $upcomingPayment->contract->transaction->room->boardingHouse->name ?? 'Sewa Bulanan' }}
                        (Bulan ke-{{ $upcomingPayment->billing_month }})
                    </p>
                    <p class="font-display-lg text-headline-lg md:text-display-lg font-bold">Rp {{ number_format($upcomingPayment->amount, 0, ',', '.') }}</p>
                    <p class="font-body-md text-body-md mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">schedule</span> Jatuh tempo: {{ $upcomingPayment?->due_date?->translatedFormat('d M Y') ?? '-' }}
                    </p>
                    
                    @if($daysUntilDue !== null && $daysUntilDue <= 3 && ($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) === 'pending')
                        <p class="font-label-md text-label-md mt-2 {{ $daysUntilDue == 0 ? 'text-error font-bold' : '' }}">
                            @if($daysUntilDue == 0) Jatuh tempo hari ini! @else Jatuh tempo dalam {{ $daysUntilDue }} hari @endif
                        </p>
                    @elseif($daysUntilDue !== null && ($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) === 'pending')
                        <p class="font-label-md text-label-md mt-2">Jatuh tempo dalam {{ $daysUntilDue }} hari</p>
                    @endif
                    
                    @php
                        $statusLabel = match($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) {
                            'pending'           => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-error text-on-error'],
                            'paid_to_escrow'    => ['label' => 'Dalam Escrow', 'class' => 'bg-secondary text-on-secondary'],
                            'released_to_owner' => ['label' => 'Lunas', 'class' => 'bg-secondary text-on-secondary'],
                            'cancelled'         => ['label' => 'Dibatalkan', 'class' => 'bg-error text-on-error'],
                            default             => ['label' => 'Tidak Diketahui', 'class' => 'bg-surface-variant text-on-surface-variant'],
                        };
                    @endphp
                    <div class="mt-3 inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusLabel['class'] }}">
                        {{ $statusLabel['label'] }}
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center text-center py-6 h-full">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-3">check_circle</span>
                    <p class="font-headline-sm text-headline-sm font-semibold text-on-surface mb-1">Tidak Ada Tagihan</p>
                    <p class="font-body-md text-body-md text-on-surface-variant">Semua tagihan Anda sudah lunas.</p>
                </div>
            @endif
        </div>
        <a class="w-full bg-secondary text-on-secondary font-label-md text-label-md py-3 rounded-lg hover:opacity-90 transition-opacity text-center block mt-4"
            href="{{ route('ux2.tenant.payments') }}">
            {{ $upcomingPayment && ($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) === 'pending' ? 'Bayar Sekarang' : ($upcomingPayment ? 'Lihat Detail Tagihan' : 'Riwayat Tagihan') }}
        </a>
    </div>

    <!-- Quick Stats Row - Spans 12 cols -->
    <div class="md:col-span-12 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-on-surface shrink-0">
                <span class="material-symbols-outlined text-[24px]">calendar_month</span>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Total Masa Sewa</p>
                <p class="font-headline-md text-headline-md font-bold text-on-surface mt-1">{{ $durationMonths ?? '—' }} Bln</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-on-surface shrink-0">
                <span class="material-symbols-outlined text-[24px]">report_problem</span>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tiket Aktif</p>
                <p class="font-headline-md text-headline-md font-bold text-on-surface mt-1">{{ $ticketStats['active'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-on-surface shrink-0">
                <span class="material-symbols-outlined text-[24px]">check_circle</span>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tiket Selesai</p>
                <p class="font-headline-md text-headline-md font-bold text-on-surface mt-1">{{ $ticketStats['resolved'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- AI Chatbot (KosBot) - Spans 6 cols -->
    <div class="md:col-span-6 bg-tertiary-container rounded-xl shadow-sm p-6 flex flex-col md:flex-row items-center gap-6 overflow-hidden relative">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-on-tertiary-container rounded-full blur-3xl opacity-20 pointer-events-none"></div>
        <div class="w-16 h-16 rounded-full bg-on-tertiary-container flex items-center justify-center shrink-0 shadow-lg">
            <span class="material-symbols-outlined text-on-tertiary text-[32px]">smart_toy</span>
        </div>
        <div class="flex-1 text-center md:text-left z-10">
            <h3 class="font-headline-md text-headline-md font-semibold text-on-tertiary mb-2">Tanya KosBot AI</h3>
            <p class="font-body-md text-body-md text-tertiary-fixed-dim mb-4">Butuh bantuan seputar kos atau ada pertanyaan teknis? KosBot siap membantu 24/7.</p>
            <a class="bg-on-tertiary text-tertiary-container px-6 py-2 rounded-full font-label-md text-label-md font-bold hover:bg-opacity-90 transition-opacity inline-flex" href="{{ route('ux2.bot') }}">Mulai Chat</a>
        </div>
    </div>

    <!-- Laporan Kerusakan Terakhir - Spans 6 cols -->
    <div class="md:col-span-6 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Status Laporan</h3>
            <a class="text-secondary font-label-md text-label-md hover:underline" href="{{ route('ux2.tenant.tickets') }}">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @forelse ($recentTickets as $ticket)
            <div class="flex items-start gap-4 p-4 rounded-lg {{ $loop->odd ? 'bg-surface-container' : 'bg-surface' }}">
                <div class="w-10 h-10 rounded-full bg-surface-variant flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-on-surface-variant">build</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <h4 class="font-label-md text-label-md font-semibold text-on-surface truncate pr-2">{{ $ticket->title }}</h4>
                        @php
                            $statusLabel = match($ticket->status->value ?? $ticket->status) {
                                'reported' => 'Dilaporkan',
                                'in_progress' => 'Diproses',
                                'resolved' => 'Selesai',
                                default => 'Unknown'
                            };
                        @endphp
                        <span class="bg-surface-variant text-on-surface-variant px-2 py-0.5 rounded text-xs whitespace-nowrap">{{ $statusLabel }}</span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1 text-xs">{{ $ticket->created_at?->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="p-4 rounded-lg bg-surface text-center">
                <span class="material-symbols-outlined text-on-surface-variant mb-2 text-3xl">check_circle</span>
                <p class="font-label-md text-label-md font-semibold text-on-surface mb-1">Tidak Ada Laporan</p>
                <p class="font-body-md text-body-md text-on-surface-variant">Belum ada laporan kerusakan yang diajukan.</p>
            </div>
            @endforelse
            
            <!-- AI Insight Card -->
            <div class="bg-surface-container rounded-xl p-4 flex items-center gap-3 mt-4 border border-outline-variant/50">
                <div class="w-10 h-10 bg-surface-container-lowest rounded-full flex items-center justify-center text-on-surface shrink-0 border border-outline-variant/30">
                    <span class="material-symbols-outlined text-[20px]">tips_and_updates</span>
                </div>
                <div class="flex-1">
                    @if(($ticketStats['total'] ?? 0) === 0)
                        <p class="font-body-sm text-body-sm text-on-surface">Riwayat maintenance rendah. Nikmati kenyamanan maksimal!</p>
                    @elseif(($ticketStats['active'] ?? 0) > 0)
                        <p class="font-body-sm text-body-sm text-on-surface">Anda memiliki <b>{{ $ticketStats['active'] }}</b> laporan aktif yang sedang ditangani.</p>
                    @else
                        <p class="font-body-sm text-body-sm text-on-surface">Semua laporan kerusakan terselesaikan. Terima kasih!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
