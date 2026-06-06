@extends('layouts.ux2.app')

@section('title', 'Booking Kos - KosKu')

@section('styles')
<style>
    /* Custom radio checked styling */
    input[type="radio"]:checked + .room-card {
        border-color: #006c49;
        background-color: rgba(0, 108, 73, 0.05);
    }
    input[type="radio"]:checked + .room-card .radio-dot {
        border-color: #006c49;
        background-color: #006c49;
    }
    input[type="radio"]:checked + .room-card .radio-dot .check-icon {
        display: block;
    }
    input[type="radio"]:checked + .duration-card {
        border-color: #006c49;
        background-color: rgba(0, 108, 73, 0.05);
    }
</style>
@endsection

@section('content')
@php
    $rooms = collect($boardingHouse['rooms'] ?? []);
    $firstRoom = $rooms->first();
    $requestedRoomId = old('room_id', request('room_id'));
    $selectedRoomId = $rooms->contains(fn ($room) => $room['id'] === $requestedRoomId && $room['is_available'])
        ? $requestedRoomId
        : ($rooms->firstWhere('is_available', true)['id'] ?? $firstRoom['id'] ?? null);
@endphp

<main class="max-w-[960px] mx-auto px-margin-mobile md:px-margin-desktop py-lg">

    <!-- Flash Messages -->
    @if(session('error'))
    <div class="mb-6 bg-error-container text-on-error-container p-4 rounded-xl flex items-center gap-3 border border-error/20">
        <span class="material-symbols-outlined">error</span>
        <p class="font-body-md text-body-md">{{ session('error') }}</p>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 bg-secondary-container text-on-secondary-container p-4 rounded-xl flex items-center gap-3 border border-secondary/20">
        <span class="material-symbols-outlined">check_circle</span>
        <p class="font-body-md text-body-md">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Breadcrumbs -->
    <div class="mb-md flex items-center gap-2 text-on-surface-variant font-label-md text-label-md">
        <a class="hover:text-primary transition-colors" href="{{ route('ux2.search') }}">Cari Kos</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <a class="hover:text-primary transition-colors" href="{{ route('ux2.kos.show', $boardingHouse['id']) }}">{{ $boardingHouse['name'] }}</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="text-primary font-semibold">Booking</span>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('ux2.kos.show', $boardingHouse['id']) }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary mb-4 transition-colors group">
            <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
            <span class="font-label-md text-label-md font-semibold">Kembali ke Detail Kos</span>
        </a>
        <h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary mb-2">Booking Kos</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">{{ $boardingHouse['name'] }} — {{ $boardingHouse['address'] }}, {{ $boardingHouse['city'] }}</p>
    </div>

    <form action="{{ route('ux2.booking.store') }}" method="POST" class="space-y-6" id="bookingForm">
        @csrf
        <input type="hidden" name="boarding_house_id" value="{{ $boardingHouse['id'] }}">

        <!-- Pilih Kamar -->
        <div class="bg-surface-container-lowest rounded-xl p-6 md:p-8 shadow-sm border border-outline-variant">
            <h2 class="font-headline-md text-headline-md text-primary mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">bed</span>
                Pilih Tipe Kamar
            </h2>
            <div class="space-y-4">
                @foreach($boardingHouse['rooms'] as $index => $room)
                <label class="relative flex items-center gap-4 cursor-pointer {{ !$room['is_available'] ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <input type="radio" name="room_id" value="{{ $room['id'] }}" class="sr-only peer"
                           data-price="{{ $room['price_per_month'] }}"
                           data-name="{{ $room['type_name'] }}"
                           required
                           {{ $room['id'] === $selectedRoomId ? 'checked' : '' }}
                           {{ !$room['is_available'] ? 'disabled' : '' }}>
                    <div class="room-card flex-1 flex items-center gap-4 p-4 border-2 border-outline-variant rounded-xl transition-all hover:border-outline {{ !$room['is_available'] ? '' : 'hover:shadow-sm' }}">
                        @if($room['image_url'])
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
                            <img src="{{ $room['image_url'] }}" alt="{{ $room['type_name'] }}" class="w-full h-full object-cover">
                        </div>
                        @else
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-lg bg-surface-variant flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-on-surface-variant text-3xl">meeting_room</span>
                        </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h3 class="font-label-md text-label-md font-bold text-on-surface text-base">{{ $room['type_name'] }}</h3>
                                @if($room['is_available'])
                                    <span class="bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-full font-label-sm text-label-sm">{{ $room['stock'] }} tersedia</span>
                                @else
                                    <span class="bg-error-container text-on-error-container px-2 py-0.5 rounded-full font-label-sm text-label-sm">Penuh</span>
                                @endif
                            </div>
                            @if($room['size'])
                            <p class="font-body-md text-body-md text-sm text-on-surface-variant mb-1">{{ $room['size'] }}</p>
                            @endif
                            <p class="font-headline-md text-headline-md text-lg font-bold text-primary">{{ $room['price_formatted'] }}<span class="text-sm font-normal text-on-surface-variant"> /bulan</span></p>
                        </div>

                        <div class="radio-dot w-6 h-6 rounded-full border-2 border-outline-variant flex items-center justify-center flex-shrink-0 transition-colors">
                            <span class="check-icon material-symbols-outlined text-on-secondary text-[16px] hidden" style="font-variation-settings: 'FILL' 1;">check</span>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('room_id')
            <div class="mt-3 flex items-center gap-2 text-error">
                <span class="material-symbols-outlined text-[16px]">error</span>
                <span class="font-label-md text-label-md">{{ $message }}</span>
            </div>
            @enderror
        </div>

        <!-- Durasi Sewa -->
        <div class="bg-surface-container-lowest rounded-xl p-6 md:p-8 shadow-sm border border-outline-variant">
            <h2 class="font-headline-md text-headline-md text-primary mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">calendar_month</span>
                Durasi Sewa
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([1, 3, 6, 12] as $months)
                <label class="relative cursor-pointer">
                    <input type="radio" name="duration_months" value="{{ $months }}" class="sr-only peer" required {{ $months === 6 ? 'checked' : '' }}>
                    <div class="duration-card p-4 border-2 border-outline-variant rounded-xl text-center transition-all hover:border-outline hover:shadow-sm">
                        <p class="font-headline-lg text-headline-md font-bold text-on-surface">{{ $months }}</p>
                        <p class="font-body-md text-body-md text-sm text-on-surface-variant">{{ $months === 1 ? 'Bulan' : 'Bulan' }}</p>
                        @if($months === 6)
                        <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-secondary text-on-secondary px-2 py-0.5 rounded-full font-label-sm text-label-sm text-[10px]">Populer</span>
                        @endif
                        @if($months === 12)
                        <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-on-tertiary-container text-on-tertiary px-2 py-0.5 rounded-full font-label-sm text-label-sm text-[10px]">Hemat</span>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>
            @error('duration_months')
            <div class="mt-3 flex items-center gap-2 text-error">
                <span class="material-symbols-outlined text-[16px]">error</span>
                <span class="font-label-md text-label-md">{{ $message }}</span>
            </div>
            @enderror
        </div>

        <!-- Tanggal Mulai -->
        <div class="bg-surface-container-lowest rounded-xl p-6 md:p-8 shadow-sm border border-outline-variant">
            <h2 class="font-headline-md text-headline-md text-primary mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">event</span>
                Tanggal Mulai Sewa
            </h2>
            <input type="date" name="start_date" id="startDateInput"
                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                   value="{{ request('start_date', date('Y-m-d', strtotime('+7 days'))) }}"
                   class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 text-on-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition-all"
                   required>
            <p class="mt-2 font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">info</span>
                Tanggal mulai minimal 1 hari dari sekarang
            </p>
            @error('start_date')
            <div class="mt-3 flex items-center gap-2 text-error">
                <span class="material-symbols-outlined text-[16px]">error</span>
                <span class="font-label-md text-label-md">{{ $message }}</span>
            </div>
            @enderror
        </div>



        <!-- Submit Buttons -->
        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ route('ux2.kos.show', $boardingHouse['id']) }}"
               class="flex-1 bg-surface-container text-on-surface font-label-md text-label-md font-bold py-4 rounded-xl hover:bg-surface-container-high transition-colors text-center flex justify-center items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">close</span>
                Batal
            </a>
            <button type="submit" id="submitBtn"
                    class="flex-1 bg-secondary text-on-secondary font-label-md text-label-md font-bold py-4 rounded-xl hover:bg-secondary/90 transition-all shadow-sm flex justify-center items-center gap-2 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                Lanjut ke Pembayaran
            </button>
        </div>
    </form>

</main>

@endsection

@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomRadios = document.querySelectorAll('input[name="room_id"]');
    const durationRadios = document.querySelectorAll('input[name="duration_months"]');
    const submitBtn = document.getElementById('submitBtn');



    // Prevent double submit
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span> Memproses...';
    });
});
</script>
@endsection
