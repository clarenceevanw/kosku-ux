@extends('layouts.ux2.owner')

@section('title', 'Kelola Kos')

@section('content')
<div x-data="kosManager()">
    <!-- Header -->
    <header class="flex flex-col md:flex-row justify-between items-start md:items-end gap-md mb-lg">
        <div>
            <h1 class="font-display-lg text-display-lg text-on-background mb-xs">Kelola Kos</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Kelola portofolio properti Anda secara efisien</p>
        </div>
    </header>

    <!-- Search & Actions -->
    <section class="mb-lg">
        <form method="GET" action="{{ route('ux2.owner.kos.index') }}" class="flex flex-col md:flex-row gap-sm">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">search</span>
                <input name="search" value="{{ request('search') }}" type="text" placeholder="Cari nama kos atau kota..." class="w-full pl-lg pr-sm py-sm bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary-container font-body-md text-body-md" />
            </div>
            <select name="type" onchange="this.form.submit()" class="px-md py-sm bg-surface-container-lowest border border-outline-variant rounded-xl font-label-md text-label-md cursor-pointer min-w-[180px]">
                <option value="">Semua Tipe</option>
                <option value="putra" {{ request('type') == 'putra' ? 'selected' : '' }}>Putra</option>
                <option value="putri" {{ request('type') == 'putri' ? 'selected' : '' }}>Putri</option>
                <option value="campur" {{ request('type') == 'campur' ? 'selected' : '' }}>Campur</option>
            </select>
            <button type="button" @click="showCreateDrawer = true" class="bg-primary text-on-primary px-lg py-sm rounded-xl font-label-md text-label-md font-bold flex items-center justify-center gap-xs hover:bg-inverse-surface transition-colors whitespace-nowrap">
                <span class="material-symbols-outlined">add_circle</span>
                Tambah Kos
            </button>
        </form>
    </section>

    <!-- Kos Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        @forelse($kosList as $kos)
            <div x-data="{ showEditDrawer: false }" class="bg-surface-container-lowest rounded-2xl overflow-hidden border border-outline-variant hover:shadow-lg transition-all">
                <!-- Image -->
                <div class="relative h-48 w-full overflow-hidden">
                    <img src="{{ $kos->image_url ?? 'https://via.placeholder.com/800x400?text=No+Image' }}" alt="{{ $kos->name }}" class="w-full h-full object-cover" />
                    @if($kos->occupancy_rate >= 100)
                        <div class="absolute top-sm right-sm px-sm py-xs bg-error text-on-error rounded-lg font-label-sm text-label-sm font-bold">FULL</div>
                    @else
                        <div class="absolute top-sm right-sm px-sm py-xs bg-secondary-container text-on-secondary-container rounded-lg font-label-sm text-label-sm font-bold">ACTIVE</div>
                    @endif
                    <div class="absolute bottom-sm left-sm px-sm py-xs bg-surface-container-lowest/90 backdrop-blur-sm rounded-lg flex items-center gap-xs">
                        <span class="material-symbols-outlined text-sm">{{ $kos->gender_type->value == 'putri' ? 'female' : ($kos->gender_type->value == 'putra' ? 'male' : 'wc') }}</span>
                        <span class="font-label-sm text-label-sm font-bold">{{ ucfirst($kos->gender_type->value) }}</span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-md">
                    <h3 class="font-headline-md text-headline-md text-on-surface mb-xs">{{ $kos->name }}</h3>
                    <div class="flex items-center gap-xs text-on-surface-variant mb-md">
                        <span class="material-symbols-outlined text-sm">location_on</span>
                        <span class="font-body-md text-body-md">{{ $kos->city }}</span>
                    </div>

                    <!-- Occupancy -->
                    <div class="mb-md">
                        <div class="flex justify-between items-center mb-xs">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Okupansi</span>
                            <span class="font-label-sm text-label-sm font-bold text-on-surface">{{ $kos->occupied_rooms }} / {{ $kos->total_rooms }} Kamar</span>
                        </div>
                        <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-secondary-container" style="width: {{ $kos->occupancy_rate }}%"></div>
                        </div>
                        <span class="font-label-sm text-label-sm text-outline mt-xs block">{{ round($kos->occupancy_rate) }}% Terisi</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-sm">
                        <a href="{{ route('ux2.owner.rooms.index', ['kos_id' => $kos->id]) }}" class="flex-1 bg-primary text-on-primary py-sm px-md rounded-xl font-label-md text-label-md text-center hover:bg-inverse-surface transition-colors flex items-center justify-center gap-xs">
                            <span class="material-symbols-outlined text-sm">bed</span>
                            Kelola Kamar
                        </a>
                        <button type="button" @click="showEditDrawer = true" class="px-md border border-outline-variant text-on-surface py-sm rounded-xl font-label-md text-label-md hover:bg-surface-container transition-colors">
                            Edit
                        </button>
                    </div>
                </div>

                <!-- Edit Drawer -->
                <template x-teleport="body">
                    <div x-show="showEditDrawer" class="relative z-50" role="dialog">
                        <div x-show="showEditDrawer" x-transition.opacity class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="showEditDrawer = false"></div>
                        <div class="fixed inset-0 overflow-hidden pointer-events-none">
                            <div class="absolute inset-0 overflow-hidden">
                                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                                    <div x-show="showEditDrawer" x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-lg">
                                        <form method="POST" action="{{ route('ux2.owner.kos.update', $kos->id) }}" class="flex h-full flex-col bg-surface-container-lowest shadow-xl overflow-y-auto">
                                            @csrf
                                            @method('PUT')
                                            
                                            <!-- Header -->
                                            <div class="px-md py-md border-b border-outline-variant sticky top-0 bg-surface-container-lowest z-10">
                                                <div class="flex items-center justify-between">
                                                    <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Kos</h2>
                                                    <button type="button" @click="showEditDrawer = false" class="text-on-surface-variant hover:text-on-surface">
                                                        <span class="material-symbols-outlined">close</span>
                                                    </button>
                                                </div>
                                                <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Perbarui informasi {{ $kos->name }}</p>
                                            </div>

                                            <!-- Form Content -->
                                            <div class="flex-1 px-md py-md space-y-md">
                                                <div>
                                                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Nama Kos</label>
                                                    <input type="text" name="name" value="{{ old('name', $kos->name) }}" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 gap-sm">
                                                    <div>
                                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Kota</label>
                                                        <input type="text" name="city" value="{{ old('city', $kos->city) }}" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Tipe</label>
                                                        <select name="gender_type" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                                            <option value="putra" {{ $kos->gender_type->value == 'putra' ? 'selected' : '' }}>Putra</option>
                                                            <option value="putri" {{ $kos->gender_type->value == 'putri' ? 'selected' : '' }}>Putri</option>
                                                            <option value="campur" {{ $kos->gender_type->value == 'campur' ? 'selected' : '' }}>Campur</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-sm">
                                                    <div>
                                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Provinsi</label>
                                                        <input type="text" name="province" value="{{ old('province', $kos->province) }}" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Kode Pos</label>
                                                        <input type="text" name="postal_code" value="{{ old('postal_code', $kos->postal_code) }}" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Alamat</label>
                                                    <textarea name="address" rows="2" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>{{ old('address', $kos->address) }}</textarea>
                                                </div>

                                                <div>
                                                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Deskripsi</label>
                                                    <textarea name="description" rows="3" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container">{{ old('description', $kos->description) }}</textarea>
                                                </div>

                                                <!-- Facilities -->
                                                <div class="pt-md border-t border-outline-variant">
                                                    <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">Fasilitas Bersama</h3>
                                                    <div class="grid grid-cols-2 gap-sm">
                                                        @foreach($masterFacilities as $facility)
                                                            <label class="flex items-center gap-sm p-sm rounded-lg border border-outline-variant cursor-pointer hover:bg-surface-container">
                                                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="text-secondary-container focus:ring-secondary-container rounded" {{ $kos->facilities->contains($facility->id) ? 'checked' : '' }}>
                                                                <span class="font-label-sm text-label-sm text-on-surface">{{ $facility->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Rules -->
                                                <div class="pt-md border-t border-outline-variant pb-xl">
                                                    <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">Aturan Kos</h3>
                                                    @foreach($masterRules as $category => $rules)
                                                        <div class="mb-md">
                                                            <h4 class="font-label-sm text-label-sm text-on-surface-variant uppercase mb-xs">{{ $category }}</h4>
                                                            <div class="space-y-xs">
                                                                @foreach($rules as $rule)
                                                                    <label class="flex items-start gap-sm">
                                                                        <input type="checkbox" name="rules[]" value="{{ $rule->id }}" class="mt-1 text-secondary-container focus:ring-secondary-container rounded" {{ $kos->rules->contains($rule->id) ? 'checked' : '' }}>
                                                                        <span class="font-label-sm text-label-sm text-on-surface">{{ $rule->name }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- Footer -->
                                            <div class="flex justify-end gap-sm px-md py-md border-t border-outline-variant sticky bottom-0 bg-surface-container-lowest">
                                                <button type="button" @click="showEditDrawer = false" class="px-md py-sm rounded-xl border border-outline-variant text-on-surface font-label-md text-label-md hover:bg-surface-container">Batal</button>
                                                <button type="submit" class="px-md py-sm rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-inverse-surface">Simpan</button>
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
            <div class="col-span-full py-xl flex flex-col items-center justify-center border-2 border-dashed border-outline-variant rounded-2xl bg-surface-container">
                <span class="material-symbols-outlined text-6xl text-outline mb-md">home_work</span>
                <h3 class="font-headline-md text-headline-md text-on-surface mb-xs">Belum Ada Kos</h3>
                <p class="font-body-md text-body-md text-on-surface-variant mb-md text-center max-w-md">Mulai kelola properti kos Anda dengan menambahkan kos pertama</p>
                <button type="button" @click="showCreateDrawer = true" class="bg-primary text-on-primary px-lg py-sm rounded-xl font-label-md text-label-md font-bold flex items-center gap-xs">
                    <span class="material-symbols-outlined">add</span>
                    Tambah Kos Pertama
                </button>
            </div>
        @endforelse
    </div>

    @if($kosList->hasPages())
        <div class="mt-lg">
            {{ $kosList->links() }}
        </div>
    @endif

    <!-- Create Drawer (Similar structure to Edit) -->
    <template x-teleport="body">
        <div x-show="showCreateDrawer" class="relative z-50" role="dialog">
            <div x-show="showCreateDrawer" x-transition.opacity class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="showCreateDrawer = false"></div>
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="showCreateDrawer" x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-lg">
                            <form method="POST" action="{{ route('ux2.owner.kos.store') }}" class="flex h-full flex-col bg-surface-container-lowest shadow-xl overflow-y-auto">
                                @csrf
                                
                                <div class="px-md py-md border-b border-outline-variant sticky top-0 bg-surface-container-lowest z-10">
                                    <div class="flex items-center justify-between">
                                        <h2 class="font-headline-lg text-headline-lg text-on-surface">Tambah Kos Baru</h2>
                                        <button type="button" @click="showCreateDrawer = false" class="text-on-surface-variant hover:text-on-surface">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>
                                    <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Buat properti kos baru</p>
                                </div>

                                <div class="flex-1 px-md py-md space-y-md">
                                    <div>
                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Nama Kos</label>
                                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Kos Sudirman Elite" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-sm">
                                        <div>
                                            <label class="block font-label-md text-label-md text-on-surface mb-xs">Kota</label>
                                            <input type="text" name="city" value="{{ old('city') }}" placeholder="Jakarta" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                        </div>
                                        <div>
                                            <label class="block font-label-md text-label-md text-on-surface mb-xs">Tipe</label>
                                            <select name="gender_type" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                                <option value="putra">Putra</option>
                                                <option value="putri">Putri</option>
                                                <option value="campur">Campur</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-sm">
                                        <div>
                                            <label class="block font-label-md text-label-md text-on-surface mb-xs">Provinsi</label>
                                            <input type="text" name="province" value="{{ old('province') }}" placeholder="DKI Jakarta" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>
                                        </div>
                                        <div>
                                            <label class="block font-label-md text-label-md text-on-surface mb-xs">Kode Pos</label>
                                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="10220" class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Alamat</label>
                                        <textarea name="address" rows="2" placeholder="Jl. Sudirman No 10..." class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container" required>{{ old('address') }}</textarea>
                                    </div>

                                    <div>
                                        <label class="block font-label-md text-label-md text-on-surface mb-xs">Deskripsi</label>
                                        <textarea name="description" rows="3" placeholder="Kos nyaman dan strategis..." class="w-full rounded-xl border-outline-variant bg-surface-container-lowest focus:ring-secondary-container">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="pt-md border-t border-outline-variant">
                                        <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">Fasilitas Bersama</h3>
                                        <div class="grid grid-cols-2 gap-sm">
                                            @foreach($masterFacilities as $facility)
                                                <label class="flex items-center gap-sm p-sm rounded-lg border border-outline-variant cursor-pointer hover:bg-surface-container">
                                                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="text-secondary-container focus:ring-secondary-container rounded">
                                                    <span class="font-label-sm text-label-sm text-on-surface">{{ $facility->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="pt-md border-t border-outline-variant pb-xl">
                                        <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">Aturan Kos</h3>
                                        @foreach($masterRules as $category => $rules)
                                            <div class="mb-md">
                                                <h4 class="font-label-sm text-label-sm text-on-surface-variant uppercase mb-xs">{{ $category }}</h4>
                                                <div class="space-y-xs">
                                                    @foreach($rules as $rule)
                                                        <label class="flex items-start gap-sm">
                                                            <input type="checkbox" name="rules[]" value="{{ $rule->id }}" class="mt-1 text-secondary-container focus:ring-secondary-container rounded">
                                                            <span class="font-label-sm text-label-sm text-on-surface">{{ $rule->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex justify-end gap-sm px-md py-md border-t border-outline-variant sticky bottom-0 bg-surface-container-lowest">
                                    <button type="button" @click="showCreateDrawer = false" class="px-md py-sm rounded-xl border border-outline-variant text-on-surface font-label-md text-label-md hover:bg-surface-container">Batal</button>
                                    <button type="submit" class="px-md py-sm rounded-xl bg-secondary-container text-on-secondary-container font-label-md text-label-md font-bold hover:bg-secondary-fixed">Simpan Kos</button>
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
