@extends('layouts.ux2.owner')

@section('title', 'Detail Laporan Kerusakan')

@section('content')
<div class="max-w-4xl space-y-8">
    <!-- Back Button -->
    <a href="{{ route('ux2.owner.tickets.index') }}" class="inline-flex items-center gap-sm text-on-surface-variant hover:text-primary transition-colors">
        <span class="material-symbols-outlined">arrow_back</span>
        <span class="font-label-md text-label-md font-semibold">Kembali ke Laporan</span>
    </a>

    <!-- Header -->
    <div class="flex items-start justify-between gap-md">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-primary mb-xs">{{ $ticket->title }}</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Laporan #{{ $ticket->id }}</p>
        </div>
        @php
            $statusVal = $ticket->status->value ?? $ticket->status;
            $statusConfig = match($statusVal) {
                'reported' => [
                    'label' => 'Dilaporkan',
                    'badge' => 'bg-error-container text-error',
                ],
                'in_progress' => [
                    'label' => 'Dikerjakan',
                    'badge' => 'bg-tertiary-container text-on-tertiary-container',
                ],
                'resolved' => [
                    'label' => 'Selesai',
                    'badge' => 'bg-secondary-container text-on-secondary-container',
                ],
                default => [
                    'label' => '—',
                    'badge' => 'bg-surface-container text-on-surface-variant',
                ],
            };
        @endphp
        <span class="{{ $statusConfig['badge'] }} font-label-md text-label-md font-bold px-md py-sm rounded-full uppercase">
            {{ $statusConfig['label'] }}
        </span>
    </div>

    <!-- Main Content Card -->
    <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant p-lg shadow-lg space-y-lg">
        
        <!-- Ticket Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg pb-lg border-b border-outline-variant">
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Prioritas</p>
                <div class="flex items-center gap-sm">
                    @if(($ticket->priority->value ?? $ticket->priority) === 'tinggi')
                    <span class="w-2 h-2 rounded-full bg-error"></span>
                    <p class="font-label-md text-label-md font-bold text-error">Urgent</p>
                    @else
                    <span class="w-2 h-2 rounded-full bg-surface-tint"></span>
                    <p class="font-label-md text-label-md font-bold text-on-surface">Normal</p>
                    @endif
                </div>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Dilaporkan Pada</p>
                <p class="font-label-md text-label-md font-bold text-on-surface">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Penyewa</p>
                <p class="font-label-md text-label-md font-bold text-on-surface">{{ $ticket->tenant->name }}</p>
            </div>
            <div>
                <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Lokasi Kamar</p>
                <p class="font-label-md text-label-md font-bold text-on-surface">{{ $ticket->room->boardingHouse->name }} - Kamar {{ $ticket->room->type_name }}</p>
            </div>
        </div>

        <!-- Description -->
        <div>
            <h3 class="font-headline-md text-headline-md text-primary mb-md">Deskripsi Kerusakan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">{{ $ticket->description }}</p>
        </div>

        <!-- Photo if exists -->
        @if($ticket->photo_url)
        <div>
            <h3 class="font-headline-md text-headline-md text-primary mb-md">Lampiran Foto</h3>
            <div class="rounded-2xl overflow-hidden border border-outline-variant">
                <img src="{{ asset('storage/' . $ticket->photo_url) }}" alt="Foto {{ $ticket->title }}" class="w-full h-auto">
            </div>
        </div>
        @endif

        <!-- Action Update Status -->
        <div class="pt-lg border-t border-outline-variant">
            <h3 class="font-headline-md text-headline-md text-primary mb-md">Tindakan</h3>
            <form action="{{ route('ux2.owner.tickets.update', $ticket->id) }}" method="POST" class="max-w-md">
                @csrf
                @method('PUT')
                <div class="flex items-center gap-md">
                    <select name="status" class="flex-grow font-label-md text-label-md font-bold py-3 px-4 rounded-2xl border border-outline-variant bg-surface-container appearance-none cursor-pointer text-primary transition-colors focus:ring-2 focus:ring-secondary-container">
                        <option value="reported" {{ $statusVal === 'reported' ? 'selected' : '' }}>Dilaporkan</option>
                        <option value="in_progress" {{ $statusVal === 'in_progress' ? 'selected' : '' }}>Dikerjakan</option>
                        <option value="resolved" {{ $statusVal === 'resolved' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="bg-primary text-on-primary font-label-md text-label-md font-bold px-lg py-3 rounded-2xl hover:shadow-lg transition-all whitespace-nowrap">
                        Update Status
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
