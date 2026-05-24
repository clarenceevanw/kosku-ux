@extends('layouts.owner')

@section('title', 'Laporan Kerusakan')

@section('content')

<!-- Header Section -->
<header class="flex justify-between items-end mb-12">
    <div>
        <h2 class="font-display text-4xl md:text-5xl font-extrabold tracking-tighter text-primary">Laporan Kerusakan</h2>
        <p class="font-body text-base text-on-surface-variant mt-2">Pantau dan tangani laporan pemeliharaan secara efisien.</p>
    </div>
</header>

<!-- Filter Row -->
<section class="mb-8">
    <form method="GET" action="{{ route('owner.tickets.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="relative flex-grow group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">search</span>
            <input name="search" value="{{ request('search') }}"
                class="w-full pl-12 pr-6 py-4 bg-surface-container-lowest border border-outline-variant/50 rounded-full shadow-sm focus:ring-2 focus:ring-primary text-sm font-body text-on-surface"
                placeholder="Cari judul laporan atau nama penyewa..." type="text" />
        </div>
        <div class="relative min-w-[250px]">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">tune</span>
            <select name="kos_id" onchange="this.form.submit()"
                class="w-full pl-12 pr-6 py-4 bg-surface-container-lowest border border-outline-variant/50 rounded-full shadow-sm appearance-none focus:ring-2 focus:ring-primary text-sm font-label font-semibold text-on-surface cursor-pointer">
                @foreach($boardingHouses as $kos)
                    <option value="{{ $kos->id }}" {{ ($selectedKos?->id == $kos->id) ? 'selected' : '' }}>
                        Kos: {{ $kos->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="hidden">Search</button>
    </form>
</section>

<!-- SECTION 2: LAPORAN KERUSAKAN -->
<section class="space-y-8 animate-in fade-in duration-500">
    <div>
        <h3 class="font-display text-headline-md font-bold tracking-tight mb-1">Maintenance Board</h3>
        <p class="text-on-surface-variant text-sm font-body">Tracking and resolving infrastructure issues di {{ $selectedKos?->name ?? 'Belum ada Kos' }}.</p>
    </div>
    
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl relative font-body text-sm mb-6 shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- COLUMN: DILAPORKAN -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-label font-bold tracking-widest uppercase py-1 px-3 bg-red-100 text-red-700 rounded-full">Dilaporkan</span>
                    <span class="text-on-surface-variant font-bold text-sm">{{ count($groupedTickets['dilaporkan'] ?? []) }}</span>
                </div>
                <button class="material-symbols-outlined text-on-surface-variant text-sm hover:text-primary transition-colors">more_horiz</button>
            </div>
            
            <div class="space-y-4">
                @forelse($groupedTickets['dilaporkan'] ?? [] as $ticket)
                <div class="bg-surface-container-lowest p-5 rounded-xl border-l-4 {{ $ticket->priority->value == 'tinggi' ? 'border-red-600' : 'border-outline-variant' }} shadow-sm border border-outline-variant/50 hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start mb-3">
                        @if($ticket->priority->value == 'tinggi')
                            <span class="bg-red-50 text-red-700 text-[10px] font-label font-black uppercase px-2 py-0.5 rounded tracking-tighter">Urgent</span>
                        @else
                            <span class="bg-surface-container text-on-surface-variant text-[10px] font-label font-black uppercase px-2 py-0.5 rounded tracking-tighter">Normal</span>
                        @endif
                        <span class="text-[10px] font-body font-medium text-on-surface-variant">{{ $ticket->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-headline font-bold text-base leading-tight mb-1 text-primary">{{ $ticket->title }}</h4>
                    <p class="font-body text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $ticket->tenant->name }}</p>
                    
                    <div class="flex gap-2 pt-4 border-t border-outline-variant/30">
                        <a href="{{ route('owner.tickets.show', $ticket->id) }}" class="flex items-center justify-center w-10 h-10 rounded-full bg-surface-container hover:bg-primary text-on-surface-variant hover:text-white transition-colors flex-shrink-0" title="Lihat Detail">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </a>
                        <form action="{{ route('owner.tickets.update', $ticket->id) }}" method="POST" class="flex-grow relative">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="w-full text-xs font-label font-bold py-2 px-3 rounded-full border-none bg-surface-container hover:bg-surface-container-high appearance-none cursor-pointer text-primary transition-colors focus:ring-0">
                                <option value="reported" selected disabled>Ubah Status</option>
                                <option value="in_progress">Kerjakan</option>
                                <option value="resolved">Selesai</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-[16px] pointer-events-none text-on-surface-variant">swap_horiz</span>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center border border-dashed border-outline-variant/50 rounded-xl bg-surface-container-lowest/50">
                    <p class="text-xs text-on-surface-variant font-body">Tidak ada laporan baru.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- COLUMN: DIKERJAKAN -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-label font-bold tracking-widest uppercase py-1 px-3 bg-orange-100 text-orange-700 rounded-full">Dikerjakan</span>
                    <span class="text-on-surface-variant font-bold text-sm">{{ count($groupedTickets['dikerjakan'] ?? []) }}</span>
                </div>
                <button class="material-symbols-outlined text-on-surface-variant text-sm hover:text-primary transition-colors">more_horiz</button>
            </div>
            
            <div class="space-y-4">
                @forelse($groupedTickets['dikerjakan'] ?? [] as $ticket)
                <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm border border-outline-variant/50 hover:shadow-md transition-all">
                    <div class="flex justify-between items-start mb-3">
                        <span class="bg-orange-50 text-orange-700 text-[10px] font-label font-black uppercase px-2 py-0.5 rounded tracking-tighter">On-Going</span>
                        <span class="text-[10px] font-body font-medium text-on-surface-variant">{{ $ticket->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-headline font-bold text-base leading-tight mb-1 text-primary">{{ $ticket->title }}</h4>
                    <p class="font-body text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $ticket->tenant->name }}</p>
                    
                    <div class="flex gap-2 pt-4 border-t border-outline-variant/30">
                        <a href="{{ route('owner.tickets.show', $ticket->id) }}" class="flex items-center justify-center w-10 h-10 rounded-full bg-surface-container hover:bg-primary text-on-surface-variant hover:text-white transition-colors flex-shrink-0" title="Lihat Detail">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </a>
                        <form action="{{ route('owner.tickets.update', $ticket->id) }}" method="POST" class="flex-grow relative">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="w-full text-xs font-label font-bold py-2 px-3 rounded-full border-none bg-surface-container hover:bg-surface-container-high appearance-none cursor-pointer text-primary transition-colors focus:ring-0">
                                <option value="reported">Kembali ke Laporan</option>
                                <option value="in_progress" selected disabled>Sedang Dikerjakan</option>
                                <option value="resolved">Selesai</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-[16px] pointer-events-none text-on-surface-variant">swap_horiz</span>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center border border-dashed border-outline-variant/50 rounded-xl bg-surface-container-lowest/50">
                    <p class="text-xs text-on-surface-variant font-body">Tidak ada tiket yang sedang dikerjakan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- COLUMN: SELESAI -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-label font-bold tracking-widest uppercase py-1 px-3 bg-emerald-100 text-emerald-700 rounded-full">Selesai</span>
                    <span class="text-on-surface-variant font-bold text-sm">{{ count($groupedTickets['selesai'] ?? []) }}</span>
                </div>
                <button class="material-symbols-outlined text-on-surface-variant text-sm hover:text-primary transition-colors">more_horiz</button>
            </div>
            
            <div class="space-y-4 opacity-70">
                @forelse($groupedTickets['selesai'] ?? [] as $ticket)
                <div class="bg-surface-container-lowest p-5 rounded-xl shadow-sm border border-outline-variant/50 border-dashed hover:opacity-100 transition-opacity">
                    <div class="flex justify-between items-start mb-3">
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] font-label font-black uppercase px-2 py-0.5 rounded tracking-tighter">Resolved</span>
                        <span class="text-[10px] font-body font-medium text-on-surface-variant">{{ $ticket->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-headline font-bold text-base leading-tight mb-1 line-through text-on-surface-variant">{{ $ticket->title }}</h4>
                    <p class="font-body text-sm text-on-surface-variant mb-4">{{ $ticket->tenant->name }}</p>
                    <div class="pt-4 border-t border-outline-variant/30 flex justify-between items-center gap-2">
                        <span class="text-[10px] font-label font-bold uppercase tracking-widest text-emerald-600">Teratasi pada {{ $ticket->updated_at->format('d M Y') }}</span>
                        <a href="{{ route('owner.tickets.show', $ticket->id) }}" class="flex items-center justify-center w-8 h-8 rounded-full bg-surface-container hover:bg-primary text-on-surface-variant hover:text-white transition-colors" title="Lihat Detail">
                            <span class="material-symbols-outlined text-[14px]">visibility</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center border border-dashed border-outline-variant/50 rounded-xl bg-surface-container-lowest/50">
                    <p class="text-xs text-on-surface-variant font-body">Belum ada tiket yang selesai.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</section>

@endsection
