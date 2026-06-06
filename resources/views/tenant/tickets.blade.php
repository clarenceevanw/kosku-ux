@extends('layouts.tenant', ['activeContract' => $activeContract ?? null])

@section('title', 'Laporan Kerusakan')

@section('content')

{{-- Header Section --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 sm:gap-6 mb-12">
    <div>
        <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary tracking-tight mb-2 sm:mb-3">Laporan Kerusakan</h1>
        <p class="font-body text-sm sm:text-base md:text-lg text-on-surface-variant">Pantau status perbaikan fasilitas kamar Anda.</p>
    </div>
    @if($activeContract)
    <a href="{{ route('tenant.tickets.create', ['kos' => $activeContract->id]) }}" class="bg-primary text-on-primary font-bold py-2.5 sm:py-3 px-5 sm:px-6 rounded-full hover:bg-inverse-surface transition-transform hover:scale-[0.98] flex items-center justify-center gap-2 self-start md:self-auto shadow-sm text-sm sm:text-base">
        <span class="material-symbols-outlined text-[20px]">add</span>
        <span class="hidden sm:inline">Buat Laporan Baru</span>
        <span class="sm:hidden">Buat Laporan</span>
    </a>
    @endif
</div>

{{-- Kos Selector --}}
<x-kos-selector :activeContracts="$allActiveContracts" :selectedContract="$activeContract" />

{{-- Filters --}}
<div class="flex items-center gap-4 sm:gap-8 mb-8 border-b border-surface-container-high overflow-x-auto">
    <a href="{{ route('tenant.tickets') }}" class="{{ !request('status') ? 'text-primary font-semibold border-b-2 border-primary' : 'text-on-surface-variant font-medium hover:text-primary' }} text-xs sm:text-sm tracking-wide py-4 whitespace-nowrap transition-colors">Semua</a>
    <a href="{{ route('tenant.tickets', ['status' => 'process']) }}" class="{{ request('status') === 'process' ? 'text-primary font-semibold border-b-2 border-primary' : 'text-on-surface-variant font-medium hover:text-primary' }} text-xs sm:text-sm tracking-wide py-4 whitespace-nowrap transition-colors">Proses</a>
    <a href="{{ route('tenant.tickets', ['status' => 'resolved']) }}" class="{{ request('status') === 'resolved' ? 'text-primary font-semibold border-b-2 border-primary' : 'text-on-surface-variant font-medium hover:text-primary' }} text-xs sm:text-sm tracking-wide py-4 whitespace-nowrap transition-colors">Selesai</a>
</div>

@if($tickets->isEmpty())
    <div class="bg-surface-container-lowest border border-surface-container-high rounded-2xl p-16 flex flex-col items-center justify-center text-center shadow-sm">
        <span class="material-symbols-outlined text-on-surface-variant text-7xl mb-6">report_problem</span>
        <h3 class="font-headline text-xl font-semibold text-on-surface mb-2">Belum Ada Laporan</h3>
        <p class="font-body text-base text-on-surface-variant max-w-sm mb-6">
            Anda belum pernah mengajukan laporan kerusakan. Jika ada masalah pada kamar, laporkan sekarang.
        </p>
        @if($activeContract)
        <a href="{{ route('tenant.tickets.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-full font-label text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Buat Laporan Pertama
        </a>
        @else
        <p class="font-body text-sm text-on-surface-variant">Anda perlu memiliki hunian aktif untuk membuat laporan.</p>
        @endif
    </div>
@else
    {{-- Grid Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tickets as $ticket)
            @php
                $statusVal = $ticket->status->value ?? $ticket->status;
                $statusConfig = match($statusVal) {
                    'reported' => [
                        'label' => 'Menunggu',
                        'badge' => 'bg-surface-container-high text-on-surface-variant',
                    ],
                    'in_progress' => [
                        'label' => 'Diproses',
                        'badge' => 'bg-primary-fixed text-on-primary-fixed',
                    ],
                    'resolved' => [
                        'label' => 'Selesai',
                        'badge' => 'bg-[#DCFCE7] text-[#10B981]',
                    ],
                    default => [
                        'label' => '—',
                        'badge' => 'bg-gray-100 text-gray-600',
                    ],
                };
            @endphp
            
            <div class="bg-surface-container-lowest border border-surface-container-high rounded-2xl p-4 sm:p-6 hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] transition-all flex flex-col justify-between min-h-[240px] sm:h-[280px] relative overflow-hidden group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="{{ $statusConfig['badge'] }} text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $statusConfig['label'] }}
                        </span>
                        <span class="material-symbols-outlined text-outline-variant">more_horiz</span>
                    </div>
                    <h3 class="font-headline text-lg sm:text-xl font-bold text-primary mb-2 leading-tight line-clamp-1" title="{{ $ticket->title }}">
                        {{ $ticket->title }}
                    </h3>
                    <p class="font-body text-xs sm:text-sm text-on-surface-variant line-clamp-2" title="{{ $ticket->description }}">
                        {{ $ticket->description }}
                    </p>
                    @if($ticket->photo_url)
                    <div class="mt-3 flex items-center gap-1 text-xs font-medium text-primary bg-primary/5 w-fit px-2 py-1 rounded-md">
                        <span class="material-symbols-outlined text-[14px]">image</span>
                        Ada Lampiran
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 sm:mt-6 flex items-center justify-between border-t border-surface-container pt-3 sm:pt-4">
                    <span class="text-[10px] sm:text-xs text-outline font-medium">Dilaporkan: {{ $ticket->created_at->translatedFormat('d M') }}</span>
                    <a href="{{ route('tenant.tickets.show', $ticket->id) }}" class="text-xs sm:text-sm font-bold text-primary flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        <span class="hidden sm:inline">Lihat Detail</span>
                        <span class="sm:hidden">Detail</span>
                        <span class="material-symbols-outlined text-[14px] sm:text-[16px]">chevron_right</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
