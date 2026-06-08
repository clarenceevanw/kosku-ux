@extends('layouts.ux2.app')

@section('title', 'Hasil Pencarian Kos - KosKu')

@section('styles')
<style>
    .icon-fill {
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    /* Custom Scrollbar for sleekness */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    ::-webkit-scrollbar-thumb {
        background-color: #c6c6cd;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<!-- Main Layout -->
<main class="max-w-[1440px] mx-auto px-margin-mobile md:px-margin-desktop py-lg flex flex-col lg:flex-row gap-gutter relative">
<!-- Sidebar Filters -->
<aside class="w-full lg:w-72 flex-shrink-0">
<div class="sticky top-[104px] bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-[0px_4px_20px_rgba(15,23,42,0.02)] space-y-md max-h-[calc(100vh-120px)] overflow-y-auto" id="search-filter-form">
<div class="flex items-center gap-2 border-b border-outline-variant pb-sm">
<span class="material-symbols-outlined text-primary">filter_list</span>
<h2 class="font-headline-md text-headline-md text-primary font-bold">Filter</h2>
</div>
<!-- Location Search -->
<div class="space-y-sm">
<label class="font-label-md text-label-md text-on-surface-variant">Lokasi (Area/Nama Kos)</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
<input class="js-live-filter w-full pl-10 pr-4 py-2 border border-outline-variant rounded-lg focus:border-secondary focus:ring-2 focus:ring-secondary-container/50 outline-none text-body-md font-body-md bg-surface transition-all" id="filter-keyword" name="q" placeholder="Ketik pencarian..." type="text" value="{{ $keyword ?? '' }}"/>
</div>
</div>
<!-- City Filter -->
@if(isset($cities) && $cities->isNotEmpty())
<div class="space-y-sm">
<label class="font-label-md text-label-md text-on-surface-variant">Kota</label>
<select class="js-live-filter w-full px-4 py-2 border border-outline-variant rounded-lg focus:border-secondary focus:ring-2 focus:ring-secondary-container/50 outline-none text-body-md font-body-md bg-surface transition-all cursor-pointer" id="filter-city" name="city">
<option value="">Semua Kota</option>
@foreach($cities as $city)
<option value="{{ $city }}" @selected(($filters['city'] ?? '') === $city)>{{ $city }}</option>
@endforeach
</select>
</div>
@endif
<!-- Price Range -->
<div class="space-y-sm">
<label class="font-label-md text-label-md text-on-surface-variant">Harga Per Bulan</label>
<div class="flex gap-2 items-center">
<div class="relative w-full">
<span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline font-label-sm text-label-sm">Rp</span>
<input class="js-live-filter w-full pl-8 pr-2 py-2 border border-outline-variant rounded-lg focus:border-secondary focus:ring-2 focus:ring-secondary-container/50 outline-none text-body-md font-body-md bg-surface transition-all" id="filter-min-price" name="min_price" placeholder="Min" type="text" value="{{ $filters['min_price'] ?? '' }}"/>
</div>
<span class="text-outline-variant">-</span>
<div class="relative w-full">
<span class="absolute left-3 top-1/2 -translate-y-1/2 text-outline font-label-sm text-label-sm">Rp</span>
<input class="js-live-filter w-full pl-8 pr-2 py-2 border border-outline-variant rounded-lg focus:border-secondary focus:ring-2 focus:ring-secondary-container/50 outline-none text-body-md font-body-md bg-surface transition-all" id="filter-max-price" name="max_price" placeholder="Max" type="text" value="{{ $filters['max_price'] ?? '' }}"/>
</div>
</div>
</div>
<!-- Property Type -->
<div class="space-y-sm">
<label class="font-label-md text-label-md text-on-surface-variant">Tipe Kos</label>
<div class="flex flex-col gap-2">
<label class="flex items-center gap-2 cursor-pointer group">
<input class="js-live-filter w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary-container transition-all" name="gender_type[]" type="checkbox" value="campur" @checked(in_array('campur', $filters['gender_type'] ?? []))/>
<span class="material-symbols-outlined text-[18px] text-violet">group</span>
<span class="font-body-md text-body-md text-on-surface group-hover:text-secondary transition-colors">Campur</span>
</label>
<label class="flex items-center gap-2 cursor-pointer group">
<input class="js-live-filter w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary-container transition-all" name="gender_type[]" type="checkbox" value="putra" @checked(in_array('putra', $filters['gender_type'] ?? []))/>
<span class="material-symbols-outlined text-[18px] text-primary">man</span>
<span class="font-body-md text-body-md text-on-surface group-hover:text-secondary transition-colors">Putra</span>
</label>
<label class="flex items-center gap-2 cursor-pointer group">
<input class="js-live-filter w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary-container transition-all" name="gender_type[]" type="checkbox" value="putri" @checked(in_array('putri', $filters['gender_type'] ?? []))/>
<span class="material-symbols-outlined text-[18px] text-coral">woman</span>
<span class="font-body-md text-body-md text-on-surface group-hover:text-secondary transition-colors">Putri</span>
</label>
</div>
</div>
<!-- Fasilitas Bersama -->
@php $besamaFacilities = $facilitiesByType['bersama'] ?? collect(); @endphp
@if($besamaFacilities->isNotEmpty())
<div class="space-y-sm">
<label class="font-label-md text-label-md text-on-surface-variant">Fasilitas Bersama</label>
<div class="flex flex-col gap-2 max-h-48 overflow-y-auto pr-2">
@foreach($besamaFacilities as $facility)
<label class="flex items-center gap-2 cursor-pointer group">
<input class="js-live-filter w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary-container transition-all" name="facilities[]" type="checkbox" value="{{ $facility->id }}" @checked(in_array($facility->id, $filters['facilities'] ?? []))/>
<span class="font-body-md text-body-md text-on-surface group-hover:text-secondary transition-colors flex items-center gap-1.5">
@if($facility->icon)<span class="material-symbols-outlined text-[14px] text-outline">{{ $facility->icon }}</span>@endif
{{ $facility->name }}
</span>
</label>
@endforeach
</div>
</div>
@endif
<!-- Fasilitas Kamar -->
@php $ruangFacilities = $facilitiesByType['ruang'] ?? collect(); @endphp
@if($ruangFacilities->isNotEmpty())
<div class="space-y-sm">
<label class="font-label-md text-label-md text-on-surface-variant">Fasilitas Kamar</label>
<div class="flex flex-col gap-2 max-h-48 overflow-y-auto pr-2">
@foreach($ruangFacilities as $facility)
<label class="flex items-center gap-2 cursor-pointer group">
<input class="js-live-filter w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary-container transition-all" name="room_facilities[]" type="checkbox" value="{{ $facility->id }}" @checked(in_array($facility->id, $filters['room_facilities'] ?? []))/>
<span class="font-body-md text-body-md text-on-surface group-hover:text-secondary transition-colors flex items-center gap-1.5">
@if($facility->icon)<span class="material-symbols-outlined text-[14px] text-outline">{{ $facility->icon }}</span>@endif
{{ $facility->name }}
</span>
</label>
@endforeach
</div>
</div>
@endif
</div>
</aside>
<!-- Search Results Area -->
<section class="flex-1 flex flex-col gap-md">
<!-- Top Action Bar -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-surface-container-lowest p-4 rounded-xl border border-outline-variant shadow-sm">
<div>
<h1 class="font-headline-md text-headline-md text-primary font-bold">{{ !empty($keyword) ? 'Kos untuk "' . $keyword . '"' : 'Semua Kos' }}</h1>
<p class="font-body-md text-body-md text-on-surface-variant mt-1">Menampilkan <span id="live-result-count">{{ $totalHouses ?? count($boardingHouses) }}</span> hasil properti</p>
</div>
<div class="flex items-center gap-2">
<span class="font-label-md text-label-md text-on-surface-variant">Urutkan:</span>
<select class="js-live-filter border border-outline-variant rounded-lg py-2 pl-3 pr-8 focus:border-secondary focus:ring-2 focus:ring-secondary-container/50 outline-none text-body-md font-body-md bg-surface cursor-pointer" id="filter-sort" name="sort">
<option value="recommended" @selected(($filters['sort'] ?? 'recommended') === 'recommended')>Rekomendasi</option>
<option value="price_low" @selected(($filters['sort'] ?? 'recommended') === 'price_low')>Harga Terendah</option>
<option value="price_high" @selected(($filters['sort'] ?? 'recommended') === 'price_high')>Harga Tertinggi</option>
<option value="oldest" @selected(($filters['sort'] ?? 'recommended') === 'oldest')>Terlama</option>
</select>
</div>
</div>
<!-- Grid Layout -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter" id="boarding-house-grid">
@forelse ($boardingHouses as $house)
@php
    // Get array of facility IDs for this house (both shared and room facilities for filtering)
    $allFacilityIds = $house['all_facility_ids'] ?? [];
@endphp
<article class="js-house-card bg-surface-container-lowest rounded-xl border border-outline-variant shadow-[0px_4px_20px_rgba(15,23,42,0.05)] overflow-hidden group hover:-translate-y-1 hover:shadow-[0px_10px_30px_rgba(15,23,42,0.12)] transition-all duration-300 cursor-pointer flex flex-col" data-created-order="{{ $loop->index }}" data-gender="{{ $house['gender_type'] }}" data-price="{{ $house['min_price'] ?? 0 }}" data-city="{{ strtolower($house['city'] ?? '') }}" data-facilities="{{ json_encode($allFacilityIds) }}" data-search="{{ strtolower(trim(($house['name'] ?? '') . ' ' . ($house['city'] ?? '') . ' ' . ($house['province'] ?? '') . ' ' . ($house['address'] ?? '') . ' ' . ($house['description'] ?? ''))) }}" onclick="window.location='{{ route('ux2.kos.show', $house['id']) }}'">
<div class="relative aspect-[4/3] overflow-hidden bg-surface-variant">
@if (!empty($house['available_stock']))
<div class="absolute top-3 left-3 z-10 bg-secondary-container text-on-secondary-container font-label-sm text-label-sm px-2 py-1 rounded-full flex items-center gap-1 shadow-sm">
<span class="material-symbols-outlined text-[14px] icon-fill">verified</span>
                            Tersedia
                        </div>
@endif
@if (!empty($house['primary_image']))
<img alt="{{ $house['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $house['primary_image'] }}"/>
@else
<div class="w-full h-full flex items-center justify-center text-on-surface-variant">
<span class="material-symbols-outlined text-5xl">home_work</span>
</div>
@endif
</div>
<div class="p-4 flex flex-col flex-1 gap-2">
<div class="flex justify-between items-start gap-2">
<h3 class="font-headline-md text-headline-md text-primary font-bold text-lg leading-tight line-clamp-2">{{ $house['name'] }}</h3>
<span class="text-outline mt-1"><span class="material-symbols-outlined">favorite_border</span></span>
</div>
<div class="flex items-center gap-3">
<div class="flex items-center gap-1 text-on-surface-variant font-body-md text-body-md text-sm">
<span class="material-symbols-outlined text-[16px]">location_on</span>
<span class="truncate">{{ $house['city'] }}{{ !empty($house['province']) ? ', ' . $house['province'] : '' }}</span>
</div>
@php
    $genderIcon = match($house['gender_type']) {
        'putra' => ['icon' => 'man', 'color' => 'text-primary', 'bg' => 'bg-primary-fixed'],
        'putri' => ['icon' => 'woman', 'color' => 'text-coral', 'bg' => 'bg-error-container'],
        'campur' => ['icon' => 'group', 'color' => 'text-violet', 'bg' => 'bg-tertiary-fixed'],
        default => ['icon' => 'home_work', 'color' => 'text-outline', 'bg' => 'bg-surface-container']
    };
@endphp
<div class="flex items-center gap-1 {{ $genderIcon['bg'] }} px-2 py-1 rounded-md">
<span class="material-symbols-outlined text-[16px] {{ $genderIcon['color'] }}">{{ $genderIcon['icon'] }}</span>
<span class="font-label-sm text-label-sm {{ $genderIcon['color'] }} font-semibold">{{ $house['gender_label'] }}</span>
</div>
</div>
<div class="flex flex-wrap gap-2 mt-2">
@foreach ($house['facility_preview'] ?? [] as $facility)
<span class="bg-surface-container px-2 py-1 rounded-md text-on-primary-container font-label-sm text-label-sm">{{ $facility['name'] }}</span>
@endforeach
</div>
<div class="mt-auto pt-4 border-t border-outline-variant/50 flex justify-between items-end">
<div class="flex flex-col">
<span class="font-label-sm text-label-sm text-on-surface-variant">Mulai dari</span>
<span class="font-headline-md text-headline-md text-secondary font-bold">{{ $house['min_price_formatted'] ?? 'Hubungi pemilik' }}@if(!empty($house['min_price_formatted']))<span class="font-body-md text-sm text-outline font-normal">/bln</span>@endif</span>
</div>
</div>
</div>
</article>
@empty
<div class="col-span-full bg-surface-container-lowest rounded-xl border border-outline-variant p-lg text-center">
<span class="material-symbols-outlined text-5xl text-outline mb-2">search_off</span>
<h2 class="font-headline-md text-headline-md text-primary">Belum ada kos ditemukan</h2>
<p class="font-body-md text-body-md text-on-surface-variant mt-2">Coba gunakan kata kunci lain atau hapus filter pencarian.</p>
</div>
@endforelse
<div class="hidden col-span-full bg-surface-container-lowest rounded-xl border border-outline-variant p-lg text-center" id="live-empty-state">
<span class="material-symbols-outlined text-5xl text-outline mb-2">search_off</span>
<h2 class="font-headline-md text-headline-md text-primary">Belum ada kos ditemukan</h2>
<p class="font-body-md text-body-md text-on-surface-variant mt-2">Ubah kata kunci, harga, atau tipe kos untuk melihat hasil lain.</p>
</div>
</div>
</section>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('boarding-house-grid');
    const cards = Array.from(document.querySelectorAll('.js-house-card'));
    const emptyState = document.getElementById('live-empty-state');
    const resultCount = document.getElementById('live-result-count');
    const keywordInput = document.getElementById('filter-keyword');
    const minPriceInput = document.getElementById('filter-min-price');
    const maxPriceInput = document.getElementById('filter-max-price');
    const sortSelect = document.getElementById('filter-sort');
    const citySelect = document.getElementById('filter-city');
    const genderInputs = Array.from(document.querySelectorAll('input[name="gender_type[]"]'));
    const facilityInputs = Array.from(document.querySelectorAll('input[name="facilities[]"], input[name="room_facilities[]"]'));
    const controls = Array.from(document.querySelectorAll('.js-live-filter'));

    const numberValue = (value) => {
        const parsed = String(value || '').replace(/\D+/g, '');
        return parsed ? Number(parsed) : null;
    };

    const selectedGenders = () => genderInputs
        .filter((input) => input.checked)
        .map((input) => input.value);
        
    const selectedFacilities = () => facilityInputs
        .filter((input) => input.checked)
        .map((input) => input.value);

    const applyLiveFilters = () => {
        const keyword = (keywordInput.value || '').trim().toLowerCase();
        const city = citySelect ? (citySelect.value || '').trim().toLowerCase() : '';
        const minPrice = numberValue(minPriceInput.value);
        const maxPrice = numberValue(maxPriceInput.value);
        const genders = selectedGenders();
        const facilities = selectedFacilities();

        const visibleCards = cards.filter((card) => {
            const searchableText = card.dataset.search || '';
            const price = Number(card.dataset.price || 0);
            const gender = card.dataset.gender || '';
            const cardCity = card.dataset.city || '';
            const cardFacilities = JSON.parse(card.dataset.facilities || '[]');

            const matchesKeyword = !keyword || searchableText.includes(keyword);
            const matchesCity = !city || cardCity === city;
            const matchesMinPrice = minPrice === null || price >= minPrice;
            const matchesMaxPrice = maxPrice === null || price <= maxPrice;
            const matchesGender = genders.length === 0 || genders.includes(gender);
            
            // For facilities, the house must have ALL selected facilities
            const matchesFacilities = facilities.length === 0 || facilities.every(fId => cardFacilities.includes(fId));

            return matchesKeyword && matchesCity && matchesMinPrice && matchesMaxPrice && matchesGender && matchesFacilities;
        });

        visibleCards.sort((a, b) => {
            const sort = sortSelect.value;
            const priceA = Number(a.dataset.price || 0);
            const priceB = Number(b.dataset.price || 0);
            const orderA = Number(a.dataset.createdOrder || 0);
            const orderB = Number(b.dataset.createdOrder || 0);

            if (sort === 'price_low') return priceA - priceB;
            if (sort === 'price_high') return priceB - priceA;
            if (sort === 'oldest') return orderB - orderA;
            return orderA - orderB;
        });

        cards.forEach((card) => card.classList.add('hidden'));
        visibleCards.forEach((card) => {
            card.classList.remove('hidden');
            grid.insertBefore(card, emptyState);
        });

        resultCount.textContent = visibleCards.length;
        emptyState.classList.toggle('hidden', visibleCards.length !== 0);
    };

    controls.forEach((control) => {
        control.addEventListener('input', applyLiveFilters);
        control.addEventListener('change', applyLiveFilters);
    });

    applyLiveFilters();
});
</script>
@endsection
