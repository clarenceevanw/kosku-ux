@extends('layouts.ux2.tenant')

@section('title', 'Laporan Kerusakan - KosKu')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary mb-2">Laporan Kerusakan</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Pantau dan buat laporan perbaikan untuk kamar Anda.</p>
        </div>
        @if(isset($activeTransaction))
        <a href="{{ route('ux2.tenant.tickets.create', ['kos' => $activeTransaction->id]) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-xl font-label-md text-label-md font-medium hover:bg-opacity-90 transition-colors shadow-sm">
            <span class="material-symbols-outlined">add</span>
            Buat Laporan
        </a>
        @endif
    </div>

    {{-- Kos Selector --}}
    @if(isset($allActiveContracts) && count($allActiveContracts) > 1)
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="material-symbols-outlined text-primary">home_work</span>
            <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Pilih Kos yang Ingin Dilihat</h3>
        </div>
        <p class="font-body-md text-body-md text-on-surface-variant mb-6">Anda memiliki {{ count($allActiveContracts) }} kos aktif. Pilih salah satu untuk melihat laporannya.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($allActiveContracts as $contract)
            <a href="?kos={{ $contract->id }}" 
               class="group relative flex items-center gap-4 p-4 border-2 {{ (isset($activeTransaction) && $activeTransaction->id == $contract->id) ? 'border-primary bg-primary/5' : 'border-outline-variant hover:border-outline' }} rounded-xl transition-all cursor-pointer">
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
                @if(isset($activeTransaction) && $activeTransaction->id == $contract->id)
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
        $totalTickets = $tickets->count();
        $processingTickets = clone $tickets;
        $processingCount = $processingTickets->filter(function($t) { 
            return ($t->status->value ?? $t->status) === 'in_progress' || ($t->status->value ?? $t->status) === 'reported'; 
        })->count();
        $completedTickets = clone $tickets;
        $completedCount = $completedTickets->filter(function($t) { 
            return ($t->status->value ?? $t->status) === 'resolved'; 
        })->count();
    @endphp

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">assignment</span>
                </div>
                <h3 class="font-label-md text-label-md text-on-surface-variant">Total Laporan</h3>
            </div>
            <p class="font-display-lg text-display-lg font-bold text-primary">{{ $totalTickets }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-5 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-24 h-24 bg-error-container opacity-50 rounded-tl-full blur-xl"></div>
            <div class="flex items-center gap-3 mb-2 relative z-10">
                <div class="w-10 h-10 rounded-full bg-error-container text-on-error-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                </div>
                <h3 class="font-label-md text-label-md text-on-surface-variant">Sedang Diproses</h3>
            </div>
            <p class="font-display-lg text-display-lg font-bold text-primary relative z-10">{{ $processingCount }}</p>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-5 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-24 h-24 bg-secondary-container opacity-50 rounded-tl-full blur-xl"></div>
            <div class="flex items-center gap-3 mb-2 relative z-10">
                <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
                <h3 class="font-label-md text-label-md text-on-surface-variant">Selesai</h3>
            </div>
            <p class="font-display-lg text-display-lg font-bold text-primary relative z-10">{{ $completedCount }}</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex overflow-x-auto no-scrollbar gap-2 mb-6 border-b border-outline-variant/30 pb-2">
        <a href="{{ route('ux2.tenant.tickets') }}" class="px-5 py-2.5 {{ !request('status') ? 'bg-secondary-container text-on-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-full font-label-md text-label-md font-medium transition-colors whitespace-nowrap">
            Semua Laporan
        </a>
        <a href="{{ route('ux2.tenant.tickets', ['status' => 'process']) }}" class="px-5 py-2.5 {{ request('status') === 'process' ? 'bg-secondary-container text-on-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-full font-label-md text-label-md transition-colors whitespace-nowrap">
            Sedang Proses
        </a>
        <a href="{{ route('ux2.tenant.tickets', ['status' => 'resolved']) }}" class="px-5 py-2.5 {{ request('status') === 'resolved' ? 'bg-secondary-container text-on-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' }} rounded-full font-label-md text-label-md transition-colors whitespace-nowrap">
            Selesai
        </a>
    </div>

    <!-- Tickets List -->
    <div class="space-y-4">
        @forelse($tickets as $ticket)
            @php
                $statusVal = $ticket->status->value ?? $ticket->status;
                $isResolved = $statusVal === 'resolved';
                $statusColor = $isResolved ? 'secondary' : 'error';
                $statusIcon = $isResolved ? 'check_circle' : 'build';
                $statusLabel = $isResolved ? 'Selesai' : ($statusVal === 'in_progress' ? 'Sedang Diproses' : 'Menunggu');
            @endphp
            <a href="{{ route('ux2.tenant.tickets.show', $ticket->id) }}" class="block bg-surface-container-lowest rounded-2xl p-5 border border-outline-variant/50 hover:shadow-md transition-all group relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-{{ $statusColor }}"></div>
                <div class="flex flex-col md:flex-row gap-4 md:items-center justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-{{ $statusColor }}-container text-on-{{ $statusColor }}-container flex items-center justify-center shrink-0 mt-1 md:mt-0">
                            <span class="material-symbols-outlined">{{ $statusIcon }}</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-{{ $statusColor }}-container text-on-{{ $statusColor }}-container rounded text-[10px] font-bold uppercase tracking-wider">{{ $statusLabel }}</span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Dilaporkan: {{ $ticket->created_at->translatedFormat('d M') }}</span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm font-semibold text-primary mb-1">{{ $ticket->title }}</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant line-clamp-2">{{ $ticket->description }}</p>
                            
                            @if($ticket->photo_url)
                            <div class="mt-2 flex items-center gap-1 font-label-sm text-label-sm text-primary bg-primary/5 w-fit px-2 py-1 rounded">
                                <span class="material-symbols-outlined text-[14px]">image</span>
                                Ada Lampiran
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="shrink-0 text-right mt-2 md:mt-0 ml-16 md:ml-0">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">chevron_right</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-surface-container-lowest rounded-2xl p-8 border border-outline-variant/50 text-center">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant mb-2">assignment</span>
                <p class="font-body-md text-body-md text-on-surface-variant mb-4">Belum ada laporan kerusakan.</p>
                @if(isset($activeTransaction))
                <a href="{{ route('ux2.tenant.tickets.create', ['kos' => $activeTransaction->id]) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-primary text-on-primary rounded-xl font-label-md text-label-md font-medium hover:bg-opacity-90 transition-colors shadow-sm">
                    Buat Laporan Pertama
                </a>
                @endif
            </div>
        @endforelse
    </div>
</div>
@endsection
