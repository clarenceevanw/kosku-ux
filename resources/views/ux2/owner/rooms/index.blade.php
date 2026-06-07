@extends('layouts.ux2.owner')

@section('title', 'Kelola Kamar - KosKu')

@push('styles')
<style>
    /* ── ANIMATIONS ──────────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slide-right {
        from { opacity: 0; transform: translateX(-16px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .anim-fade-up  { animation: fade-up  0.55s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in  { animation: fade-in  0.45s ease both; }
    .anim-slide-r  { animation: slide-right 0.5s cubic-bezier(.22,.68,0,1.1) both; }
    .d1{animation-delay:.07s} .d2{animation-delay:.14s}
    .d3{animation-delay:.21s} .d4{animation-delay:.28s}

    /* ── SCROLL REVEAL ───────────────────────── */
    .reveal {
        opacity: 0; transform: translateY(20px);
        transition: opacity .55s cubic-bezier(.22,.68,0,1.1), transform .55s cubic-bezier(.22,.68,0,1.1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .rev-d1{transition-delay:.08s} .rev-d2{transition-delay:.18s}
    .rev-d3{transition-delay:.28s} .rev-d4{transition-delay:.38s}

    /* ── PAGE HEADER ─────────────────────────── */
    .page-header {
        background: linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: ''; position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(189,235,216,0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(189,235,216,0.1) 1px, transparent 1px);
        background-size: 32px 32px; pointer-events: none;
    }

    /* ── FILTER CARD ─────────────────────────── */
    .filter-card {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        padding: 16px;
        box-shadow: var(--ux2-shadow-soft);
        display: flex; gap: 16px; align-items: center; flex-wrap: wrap;
    }
    .search-input {
        flex: 1; min-width: 200px;
        position: relative;
    }
    .search-input input {
        width: 100%; padding: 12px 16px 12px 48px;
        border-radius: 12px;
        border: 1px solid var(--ux2-line);
        background: var(--ux2-surface);
        transition: all .22s ease;
    }
    .search-input input:focus {
        border-color: var(--ux2-secondary);
        box-shadow: 0 0 0 3px rgba(47,143,121,0.15);
        background: #fff;
    }
    .search-input .icon {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: var(--ux2-muted);
    }

    /* ── ADD BTN ─────────────────────────────── */
    @keyframes shimmer { from{left:-80%} to{left:140%} }
    .btn-add {
        position: relative; overflow: hidden;
        display: flex; align-items: center; gap: 8px;
        padding: 12px 24px; border-radius: 12px;
        font-weight: 700; color: #fff;
        background: var(--ux2-primary);
        transition: transform .2s ease, box-shadow .2s ease;
        white-space: nowrap;
    }
    .btn-add::after {
        content:''; position:absolute; top:0; left:-80%;
        width:55%; height:100%;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,0.22),transparent);
        transform:skewX(-18deg);
        animation: shimmer 2.8s ease-in-out 1s infinite;
    }
    .btn-add:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

    /* ── TABLE ───────────────────────────────── */
    .table-card {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        box-shadow: var(--ux2-shadow-soft);
        overflow: hidden;
    }
    .table-row { transition: background .22s ease; }
    .table-row:hover { background: var(--ux2-panel); }

    /* ── ACTION BUTTONS ──────────────────────── */
    .action-btn-wrap {
        display: flex; justify-content: flex-end; align-items: center; gap: 10px;
    }
    .btn-edit {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--ux2-line);
        color: var(--ux2-muted);
        background: #fff;
        transition: all .2s ease;
    }
    .btn-edit:hover {
        border-color: var(--ux2-primary);
        color: var(--ux2-primary);
        background: var(--ux2-primary-soft);
    }
    .btn-manage {
        padding: 8px 16px; border-radius: 10px;
        font-weight: 700; font-size: 13px;
        color: var(--ux2-primary);
        background: var(--ux2-primary-soft);
        border: 1px solid transparent;
        transition: all .2s ease;
        display: flex; align-items: center; justify-content: center; height: 36px;
    }
    .btn-manage:hover {
        border-color: var(--ux2-primary);
        background: var(--ux2-primary);
        color: #fff;
    }
</style>
@endpush

@section('content')
<div x-data="{ showCreateDrawer: false }">

    {{-- ════ PAGE HEADER ════ --}}
    <div class="page-header p-lg mb-lg anim-fade-up">
        <div class="relative z-10">
            <p class="anim-slide-r d1" style="font-size:12px; font-weight:700; color:rgba(255,255,255,0.6); letter-spacing:.07em; text-transform:uppercase; margin-bottom:8px;">Manajemen Inventori</p>
            <h1 class="font-display-lg text-display-lg anim-fade-up d2" style="color:#fff; line-height:1.1;">
                Kelola Kamar
            </h1>
            <p class="anim-fade-up d3" style="color:rgba(255,255,255,0.7); font-size:14px; margin-top:6px;">
                Mengelola {{ $rooms->count() }} unit kamar untuk properti Anda.
            </p>
        </div>
    </div>

    {{-- ════ FILTER & ACTIONS ════ --}}
    <div class="anim-fade-up d2 mb-lg">
        <form method="GET" action="{{ route('ux2.owner.rooms.index') }}" class="filter-card">
            <div class="search-input">
                <span class="material-symbols-outlined icon">search</span>
                <input name="search" value="{{ request('search') }}" type="text" placeholder="Cari nama kamar..." class="font-body-md text-body-md" />
            </div>
            
            <div style="position: relative; min-width: 220px;">
                <select name="kos_id" onchange="this.form.submit()" 
                    style="width: 100%; padding: 12px 16px 12px 42px; border-radius: 12px; border: 1px solid var(--ux2-line); background: var(--ux2-surface); appearance: none; cursor:pointer;" 
                    class="font-label-md text-label-md text-on-surface">
                    @foreach($boardingHouses as $kos)
                        <option value="{{ $kos->id }}" {{ $selectedKos?->id == $kos->id ? 'selected' : '' }}>{{ $kos->name }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--ux2-muted); pointer-events: none;">home_work</span>
                <span class="material-symbols-outlined" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--ux2-muted); pointer-events: none;">arrow_drop_down</span>
            </div>

            <button type="button" @click="showCreateDrawer = true" class="btn-add">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">add_circle</span>
                Tambah Kamar
            </button>
        </form>
    </div>

    {{-- ════ ROOMS TABLE ════ --}}
    <div class="table-card reveal anim-fade-up d3">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[900px]">
                <thead>
                    <tr style="background:var(--ux2-surface); border-bottom:1px solid var(--ux2-line);">
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Tipe Kamar</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Harga/Bulan</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Stok</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Status</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider" style="color:var(--ux2-muted);">Fasilitas</th>
                        <th class="px-md py-sm font-label-sm text-label-sm uppercase tracking-wider text-right" style="color:var(--ux2-muted);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--ux2-line);">
                    @forelse($rooms as $room)
                        <tr x-data="{ showEditDrawer: false }" class="table-row">
                            <td class="px-md py-md">
                                <span class="font-headline-md text-headline-md font-bold" style="color:var(--ux2-ink);">{{ $room->type_name }}</span>
                            </td>
                            <td class="px-md py-md">
                                <span class="font-body-md text-body-md font-bold" style="color:var(--ux2-primary);">Rp {{ number_format($room->price_per_month, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-md py-md font-body-md text-body-md" style="color:var(--ux2-ink);">
                                {{ $room->stock }} Unit
                            </td>
                            <td class="px-md py-md">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider" 
                                    style="background:var(--ux2-panel); border:1px solid var(--ux2-line); color:var(--ux2-ink);">
                                    <span class="w-2 h-2 rounded-full {{ $room->status_dot }}"></span>
                                    {{ $room->dynamic_status }}
                                </span>
                            </td>
                            <td class="px-md py-md">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($room->facilities as $facility)
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:var(--ux2-surface); border:1px solid var(--ux2-line);" title="{{ $facility->name }}">
                                            <span class="material-symbols-outlined text-[16px]" style="color:var(--ux2-muted);">
                                                {{ $facility->icon ?? 'check_circle' }}
                                            </span>
                                        </div>
                                    @empty
                                        <span class="font-label-sm text-label-sm italic" style="color:var(--ux2-muted);">Tidak ada</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-md py-md">
                                <div class="action-btn-wrap">
                                    <button type="button" @click="showEditDrawer = true" class="btn-edit" title="Edit Kamar">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <a href="{{ route('ux2.owner.rooms.show', $room->id) }}" class="btn-manage" title="Kelola Penyewa Aktif">
                                        Kelola Unit
                                    </a>
                                </div>
                            </td>

                            {{-- ════ EDIT DRAWER ════ --}}
                            <template x-teleport="body">
                                <div x-show="showEditDrawer" class="relative z-50" role="dialog">
                                    <div x-show="showEditDrawer" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showEditDrawer = false"></div>
                                    <div class="fixed inset-0 overflow-hidden pointer-events-none">
                                        <div class="absolute inset-0 overflow-hidden">
                                            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                                                <div x-show="showEditDrawer" 
                                                    x-transition:enter="transform transition ease-in-out duration-500" 
                                                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" 
                                                    x-transition:leave="transform transition ease-in-out duration-500" 
                                                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" 
                                                    class="pointer-events-auto w-screen max-w-lg">
                                                    
                                                    <form method="POST" action="{{ route('ux2.owner.rooms.update', $room->id) }}" class="flex h-full flex-col bg-surface-container-lowest shadow-2xl overflow-y-auto">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        {{-- Drawer Header --}}
                                                        <div class="px-lg py-md border-b sticky top-0 bg-surface-container-lowest z-10" style="border-color:var(--ux2-line);">
                                                            <div class="flex items-center justify-between">
                                                                <h2 class="font-headline-lg text-headline-lg font-bold" style="color:var(--ux2-ink);">Edit Kamar</h2>
                                                                <button type="button" @click="showEditDrawer = false" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" style="background:var(--ux2-surface); color:var(--ux2-muted);" onmouseover="this.style.background='var(--ux2-panel)'; this.style.color='var(--ux2-ink)'" onmouseout="this.style.background='var(--ux2-surface)'; this.style.color='var(--ux2-muted)'">
                                                                    <span class="material-symbols-outlined">close</span>
                                                                </button>
                                                            </div>
                                                            <p style="font-size:14px; color:var(--ux2-muted); margin-top:4px;">Perbarui informasi tipe kamar {{ $room->type_name }}</p>
                                                        </div>

                                                        {{-- Drawer Body --}}
                                                        <div class="flex-1 px-lg py-md space-y-md">
                                                            <div>
                                                                <label class="block font-label-md text-label-md font-bold mb-2" style="color:var(--ux2-ink);">Tipe Kamar</label>
                                                                <input type="text" name="type_name" value="{{ $room->type_name }}" style="border-color:var(--ux2-line);" class="w-full rounded-xl bg-surface-container-lowest focus:ring-secondary-container" required>
                                                            </div>
                                                            
                                                            <div class="grid grid-cols-2 gap-md">
                                                                <div>
                                                                    <label class="block font-label-md text-label-md font-bold mb-2" style="color:var(--ux2-ink);">Harga/Bulan</label>
                                                                    <input type="number" name="price_per_month" value="{{ (int)$room->price_per_month }}" style="border-color:var(--ux2-line);" class="w-full rounded-xl bg-surface-container-lowest focus:ring-secondary-container" required>
                                                                </div>
                                                                <div>
                                                                    <label class="block font-label-md text-label-md font-bold mb-2" style="color:var(--ux2-ink);">Stok Unit</label>
                                                                    <input type="number" name="stock" value="{{ $room->stock }}" style="border-color:var(--ux2-line);" class="w-full rounded-xl bg-surface-container-lowest focus:ring-secondary-container" required>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block font-label-md text-label-md font-bold mb-2" style="color:var(--ux2-ink);">Ukuran Kamar</label>
                                                                <input type="text" name="size" value="{{ $room->size }}" placeholder="3x4 meter" style="border-color:var(--ux2-line);" class="w-full rounded-xl bg-surface-container-lowest focus:ring-secondary-container">
                                                            </div>

                                                            <div>
                                                                <label class="block font-label-md text-label-md font-bold mb-3" style="color:var(--ux2-ink);">Fasilitas Kamar</label>
                                                                <div class="grid grid-cols-2 gap-sm">
                                                                    @foreach($roomFacilities as $facility)
                                                                        <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg transition-colors" style="border:1px solid transparent;" onmouseover="this.style.background='var(--ux2-panel)'" onmouseout="this.style.background='transparent'">
                                                                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" {{ $room->facilities->contains('id', $facility->id) ? 'checked' : '' }} class="rounded border-outline-variant text-secondary-container focus:ring-secondary-container w-5 h-5">
                                                                            <span class="font-label-md text-label-md flex items-center gap-2" style="color:var(--ux2-ink);">
                                                                                <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-muted);">{{ $facility->icon ?? 'check_circle' }}</span>
                                                                                {{ $facility->name }}
                                                                            </span>
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Drawer Footer --}}
                                                        <div class="flex justify-between px-lg py-md border-t sticky bottom-0 bg-surface-container-lowest" style="border-color:var(--ux2-line);">
                                                            <button type="button" onclick="if(confirm('Yakin ingin menghapus kamar ini?')) document.getElementById('delete-room-{{ $room->id }}').submit()" class="px-md py-sm rounded-xl font-label-md text-label-md font-bold transition-colors" style="background:var(--ux2-coral-soft); color:var(--ux2-coral);" onmouseover="this.style.background='var(--ux2-coral)'; this.style.color='#fff'" onmouseout="this.style.background='var(--ux2-coral-soft)'; this.style.color='var(--ux2-coral)'">Hapus Kamar</button>
                                                            <div class="flex gap-sm">
                                                                <button type="button" @click="showEditDrawer = false" class="px-md py-sm rounded-xl border font-label-md text-label-md font-bold transition-colors" style="border-color:var(--ux2-line); color:var(--ux2-ink);" onmouseover="this.style.background='var(--ux2-panel)'" onmouseout="this.style.background='transparent'">Batal</button>
                                                                <button type="submit" class="px-md py-sm rounded-xl font-label-md text-label-md font-bold transition-colors" style="background:var(--ux2-primary); color:#fff;" onmouseover="this.style.background='var(--ux2-primary-deep)'" onmouseout="this.style.background='var(--ux2-primary)'">Simpan Perubahan</button>
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
                                    <div class="w-20 h-20 rounded-full flex items-center justify-center mb-sm" style="background:var(--ux2-panel);">
                                        <span class="material-symbols-outlined text-5xl" style="color:var(--ux2-muted); font-variation-settings:'FILL' 1;">bed</span>
                                    </div>
                                    <h3 class="font-headline-md text-headline-md font-bold mb-1" style="color:var(--ux2-ink);">Belum Ada Kamar</h3>
                                    <p style="font-size:14px; color:var(--ux2-muted); max-width:300px; line-height:1.6;">Tambahkan tipe kamar untuk properti ini untuk mulai menyewakan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ════ CREATE DRAWER ════ --}}
    <template x-teleport="body">
        <div x-show="showCreateDrawer" class="relative z-50" role="dialog">
            <div x-show="showCreateDrawer" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showCreateDrawer = false"></div>
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <div x-show="showCreateDrawer" 
                            x-transition:enter="transform transition ease-in-out duration-500" 
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" 
                            x-transition:leave="transform transition ease-in-out duration-500" 
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" 
                            class="pointer-events-auto w-screen max-w-lg">
                            
                            <form method="POST" action="{{ route('ux2.owner.rooms.store') }}" class="flex h-full flex-col bg-surface-container-lowest shadow-2xl overflow-y-auto">
                                @csrf
                                <input type="hidden" name="boarding_house_id" value="{{ $selectedKos?->id }}">
                                
                                {{-- Drawer Header --}}
                                <div class="px-lg py-md border-b sticky top-0 bg-surface-container-lowest z-10" style="border-color:var(--ux2-line);">
                                    <div class="flex items-center justify-between">
                                        <h2 class="font-headline-lg text-headline-lg font-bold" style="color:var(--ux2-ink);">Tambah Kamar</h2>
                                        <button type="button" @click="showCreateDrawer = false" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors" style="background:var(--ux2-surface); color:var(--ux2-muted);" onmouseover="this.style.background='var(--ux2-panel)'; this.style.color='var(--ux2-ink)'" onmouseout="this.style.background='var(--ux2-surface)'; this.style.color='var(--ux2-muted)'">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>
                                    <p style="font-size:14px; color:var(--ux2-muted); margin-top:4px;">Tambah tipe kamar baru ke inventori</p>
                                </div>

                                {{-- Drawer Body --}}
                                <div class="flex-1 px-lg py-md space-y-md">
                                    <div>
                                        <label class="block font-label-md text-label-md font-bold mb-2" style="color:var(--ux2-ink);">Tipe Kamar</label>
                                        <input type="text" name="type_name" placeholder="VIP, Standard" style="border-color:var(--ux2-line);" class="w-full rounded-xl bg-surface-container-lowest focus:ring-secondary-container" required>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-md">
                                        <div>
                                            <label class="block font-label-md text-label-md font-bold mb-2" style="color:var(--ux2-ink);">Harga/Bulan</label>
                                            <input type="number" name="price_per_month" placeholder="2000000" style="border-color:var(--ux2-line);" class="w-full rounded-xl bg-surface-container-lowest focus:ring-secondary-container" required>
                                        </div>
                                        <div>
                                            <label class="block font-label-md text-label-md font-bold mb-2" style="color:var(--ux2-ink);">Stok Unit</label>
                                            <input type="number" name="stock" placeholder="10" style="border-color:var(--ux2-line);" class="w-full rounded-xl bg-surface-container-lowest focus:ring-secondary-container" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block font-label-md text-label-md font-bold mb-2" style="color:var(--ux2-ink);">Ukuran Kamar</label>
                                        <input type="text" name="size" placeholder="3x4 meter" style="border-color:var(--ux2-line);" class="w-full rounded-xl bg-surface-container-lowest focus:ring-secondary-container">
                                    </div>

                                    <div>
                                        <label class="block font-label-md text-label-md font-bold mb-3" style="color:var(--ux2-ink);">Fasilitas Kamar</label>
                                        <div class="grid grid-cols-2 gap-sm">
                                            @foreach($roomFacilities as $facility)
                                                <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg transition-colors" style="border:1px solid transparent;" onmouseover="this.style.background='var(--ux2-panel)'" onmouseout="this.style.background='transparent'">
                                                    <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" class="rounded border-outline-variant text-secondary-container focus:ring-secondary-container w-5 h-5">
                                                    <span class="font-label-md text-label-md flex items-center gap-2" style="color:var(--ux2-ink);">
                                                        <span class="material-symbols-outlined text-[18px]" style="color:var(--ux2-muted);">{{ $facility->icon ?? 'check_circle' }}</span>
                                                        {{ $facility->name }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Drawer Footer --}}
                                <div class="flex justify-end gap-sm px-lg py-md border-t sticky bottom-0 bg-surface-container-lowest" style="border-color:var(--ux2-line);">
                                    <button type="button" @click="showCreateDrawer = false" class="px-md py-sm rounded-xl border font-label-md text-label-md font-bold transition-colors" style="border-color:var(--ux2-line); color:var(--ux2-ink);" onmouseover="this.style.background='var(--ux2-panel)'" onmouseout="this.style.background='transparent'">Batal</button>
                                    <button type="submit" class="px-md py-sm rounded-xl font-label-md text-label-md font-bold transition-colors" style="background:var(--ux2-primary); color:#fff;" onmouseover="this.style.background='var(--ux2-primary-deep)'" onmouseout="this.style.background='var(--ux2-primary)'">Simpan Kamar</button>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ro = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
    document.querySelectorAll('.reveal').forEach(el => ro.observe(el));
});
</script>
@endpush
