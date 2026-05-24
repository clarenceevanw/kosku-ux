@extends('layouts.owner')

@section('title', 'Detail Laporan Kerusakan')

@section('content')

<div class="max-w-4xl md:mx-0">
    {{-- Header Section --}}
    <div class="mb-12">
        <a href="{{ route('owner.tickets.index') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary mb-6 transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            <span class="font-label text-sm font-semibold tracking-wide">Kembali ke Laporan</span>
        </a>
        <div class="flex items-start justify-between gap-4 mb-4">
            <h1 class="font-headline text-4xl font-extrabold tracking-tight text-primary">{{ $ticket->title }}</h1>
            @php
                $statusVal = $ticket->status->value ?? $ticket->status;
                $statusConfig = match($statusVal) {
                    'reported' => [
                        'label' => 'Dilaporkan',
                        'badge' => 'bg-red-50 text-red-700 border-red-200',
                    ],
                    'in_progress' => [
                        'label' => 'Dikerjakan',
                        'badge' => 'bg-orange-50 text-orange-700 border-orange-200',
                    ],
                    'resolved' => [
                        'label' => 'Selesai',
                        'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
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
                    @if(($ticket->priority->value ?? $ticket->priority) === 'tinggi')
                    <span class="w-2 h-2 rounded-full bg-error"></span>
                    <p class="font-body text-base font-semibold text-error">Urgent</p>
                    @else
                    <span class="w-2 h-2 rounded-full bg-surface-tint"></span>
                    <p class="font-body text-base font-semibold text-on-surface">Normal</p>
                    @endif
                </div>
            </div>
            <div>
                <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-2">Dilaporkan Pada</p>
                <p class="font-body text-base font-semibold text-on-surface">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-2">Penyewa</p>
                <p class="font-body text-base font-semibold text-on-surface">{{ $ticket->tenant->name }}</p>
            </div>
            <div>
                <p class="font-label text-xs text-on-surface-variant uppercase tracking-wider mb-2">Lokasi Kamar</p>
                <p class="font-body text-base font-semibold text-on-surface">{{ $ticket->room->boardingHouse->name }} - Kamar {{ $ticket->room->type_name }}</p>
            </div>
        </div>

        {{-- Description --}}
        <div>
            <h3 class="font-headline text-lg font-semibold text-on-surface mb-4">Deskripsi Kerusakan</h3>
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

        {{-- Action Update Status --}}
        <div class="pt-8 border-t border-outline-variant/30">
            <h3 class="font-headline text-lg font-semibold text-on-surface mb-6">Tindakan</h3>
            <form action="{{ route('owner.tickets.update', $ticket->id) }}" method="POST" class="w-full relative max-w-sm">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-4">
                    <select name="status" class="w-full text-sm font-label font-bold py-3 px-4 rounded-xl border border-outline-variant/50 bg-surface-container-lowest hover:bg-surface-container-low appearance-none cursor-pointer text-primary transition-colors focus:ring-2 focus:ring-primary">
                        <option value="reported" {{ $statusVal === 'reported' ? 'selected' : '' }}>Dilaporkan</option>
                        <option value="in_progress" {{ $statusVal === 'in_progress' ? 'selected' : '' }}>Dikerjakan</option>
                        <option value="resolved" {{ $statusVal === 'resolved' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="bg-primary text-white font-label font-bold text-sm px-6 py-3 rounded-xl hover:bg-primary/90 transition-colors whitespace-nowrap">
                        Update
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
