@extends('layouts.app')

@section('content')
<main class="pt-20 pb-16 bg-background min-h-screen">
    <div class="max-w-5xl mx-auto px-5 md:px-8">
        
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('kos.show', $boardingHouse['id']) }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary mb-4 transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                <span class="font-label text-sm font-semibold">Kembali ke Detail Kos</span>
            </a>
            <h1 class="font-headline text-3xl md:text-4xl font-bold text-on-surface mb-2">Booking Kos</h1>
            <p class="font-body text-base text-on-surface-variant">{{ $boardingHouse['name'] }}</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500 mt-0.5 flex-shrink-0" style="font-variation-settings: 'FILL' 1;">error</span>
                <p class="font-body text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                <span class="material-symbols-outlined text-green-500 mt-0.5 flex-shrink-0" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <p class="font-body text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('booking.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="boarding_house_id" value="{{ $boardingHouse['id'] }}">

            {{-- Pilih Kamar --}}
            <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/50">
                <h2 class="font-headline text-xl font-semibold text-on-surface mb-6">Pilih Tipe Kamar</h2>
                <div class="space-y-4">
                    @foreach($boardingHouse['rooms'] as $room)
                    <label class="relative flex items-center gap-4 p-4 border-2 {{ !$room['is_available'] ? 'border-outline-variant/50 opacity-50 cursor-not-allowed' : 'border-outline-variant hover:border-outline cursor-pointer' }} rounded-xl transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                        <input type="radio" name="room_id" value="{{ $room['id'] }}" class="sr-only peer" required 
                               data-price="{{ $room['price_per_month'] }}"
                               data-name="{{ $room['type_name'] }}"
                               {{ !$room['is_available'] ? 'disabled' : '' }}>
                        
                        @if($room['image_url'])
                        <div class="w-20 h-20 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
                            <img src="{{ $room['image_url'] }}" alt="{{ $room['type_name'] }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-headline text-base font-semibold text-on-surface">{{ $room['type_name'] }}</h3>
                                @if($room['is_available'])
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">{{ $room['stock'] }} tersedia</span>
                                @else
                                    <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-xs font-bold">Penuh</span>
                                @endif
                            </div>
                            @if($room['size'])
                            <p class="font-body text-sm text-on-surface-variant mb-2">{{ $room['size'] }}</p>
                            @endif
                            <p class="font-headline text-lg font-bold text-primary">{{ $room['price_formatted'] }}<span class="text-sm font-normal text-on-surface-variant">/bulan</span></p>
                        </div>
                        
                        <div class="w-6 h-6 rounded-full border-2 {{ !$room['is_available'] ? 'border-outline-variant/50' : 'border-outline-variant peer-checked:border-primary peer-checked:bg-primary' }} flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-on-primary text-[16px] hidden peer-checked:block" style="font-variation-settings: 'FILL' 1;">check</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('room_id') <span class="text-error text-sm font-semibold mt-2 block">{{ $message }}</span> @enderror
            </div>

            {{-- Durasi Sewa --}}
            <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/50">
                <h2 class="font-headline text-xl font-semibold text-on-surface mb-6">Durasi Sewa</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach([1, 3, 6, 12] as $months)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="duration_months" value="{{ $months }}" class="sr-only peer" required {{ $months === 6 ? 'checked' : '' }}>
                        <div class="p-4 border-2 border-outline-variant peer-checked:border-primary peer-checked:bg-primary/5 rounded-xl text-center transition-all hover:border-outline">
                            <p class="font-headline text-2xl font-bold text-on-surface">{{ $months }}</p>
                            <p class="font-body text-sm text-on-surface-variant">Bulan</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('duration_months') <span class="text-error text-sm font-semibold mt-2 block">{{ $message }}</span> @enderror
            </div>

            {{-- Tanggal Mulai --}}
            <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/50">
                <h2 class="font-headline text-xl font-semibold text-on-surface mb-6">Tanggal Mulai Sewa</h2>
                <input type="date" name="start_date" id="startDateInput"
                       min="{{ date('Y-m-d') }}"
                       value="{{ request('start_date', date('Y-m-d', strtotime('+7 days'))) }}"
                       class="w-full bg-surface-container-low border border-outline-variant rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                       required>
                @error('start_date') <span class="text-error text-sm font-semibold mt-2 block">{{ $message }}</span> @enderror
            </div>

            {{-- Ringkasan Biaya --}}
            <div class="bg-surface-container rounded-2xl p-6 md:p-8 shadow-sm border border-outline-variant/50">
                <h2 class="font-headline text-xl font-semibold text-on-surface mb-6">Ringkasan Biaya</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="font-body text-base text-on-surface-variant">Kamar</span>
                        <span class="font-label text-base font-semibold text-on-surface" id="selectedRoom">Pilih kamar</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-body text-base text-on-surface-variant">Harga per bulan</span>
                        <span class="font-label text-base font-semibold text-on-surface" id="pricePerMonth">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-body text-base text-on-surface-variant">Durasi</span>
                        <span class="font-label text-base font-semibold text-on-surface" id="duration">6 bulan</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-outline-variant">
                        <span class="font-headline text-lg font-semibold text-on-surface">Total Biaya Sewa</span>
                        <span class="font-headline text-2xl font-bold text-primary" id="totalCost">Rp 0</span>
                    </div>
                    <div class="bg-surface-container-low p-4 rounded-xl">
                        <p class="font-body text-sm text-on-surface-variant">
                            <span class="material-symbols-outlined text-primary text-[16px] align-middle" style="font-variation-settings: 'FILL' 1;">info</span>
                            Anda akan membayar <strong>sewa bulan pertama saja</strong> saat booking. Tagihan bulan berikutnya akan muncul di tab Tagihan sesuai jadwal.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex gap-4">
                <a href="{{ route('kos.show', $boardingHouse['id']) }}" class="flex-1 bg-surface-container text-on-surface font-label text-base font-semibold py-4 rounded-xl hover:bg-surface-container-high transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-primary text-on-primary font-label text-base font-semibold py-4 rounded-xl hover:bg-inverse-surface transition-colors shadow-sm">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </form>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomRadios = document.querySelectorAll('input[name="room_id"]');
    const durationRadios = document.querySelectorAll('input[name="duration_months"]');
    
    function updateSummary() {
        const selectedRoom = document.querySelector('input[name="room_id"]:checked');
        const selectedDuration = document.querySelector('input[name="duration_months"]:checked');
        
        if (selectedRoom && selectedDuration) {
            const price = parseInt(selectedRoom.dataset.price);
            const roomName = selectedRoom.dataset.name;
            const months = parseInt(selectedDuration.value);
            const total = price * months;
            
            document.getElementById('selectedRoom').textContent = roomName;
            document.getElementById('pricePerMonth').textContent = 'Rp ' + price.toLocaleString('id-ID');
            document.getElementById('duration').textContent = months + ' bulan';
            document.getElementById('totalCost').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    }
    
    roomRadios.forEach(radio => radio.addEventListener('change', updateSummary));
    durationRadios.forEach(radio => radio.addEventListener('change', updateSummary));
    
    // Initial update
    updateSummary();
});
</script>
@endsection
