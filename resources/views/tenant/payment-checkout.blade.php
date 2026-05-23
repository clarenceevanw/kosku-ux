@extends('layouts.checkout')

@section('title', 'Pembayaran Escrow')

@section('content')

{{-- Global Nav / Minimal Top Header for Checkout --}}
<nav class="bg-surface-container-lowest border-b border-outline-variant sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 md:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('tenant.payments') }}" class="text-on-surface-variant hover:text-on-surface transition-colors flex items-center justify-center p-2 rounded-full hover:bg-surface-container">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">arrow_back</span>
            </a>
            <div class="flex flex-col">
                <span class="font-headline text-lg font-semibold text-on-surface">Pembayaran</span>
                <span class="font-body text-sm text-on-surface-variant">{{ $payment->room->boardingHouse->name }} - {{ $payment->room->type_name }}</span>
            </div>
        </div>
        <div class="hidden md:flex items-center gap-2 bg-surface-container px-4 py-2 rounded-full">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">lock</span>
            <span class="font-label text-sm font-medium text-on-surface">Checkout Aman</span>
        </div>
    </div>
</nav>

<main class="flex-grow w-full max-w-7xl mx-auto px-4 md:px-8 py-8 md:py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Left Column: 3-Step Wizard --}}
        <div class="w-full lg:w-2/3 flex flex-col gap-8">
            {{-- Wizard Progress Indicator --}}
            <div class="flex items-center justify-between px-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-label text-sm font-bold">1</div>
                    <span class="font-label text-sm font-medium text-on-surface hidden sm:inline">Ringkasan</span>
                </div>
                <div class="flex-grow border-t border-outline-variant mx-4"></div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-surface-container text-on-surface flex items-center justify-center font-label text-sm font-bold border border-outline">2</div>
                    <span class="font-label text-sm font-medium text-on-surface hidden sm:inline">Metode</span>
                </div>
                <div class="flex-grow border-t border-outline-variant mx-4"></div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-surface-container text-outline flex items-center justify-center font-label text-sm font-bold border border-outline-variant">3</div>
                    <span class="font-label text-sm font-medium text-outline hidden sm:inline">Konfirmasi</span>
                </div>
            </div>

            {{-- Step 1: Summary --}}
            <section class="bg-surface-container-lowest rounded-[2rem] p-6 md:p-8 shadow-sm border border-outline-variant relative overflow-hidden">
                <div class="flex justify-between items-start mb-8">
                    <h2 class="font-headline text-2xl font-semibold text-on-surface">Rincian Tagihan</h2>
                    @if($payment->billing_month)
                    <div class="inline-flex items-center gap-2 bg-primary/10 text-primary rounded-full px-4 py-2 border border-primary/30">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
                        <span class="font-label text-xs font-semibold tracking-wide uppercase">Bulan ke-{{ $payment->billing_month }}</span>
                    </div>
                    @endif
                </div>
                <div class="space-y-6">
                    @if($payment->billing_month === 1)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-600 mt-0.5" style="font-variation-settings: 'FILL' 1;">info</span>
                            <div>
                                <p class="font-label text-sm font-semibold text-blue-900 mb-1">Pembayaran Bulan Pertama</p>
                                <p class="font-body text-sm text-blue-700">Anda hanya perlu membayar sewa bulan pertama saat ini. Tagihan bulan berikutnya akan muncul di tab Tagihan sesuai jadwal.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="flex justify-between items-center pb-4 border-b border-outline-variant">
                        <div class="flex flex-col">
                            <span class="font-body text-base text-on-surface-variant">Sewa Bulan {{ $payment->billing_month ?? 1 }}</span>
                            @if($payment->start_date && $payment->end_date)
                            <span class="font-body text-sm text-outline mt-1">
                                {{ $payment->start_date->translatedFormat('d M Y') }} - {{ $payment->end_date->translatedFormat('d M Y') }}
                            </span>
                            @endif
                        </div>
                        <span class="font-label text-base font-semibold text-on-surface">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-outline-variant">
                        <span class="font-body text-base text-on-surface-variant">Biaya Admin Platform</span>
                        <span class="font-label text-base font-semibold text-on-surface">Rp 2.000</span>
                    </div>
                    <div class="flex justify-between items-center pt-4">
                        <span class="font-headline text-xl font-semibold text-on-surface">Total Pembayaran</span>
                        <span class="font-headline text-3xl font-bold text-primary">Rp {{ number_format($payment->total_amount + 2000, 0, ',', '.') }}</span>
                    </div>
                    @if($payment->contract)
                    <div class="bg-surface-container p-4 rounded-xl flex gap-4 items-start mt-6 border border-outline-variant">
                        <span class="material-symbols-outlined text-primary mt-0.5" style="font-variation-settings: 'FILL' 1;">info</span>
                        <div class="font-body text-sm text-on-surface-variant">
                            <p class="mb-2"><strong>Informasi Kontrak:</strong></p>
                            <ul class="space-y-1 list-disc list-inside">
                                <li>Periode sewa: {{ $payment->contract->start_date->translatedFormat('d M Y') }} - {{ $payment->contract->end_date->translatedFormat('d M Y') }}</li>
                                <li>Durasi: {{ $payment->contract->start_date->diffInMonths($payment->contract->end_date) }} bulan</li>
                                <li>Pembayaran bulanan: Rp {{ number_format($payment->contract->monthly_fee, 0, ',', '.') }}/bulan</li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </section>

            {{-- Step 2: Payment Methods (Bento Grid) --}}
            <section class="bg-surface-container-lowest rounded-[2rem] p-6 md:p-8 shadow-sm border border-outline-variant">
                <h2 class="font-headline text-2xl font-semibold text-on-surface mb-4">Pilih Metode Pembayaran</h2>
                <p class="font-body text-sm text-on-surface-variant mb-8">Pilih metode yang paling nyaman untuk Anda.</p>
                <form id="paymentForm" action="{{ route('tenant.payment.process', $payment->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- VA Group --}}
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="font-label text-sm font-medium text-on-surface mb-3 uppercase tracking-wider">Virtual Account</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="relative flex items-center justify-between p-4 border-2 border-outline-variant hover:border-outline rounded-xl cursor-pointer transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-8 bg-surface-container-lowest border border-outline-variant rounded flex items-center justify-center font-bold text-[#1D4ED8] text-xs">BCA</div>
                                        <span class="font-body text-base text-on-surface font-medium">BCA Virtual Account</span>
                                    </div>
                                    <input checked class="w-5 h-5 text-primary border-outline-variant focus:ring-primary" name="payment_method" type="radio" value="bca_va">
                                </label>
                                <label class="relative flex items-center justify-between p-4 border-2 border-outline-variant hover:border-outline rounded-xl cursor-pointer transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-8 bg-surface-container-lowest border border-outline-variant rounded flex items-center justify-center font-bold text-[#B45309] text-xs">MANDIRI</div>
                                        <span class="font-body text-base text-on-surface font-medium">Mandiri VA</span>
                                    </div>
                                    <input class="w-5 h-5 text-primary border-outline-variant focus:ring-primary" name="payment_method" type="radio" value="mandiri_va">
                                </label>
                            </div>
                        </div>
                        {{-- E-Wallet & QRIS --}}
                        <div class="col-span-1 mt-4">
                            <h3 class="font-label text-sm font-medium text-on-surface mb-3 uppercase tracking-wider">E-Wallet</h3>
                            <div class="flex flex-col gap-4">
                                <label class="relative flex items-center justify-between p-4 border-2 border-outline-variant hover:border-outline rounded-xl cursor-pointer transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 bg-[#15803D] text-white rounded-full flex items-center justify-center font-bold text-xs">G</div>
                                        <span class="font-body text-base text-on-surface font-medium">GoPay</span>
                                    </div>
                                    <input class="w-5 h-5 text-primary border-outline-variant focus:ring-primary" name="payment_method" type="radio" value="gopay">
                                </label>
                            </div>
                        </div>
                        <div class="col-span-1 mt-4">
                            <h3 class="font-label text-sm font-medium text-on-surface mb-3 uppercase tracking-wider">Instan</h3>
                            <label class="relative flex items-center justify-between p-4 border-2 border-outline-variant hover:border-outline rounded-xl cursor-pointer transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 bg-surface-container rounded-lg flex items-center justify-center">
                                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">qr_code_scanner</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-body text-base text-on-surface font-medium">QRIS</span>
                                        <span class="font-body text-sm text-on-surface-variant">Scan via aplikasi banking</span>
                                    </div>
                                </div>
                                <input class="w-5 h-5 text-primary border-outline-variant focus:ring-primary" name="payment_method" type="radio" value="qris">
                            </label>
                        </div>
                    </div>
                </form>
            </section>

            {{-- Step 3: Escrow Timeline (Visual Education) --}}
            <section class="bg-surface-container-lowest rounded-[2rem] p-6 md:p-8 shadow-sm border border-outline-variant">
                <h2 class="font-headline text-2xl font-semibold text-on-surface mb-8">Bagaimana Escrow Melindungi Anda?</h2>
                <div class="relative pl-8 border-l border-outline-variant space-y-8 py-2 ml-2">
                    <div class="relative">
                        <div class="absolute -left-[33px] top-0 w-6 h-6 rounded-full bg-primary flex items-center justify-center border-[3px] border-surface-container-lowest shadow-sm">
                            <span class="material-symbols-outlined text-on-primary text-[12px]" style="font-variation-settings: 'FILL' 1;">check</span>
                        </div>
                        <h4 class="font-label text-sm font-medium text-on-surface mb-1">1. Anda Membayar</h4>
                        <p class="font-body text-sm text-on-surface-variant">Transfer dana sesuai tagihan melalui metode pilihan Anda.</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[33px] top-0 w-6 h-6 rounded-full bg-surface-container flex items-center justify-center border-[3px] border-surface-container-lowest shadow-sm">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                        </div>
                        <h4 class="font-label text-sm font-medium text-on-surface mb-1">2. Dana Ditahan Aman (Escrow)</h4>
                        <p class="font-body text-sm text-on-surface-variant">KosKu menyimpan dana Anda di rekening bersama. Uang tidak langsung diberikan ke pemilik.</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[33px] top-0 w-6 h-6 rounded-full bg-surface-container flex items-center justify-center border-[3px] border-surface-container-lowest shadow-sm">
                            <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                        </div>
                        <h4 class="font-label text-sm font-medium text-on-surface mb-1">3. Anda Check-in &amp; Konfirmasi</h4>
                        <p class="font-body text-sm text-on-surface-variant">Datang ke lokasi, pastikan kamar sesuai. Lakukan konfirmasi di aplikasi.</p>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[33px] top-0 w-6 h-6 rounded-full bg-surface-container flex items-center justify-center border-[3px] border-surface-container-lowest shadow-sm">
                            <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                        </div>
                        <h4 class="font-label text-sm font-medium text-on-surface mb-1">4. Dana Diteruskan</h4>
                        <p class="font-body text-sm text-on-surface-variant">Hanya setelah Anda setuju, dana baru diteruskan ke pemilik kos.</p>
                    </div>
                </div>
            </section>
        </div>

        {{-- Right Column: Sticky Action Card --}}
        <div class="w-full lg:w-1/3">
            <div class="sticky top-[88px] bg-surface-container-lowest rounded-[2rem] shadow-sm border border-outline-variant p-6 md:p-8 flex flex-col gap-6">
                <div>
                    <h3 class="font-headline text-xl font-semibold text-on-surface mb-1">{{ $payment->room->boardingHouse->name }}</h3>
                    <p class="font-body text-sm text-on-surface-variant mb-4">{{ $payment->room->type_name }} • Mulai {{ $payment->contract ? $payment->contract->start_date->translatedFormat('d M Y') : $payment->start_date->translatedFormat('d M Y') }}</p>
                    @if($payment->billing_month)
                    <div class="bg-primary/10 border border-primary/30 rounded-lg p-3 mb-4">
                        <p class="font-label text-xs text-primary font-semibold mb-1">TAGIHAN BULAN KE-{{ $payment->billing_month }}</p>
                        <p class="font-body text-xs text-on-surface-variant">
                            Periode: {{ $payment->start_date->translatedFormat('d M') }} - {{ $payment->end_date->translatedFormat('d M Y') }}
                        </p>
                    </div>
                    @endif
                    <div class="flex items-center gap-3 bg-surface-container p-3 rounded-xl border border-outline-variant">
                        <span class="material-symbols-outlined text-on-surface-variant">account_balance_wallet</span>
                        <span class="font-label text-sm font-medium text-on-surface" id="selectedMethod">BCA Virtual Account</span>
                    </div>
                </div>
                <div class="border-t border-outline-variant pt-6">
                    <div class="flex justify-between items-end mb-2">
                        <span class="font-body text-lg text-on-surface-variant">Total Bayar</span>
                        <span class="font-headline text-3xl font-bold text-primary">Rp {{ number_format($payment->total_amount + 2000, 0, ',', '.') }}</span>
                    </div>
                    <p class="font-body text-sm text-outline text-right">Termasuk admin Rp 2.000</p>
                </div>
                <button type="submit" form="paymentForm" class="w-full bg-primary hover:bg-inverse-surface text-on-primary py-4 rounded-xl font-label text-sm font-bold text-center transition-colors shadow-sm flex items-center justify-center gap-2">
                    Lanjut Bayar
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
                <div class="flex items-center justify-center gap-2 text-outline mt-2">
                    <span class="material-symbols-outlined text-[16px]">lock</span>
                    <span class="font-label text-xs font-medium">Transaksi Aman &amp; Terenkripsi 256-bit</span>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const methodNames = {
            'bca_va': 'BCA Virtual Account',
            'mandiri_va': 'Mandiri VA',
            'gopay': 'GoPay',
            'qris': 'QRIS'
        };
        document.getElementById('selectedMethod').textContent = methodNames[this.value] || this.value;
    });
});
</script>

@endsection
