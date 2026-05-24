@extends('layouts.owner')

@section('title', 'Manajemen Kamar')

@section('content')

    <!-- Header Section -->
    <header class="flex justify-between items-end mb-12">
        <div>
            <h2 class="font-display text-4xl md:text-5xl font-extrabold tracking-tighter text-primary">Manajemen Kamar</h2>
            <p class="font-body text-base text-on-surface-variant mt-2">Kelola inventori kamar untuk properti Anda secara
                efisien.</p>
        </div>
    </header>

    <!-- Filter Row -->
    <section class="mb-8">
        <form method="GET" action="{{ route('owner.rooms.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-grow group">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">search</span>
                <input name="search" value="{{ request('search') }}"
                    class="w-full pl-12 pr-6 py-4 bg-surface-container-lowest border border-outline-variant/50 rounded-full shadow-sm focus:ring-2 focus:ring-primary text-sm font-body text-on-surface"
                    placeholder="Cari nama kamar..." type="text" />
            </div>
            <div class="relative min-w-[250px]">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">tune</span>
                <select name="kos_id" onchange="this.form.submit()"
                    class="w-full pl-12 pr-6 py-4 bg-surface-container-lowest border border-outline-variant/50 rounded-full shadow-sm appearance-none focus:ring-2 focus:ring-primary text-sm font-label font-semibold text-on-surface cursor-pointer">
                    @foreach ($boardingHouses as $kos)
                        <option value="{{ $kos->id }}" {{ $selectedKos?->id == $kos->id ? 'selected' : '' }}>
                            {{ $kos->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="hidden">Search</button>
        </form>
    </section>

    <!-- SECTION 1: MANAJEMEN KAMAR -->
    <div x-data="{ showCreateDrawer: false }">
        <section class="block space-y-8 animate-in fade-in duration-500">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h3 class="font-display text-headline-md font-bold tracking-tight mb-1">Room Inventory</h3>
                    <p class="text-on-surface-variant text-sm font-body">Mengelola {{ $rooms->count() }} unit di properti
                        {{ $selectedKos?->name ?? 'Belum ada Kos' }}.</p>
                </div>
                <button type="button" @click="showCreateDrawer = true"
                    class="flex items-center gap-2 bg-primary text-white px-8 py-4 rounded-full font-label text-sm font-bold tracking-tight hover:brightness-110 transition-all shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    <span class="">Tambah Kamar</span>
                </button>
            </div>

            <!-- TABLE CONTAINER -->
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden border border-outline-variant/50">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-surface-container/50 border-b border-outline-variant/50">
                                <th
                                    class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">
                                    Tipe Kamar</th>
                                <th
                                    class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">
                                    Harga/Bulan</th>
                                <th
                                    class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">
                                    Total Stok</th>
                                <th
                                    class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">
                                    Status</th>
                                <th
                                    class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant">
                                    Fasilitas</th>
                                <th
                                    class="px-6 py-4 font-label text-[11px] font-bold uppercase tracking-widest text-on-surface-variant text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            @forelse($rooms as $room)
                                <tr x-data="{ showEditDrawer: false }" class="hover:bg-surface-container-low transition-colors group">
                                    <td class="px-6 py-6">
                                        <span
                                            class="font-display font-bold text-lg text-primary">{{ $room->type_name }}</span>
                                    </td>
                                    <td class="px-6 py-6 font-body font-semibold text-primary">
                                        Rp {{ number_format($room->price_per_month, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-6 font-body text-primary font-medium">
                                        {{ $room->stock }} Unit
                                    </td>
                                    <td class="px-6 py-6">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $room->status_color }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $room->status_dot }}"></span>
                                            {{ $room->dynamic_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex gap-2">
                                            @forelse($room->facilities as $facility)
                                                <span class="material-symbols-outlined text-on-surface-variant text-[20px]"
                                                    title="{{ $facility->name }}">
                                                    {{ $facility->icon ?? 'check_circle' }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-on-surface-variant italic font-body">Tidak ada
                                                    fasilitas terdaftar</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-right">
                                        <div class="flex justify-end gap-2 items-center whitespace-nowrap">
                                            <button type="button" @click="showEditDrawer = true"
                                                class="p-2 hover:bg-surface-container rounded-full transition-colors text-primary border border-outline-variant/50 flex-shrink-0">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </button>
                                            <a href="{{ route('owner.rooms.show', $room->id) }}"
                                                class="px-4 py-2 text-xs font-label font-bold bg-primary text-white rounded-full hover:bg-black/80 transition-colors flex-shrink-0">
                                                Kelola Unit
                                            </a>
                                        </div>
                                    </td>

                                    <!-- Edit Slide-Over Drawer -->
                                    <template x-teleport="body">
                                        <div x-show="showEditDrawer" class="relative z-50"
                                            aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
                                            <div x-show="showEditDrawer" x-transition.opacity.duration.300ms
                                                class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity"
                                                @click="showEditDrawer = false"></div>

                                            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                                                <div class="absolute inset-0 overflow-hidden">
                                                    <div
                                                        class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                                                        <div x-show="showEditDrawer"
                                                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                                                            x-transition:enter-start="translate-x-full"
                                                            x-transition:enter-end="translate-x-0"
                                                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                                                            x-transition:leave-start="translate-x-0"
                                                            x-transition:leave-end="translate-x-full"
                                                            class="pointer-events-auto w-screen max-w-lg">

                                                            <form method="POST" action="{{ route('owner.rooms.update', $room->id) }}"
                                                                class="flex h-full flex-col overflow-y-scroll bg-surface-container-lowest shadow-xl">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="bg-primary px-4 py-6 sm:px-6">
                                                                    <div class="flex items-center justify-between">
                                                                        <h2 class="text-xl font-display font-bold text-white"
                                                                            id="slide-over-title">Edit Tipe Kamar</h2>
                                                                        <button type="button"
                                                                            @click="showEditDrawer = false"
                                                                            class="rounded-full text-white hover:bg-white/20 p-2 focus:outline-none">
                                                                            <span
                                                                                class="material-symbols-outlined">close</span>
                                                                        </button>
                                                                    </div>
                                                                    <p class="mt-1 text-sm text-white/80 font-body">Perbarui
                                                                        informasi untuk kamar {{ $room->type_name }}.</p>
                                                                </div>
                                                                <div class="relative flex-1 px-4 py-6 sm:px-6 space-y-6">
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-label font-bold text-on-surface mb-2">Tipe
                                                                            Kamar</label>
                                                                        <input type="text" name="type_name"
                                                                            value="{{ $room->type_name }}"
                                                                            class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                                            required>
                                                                    </div>
                                                                    <div class="grid grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-label font-bold text-on-surface mb-2">Harga
                                                                                / Bulan</label>
                                                                            <input type="number" name="price_per_month"
                                                                                value="{{ (int) $room->price_per_month }}"
                                                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                                                required>
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-label font-bold text-on-surface mb-2">Jumlah
                                                                                Stok Unit</label>
                                                                            <input type="number" name="stock"
                                                                                value="{{ $room->stock }}"
                                                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-label font-bold text-on-surface mb-2">Ukuran
                                                                            Kamar</label>
                                                                        <input type="text" name="size"
                                                                            value="{{ $room->size }}"
                                                                            class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary">
                                                                    </div>
                                                                    <div>
                                                                        <label class="block text-sm font-label font-bold text-on-surface mb-3">Fasilitas Kamar</label>
                                                                        <div class="grid grid-cols-2 gap-3">
                                                                            @foreach($roomFacilities as $facility)
                                                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" {{ $room->facilities->contains('id', $facility->id) ? 'checked' : '' }} class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4 bg-surface-container-lowest">
                                                                                <span class="text-sm font-body text-on-surface group-hover:text-primary transition-colors flex items-center gap-1.5">
                                                                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">{{ $facility->icon ?? 'check_circle' }}</span>
                                                                                    {{ $facility->name }}
                                                                                </span>
                                                                            </label>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Sticky Footer -->
                                                                <div class="flex flex-shrink-0 justify-between px-4 py-4 border-t border-outline-variant/30 bg-surface-container-lowest sticky bottom-0 z-10">
                                                                    <div>
                                                                        <button type="button" onclick="if(confirm('Yakin ingin menghapus kamar ini?')) document.getElementById('delete-room-{{ $room->id }}').submit()" class="rounded-full bg-error/10 px-6 py-2.5 text-sm font-bold text-error shadow-sm hover:bg-error hover:text-white transition-colors">Hapus</button>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" @click="showEditDrawer = false" class="rounded-full bg-white px-6 py-2.5 text-sm font-bold text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant hover:bg-surface-container mr-3">Cancel</button>
                                                                        <button type="submit" class="inline-flex justify-center rounded-full bg-primary px-6 py-2.5 text-sm font-bold text-on-primary shadow-sm hover:bg-primary/90">Simpan Perubahan</button>
                                                                    </div>
                                                                </div>
                                                            </form>

                                                            <!-- Delete Form -->
                                                            <form id="delete-room-{{ $room->id }}" action="{{ route('owner.rooms.destroy', $room->id) }}" method="POST" class="hidden">
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
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-5xl text-outline mb-4">bed</span>
                                            <h3 class="font-headline text-lg font-bold text-on-surface mb-2">Belum ada Tipe
                                                Kamar</h3>
                                            <p class="font-body text-sm text-on-surface-variant mb-6 max-w-sm">Anda belum
                                                menambahkan tipe kamar untuk properti ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Create Slide-Over Drawer -->
        <template x-teleport="body">
            <div x-show="showCreateDrawer" class="relative z-50" aria-labelledby="slide-over-title" role="dialog"
                aria-modal="true">
                <div x-show="showCreateDrawer" x-transition.opacity.duration.300ms
                    class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity"
                    @click="showCreateDrawer = false"></div>

                <div class="fixed inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                            <div x-show="showCreateDrawer"
                                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                                class="pointer-events-auto w-screen max-w-lg">

                                <form method="POST" action="{{ route('owner.rooms.store') }}"
                                    class="flex h-full flex-col overflow-y-scroll bg-surface-container-lowest shadow-xl">
                                    @csrf
                                    <input type="hidden" name="boarding_house_id" value="{{ $selectedKos?->id }}">
                                    <div class="bg-primary px-4 py-6 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <h2 class="text-xl font-display font-bold text-white" id="slide-over-title">
                                                Tambah Kamar Baru</h2>
                                            <button type="button" @click="showCreateDrawer = false"
                                                class="rounded-full text-white hover:bg-white/20 p-2 focus:outline-none">
                                                <span class="material-symbols-outlined">close</span>
                                            </button>
                                        </div>
                                        <p class="mt-1 text-sm text-white/80 font-body">Tambahkan tipe kamar baru untuk
                                            properti ini.</p>
                                    </div>
                                    <div class="relative flex-1 px-4 py-6 sm:px-6 space-y-6">
                                        <div>
                                            <label class="block text-sm font-label font-bold text-on-surface mb-2">Tipe
                                                Kamar</label>
                                            <input type="text" name="type_name"
                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                placeholder="e.g. VIP, Standard" required>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-label font-bold text-on-surface mb-2">Harga /
                                                    Bulan</label>
                                                <input type="number" name="price_per_month"
                                                    class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                    placeholder="2000000" required>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-label font-bold text-on-surface mb-2">Jumlah
                                                    Stok Unit</label>
                                                <input type="number" name="stock"
                                                    class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                    placeholder="10" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-label font-bold text-on-surface mb-2">Ukuran
                                                Kamar</label>
                                            <input type="text" name="size"
                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                placeholder="e.g. 3x4 meter">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-label font-bold text-on-surface mb-3">Fasilitas Kamar</label>
                                            <div class="grid grid-cols-2 gap-3">
                                                @foreach($roomFacilities as $facility)
                                                <label class="flex items-center gap-2 cursor-pointer group">
                                                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="rounded border-outline-variant text-primary focus:ring-primary w-4 h-4 bg-surface-container-lowest">
                                                    <span class="text-sm font-body text-on-surface group-hover:text-primary transition-colors flex items-center gap-1.5">
                                                        <span class="material-symbols-outlined text-[16px] text-on-surface-variant">{{ $facility->icon ?? 'check_circle' }}</span>
                                                        {{ $facility->name }}
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sticky Footer -->
                                    <div
                                        class="flex flex-shrink-0 justify-end px-4 py-4 border-t border-outline-variant/30 bg-surface-container-lowest sticky bottom-0 z-10">
                                        <button type="button" @click="showCreateDrawer = false"
                                            class="rounded-full bg-white px-6 py-2.5 text-sm font-bold text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant hover:bg-surface-container mr-3">Cancel</button>
                                        <button type="submit"
                                            class="inline-flex justify-center rounded-full bg-primary px-6 py-2.5 text-sm font-bold text-on-primary shadow-sm hover:bg-primary/90">Simpan
                                            Kamar</button>
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
