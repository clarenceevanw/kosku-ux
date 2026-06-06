@extends('layouts.ux2.app')

@section('title', 'Pembayaran - KosKu')

@section('content')
<div class="pt-24 pb-16 px-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('ux2.home') }}" class="w-10 h-10 rounded-full border border-outline-variant flex items-center justify-center text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary">Selesaikan Pembayaran</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Pilih metode pembayaran dan selesaikan transaksi Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Payment Methods -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 shadow-sm">
                <h2 class="font-headline-sm text-headline-sm font-semibold text-primary mb-6 border-b border-outline-variant/30 pb-4">Metode Pembayaran</h2>
                
                <div class="space-y-4">
                    <!-- Virtual Account -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden has-[:checked]:border-secondary has-[:checked]:bg-secondary-container/10 transition-all">
                        <label class="flex items-center gap-4 p-4 cursor-pointer">
                            <input type="radio" name="payment_method" value="bca_va" class="w-5 h-5 text-secondary focus:ring-secondary border-outline-variant" checked>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-label-md text-label-md font-bold text-primary">BCA Virtual Account</p>
                                    <span class="font-label-sm text-label-sm px-2 py-0.5 bg-surface-variant text-on-surface-variant rounded">Otomatis</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Verifikasi otomatis, bayar melalui ATM/M-Banking BCA</p>
                            </div>
                        </label>
                    </div>

                    <!-- E-Wallet -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden has-[:checked]:border-secondary has-[:checked]:bg-secondary-container/10 transition-all">
                        <label class="flex items-center gap-4 p-4 cursor-pointer">
                            <input type="radio" name="payment_method" value="gopay" class="w-5 h-5 text-secondary focus:ring-secondary border-outline-variant">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-label-md text-label-md font-bold text-primary">GoPay</p>
                                    <span class="font-label-sm text-label-sm px-2 py-0.5 bg-surface-variant text-on-surface-variant rounded">Otomatis</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Buka aplikasi Gojek untuk menyelesaikan pembayaran</p>
                            </div>
                        </label>
                    </div>

                    <!-- QRIS -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden has-[:checked]:border-secondary has-[:checked]:bg-secondary-container/10 transition-all">
                        <label class="flex items-center gap-4 p-4 cursor-pointer">
                            <input type="radio" name="payment_method" value="qris" class="w-5 h-5 text-secondary focus:ring-secondary border-outline-variant">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-label-md text-label-md font-bold text-primary">QRIS</p>
                                    <span class="font-label-sm text-label-sm px-2 py-0.5 bg-surface-variant text-on-surface-variant rounded">Otomatis</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Scan QR dengan aplikasi e-wallet atau m-banking favorit Anda</p>
                            </div>
                        </label>
                    </div>

                    <!-- Manual Transfer -->
                    <div class="border border-outline-variant/50 rounded-xl overflow-hidden has-[:checked]:border-secondary has-[:checked]:bg-secondary-container/10 transition-all">
                        <label class="flex items-center gap-4 p-4 cursor-pointer">
                            <input type="radio" name="payment_method" value="manual" class="w-5 h-5 text-secondary focus:ring-secondary border-outline-variant">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="font-label-md text-label-md font-bold text-primary">Transfer Bank Manual</p>
                                    <span class="font-label-sm text-label-sm px-2 py-0.5 bg-surface-variant text-on-surface-variant rounded">Manual</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">Perlu upload bukti transfer setelah pembayaran</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="bg-surface-container border border-outline-variant/30 rounded-2xl p-4 flex gap-4 items-start">
                <span class="material-symbols-outlined text-secondary shrink-0">security</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Transaksi Anda dienkripsi dengan aman. KosKu tidak menyimpan informasi kartu atau kredensial perbankan Anda.</p>
            </div>
        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 shadow-sm sticky top-24">
                <h2 class="font-headline-sm text-headline-sm font-semibold text-primary mb-6 border-b border-outline-variant/30 pb-4">Ringkasan Biaya</h2>
                
                @php
                    $boardingHouse = $payment->contract->room->boardingHouse;
                    $room = $payment->contract->room;
                    $contract = $payment->contract;
                    $adminFee = 2000;
                    $total = $payment->amount + $adminFee;
                @endphp

                <div class="flex gap-4 mb-6">
                    <div class="w-20 h-20 rounded-xl bg-surface-variant shrink-0 overflow-hidden">
                        @if($room->image_url)
                            <img src="{{ $room->image_url }}" alt="{{ $room->type_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-secondary-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-secondary-container">home</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mb-1 uppercase tracking-wider">{{ $boardingHouse->category ?? 'Kos' }}</p>
                        <h3 class="font-headline-sm text-headline-sm font-bold text-primary leading-tight">{{ $boardingHouse->name }}</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Kamar {{ $room->type_name }}</p>
                    </div>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <p class="font-body-md text-body-md text-on-surface-variant">Biaya Sewa (Bulan {{ $payment->billing_month }})</p>
                        <p class="font-label-md text-label-md font-medium text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="font-body-md text-body-md text-on-surface-variant">Biaya Admin</p>
                        <p class="font-label-md text-label-md font-medium text-primary">Rp {{ number_format($adminFee, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="border-t border-outline-variant/30 pt-4 mb-8">
                    <div class="flex justify-between items-center">
                        <p class="font-label-md text-label-md font-bold text-primary">Total Pembayaran</p>
                        <p class="font-headline-md text-headline-md font-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</p>
                    </div>
                </div>

                <form action="{{ route('ux2.tenant.payment.process', $payment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="selected_payment_method" value="bca_va">
                    <button type="submit" class="w-full py-4 bg-primary text-on-primary rounded-xl font-label-md text-label-md font-bold hover:bg-inverse-surface transition-colors shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">lock</span>
                        Bayar Sekarang
                    </button>
                </form>
                
                <p class="font-label-sm text-label-sm text-on-surface-variant text-center mt-4">Dengan menekan tombol di atas, Anda menyetujui <a href="#" class="text-secondary hover:underline">Syarat & Ketentuan</a> KosKu.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const hiddenInput = document.getElementById('selected_payment_method');
        
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    hiddenInput.value = this.value;
                }
            });
        });
    });
</script>
@endsection
