@extends('layouts.ux2.owner')

@section('title', 'Laporan Kerusakan')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary mb-xs">Laporan Kerusakan</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Pantau dan tangani laporan pemeliharaan secara efisien</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-surface-container-lowest p-md rounded-3xl shadow-lg border border-outline-variant">
        <form method="GET" action="{{ route('ux2.owner.tickets.index') }}" class="flex flex-col md:flex-row gap-md">
            <div class="relative flex-grow">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input name="search" value="{{ request('search') }}"
                    class="w-full pl-12 pr-6 py-3 bg-surface-container border border-outline-variant rounded-3xl font-body-md text-body-md text-on-surface focus:ring-2 focus:ring-secondary-container"
                    placeholder="Cari judul laporan atau nama penyewa..." type="text" />
            </div>
            <div class="relative min-w-[250px]">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">tune</span>
                <select name="kos_id" onchange="this.form.submit()"
                    class="w-full pl-12 pr-6 py-3 bg-surface-container border border-outline-variant rounded-3xl appearance-none focus:ring-2 focus:ring-secondary-container font-label-md text-label-md text-on-surface cursor-pointer">
                    @foreach($boardingHouses as $kos)
                        <option value="{{ $kos->id }}" {{ ($selectedKos?->id == $kos->id) ? 'selected' : '' }}>
                            {{ $kos->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="hidden">Search</button>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-secondary-container/20 border border-secondary-container text-on-surface px-md py-sm rounded-2xl font-body-md text-body-md">
        {{ session('success') }}
    </div>
    @endif

    <!-- Kanban Board -->
    <div>
        <div class="flex items-center gap-sm mb-md">
            <span class="material-symbols-outlined text-primary">view_kanban</span>
            <h3 class="font-headline-md text-headline-md text-primary">Maintenance Board</h3>
        </div>
        <p class="font-body-md text-body-md text-on-surface-variant mb-lg">{{ $selectedKos?->name ?? 'Belum ada Kos' }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
        
        <!-- COLUMN: DILAPORKAN -->
        <div class="flex flex-col gap-md">
            <div class="flex items-center justify-between px-sm">
                <div class="flex items-center gap-sm">
                    <span class="font-label-sm text-label-sm font-bold uppercase py-1 px-3 bg-error-container text-error rounded-full">Dilaporkan</span>
                    <span class="font-label-md text-label-md text-on-surface-variant font-bold">{{ count($groupedTickets['dilaporkan'] ?? []) }}</span>
                </div>
            </div>
            
            <div class="space-y-md">
                @forelse($groupedTickets['dilaporkan'] ?? [] as $ticket)
                <div class="bg-surface-container-lowest p-md rounded-2xl border-l-4 {{ $ticket->priority->value == 'tinggi' ? 'border-error' : 'border-outline-variant' }} shadow-sm border border-outline-variant hover:shadow-lg transition-all">
                    <div class="flex justify-between items-start mb-sm">
                        @if($ticket->priority->value == 'tinggi')
                            <span class="bg-error-container text-error font-label-sm text-label-sm font-bold uppercase px-2 py-1 rounded-full">Urgent</span>
                        @else
                            <span class="bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold uppercase px-2 py-1 rounded-full">Normal</span>
                        @endif
                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $ticket->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-label-md text-label-md font-bold text-primary mb-xs">{{ $ticket->title }}</h4>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-md line-clamp-2">{{ $ticket->tenant->name }}</p>
                    
                    <div class="flex gap-sm pt-md border-t border-outline-variant">
                        <a href="{{ route('ux2.owner.tickets.show', $ticket->id) }}" class="flex items-center justify-center w-10 h-10 rounded-full bg-surface-container hover:bg-secondary-container text-on-surface-variant hover:text-on-secondary-container transition-colors" title="Lihat Detail">
                            <span class="material-symbols-outlined">visibility</span>
                        </a>
                        <form action="{{ route('ux2.owner.tickets.update', $ticket->id) }}" method="POST" class="flex-grow relative">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="w-full font-label-sm text-label-sm font-bold py-2 px-3 rounded-full border-none bg-surface-container hover:bg-surface-container-high appearance-none cursor-pointer text-primary transition-colors focus:ring-0">
                                <option value="reported" selected disabled>Ubah Status</option>
                                <option value="in_progress">Kerjakan</option>
                                <option value="resolved">Selesai</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-sm pointer-events-none text-on-surface-variant">swap_horiz</span>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-lg text-center border border-dashed border-outline-variant rounded-2xl bg-surface-container/50">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-50 mb-sm block">inbox</span>
                    <p class="font-body-md text-body-md text-on-surface-variant">Tidak ada laporan baru</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- COLUMN: DIKERJAKAN -->
        <div class="flex flex-col gap-md">
            <div class="flex items-center justify-between px-sm">
                <div class="flex items-center gap-sm">
                    <span class="font-label-sm text-label-sm font-bold uppercase py-1 px-3 bg-tertiary-container text-on-tertiary-container rounded-full">Dikerjakan</span>
                    <span class="font-label-md text-label-md text-on-surface-variant font-bold">{{ count($groupedTickets['dikerjakan'] ?? []) }}</span>
                </div>
            </div>
            
            <div class="space-y-md">
                @forelse($groupedTickets['dikerjakan'] ?? [] as $ticket)
                <div class="bg-surface-container-lowest p-md rounded-2xl shadow-sm border border-outline-variant hover:shadow-lg transition-all">
                    <div class="flex justify-between items-start mb-sm">
                        <span class="bg-tertiary-container text-on-tertiary-container font-label-sm text-label-sm font-bold uppercase px-2 py-1 rounded-full">On-Going</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $ticket->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-label-md text-label-md font-bold text-primary mb-xs">{{ $ticket->title }}</h4>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-md line-clamp-2">{{ $ticket->tenant->name }}</p>
                    
                    <div class="flex gap-sm pt-md border-t border-outline-variant">
                        <a href="{{ route('ux2.owner.tickets.show', $ticket->id) }}" class="flex items-center justify-center w-10 h-10 rounded-full bg-surface-container hover:bg-secondary-container text-on-surface-variant hover:text-on-secondary-container transition-colors" title="Lihat Detail">
                            <span class="material-symbols-outlined">visibility</span>
                        </a>
                        <form action="{{ route('ux2.owner.tickets.update', $ticket->id) }}" method="POST" class="flex-grow relative">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="w-full font-label-sm text-label-sm font-bold py-2 px-3 rounded-full border-none bg-surface-container hover:bg-surface-container-high appearance-none cursor-pointer text-primary transition-colors focus:ring-0">
                                <option value="reported">Kembali ke Laporan</option>
                                <option value="in_progress" selected disabled>Sedang Dikerjakan</option>
                                <option value="resolved">Selesai</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-sm pointer-events-none text-on-surface-variant">swap_horiz</span>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-lg text-center border border-dashed border-outline-variant rounded-2xl bg-surface-container/50">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-50 mb-sm block">engineering</span>
                    <p class="font-body-md text-body-md text-on-surface-variant">Tidak ada tiket yang sedang dikerjakan</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- COLUMN: SELESAI -->
        <div class="flex flex-col gap-md">
            <div class="flex items-center justify-between px-sm">
                <div class="flex items-center gap-sm">
                    <span class="font-label-sm text-label-sm font-bold uppercase py-1 px-3 bg-secondary-container text-on-secondary-container rounded-full">Selesai</span>
                    <span class="font-label-md text-label-md text-on-surface-variant font-bold">{{ count($groupedTickets['selesai'] ?? []) }}</span>
                </div>
            </div>
            
            <div class="space-y-md opacity-70">
                @forelse($groupedTickets['selesai'] ?? [] as $ticket)
                <div class="bg-surface-container-lowest p-md rounded-2xl shadow-sm border border-dashed border-outline-variant hover:opacity-100 transition-opacity">
                    <div class="flex justify-between items-start mb-sm">
                        <span class="bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-bold uppercase px-2 py-1 rounded-full">Resolved</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $ticket->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-label-md text-label-md font-bold line-through text-on-surface-variant mb-xs">{{ $ticket->title }}</h4>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-md">{{ $ticket->tenant->name }}</p>
                    <div class="pt-md border-t border-outline-variant flex justify-between items-center gap-sm">
                        <span class="font-label-sm text-label-sm font-bold uppercase text-secondary-container">{{ $ticket->updated_at->format('d M Y') }}</span>
                        <a href="{{ route('ux2.owner.tickets.show', $ticket->id) }}" class="flex items-center justify-center w-8 h-8 rounded-full bg-surface-container hover:bg-secondary-container text-on-surface-variant hover:text-on-secondary-container transition-colors" title="Lihat Detail">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-lg text-center border border-dashed border-outline-variant rounded-2xl bg-surface-container/50">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant opacity-50 mb-sm block">task_alt</span>
                    <p class="font-body-md text-body-md text-on-surface-variant">Belum ada tiket yang selesai</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
