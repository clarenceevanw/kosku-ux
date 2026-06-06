@extends('layouts.ux2.app')

@section('title', 'Detail Kos - KosKu')

@section('content')
@php
    $rooms = collect($boardingHouse['rooms'] ?? []);
    $roomImages = $rooms->pluck('image_url')->filter()->values();
    $primaryImage = $boardingHouse['primary_image'] ?? $roomImages->first();
    $firstRoom = $rooms->first();
    $displayPrice = $firstRoom['price_formatted'] ?? ($boardingHouse['min_price_formatted'] ?? 'Hubungi pemilik');
@endphp
<main class="max-w-[1440px] mx-auto px-margin-mobile md:px-margin-desktop py-lg grid grid-cols-1 md:grid-cols-12 gap-gutter">
<!-- Breadcrumbs -->
<div class="col-span-1 md:col-span-12 mb-sm flex items-center gap-2 text-on-surface-variant font-label-md text-label-md">
<a class="hover:text-primary transition-colors" href="{{ route('ux2.search') }}">Cari Kos</a>
<span class="material-symbols-outlined text-sm">chevron_right</span>
<a class="hover:text-primary transition-colors" href="{{ route('ux2.search', ['q' => $boardingHouse['city'] ?? '']) }}">{{ $boardingHouse['city'] ?? 'Lokasi' }}</a>
<span class="material-symbols-outlined text-sm">chevron_right</span>
<span class="text-primary font-semibold">{{ $boardingHouse['name'] }}</span>
</div>
<!-- Left Column: Image Gallery & Details -->
<div class="col-span-1 md:col-span-8 flex flex-col gap-lg">
<!-- Bento Gallery -->
<div class="grid grid-cols-4 grid-rows-2 gap-sm md:gap-4 h-[400px] md:h-[500px] rounded-xl overflow-hidden">
<div class="col-span-4 md:col-span-3 row-span-2 relative group cursor-pointer">
@if ($primaryImage)
<img alt="{{ $boardingHouse['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" src="{{ $primaryImage }}"/>
@else
<div class="w-full h-full bg-surface-variant flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined text-6xl">home_work</span></div>
@endif
<div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors"></div>
</div>
<div class="hidden md:block col-span-1 row-span-1 relative group cursor-pointer overflow-hidden rounded-tr-xl">
@if ($roomImages->get(1))
<img alt="{{ $boardingHouse['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="{{ $roomImages->get(1) }}"/>
@else
<div class="w-full h-full bg-surface-variant flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined">bed</span></div>
@endif
</div>
<div class="hidden md:block col-span-1 row-span-1 relative group cursor-pointer overflow-hidden rounded-br-xl">
@if ($roomImages->get(2))
<img alt="{{ $boardingHouse['name'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="{{ $roomImages->get(2) }}"/>
@else
<div class="w-full h-full bg-surface-variant flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined">meeting_room</span></div>
@endif
<div class="absolute inset-0 bg-black/40 flex items-center justify-center">
<span class="text-white font-label-md text-label-md font-bold flex items-center gap-1">
<span class="material-symbols-outlined">photo_library</span> {{ $roomImages->count() }} Foto
                        </span>
</div>
</div>
</div>
<!-- Property Header Info -->
<div class="flex flex-col gap-4 border-b border-outline-variant pb-md">
<div class="flex justify-between items-start">
<div>
<div class="flex items-center gap-2 mb-2">
<span class="bg-secondary-container text-on-secondary-container px-2 py-1 rounded-md font-label-sm text-label-sm uppercase tracking-wider">Tersedia</span>
<span class="bg-surface-variant text-on-surface-variant px-2 py-1 rounded-md font-label-sm text-label-sm">{{ $boardingHouse['gender_label'] }}</span>
</div>
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-2">{{ $boardingHouse['name'] }}</h1>
<p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-1">
<span class="material-symbols-outlined text-secondary">location_on</span>
                            {{ $boardingHouse['address'] }}, {{ $boardingHouse['city'] }}
                        </p>
</div>
<button class="p-2 border border-outline-variant rounded-full hover:bg-surface-variant transition-colors flex items-center justify-center text-primary">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
</div>
<!-- Facilities -->
<div class="border-b border-outline-variant pb-md">
<h2 class="font-headline-md text-headline-md text-primary mb-md">Fasilitas Utama</h2>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
@forelse ($boardingHouse['facilities'] ?? [] as $facility)
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-surface-variant flex items-center justify-center text-secondary">
<span class="material-symbols-outlined">{{ $facility['icon'] ?? 'check_circle' }}</span>
</div>
<span class="font-label-md text-label-md text-on-surface">{{ $facility['name'] }}</span>
</div>
@empty
<p class="font-body-md text-body-md text-on-surface-variant col-span-full">Fasilitas belum tersedia.</p>
@endforelse
</div>
<!-- Description -->
<div class="border-b border-outline-variant pb-md">
<h2 class="font-headline-md text-headline-md text-primary mb-4">Deskripsi</h2>
<p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    {{ $boardingHouse['description'] }}
                </p>
<button class="mt-4 text-secondary font-label-md text-label-md font-bold hover:underline">Baca Selengkapnya</button>
</div>
<!-- Location Map Placeholder -->
<div>
<h2 class="font-headline-md text-headline-md text-primary mb-4">Lokasi</h2>
<div class="w-full h-[300px] bg-surface-variant rounded-xl flex flex-col items-center justify-center overflow-hidden border border-outline-variant p-md text-center">
<span class="material-symbols-outlined text-5xl text-secondary mb-sm">location_on</span>
<p class="font-label-md text-label-md text-primary">{{ $boardingHouse['address'] }}</p>
<p class="font-body-md text-body-md text-on-surface-variant mt-1">{{ $boardingHouse['city'] }}, {{ $boardingHouse['province'] }}</p>
@if (!empty($boardingHouse['latitude']) && !empty($boardingHouse['longitude']))
<p class="font-label-sm text-label-sm text-on-surface-variant mt-2">{{ $boardingHouse['latitude'] }}, {{ $boardingHouse['longitude'] }}</p>
@endif
</div>
</div>
</div>
<!-- Right Column: Booking Widget -->
<div class="col-span-1 md:col-span-4 relative">
<div class="sticky top-28 bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm p-md flex flex-col gap-6">
<div class="flex justify-between items-end border-b border-outline-variant pb-4">
<div>
<p class="font-label-sm text-label-sm text-on-surface-variant mb-1">Harga per bulan</p>
<p class="font-headline-lg text-headline-lg text-primary"><span data-room-price>{{ $displayPrice }}</span><span class="font-body-md text-body-md text-on-surface-variant font-normal"> / bulan</span></p>
</div>
</div>
<div class="flex flex-col gap-4">
<div class="border border-outline-variant rounded-lg p-3 hover:border-secondary transition-colors cursor-pointer">
<label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Tipe Kamar</label>
<select class="w-full bg-transparent border-none p-0 focus:ring-0 font-body-md text-body-md text-primary cursor-pointer" data-room-selector>
@forelse ($rooms as $room)
<option value="{{ $room['id'] }}" data-price="{{ $room['price_formatted'] }}">
{{ $room['type_name'] }}
</option>
@empty
<option data-price="Hubungi pemilik">Hubungi pemilik</option>
@endforelse
</select>
</div>
</div>
<div class="flex flex-col gap-3">
<a class="w-full bg-secondary text-on-secondary font-label-md text-label-md font-bold py-3 rounded-lg hover:bg-secondary/90 transition-colors flex justify-center items-center gap-2" href="{{ route('ux2.booking.show', $boardingHouse['id']) }}" data-booking-link data-booking-base-url="{{ route('ux2.booking.show', $boardingHouse['id']) }}">
                        Ajukan Sewa
                    </a>
<button class="w-full bg-surface text-primary border border-primary font-label-md text-label-md font-bold py-3 rounded-lg hover:bg-surface-variant transition-colors flex justify-center items-center gap-2">
<span class="material-symbols-outlined text-sm">chat</span> Tanya Pemilik
                    </button>
</div>
<div class="bg-surface-container-low p-4 rounded-lg flex items-start gap-3 border border-outline-variant/50">
<span class="material-symbols-outlined text-secondary mt-1">verified_user</span>
<div>
<p class="font-label-md text-label-md font-bold text-primary">KosKu Verified</p>
<p class="font-body-md text-body-md text-sm text-on-surface-variant">Properti ini telah diverifikasi dan dikelola secara profesional.</p>
</div>
</div>
</div>
</div>
</main>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const roomSelector = document.querySelector('[data-room-selector]');
    const roomPrice = document.querySelector('[data-room-price]');
    const bookingLink = document.querySelector('[data-booking-link]');

    if (!roomSelector || !roomPrice) {
        return;
    }

    const syncRoomPrice = () => {
        const selectedRoom = roomSelector.options[roomSelector.selectedIndex];
        roomPrice.textContent = selectedRoom?.dataset.price || 'Hubungi pemilik';

        if (bookingLink && selectedRoom?.value) {
            const bookingUrl = new URL(bookingLink.dataset.bookingBaseUrl, window.location.origin);
            bookingUrl.searchParams.set('room_id', selectedRoom.value);
            bookingLink.href = bookingUrl.toString();
        }
    };

    roomSelector.addEventListener('change', syncRoomPrice);
    syncRoomPrice();
});
</script>
@endsection
