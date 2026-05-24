@extends('layouts.owner')

@section('title', 'Manajemen Kos')

@section('content')
    <div x-data="kosManager()">
        <!-- Header Section -->
        <header class="flex justify-between items-end mb-12">
            <div>
                <h2 class="font-display text-4xl md:text-5xl font-extrabold tracking-tighter text-primary">Manajemen Kos</h2>
                <p class="font-body text-base text-on-surface-variant mt-2">Kelola portofolio properti premium Anda secara
                    efisien.</p>
            </div>
        </header>

        <!-- Search & Filter Row -->
        <section class="mb-8">
            <form method="GET" action="{{ route('owner.kos.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-grow group">
                    <span
                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">search</span>
                    <input name="search" value="{{ request('search') }}"
                        class="w-full pl-12 pr-6 py-4 bg-surface-container-lowest border border-outline-variant/50 rounded-full shadow-sm focus:ring-2 focus:ring-primary text-sm font-body text-on-surface"
                        placeholder="Cari nama kos atau kota..." type="text" />
                </div>
                <div class="relative min-w-[200px]">
                    <span
                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">tune</span>
                    <select name="type" onchange="this.form.submit()"
                        class="w-full pl-12 pr-10 py-4 bg-surface-container-lowest border border-outline-variant/50 rounded-full shadow-sm appearance-none focus:ring-2 focus:ring-primary text-sm font-label font-semibold text-on-surface cursor-pointer">
                        <option value="">Tipe: Semua</option>
                        <option value="putra" {{ request('type') == 'putra' ? 'selected' : '' }}>Tipe: Putra</option>
                        <option value="putri" {{ request('type') == 'putri' ? 'selected' : '' }}>Tipe: Putri</option>
                        <option value="campur" {{ request('type') == 'campur' ? 'selected' : '' }}>Tipe: Campur</option>
                    </select>
                </div>
                <button type="button" @click="showCreateDrawer = true"
                    class="bg-primary text-white px-8 py-4 rounded-full font-label text-sm font-bold flex items-center justify-center gap-2 shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all whitespace-nowrap">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Tambah Kos Baru
                </button>
            </form>
        </section>

        <!-- Property Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            @forelse($kosList as $kos)
                <!-- Card with its own Alpine state for Edit Drawer -->
                <div x-data="{ showEditDrawer: false }"
                    class="bg-surface-container-lowest rounded-xl p-4 shadow-sm border border-outline-variant/50 group hover:-translate-y-1 transition-transform duration-500">
                    <div class="relative h-64 w-full rounded-md overflow-hidden mb-6">
                        <img class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            src="{{ $kos->image_url ?? 'https://via.placeholder.com/800x600?text=No+Image' }}"
                            alt="{{ $kos->name }}" />

                        @if ($kos->occupancy_rate >= 100)
                            <div
                                class="absolute top-4 right-4 px-4 py-1.5 bg-error text-white text-xs font-bold rounded-full uppercase tracking-widest shadow-lg">
                                Full
                            </div>
                        @else
                            <div
                                class="absolute top-4 right-4 px-4 py-1.5 bg-[#10B981] text-white text-xs font-bold rounded-full uppercase tracking-widest shadow-lg">
                                Active
                            </div>
                        @endif

                        <div
                            class="absolute bottom-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-md rounded-full text-xs font-bold text-primary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">
                                {{ $kos->gender_type->value == 'putri' ? 'female' : ($kos->gender_type->value == 'putra' ? 'male' : 'wc') }}
                            </span>
                            {{ ucfirst($kos->gender_type->value) }}
                        </div>
                    </div>
                    <div class="px-2">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-display text-xl font-bold tracking-tight text-primary truncate">
                                {{ $kos->name }}</h3>
                            <span
                                class="material-symbols-outlined text-outline cursor-pointer hover:text-primary transition-colors">more_horiz</span>
                        </div>
                        <div class="flex items-center gap-1 text-on-surface-variant font-body text-sm mb-6 opacity-70">
                            <span class="material-symbols-outlined text-base">location_on</span>
                            {{ $kos->city }}
                        </div>
                        <div class="mb-6">
                            <div class="flex justify-between items-end mb-2">
                                <p class="font-label text-sm font-bold text-primary">Okupansi</p>
                                <p class="font-body text-sm font-medium text-primary">{{ $kos->occupied_rooms }} /
                                    {{ $kos->total_rooms }} Kamar Terisi</p>
                            </div>
                            <div class="h-3 w-full bg-surface-container-high rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full" style="width: {{ $kos->occupancy_rate }}%">
                                </div>
                            </div>
                            <p class="font-label text-xs text-outline mt-2 font-medium">{{ round($kos->occupancy_rate) }}%
                                Terisi</p>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <a href="{{ route('owner.rooms.index', ['kos_id' => $kos->id]) }}"
                                class="flex-grow bg-primary text-on-primary py-3.5 rounded-full font-bold text-sm hover:bg-black/80 transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">room_preferences</span>
                                Kelola Kamar
                            </a>
                            <button type="button" @click="showEditDrawer = true"
                                class="px-6 border border-outline-variant text-primary py-3.5 rounded-full font-bold text-sm hover:bg-surface-container transition-all flex items-center justify-center">
                                Edit
                            </button>
                        </div>
                    </div>

                    <!-- Edit Slide-Over Drawer for this specific Kos -->
                    <template x-teleport="body">
                        <div x-show="showEditDrawer" class="relative z-50" aria-labelledby="slide-over-title" role="dialog"
                            aria-modal="true">
                            <!-- Background backdrop -->
                            <div x-show="showEditDrawer" x-transition.opacity.duration.300ms
                                class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity"
                                @click="showEditDrawer = false"></div>

                            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                                <div class="absolute inset-0 overflow-hidden">
                                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                                        <!-- Slide-over panel -->
                                        <div x-show="showEditDrawer"
                                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                                            x-transition:enter-start="translate-x-full"
                                            x-transition:enter-end="translate-x-0"
                                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                                            x-transition:leave-start="translate-x-0"
                                            x-transition:leave-end="translate-x-full"
                                            class="pointer-events-auto w-screen max-w-lg">
                                            <form method="POST" action="{{ route('owner.kos.update', $kos->id) }}"
                                                class="flex h-full flex-col overflow-y-scroll bg-surface-container-lowest shadow-xl">
                                                @csrf
                                                @method('PUT')
                                                <div class="px-6 py-6 border-b border-outline-variant/50 bg-white sticky top-0 z-10">
                                                    <div class="flex items-start justify-between">
                                                        <h2 class="text-2xl font-display font-extrabold text-on-surface"
                                                            id="slide-over-title">Edit Kos</h2>
                                                        <div class="ml-3 flex h-7 items-center">
                                                            <button type="button" @click="showEditDrawer = false"
                                                                class="relative rounded-md text-on-surface-variant hover:text-on-surface focus:outline-none focus:ring-2 focus:ring-primary">
                                                                <span class="absolute -inset-2.5"></span>
                                                                <span class="sr-only">Tutup panel</span>
                                                                <span class="material-symbols-outlined">close</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="mt-2 text-sm text-on-surface-variant">Perbarui informasi
                                                        kos {{ $kos->name }}.</p>
                                                </div>
                                                <div class="relative flex-1 px-4 py-6 sm:px-6 space-y-6">
                                                    <!-- Basic Info -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-label font-bold text-on-surface mb-2">Nama
                                                            Kos</label>
                                                        <input type="text" name="name"
                                                            value="{{ old('name', $kos->name) }}"
                                                            class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                            required>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-label font-bold text-on-surface mb-2">Kota</label>
                                                            <input type="text" name="city"
                                                                value="{{ old('city', $kos->city) }}"
                                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                                required>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-label font-bold text-on-surface mb-2">Tipe
                                                                Kos</label>
                                                            <select name="gender_type"
                                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                                required>
                                                                <option value="putra"
                                                                    {{ $kos->gender_type->value == 'putra' ? 'selected' : '' }}>
                                                                    Putra</option>
                                                                <option value="putri"
                                                                    {{ $kos->gender_type->value == 'putri' ? 'selected' : '' }}>
                                                                    Putri</option>
                                                                <option value="campur"
                                                                    {{ $kos->gender_type->value == 'campur' ? 'selected' : '' }}>
                                                                    Campur</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-label font-bold text-on-surface mb-2">Provinsi</label>
                                                            <input type="text" name="province"
                                                                value="{{ old('province', $kos->province) }}"
                                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                                required>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-label font-bold text-on-surface mb-2">Kode
                                                                Pos</label>
                                                            <input type="text" name="postal_code"
                                                                value="{{ old('postal_code', $kos->postal_code) }}"
                                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-sm font-label font-bold text-on-surface mb-2">Alamat
                                                            Lengkap</label>
                                                        <textarea name="address" rows="2"
                                                            class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                            required>{{ old('address', $kos->address) }}</textarea>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-sm font-label font-bold text-on-surface mb-2">Deskripsi
                                                            Promosi</label>
                                                        <textarea name="description" rows="3"
                                                            class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                            placeholder="Kos nyaman dan strategis...">{{ old('description', $kos->description) }}</textarea>
                                                    </div>

                                                    <!-- Fasilitas -->
                                                    <div class="pt-4 border-t border-outline-variant/30">
                                                        <h3 class="text-base font-headline font-bold text-on-surface mb-4">
                                                            Fasilitas Bersama</h3>
                                                        <div class="grid grid-cols-2 gap-3">
                                                            @foreach ($masterFacilities as $facility)
                                                                <label
                                                                    class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors">
                                                                    <input type="checkbox" name="facilities[]"
                                                                        value="{{ $facility->id }}"
                                                                        class="text-primary focus:ring-primary rounded"
                                                                        {{ $kos->facilities->contains($facility->id) ? 'checked' : '' }}>
                                                                    <span
                                                                        class="material-symbols-outlined text-on-surface-variant">{{ $facility->icon ?? 'check_circle' }}</span>
                                                                    <span
                                                                        class="text-sm font-body text-on-surface">{{ $facility->name }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!-- Aturan -->
                                                    <div class="pt-4 border-t border-outline-variant/30 pb-20">
                                                        <h3 class="text-base font-headline font-bold text-on-surface mb-4">
                                                            Aturan Kos</h3>
                                                        @foreach ($masterRules as $category => $rules)
                                                            <div class="mb-4">
                                                                <h4
                                                                    class="text-sm font-label font-bold text-on-surface-variant mb-2 uppercase tracking-wider">
                                                                    {{ $category }}</h4>
                                                                <div class="space-y-2">
                                                                    @foreach ($rules as $rule)
                                                                        <label class="flex items-start gap-3">
                                                                            <input type="checkbox" name="rules[]"
                                                                                value="{{ $rule->id }}"
                                                                                class="mt-1 text-primary focus:ring-primary rounded"
                                                                                {{ $kos->rules->contains($rule->id) ? 'checked' : '' }}>
                                                                            <div>
                                                                                <span
                                                                                    class="text-sm font-body text-on-surface block">{{ $rule->name }}</span>
                                                                            </div>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>

                                                <!-- Sticky Footer -->
                                                <div
                                                    class="flex flex-shrink-0 justify-end px-4 py-4 border-t border-outline-variant/30 bg-surface-container-lowest sticky bottom-0 z-10">
                                                    <button type="button" @click="showEditDrawer = false"
                                                        class="rounded-full bg-white px-6 py-2.5 text-sm font-bold text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant hover:bg-surface-container mr-3">Cancel</button>
                                                    <button type="submit"
                                                        class="inline-flex justify-center rounded-full bg-primary px-6 py-2.5 text-sm font-bold text-on-primary shadow-sm hover:bg-primary/90">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            @empty
                <div
                    class="col-span-full py-16 flex flex-col items-center justify-center text-center border border-dashed border-outline-variant/50 rounded-2xl bg-surface-container-lowest">
                    <span class="material-symbols-outlined text-5xl text-outline mb-4">home_work</span>
                    <h3 class="font-headline text-lg font-bold text-on-surface mb-2">Belum ada Kos</h3>
                    <p class="font-body text-sm text-on-surface-variant mb-6 max-w-sm">Anda belum mendaftarkan properti kos
                        apapun. Mulai kelola properti Anda sekarang.</p>
                    <button type="button" @click="showCreateDrawer = true"
                        class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-full font-label text-sm font-semibold hover:brightness-110 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span> Tambah Kos Pertama
                    </button>
                </div>
            @endforelse
        </div>

        @if ($kosList->hasPages())
            <div class="mt-8">
                {{ $kosList->links() }}
            </div>
        @endif

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
                                <form method="POST" action="{{ route('owner.kos.store') }}"
                                    class="flex h-full flex-col overflow-y-scroll bg-surface-container-lowest shadow-xl">
                                    @csrf
                                    <div class="px-6 py-6 border-b border-outline-variant/50 bg-white sticky top-0 z-10">
                                        <div class="flex items-start justify-between">
                                            <h2 class="text-2xl font-display font-extrabold text-on-surface" id="slide-over-title">
                                                Tambah Kos Baru</h2>
                                            <div class="ml-3 flex h-7 items-center">
                                                <button type="button" @click="showCreateDrawer = false"
                                                    class="relative rounded-md text-on-surface-variant hover:text-on-surface focus:outline-none focus:ring-2 focus:ring-primary">
                                                    <span class="absolute -inset-2.5"></span>
                                                    <span class="sr-only">Tutup panel</span>
                                                    <span class="material-symbols-outlined">close</span>
                                                </button>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-on-surface-variant">Mulai buat properti kos baru untuk
                                            portofolio Anda.</p>
                                    </div>
                                    <div class="relative flex-1 px-4 py-6 sm:px-6 space-y-6">

                                        <div>
                                            <label class="block text-sm font-label font-bold text-on-surface mb-2">Nama
                                                Kos</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                placeholder="Kos Sudirman Elite" required>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-label font-bold text-on-surface mb-2">Kota</label>
                                                <input type="text" name="city" value="{{ old('city') }}"
                                                    class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                    placeholder="Jakarta Pusat" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-label font-bold text-on-surface mb-2">Tipe
                                                    Kos</label>
                                                <select name="gender_type"
                                                    class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                    required>
                                                    <option value="putra">Putra</option>
                                                    <option value="putri">Putri</option>
                                                    <option value="campur">Campur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-label font-bold text-on-surface mb-2">Provinsi</label>
                                                <input type="text" name="province" value="{{ old('province') }}"
                                                    class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                    placeholder="DKI Jakarta" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-label font-bold text-on-surface mb-2">Kode
                                                    Pos</label>
                                                <input type="text" name="postal_code"
                                                    value="{{ old('postal_code') }}"
                                                    class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                    placeholder="10220">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-label font-bold text-on-surface mb-2">Alamat
                                                Lengkap</label>
                                            <textarea name="address" rows="2"
                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                placeholder="Jl. Sudirman No 10..." required>{{ old('address') }}</textarea>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-label font-bold text-on-surface mb-2">Deskripsi
                                                Promosi</label>
                                            <textarea name="description" rows="3"
                                                class="w-full rounded-xl border-outline-variant/50 bg-surface-container-lowest text-on-surface focus:ring-primary focus:border-primary"
                                                placeholder="Kos nyaman dan strategis...">{{ old('description') }}</textarea>
                                        </div>

                                        <!-- Fasilitas -->
                                        <div class="pt-4 border-t border-outline-variant/30">
                                            <h3 class="text-base font-headline font-bold text-on-surface mb-4">Fasilitas
                                                Bersama</h3>
                                            <div class="grid grid-cols-2 gap-3">
                                                @foreach ($masterFacilities as $facility)
                                                    <label
                                                        class="flex items-center gap-3 p-3 rounded-xl border border-outline-variant/50 cursor-pointer hover:bg-surface-container transition-colors">
                                                        <input type="checkbox" name="facilities[]"
                                                            value="{{ $facility->id }}"
                                                            class="text-primary focus:ring-primary rounded">
                                                        <span
                                                            class="material-symbols-outlined text-on-surface-variant">{{ $facility->icon ?? 'check_circle' }}</span>
                                                        <span
                                                            class="text-sm font-body text-on-surface">{{ $facility->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Aturan -->
                                        <div class="pt-4 border-t border-outline-variant/30 pb-20">
                                            <h3 class="text-base font-headline font-bold text-on-surface mb-4">Aturan Kos
                                            </h3>
                                            @foreach ($masterRules as $category => $rules)
                                                <div class="mb-4">
                                                    <h4
                                                        class="text-sm font-label font-bold text-on-surface-variant mb-2 uppercase tracking-wider">
                                                        {{ $category }}</h4>
                                                    <div class="space-y-2">
                                                        @foreach ($rules as $rule)
                                                            <label class="flex items-start gap-3">
                                                                <input type="checkbox" name="rules[]"
                                                                    value="{{ $rule->id }}"
                                                                    class="mt-1 text-primary focus:ring-primary rounded">
                                                                <div>
                                                                    <span
                                                                        class="text-sm font-body text-on-surface block">{{ $rule->name }}</span>
                                                                </div>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>

                                    <!-- Sticky Footer -->
                                    <div
                                        class="flex flex-shrink-0 justify-end px-4 py-4 border-t border-outline-variant/30 bg-surface-container-lowest sticky bottom-0 z-10">
                                        <button type="button" @click="showCreateDrawer = false"
                                            class="rounded-full bg-white px-6 py-2.5 text-sm font-bold text-on-surface shadow-sm ring-1 ring-inset ring-outline-variant hover:bg-surface-container mr-3">Cancel</button>
                                        <button type="submit"
                                            class="inline-flex justify-center rounded-full bg-[#0D9488] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:brightness-110">Simpan
                                            Kos</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>

    <script>
        function kosManager() {
            return {
                showCreateDrawer: false,
            }
        }
    </script>
@endsection
