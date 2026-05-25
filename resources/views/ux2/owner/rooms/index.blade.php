@extends('layouts.ux2.owner')

@section('title', 'Kelola Kamar')

@section('content')
<div x-data="{ showCreateDrawer: false }">
    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-md mb-lg">
        <div>
            <h1 class="font-display-lg text-display-lg text-on-background mb-xs">Kelola Kamar</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Kelola inventori kamar untuk properti Anda</p>
        </div>
    </header>

    <!-- Filter -->
    <section class="mb-lg">
        <form method="GET" action="{{ route('ux2.owner.rooms.index') }}" class="flex flex-col md:flex-row gap-sm">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">search</span>
                <input name="search" value="{{ request('search') }}" type="text" placeholder="Cari nama kamar..." class="w-full pl-lg pr-sm py-sm bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary-container font-body-md text-body-md" />
            </div>
            <select name="kos_id" onchange="this.form.submit()" class="px-md py-sm bg-surface-container-lowest border border-outline-variant rounded-xl font-label-md text-label-md cursor-pointer min-w-[250px]">
                @foreach($boardingHouses as $kos)
                    <option value="{{ $kos->id }}" {{ $selectedKos?->id == $kos->id ? 'selected' : '' }}>{{ $kos->name }}</option>
                @endforeach
            </select>
            <button type="button" @click="showCreateDrawer = true" class="bg-primary text-on-primary px-lg py-sm rounded-xl font-label-md text-label-md font-bold flex items-center justify-center gap-xs hover:bg-inverse-surface transition-colors whitespace-nowrap">
                <span class="material-symbols-outlined">add_circle</span>
                Tambah Kamar
            </button>
        </form>
    </section>

    <!-- Rooms Table -->
    <div class="bg-surface-container-lowest rounded-2xl overflow-hidden border border-outline-variant">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container border-b border-outline-variant">
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase text-on-surface-variant">Tipe Kamar</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase text-on-surface-variant">Harga/Bulan</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase text-on-surface-variant">Stok</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase text-on-surface-variant">Status</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase text-on-surface-variant">Fasilitas</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($rooms as $room)
                        <tr x-data="{ showEditDrawer: false }" class="hover:bg-surface-container-low transition-colors">
                            <td class="px-md py-md">
                                <span class="font-headline-md text-headline-md text-on-surface">{{ $room->type_name }}</span>
                            </td>
                            <td class="px-md py-md font-body-md text-body-md text-on-surface font-semibold">
                                Rp {{ number_format($room->price_per_month, 0, ',', '.') }}
                            </td>
                            <td class="px-md py-md font-body-md text-body-md text-on-surface">
                                {{ $room->stock }} Unit
                            </td>
                            <td class="px-md py-md">
                                <span class="inline-flex items-center gap-xs px-sm py-xs rounded-lg font-label-sm text-label-sm font-bold {{ $room->status_color }}">
                                    <span class="w-2 h-2 rounded-full {{ $room->status_dot }}"></span>
                                    {{ $room->dynamic_status }}
                                </span>
                            </td>
                            <td class="px-md py-md">
                                <div class="flex gap-xs">
                                    @forelse($room->facilities as $facility)
                                        <span class="material-symbols-outlined text-on-surface-variant text-sm" title="{{ $facility->name }}">
                                            {{ $facility->icon ?? 'check_circle' }}
                                        </span>
                                    @empty
                                        <span class="font-label-sm text-label-sm text-on-surface-variant italic">Tidak ada</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-md py-md text-right">
                                <div class="flex justify-end gap-sm">
                                    <button type="button" @click="showEditDrawer = true" class="p-sm hover:bg-surface-container rounded-lg transition-colors text-primary border border-outline-variant">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </button>
                                    <a href="{{ route('ux2.owner.rooms.show', $room->id) }}" class="px-sm py-xs font-label-sm text-label-sm font-bold bg-primary text-on-primary rounded-lg hover:bg-inverse-surface transition-colors">
                                        Kelola Unit
                                    </a>
                                </div>
                            </td>

                            <!-- Edit Drawer -->
                            <template x-teleport="body">
                                <div x-show="showEditDrawer" class="relative z-50" role="dialog">
                                    <div x-show="showEditDrawer" x-transition.opacity class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="showEditDrawer = false"></div>
                                    <div class="fixed inset-0 overflow-hidden pointer-events-none">
                                        <div class="absolute inset-0 overflow-hidden">
                                            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                                                <div x-show="showEditDrawer" x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-lg">
                                                    <form method="POST" action="{{ route('ux2.owner.rooms.update', $room->id) }}" class="flex h-full flex-col bg-surface-container-lowest shadow-xl overflow-y-auto">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        <div class="px-md py-md border-b border-outline-variant sticky top-0 bg-surface-container-lowest z-10">
                                                            <div class="flex items-center justify-between">
                                                                <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Kamar</h2>
                                                                <button type="button" @click="showEditDrawer = false" class="text-on-surface-variant hover:text-on-surface">
                                                                    <span class="material-symbols-outlined">close</span>
                                                                </button>
                                                            </div>
                                                            <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Perbarui {{ $room->type_name }}</p>
                                                        </div>

                                                        <div class="flex-1 px-md py-md space-y-md">
                                                            <div>
                                                                <label class="block font-label-md text-label-md text-on-surface mb-xs">Tipe Kamar</label>
                                                                <input type="text" name="type_name" value="{{ $room->type_name }}" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                                            </div>
                                                            
                                                            <div class="grid grid-cols-2 gap-sm">
                                                                <div>
                                                                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Harga/Bulan</label>
                                                                    <input type="number" name="price_per_month" value="{{ (int)$room->price_per_month }}" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                                                </div>
                                                                <div>
                                                                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Stok Unit</label>
                                                                    <input type="number" name="stock" value="{{ $room->stock }}" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block font-label-md text-label-md text-on-surface mb-xs">Ukuran</label>
                                                                <input type="text" name="size" value="{{ $room->size }}" placeholder="3x4 meter" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container">
                                                            </div>

                                                            <div>
                                                                <label class="block font-label-md text-label-md text-on-surface mb-sm">Fasilitas</label>
                                                                <div class="grid grid-cols-2 gap-sm">
                                                                    @foreach($roomFacilities as $facility)
                                                                        <label class="flex items-center gap-sm cursor-pointer">
                                                                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" {{ $room->facilities->contains('id', $facility->id) ? 'checked' : '' }} class="rounded border-outline-variant text-secondary-container focus:ring-secondary-container">
                                                                            <span class="font-label-sm text-label-sm text-on-surface">{{ $facility->name }}</span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="flex justify-between px-md py-md border-t border-outline-variant sticky bottom-0 bg-surface-container-lowest">
                                                            <button type="button" onclick="if(confirm('Yakin hapus?')) document.getElementById('delete-room-{{ $room->id }}').submit()" class="px-md py-sm rounded-xl bg-error/10 text-error font-label-md text-label-md font-bold hover:bg-error hover:text-on-error">Hapus</button>
                                                            <div class="flex gap-sm">
                                                                <button type="button" @click="showEditDrawer = false" class="px-md py-sm rounded-xl border border-outline-variant text-on-surface font-label-md text-label-md hover:bg-surface-container">Batal</button>
                                                                <button type="submit" class="px-md py-sm rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-inverse-surface">Simpan</button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <form id="delete-room-{{ $room->id }}" action="{{ route('ux2.owner.rooms.destroy', $room->id) }}" method="POST" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-md py-xl text-center">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-6xl text-outline mb-md">bed</span>
                                    <h3 class="font-headline-md text-headline-md text-on-surface mb-xs">Belum Ada Kamar</h3>
                                    <p class="font-body-md text-body-md text-on-surface-variant">Tambahkan tipe kamar untuk properti ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Drawer -->
    <template x-teleport="body">
        <div x-show="showCreateDrawer" class="relative z-50" role="dialog">
            <div x-show="showCreateDrawer" x-transition.opacity class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="showCreateDrawer = false"></div>
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="showCreateDrawer" x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-lg">
                            <form method="POST" action="{{ route('ux2.owner.rooms.store') }}" class="flex h-full flex-col bg-surface-container-lowest shadow-xl overflow-y-auto">
                                @csrf
                                <input type="hidden" name="boarding_house_id" value="{{ $selectedKos?->id }}">
                                
                                <div class="px-md py-md border-b border-outline-variant sticky top-0 bg-surface-container-lowest z-10">
                                    <div class="flex items-center justify-between">
                                        <h2 class="font-headline-lg text-headline-lg text-on-surface">Tambah Kamar</h2>
                                        <button type="button" @click="showCreateDrawer = false" class="text-on-surface-variant hover:text-on-surface">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>
                                    <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Tambah tipe kamar baru</p>
                                </div>

                                <div class="flex-1 px-md py-md space-y-md">
                                    <div>
                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Tipe Kamar</label>
                                        <input type="text" name="type_name" placeholder="VIP, Standard" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-sm">
                                        <div>
                                            <label class="block font-label-md text-label-md text-on-surface mb-xs">Harga/Bulan</label>
                                            <input type="number" name="price_per_month" placeholder="2000000" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                        </div>
                                        <div>
                                            <label class="block font-label-md text-label-md text-on-surface mb-xs">Stok Unit</label>
                                            <input type="number" name="stock" placeholder="10" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Ukuran</label>
                                        <input type="text" name="size" placeholder="3x4 meter" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container">
                                    </div>

                                    <div>
                                        <label class="block font-label-md text-label-md text-on-surface mb-sm">Fasilitas</label>
                                        <div class="grid grid-cols-2 gap-sm">
                                            @foreach($roomFacilities as $facility)
                                                <label class="flex items-center gap-sm cursor-pointer">
                                                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="rounded border-outline-variant text-secondary-container focus:ring-secondary-container">
                                                    <span class="font-label-sm text-label-sm text-on-surface">{{ $facility->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-sm px-md py-md border-t border-outline-variant sticky bottom-0 bg-surface-container-lowest">
                                    <button type="button" @click="showCreateDrawer = false" class="px-md py-sm rounded-xl border border-outline-variant text-on-surface font-label-md text-label-md hover:bg-surface-container">Batal</button>
                                    <button type="submit" class="px-md py-sm rounded-xl bg-secondary-container text-on-secondary-container font-label-md text-label-md font-bold hover:bg-secondary-fixed">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
