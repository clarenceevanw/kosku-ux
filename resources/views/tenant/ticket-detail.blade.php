@extends('layouts.tenant', ['activeContract' => $activeContract ?? null])

@section('title', 'Detail Laporan')

@section('content')

<div class="max-w-4xl md:mx-0">
    {{-- Header Section --}}
    <div class="mb-12">
        <a href="{{ route('tenant.tickets') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary mb-6 transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            <span class="font-label text-sm font-semibold tracking-wide">Kembali ke Laporan</span>
        </a>
        <div class="flex items-start justify-between gap-4 mb-4">
            <h1 class="font-headline text-4xl font-extrabold tracking-tight text-primary">{{ $ticket->title }}</h1>
            @php
                $statusVal = $ticket->status->value ?? $ticket->status;
                $statusConfig = match($statusVal) {
                    'reported' => [
                        'label' => 'Menunggu',
                        'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                    ],
                    'in_progress' => [
                        'label' => 'Diproses',
                        'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                    ],
                    'resolved' => [
                        'label' => 'Selesai',
                        'badge' => 'bg-green-50 text-green-700 border-green-200',
                    ],
                    default => [
                        'label' => '—',
                        'badge' => 'bg-gray-100 text-gray-600 border-gray-200',
                    ],
                };
            @endphp
            <span class="{{ $statusConfig['badge'] }} text-sm font-bold px-4 py-2 rounded-full uppercase tracking-wider border">
                {{ $statusConfig['label'] }}
            </span>
        </div>
        <p class="font-body text-base text-on-surface-variant">Laporan #{{ $ticket->id }}</p>
    </div>

    {{-- Main Content Card --}}
    <div class="bg-surface-container-lowest rounded-[2rem] border border-outline-variant/50 p-8 md:p-12 shadow-sm space-y-8">
        
        {{-- Ticket Info Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-8 border-b border-outline-variant/30">
            <div>
                <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-2">Prioritas</p>
                <div class="flex items-center gap-2">
                    @if(($ticket->priority->value ?? $ticket->priority) === 'urgent')
                    <span class="w-2 h-2 rounded-full bg-error"></span>
                    <p class="font-body text-base font-semibold text-error">Urgent</p>
                    @else
                    <span class="w-2 h-2 rounded-full bg-surface-tint"></span>
                    <p class="font-body text-base font-semibold text-on-surface">Normal</p>
                    @endif
                </div>
            </div>
            <div>
                <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-2">Dilaporkan</p>
                <p class="font-body text-base font-semibold text-on-surface">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>

        {{-- Location Info --}}
        <div class="bg-surface-container rounded-xl p-6 border border-outline-variant/30">
            <h3 class="font-headline text-lg font-semibold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">location_on</span>
                Lokasi
            </h3>
            <p class="font-body text-base text-on-surface">{{ $ticket->room->boardingHouse->name }}</p>
            <p class="font-body text-sm text-on-surface-variant mt-1">Kamar {{ $ticket->room->type_name }}</p>
        </div>

        {{-- Description --}}
        <div>
            <h3 class="font-headline text-lg font-semibold text-on-surface mb-4">Deskripsi Masalah</h3>
            <p class="font-body text-base text-on-surface-variant leading-relaxed">{{ $ticket->description }}</p>
        </div>

        {{-- Photo if exists --}}
        @if($ticket->photo_url)
        <div>
            <h3 class="font-headline text-lg font-semibold text-on-surface mb-4">Lampiran Foto</h3>
            <div class="rounded-xl overflow-hidden border border-outline-variant/30">
                <img src="{{ asset('storage/' . $ticket->photo_url) }}" alt="Foto {{ $ticket->title }}" class="w-full h-auto">
            </div>
        </div>
        @endif

        {{-- Timeline --}}
        <div class="pt-8 border-t border-outline-variant/30">
            <h3 class="font-headline text-lg font-semibold text-on-surface mb-6">Timeline</h3>
            <div class="relative pl-8 border-l-2 border-outline-variant space-y-6 ml-2">
                <div class="relative">
                    <div class="absolute -left-[37px] top-0 w-6 h-6 rounded-full bg-primary flex items-center justify-center border-4 border-surface-container-lowest">
                        <span class="w-2 h-2 rounded-full bg-on-primary"></span>
                    </div>
                    <p class="font-label text-sm font-semibold text-on-surface">Laporan Dibuat</p>
                    <p class="font-body text-xs text-on-surface-variant mt-1">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                </div>
                
                @if($statusVal === 'in_progress' || $statusVal === 'resolved')
                <div class="relative">
                    <div class="absolute -left-[37px] top-0 w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center border-4 border-surface-container-lowest">
                        <span class="w-2 h-2 rounded-full bg-white"></span>
                    </div>
                    <p class="font-label text-sm font-semibold text-on-surface">Sedang Diproses</p>
                    <p class="font-body text-xs text-on-surface-variant mt-1">{{ $ticket->updated_at->translatedFormat('d M Y, H:i') }} WIB</p>
                </div>
                @endif
                
                @if($statusVal === 'resolved')
                <div class="relative">
                    <div class="absolute -left-[37px] top-0 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center border-4 border-surface-container-lowest">
                        <span class="material-symbols-outlined text-white text-[14px]" style="font-variation-settings: 'FILL' 1;">check</span>
                    </div>
                    <p class="font-label text-sm font-semibold text-on-surface">Selesai</p>
                    <p class="font-body text-xs text-on-surface-variant mt-1">{{ $ticket->updated_at->translatedFormat('d M Y, H:i') }} WIB</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
