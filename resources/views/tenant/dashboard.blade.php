@extends('layouts.tenant', ['activeContract' => $activeContract])

@section('title', 'Dashboard Penghuni')

@section('content')

{{-- ══════════════════════════════════════════════════════════
     Header Section
══════════════════════════════════════════════════════════ --}}
<header class="flex justify-between items-end mb-8">
    <div>
        <h2 class="font-display text-4xl md:text-5xl font-bold text-on-surface tracking-tight">
            Penghuni Dashboard
        </h2>
        <p class="font-body text-base text-on-surface-variant mt-2">
            Selamat datang kembali, <span class="font-semibold text-on-surface">{{ Str::words($tenant->name, 1, '') }}</span>.
            Berikut ringkasan status hunian Anda.
        </p>
    </div>
    {{-- Desktop: Notification + Avatar --}}
    <div class="hidden md:flex items-center gap-4">
        <button class="w-12 h-12 flex items-center justify-center rounded-full bg-surface-container hover:bg-surface-container-high text-on-surface transition-colors shadow-sm"
                title="Notifikasi">
            <span class="material-symbols-outlined">notifications</span>
        </button>
        <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-on-primary font-headline text-lg font-bold shadow-sm select-none">
            {{ strtoupper(substr($tenant->name, 0, 1)) }}
        </div>
    </div>
</header>

{{-- Kos Selector --}}
<x-kos-selector :activeContracts="$allActiveContracts" :selectedContract="$activeContract" />

{{-- ══════════════════════════════════════════════════════════
     Bento Grid
══════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    {{-- ──────────────────────────────────────────────────────
         Room Status Card (col-span-8)
    ────────────────────────────────────────────────────── --}}
    <section class="col-span-1 lg:col-span-8 bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row gap-6 md:gap-8 items-start md:items-center relative overflow-hidden shadow-sm">
        {{-- Decorative background blob --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-surface-container-high rounded-full blur-3xl opacity-40 pointer-events-none"></div>

        @if($activeContract)
            {{-- Room Image --}}
            <div class="w-full md:w-56 h-48 md:h-56 rounded-xl overflow-hidden flex-shrink-0 bg-surface-container border border-outline-variant/30 shadow-sm">
                @if($activeContract->room?->image_url)
                    <img src="{{ $activeContract->room->image_url }}"
                         alt="Foto Kamar {{ $activeContract->room->type_name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-surface-container">
                        <span class="material-symbols-outlined text-on-surface-variant text-6xl">bed</span>
                    </div>
                @endif
            </div>

            {{-- Room Details --}}
            <div class="flex-1 z-10 min-w-0">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full font-label text-sm font-semibold flex items-center gap-1.5 border border-green-200">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                        Check-in Aktif
                    </span>
                    <span class="bg-surface-container text-on-surface-variant px-4 py-1.5 rounded-full font-label text-sm font-medium border border-outline-variant/30">
                        {{ $activeContract->room->type_name }}
                    </span>
                </div>

                <h3 class="font-headline text-2xl md:text-3xl font-bold text-on-surface mb-2 truncate">
                    {{ $activeContract->room->boardingHouse->name }}
                </h3>
                <p class="font-body text-base text-on-surface-variant mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] shrink-0">location_on</span>
                    <span class="truncate">{{ $activeContract->room->boardingHouse->address }}, {{ $activeContract->room->boardingHouse->city }}</span>
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 pt-6 border-t border-outline-variant/30">
                    <div>
                        <p class="font-label text-xs sm:text-sm text-on-surface-variant">Tanggal Mulai Sewa</p>
                        <p class="font-body text-base sm:text-lg font-semibold text-on-surface mt-1">
                            {{ $activeContract->contract?->start_date?->translatedFormat('d M Y') ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-label text-xs sm:text-sm text-on-surface-variant">Berlaku Hingga</p>
                        <p class="font-body text-base sm:text-lg font-semibold text-on-surface mt-1">
                            {{ $activeContract->contract?->end_date?->translatedFormat('d M Y') ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-label text-xs sm:text-sm text-on-surface-variant">Sisa Waktu Kontrak</p>
                        <p class="font-body text-base sm:text-lg font-semibold text-on-surface mt-1">
                            {{ $remainingTime ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-label text-xs sm:text-sm text-on-surface-variant">No. Kontrak</p>
                        <p class="font-body text-sm sm:text-base font-semibold text-on-surface mt-1">
                            {{ $activeContract->contract?->contract_number ?? '—' }}
                        </p>
                    </div>
                </div>
            </div>

        @else
            {{-- Empty State: No active contract --}}
            <div class="flex-1 z-10 flex flex-col items-center justify-center text-center py-8 w-full">
                <span class="material-symbols-outlined text-on-surface-variant text-6xl mb-4">home_work</span>
                <h3 class="font-headline text-xl font-semibold text-on-surface mb-2">Belum Ada Hunian Aktif</h3>
                <p class="font-body text-base text-on-surface-variant mb-6 max-w-sm">
                    Anda belum memiliki kontrak hunian yang aktif. Mulai cari kos impianmu sekarang!
                </p>
                <a href="{{ route('search') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-full font-label text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    Cari Kos
                </a>
            </div>
        @endif
    </section>

    {{-- ──────────────────────────────────────────────────────
         Upcoming Bill Card (col-span-4)
    ────────────────────────────────────────────────────── --}}
    <section class="col-span-1 lg:col-span-4 bg-surface-container border border-outline-variant/50 rounded-2xl p-6 md:p-8 flex flex-col justify-between shadow-sm">
        <div>
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-surface-container-lowest flex items-center justify-center text-on-surface shadow-sm border border-outline-variant/30">
                    <span class="material-symbols-outlined">receipt_long</span>
                </div>
                <h3 class="font-headline text-xl font-semibold text-on-surface">Tagihan Mendatang</h3>
            </div>

            @if($upcomingPayment)
                <p class="font-display text-3xl font-bold text-on-surface mb-1">
                    Rp {{ number_format($upcomingPayment->amount, 0, ',', '.') }}
                </p>
                <p class="font-body text-base text-on-surface-variant">
                    {{ $upcomingPayment->contract->transaction->room->boardingHouse->name ?? 'Sewa Bulanan' }}
                    <span class="inline-block ml-2 bg-primary/10 text-primary px-2 py-0.5 rounded-full text-xs font-bold border border-primary/30">
                        Bulan ke-{{ $upcomingPayment->billing_month }}
                    </span>
                </p>
                <p class="font-body text-sm text-on-surface-variant mt-2">
                    Jatuh tempo: {{ $upcomingPayment->due_date->translatedFormat('d M Y') }}
                </p>

                {{-- Due date indicator --}}
                <div class="mt-6 bg-surface-container-lowest rounded-xl p-4 border border-outline-variant/30 flex items-center gap-3 shadow-sm">
                    @if($daysUntilDue !== null && $daysUntilDue <= 3)
                        <span class="material-symbols-outlined text-red-500 text-[20px]">warning</span>
                        <p class="font-label text-sm font-medium text-red-600">
                            @if($daysUntilDue == 0)
                                Jatuh tempo hari ini!
                            @else
                                Jatuh tempo dalam {{ $daysUntilDue }} hari
                            @endif
                        </p>
                    @elseif($daysUntilDue !== null)
                        <span class="material-symbols-outlined text-on-surface text-[20px]">timer</span>
                        <p class="font-label text-sm font-medium text-on-surface">
                            Jatuh tempo dalam {{ $daysUntilDue }} hari
                        </p>
                    @else
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">schedule</span>
                        <p class="font-label text-sm font-medium text-on-surface-variant">Sedang diproses</p>
                    @endif
                </div>

                {{-- Payment status badge --}}
                <div class="mt-3">
                    @php
                        $statusLabel = match($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) {
                            'pending'           => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                            'paid_to_escrow'    => ['label' => 'Dalam Escrow', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                            'released_to_owner' => ['label' => 'Lunas', 'class' => 'bg-green-50 text-green-700 border-green-200'],
                            'cancelled'         => ['label' => 'Dibatalkan', 'class' => 'bg-red-50 text-red-700 border-red-200'],
                            default             => ['label' => 'Tidak Diketahui', 'class' => 'bg-gray-50 text-gray-700 border-gray-200'],
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusLabel['class'] }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $statusLabel['label'] }}
                    </span>
                </div>

            @else
                {{-- No upcoming payment --}}
                <div class="flex flex-col items-center text-center py-4">
                    <span class="material-symbols-outlined text-on-surface-variant text-5xl mb-3">check_circle</span>
                    <p class="font-body text-base font-semibold text-on-surface mb-1">Tidak Ada Tagihan</p>
                    <p class="font-body text-sm text-on-surface-variant">Semua tagihan Anda sudah lunas.</p>
                </div>
            @endif
        </div>

        <a href="{{ route('tenant.payments') }}"
           class="w-full mt-8 bg-primary text-on-primary font-label text-base font-medium py-3.5 rounded-full hover:bg-primary/90 transition-colors shadow-sm text-center block">
            {{ $upcomingPayment && ($upcomingPayment->payment_status->value ?? $upcomingPayment->payment_status) === 'pending' ? 'Bayar Tagihan' : ($upcomingPayment ? 'Lihat Detail Tagihan' : 'Riwayat Tagihan') }}
        </a>
    </section>

    {{-- ──────────────────────────────────────────────────────
         Quick Stats Row (col-span-12)
    ────────────────────────────────────────────────────── --}}
    <section class="col-span-1 lg:col-span-12 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-4 sm:p-5 shadow-sm flex items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-surface-container flex items-center justify-center text-on-surface shrink-0">
                <span class="material-symbols-outlined text-[20px] sm:text-[22px]">calendar_month</span>
            </div>
            <div class="min-w-0">
                <p class="font-label text-[10px] sm:text-xs text-on-surface-variant uppercase tracking-wider">Total Masa Sewa</p>
                <p class="font-headline text-lg sm:text-xl font-bold text-on-surface mt-0.5">{{ $durationMonths ?? '—' }} Bln</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-4 sm:p-5 shadow-sm flex items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-surface-container flex items-center justify-center text-on-surface shrink-0">
                <span class="material-symbols-outlined text-[20px] sm:text-[22px]">report_problem</span>
            </div>
            <div class="min-w-0">
                <p class="font-label text-[10px] sm:text-xs text-on-surface-variant uppercase tracking-wider">Tiket Aktif</p>
                <p class="font-headline text-lg sm:text-xl font-bold text-on-surface mt-0.5">{{ $ticketStats['active'] }}</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-4 sm:p-5 shadow-sm flex items-center gap-3 sm:gap-4">
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-surface-container flex items-center justify-center text-on-surface shrink-0">
                <span class="material-symbols-outlined text-[20px] sm:text-[22px]">check_circle</span>
            </div>
            <div class="min-w-0">
                <p class="font-label text-[10px] sm:text-xs text-on-surface-variant uppercase tracking-wider">Tiket Selesai</p>
                <p class="font-headline text-lg sm:text-xl font-bold text-on-surface mt-0.5">{{ $ticketStats['resolved'] }}</p>
            </div>
        </div>
    </section>

    {{-- ──────────────────────────────────────────────────────
         Maintenance Tickets Section (col-span-12)
    ────────────────────────────────────────────────────── --}}
    <section class="col-span-1 lg:col-span-12 bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
            <h3 class="font-headline text-2xl font-bold text-on-surface">Laporan Maintenance</h3>
            <a href="{{ route('tenant.tickets') }}"
               class="text-primary font-label text-sm font-semibold hover:text-primary/80 transition-colors flex items-center gap-2 bg-surface-container px-4 py-2 rounded-full border border-outline-variant/30 self-start sm:self-auto">
                Buat Laporan Baru
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($recentTickets as $ticket)
                @php
                    $statusConfig = match($ticket->status->value ?? $ticket->status) {
                        'reported'    => ['label' => 'Dilaporkan',     'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                        'in_progress' => ['label' => 'Diproses',       'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                        'resolved'    => ['label' => 'Selesai',        'class' => 'bg-surface-container text-on-surface-variant border-outline-variant/30'],
                        default       => ['label' => 'Tidak Diketahui','class' => 'bg-gray-50 text-gray-600 border-gray-200'],
                    };
                    $isResolved = ($ticket->status->value ?? $ticket->status) === 'resolved';
                @endphp
                <div class="bg-surface border border-outline-variant/50 rounded-xl p-6 hover:shadow-md transition-shadow {{ $isResolved ? 'opacity-70' : '' }}">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 rounded-full font-label text-xs font-semibold border {{ $statusConfig['class'] }}">
                            {{ $statusConfig['label'] }}
                        </span>
                        <!-- <span class="font-label text-xs text-on-surface-variant">#TKT-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</span> -->
                    </div>
                    <h4 class="font-headline text-base font-semibold text-on-surface mb-2">{{ $ticket->title }}</h4>
                    <p class="font-body text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $ticket->description }}</p>
                    <div class="flex items-center gap-2 text-on-surface-variant">
                        @if($isResolved)
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            <span class="font-label text-xs">Diselesaikan: {{ $ticket->updated_at->translatedFormat('d M') }}</span>
                        @else
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <span class="font-label text-xs">Dilaporkan: {{ $ticket->created_at->translatedFormat('d M') }}</span>
                        @endif
                    </div>
                    @if(($ticket->priority->value ?? $ticket->priority) === 'urgent')
                    <div class="mt-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-red-50 text-red-600 border border-red-200 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[12px]">priority_high</span>
                            Prioritas Urgent
                        </span>
                    </div>
                    @endif
                </div>
            @empty
                {{-- AI Insight / Empty State --}}
                <div class="md:col-span-2 lg:col-span-2 bg-surface border border-outline-variant/50 rounded-xl p-6 flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-outlined text-on-surface-variant text-5xl mb-4">check_circle</span>
                    <p class="font-body text-sm font-semibold text-on-surface mb-1">Tidak Ada Laporan</p>
                    <p class="font-body text-sm text-on-surface-variant">Belum ada laporan kerusakan yang diajukan.</p>
                </div>
            @endforelse

            {{-- AI Insight Card (always shown as last item) --}}
            <div class="bg-surface-container border border-outline-variant/30 rounded-xl p-6 flex flex-col justify-center items-center text-center">
                <div class="w-14 h-14 bg-surface-container-lowest rounded-full flex items-center justify-center text-on-surface mb-4 shadow-sm border border-outline-variant/30">
                    <span class="material-symbols-outlined">tips_and_updates</span>
                </div>
                @if($ticketStats['total'] === 0)
                    <p class="font-body text-sm font-medium text-on-surface">Riwayat maintenance kamar Anda tergolong rendah. Nikmati kenyamanan maksimal di KosKu!</p>
                @elseif($ticketStats['active'] > 0)
                    <p class="font-body text-sm font-medium text-on-surface">Anda memiliki <span class="font-bold">{{ $ticketStats['active'] }}</span> laporan yang sedang aktif. Tim kami sedang menanganinya.</p>
                @else
                    <p class="font-body text-sm font-medium text-on-surface">Semua laporan kerusakan Anda sudah terselesaikan. Terima kasih atas kepercayaan Anda!</p>
                @endif
            </div>
        </div>

        @if($recentTickets->count() >= 3)
        <div class="mt-6 text-center">
            <a href="{{ route('tenant.tickets') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-on-surface-variant hover:text-on-surface transition-colors">
                Lihat Semua Laporan
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
        @endif
    </section>

</div>

@endsection
