@extends('layouts.owner')

@section('title', 'Kelola Unit')

@section('content')

    <!-- Header Section -->
    <header class="flex justify-between items-end mb-12">
        <div>
            <div class="flex items-center gap-4 mb-2">
                <a href="{{ route('owner.rooms.index') }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-primary hover:text-white transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h2 class="font-display text-4xl md:text-5xl font-extrabold tracking-tighter text-primary">Kelola Unit: {{ $room->type_name }}</h2>
            </div>
            <p class="font-body text-base text-on-surface-variant mt-2 ml-14">
                {{ $room->boardingHouse->name }} &bull; Rp {{ number_format($room->price_per_month, 0, ',', '.') }}/bulan &bull; {{ $room->contracts->count() }}/{{ $room->stock }} Unit Terisi
            </p>
        </div>
    </header>

    <!-- SECTION: DAFTAR PENYEWA AKTIF -->
    <section class="block space-y-8 animate-in fade-in duration-500">
        <div>
            <h3 class="font-display text-headline-md font-bold tracking-tight mb-1">Daftar Penyewa Aktif</h3>
            <p class="text-on-surface-variant text-sm font-body">Menampilkan penyewa yang sedang menyewa tipe kamar ini.</p>
        </div>

        <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden border border-outline-variant/50">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-surface-container/50 border-b border-outline-variant/50">
                            <th class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Nama Penyewa</th>
                            <th class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Nomor Kontrak</th>
                            <th class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Mulai Sewa</th>
                            <th class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">Selesai Sewa</th>
                            <th class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse($room->contracts as $contract)
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="px-6 py-6">
                                    <span class="font-display font-bold text-lg text-primary">{{ $contract->tenant->name }}</span>
                                    <p class="text-xs text-on-surface-variant font-body">{{ $contract->tenant->email }}</p>
                                </td>
                                <td class="px-6 py-6 font-body font-semibold text-primary">
                                    {{ $contract->contract_number ?? '-' }}
                                </td>
                                <td class="px-6 py-6 font-body text-on-surface">
                                    {{ $contract->start_date ? $contract->start_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-6 font-body text-on-surface">
                                    {{ $contract->end_date ? $contract->end_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-outlined text-5xl text-outline mb-4">person_off</span>
                                        <h3 class="font-headline text-lg font-bold text-on-surface mb-2">Belum ada penyewa aktif</h3>
                                        <p class="font-body text-sm text-on-surface-variant mb-6 max-w-sm">Saat ini belum ada penyewa yang menempati tipe kamar ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
