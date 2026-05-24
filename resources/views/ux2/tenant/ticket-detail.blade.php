@extends('layouts.ux2.tenant')

@section('title', 'Detail Laporan Kerusakan - KosKu')

@section('content')
<div class="px-6 py-8 max-w-4xl mx-auto">
    @php
        $statusVal = $ticket->status->value ?? $ticket->status;
        $isReported = $statusVal === 'reported';
        $isInProgress = $statusVal === 'in_progress';
        $isResolved = $statusVal === 'resolved';

        $statusLabel = $isResolved ? 'Selesai' : ($isInProgress ? 'Sedang Diproses' : 'Menunggu');
        $statusColor = $isResolved ? 'secondary' : ($isInProgress ? 'error' : 'surface-variant');
    @endphp
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-start justify-between gap-4">
        <div>
            <a href="{{ route('ux2.tenant.tickets') }}" class="inline-flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors font-label-md text-label-md mb-4">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Kembali ke Daftar Laporan
            </a>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="font-headline-md text-headline-md font-bold text-primary">{{ $ticket->title }}</h1>
                <span class="px-3 py-1 bg-{{ $statusColor }}-container text-on-{{ $statusColor }}-container rounded-full text-xs font-bold uppercase tracking-wider">{{ $statusLabel }}</span>
            </div>
            <p class="font-body-md text-body-md text-on-surface-variant">Dilaporkan pada {{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</p>
        </div>
        <div class="shrink-0">
            <div class="bg-surface-container border border-outline-variant/50 rounded-xl px-4 py-2 text-center">
                <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Tiket ID</p>
                <p class="font-body-md text-body-md font-mono text-primary font-bold">#TCK-{{ str_pad($ticket->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <!-- Ticket Detail -->
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                <h2 class="font-headline-sm text-headline-sm font-semibold text-primary mb-4 border-b border-outline-variant/30 pb-2">Deskripsi Laporan</h2>
                
                <div class="mb-6">
                    <p class="font-body-md text-body-md text-on-surface leading-relaxed whitespace-pre-line">{{ $ticket->description }}</p>
                </div>

                @if($ticket->photo_url)
                <div>
                    <h3 class="font-label-md text-label-md text-on-surface-variant mb-3">Foto Lampiran</h3>
                    <div class="rounded-xl overflow-hidden border border-outline-variant/30">
                        <img src="{{ asset('storage/' . $ticket->photo_url) }}" alt="Foto {{ $ticket->title }}" class="w-full h-auto">
                    </div>
                </div>
                @endif
            </div>

            <!-- Timeline (Moved from original) -->
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                <h2 class="font-headline-sm text-headline-sm font-semibold text-primary mb-6 border-b border-outline-variant/30 pb-2">Timeline</h2>
                
                <div class="relative pl-8 border-l-2 border-outline-variant space-y-6 ml-2">
                    <div class="relative">
                        <div class="absolute -left-[37px] top-0 w-6 h-6 rounded-full bg-primary flex items-center justify-center border-4 border-surface-container-lowest">
                            <span class="w-2 h-2 rounded-full bg-on-primary"></span>
                        </div>
                        <p class="font-label-md text-label-md font-bold text-on-surface">Laporan Dibuat</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                    </div>
                    
                    @if($isInProgress || $isResolved)
                    <div class="relative">
                        <div class="absolute -left-[37px] top-0 w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center border-4 border-surface-container-lowest">
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                        </div>
                        <p class="font-label-md text-label-md font-bold text-on-surface">Sedang Diproses</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $ticket->updated_at->translatedFormat('d M Y, H:i') }} WIB</p>
                    </div>
                    @endif
                    
                    @if($isResolved)
                    <div class="relative">
                        <div class="absolute -left-[37px] top-0 w-6 h-6 rounded-full bg-secondary flex items-center justify-center border-4 border-surface-container-lowest">
                            <span class="material-symbols-outlined text-white text-[14px]" style="font-variation-settings: 'FILL' 1;">check</span>
                        </div>
                        <p class="font-label-md text-label-md font-bold text-on-surface">Selesai</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $ticket->updated_at->translatedFormat('d M Y, H:i') }} WIB</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-4">Informasi Laporan</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Prioritas</p>
                        <div class="flex items-center gap-2">
                            @if(($ticket->priority->value ?? $ticket->priority) === 'urgent')
                            <span class="w-2 h-2 rounded-full bg-error"></span>
                            <p class="font-body-md text-body-md text-error font-medium">Urgent</p>
                            @else
                            <span class="w-2 h-2 rounded-full bg-surface-variant"></span>
                            <p class="font-body-md text-body-md text-on-surface font-medium">Normal</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="h-px bg-outline-variant/30 w-full"></div>
                    
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Lokasi</p>
                        <p class="font-body-md text-body-md text-primary font-medium">{{ $ticket->room->boardingHouse->name }}</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Kamar {{ $ticket->room->type_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
