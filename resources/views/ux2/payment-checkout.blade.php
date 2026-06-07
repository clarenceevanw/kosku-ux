@extends('layouts.ux2.app')

@section('title', 'Pembayaran - KosKu')

@section('styles')
<style>
    /* ── ANIMATIONS ──────────────────────────── */
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fade-in {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes slide-right {
        from { opacity: 0; transform: translateX(-18px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes scale-pop {
        from { opacity: 0; transform: scale(0.9); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes pulse-lock {
        0%, 100% { box-shadow: 0 0 0 0 rgba(20,60,58,0.35); }
        50%       { box-shadow: 0 0 0 8px rgba(20,60,58,0); }
    }

    .anim-fade-up   { animation: fade-up    0.55s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-fade-in   { animation: fade-in    0.4s  ease both; }
    .anim-slide-r   { animation: slide-right 0.5s cubic-bezier(.22,.68,0,1.1) both; }
    .anim-scale-pop { animation: scale-pop  0.5s cubic-bezier(.22,.68,0,1.2) both; }

    .d1 { animation-delay: .06s; }
    .d2 { animation-delay: .13s; }
    .d3 { animation-delay: .20s; }
    .d4 { animation-delay: .27s; }
    .d5 { animation-delay: .34s; }

    /* ── SCROLL REVEAL ───────────────────────── */
    .reveal {
        opacity: 0; transform: translateY(20px);
        transition: opacity .5s cubic-bezier(.22,.68,0,1.1), transform .5s cubic-bezier(.22,.68,0,1.1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .rev-d1 { transition-delay: .08s; }
    .rev-d2 { transition-delay: .16s; }

    /* ── STEP INDICATOR ──────────────────────── */
    .step-bubble {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 14px; flex-shrink: 0;
        transition: all 0.3s ease;
    }
    .step-line {
        flex: 1; height: 2px; border-radius: 2px;
    }

    /* ── PAYMENT METHOD CARDS ────────────────── */
    .payment-method-card {
        border: 1.5px solid var(--ux2-line);
        border-radius: 14px; overflow: hidden;
        transition: border-color .22s ease, background .22s ease, box-shadow .22s ease, transform .22s ease;
    }
    .payment-method-card:hover {
        border-color: var(--ux2-secondary);
        transform: translateX(3px);
    }
    .payment-method-card:has(input:checked) {
        border-color: var(--ux2-secondary);
        background: var(--ux2-primary-soft);
        box-shadow: 0 0 0 3px rgba(47,143,121,0.15);
    }
    .payment-method-card:has(input:checked) .method-icon-wrap {
        background: var(--ux2-primary) !important;
    }
    .payment-method-card:has(input:checked) .method-icon-wrap span {
        color: #fff !important;
    }
    .payment-method-card:has(input:checked) .radio-ring {
        border-color: var(--ux2-secondary) !important;
        background: var(--ux2-secondary) !important;
    }
    .radio-ring {
        width: 20px; height: 20px; border-radius: 50%;
        border: 2px solid var(--ux2-line);
        display: flex; align-items: center; justify-content: center;
        transition: all .22s ease; flex-shrink: 0;
    }
    .radio-ring::after {
        content: '';
        width: 8px; height: 8px; border-radius: 50%;
        background: #fff;
        opacity: 0; transition: opacity .2s ease;
    }
    .payment-method-card:has(input:checked) .radio-ring::after { opacity: 1; }

    /* ── METHOD ICON WRAP ────────────────────── */
    .method-icon-wrap {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        transition: background .22s ease;
        flex-shrink: 0;
    }

    /* ── SECTION CARD ────────────────────────── */
    .checkout-section {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px;
        padding: 28px;
        box-shadow: var(--ux2-shadow-soft);
    }
    .section-header {
        display: flex; align-items: center; gap: 10px;
        padding-bottom: 18px; margin-bottom: 20px;
        border-bottom: 1px solid var(--ux2-line);
    }
    .section-num {
        width: 28px; height: 28px; border-radius: 50%;
        background: var(--ux2-primary-soft); color: var(--ux2-primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 800; flex-shrink: 0;
    }

    /* ── ORDER SUMMARY PANEL ─────────────────── */
    .summary-panel {
        background: #fff;
        border: 1px solid var(--ux2-line);
        border-radius: 16px; overflow: hidden;
        box-shadow: var(--ux2-shadow);
    }
    .summary-panel-header {
        background: linear-gradient(135deg, var(--ux2-primary), var(--ux2-primary-deep));
        padding: 22px 24px;
    }

    /* ── PAY BUTTON ──────────────────────────── */
    @keyframes shimmer {
        from { left: -80%; }
        to   { left: 140%; }
    }
    .pay-btn {
        position: relative; overflow: hidden;
        transition: background .22s ease, transform .12s ease, box-shadow .22s ease;
        animation: pulse-lock 2.5s ease 1.5s infinite;
    }
    .pay-btn::after {
        content: '';
        position: absolute; top: 0; left: -80%;
        width: 55%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
        transform: skewX(-18deg);
        animation: shimmer 3s ease-in-out 1.2s infinite;
    }
    .pay-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(20,60,58,0.28) !important;
        animation: none;
    }
    .pay-btn:active { transform: scale(0.98); animation: none; }

    /* ── PRICE ROW ───────────────────────────── */
    .price-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed var(--ux2-line);
    }
    .price-row:last-child { border-bottom: none; }

    /* ── SECURITY BADGE ──────────────────────── */
    @keyframes badge-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: .7; }
    }
    .security-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: var(--ux2-secondary);
        animation: badge-pulse 2s ease-in-out infinite;
    }
</style>
@endsection

@section('content')
@php
    $boardingHouse = $payment->contract->room->boardingHouse;
    $room          = $payment->contract->room;
    $contract      = $payment->contract;
    $adminFee      = 2000;
    $total         = $payment->amount + $adminFee;
@endphp

{{-- ════ HERO BANNER ════ --}}
<div class="w-full anim-fade-in" style="background:linear-gradient(135deg, var(--ux2-primary) 0%, var(--ux2-primary-deep) 100%);">
    <div class="max-w-[1440px] mx-auto px-margin-mobile md:px-margin-desktop py-lg">

        {{-- Back + title row --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-md">
            <div class="anim-fade-up d1">
                <a href="{{ route('ux2.home') }}"
                    class="inline-flex items-center gap-1 mb-sm group transition-colors"
                    style="color:rgba(255,255,255,0.65); font-size:13px; font-weight:600;"
                    onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">
                    <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                    Kembali ke Beranda
                </a>
                <h1 class="font-headline-lg text-headline-lg" style="color:#fff; line-height:1.15;">Selesaikan Pembayaran</h1>
                <p class="mt-1" style="color:rgba(255,255,255,0.7); font-size:14px;">
                    Pilih metode pembayaran dan selesaikan transaksi Anda.
                </p>
            </div>

            {{-- Step indicator —— step 2 active --}}
            <div class="flex items-center gap-2 anim-fade-up d3 md:min-w-[280px]">
                <div class="step-bubble" style="background:var(--ux2-secondary); color:#fff;">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1;">check</span>
                </div>
                <div class="step-line" style="background:var(--ux2-secondary);"></div>
                <div class="step-bubble" style="background:#fff; color:var(--ux2-primary);">2</div>
                <div class="step-line" style="background:rgba(255,255,255,0.3);"></div>
                <div class="step-bubble" style="border:2px solid rgba(255,255,255,0.35); color:rgba(255,255,255,0.6); background:rgba(255,255,255,0.12);">3</div>
                <div class="ml-2" style="color:rgba(255,255,255,0.7); font-size:12px; font-weight:600; white-space:nowrap;">Pilih &rarr; Bayar &rarr; Selesai</div>
            </div>
        </div>

    </div>
</div>

{{-- ════ MAIN CONTENT ════ --}}
<div class="max-w-[1440px] mx-auto px-margin-mobile md:px-margin-desktop py-lg">
    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_360px] gap-xl items-start">

        {{-- ══ LEFT: Payment Methods ══ --}}
        <div class="flex flex-col gap-lg">

            {{-- Payment method section --}}
            <div class="checkout-section reveal anim-fade-up d2">
                <div class="section-header">
                    <div class="section-num">1</div>
                    <div>
                        <h2 class="font-headline-md text-headline-md" style="color:var(--ux2-ink); font-size:20px;">Metode Pembayaran</h2>
                        <p style="font-size:12px; color:var(--ux2-muted);">Pilih cara pembayaran yang paling mudah untuk Anda</p>
                    </div>
                    <span class="material-symbols-outlined ml-auto" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">payments</span>
                </div>

                <div class="flex flex-col gap-3">

                    {{-- BCA Virtual Account --}}
                    <div class="payment-method-card">
                        <label class="flex items-center gap-4 p-md cursor-pointer">
                            <input type="radio" name="payment_method" value="bca_va"
                                class="sr-only" checked
                                onchange="document.getElementById('selected_payment_method').value=this.value">
                            <div class="method-icon-wrap" style="background:var(--ux2-primary-soft);">
                                <span class="material-symbols-outlined" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">account_balance</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">BCA Virtual Account</p>
                                    <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm"
                                        style="background:var(--ux2-secondary-soft); color:var(--ux2-primary); font-size:10px; font-weight:700;">Otomatis</span>
                                </div>
                                <p style="font-size:13px; color:var(--ux2-muted); margin-top:2px;">Bayar melalui ATM / M-Banking BCA</p>
                            </div>
                            <div class="radio-ring"></div>
                        </label>
                    </div>

                    {{-- GoPay --}}
                    <div class="payment-method-card">
                        <label class="flex items-center gap-4 p-md cursor-pointer">
                            <input type="radio" name="payment_method" value="gopay"
                                class="sr-only"
                                onchange="document.getElementById('selected_payment_method').value=this.value">
                            <div class="method-icon-wrap" style="background:var(--ux2-primary-soft);">
                                <span class="material-symbols-outlined" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">phone_iphone</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">GoPay</p>
                                    <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm"
                                        style="background:var(--ux2-secondary-soft); color:var(--ux2-primary); font-size:10px; font-weight:700;">Otomatis</span>
                                </div>
                                <p style="font-size:13px; color:var(--ux2-muted); margin-top:2px;">Buka aplikasi Gojek untuk menyelesaikan pembayaran</p>
                            </div>
                            <div class="radio-ring"></div>
                        </label>
                    </div>

                    {{-- QRIS --}}
                    <div class="payment-method-card">
                        <label class="flex items-center gap-4 p-md cursor-pointer">
                            <input type="radio" name="payment_method" value="qris"
                                class="sr-only"
                                onchange="document.getElementById('selected_payment_method').value=this.value">
                            <div class="method-icon-wrap" style="background:var(--ux2-primary-soft);">
                                <span class="material-symbols-outlined" style="color:var(--ux2-primary); font-variation-settings:'FILL' 1;">qr_code_scanner</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">QRIS</p>
                                    <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm"
                                        style="background:var(--ux2-secondary-soft); color:var(--ux2-primary); font-size:10px; font-weight:700;">Otomatis</span>
                                </div>
                                <p style="font-size:13px; color:var(--ux2-muted); margin-top:2px;">Scan QR dengan e-wallet atau m-banking favorit Anda</p>
                            </div>
                            <div class="radio-ring"></div>
                        </label>
                    </div>

                    {{-- Manual Transfer --}}
                    <div class="payment-method-card">
                        <label class="flex items-center gap-4 p-md cursor-pointer">
                            <input type="radio" name="payment_method" value="manual"
                                class="sr-only"
                                onchange="document.getElementById('selected_payment_method').value=this.value">
                            <div class="method-icon-wrap" style="background:var(--ux2-accent-soft);">
                                <span class="material-symbols-outlined" style="color:var(--ux2-ink); font-variation-settings:'FILL' 1;">receipt_long</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">Transfer Bank Manual</p>
                                    <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm"
                                        style="background:var(--ux2-accent-soft); color:var(--ux2-ink); font-size:10px; font-weight:700;">Manual</span>
                                </div>
                                <p style="font-size:13px; color:var(--ux2-muted); margin-top:2px;">Perlu upload bukti transfer setelah pembayaran</p>
                            </div>
                            <div class="radio-ring"></div>
                        </label>
                    </div>

                </div>
            </div>

            {{-- Security notice --}}
            <div class="reveal rev-d1 flex items-center gap-3 p-md rounded-xl"
                style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
                <div class="flex-shrink-0 flex flex-col items-center gap-1">
                    <span class="material-symbols-outlined" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1;">security</span>
                    <div class="security-dot"></div>
                </div>
                <div>
                    <p class="font-label-md text-label-md font-bold mb-1" style="color:var(--ux2-primary);">Transaksi Aman & Terenkripsi</p>
                    <p style="font-size:13px; color:var(--ux2-muted); line-height:1.5;">
                        KosKu tidak menyimpan informasi kartu atau kredensial perbankan Anda.
                    </p>
                </div>
            </div>

            {{-- CTA mobile --}}
            <div class="reveal rev-d2 lg:hidden">
                <form action="{{ route('ux2.tenant.payment.process', $payment->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="selected_payment_method_mobile" value="bca_va">
                    <button type="submit"
                        class="pay-btn w-full py-md font-label-md text-label-md font-bold rounded-xl flex items-center justify-center gap-2"
                        style="background:var(--ux2-primary); color:#fff;">
                        <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">lock</span>
                        Bayar Sekarang
                    </button>
                </form>
                <p class="text-center mt-sm" style="font-size:12px; color:var(--ux2-muted);">
                    Dengan menekan tombol di atas, Anda menyetujui
                    <a href="#" style="color:var(--ux2-secondary);" class="hover:underline">Syarat &amp; Ketentuan</a> KosKu.
                </p>
            </div>

        </div>{{-- end left --}}

        {{-- ══ RIGHT: Order Summary ══ --}}
        <div class="lg:sticky lg:top-28 anim-scale-pop d4">
            <div class="summary-panel">

                {{-- Header --}}
                <div class="summary-panel-header">
                    <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:rgba(255,255,255,0.6); margin-bottom:6px;">Ringkasan Biaya</p>
                    <div class="flex gap-3 items-start">
                        {{-- Room thumbnail --}}
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0" style="border:2px solid rgba(255,255,255,0.2);">
                            @if($room->image_url)
                                <img src="{{ $room->image_url }}" alt="{{ $room->type_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background:rgba(255,255,255,0.15);">
                                    <span class="material-symbols-outlined" style="color:#fff; font-variation-settings:'FILL' 1;">home</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p style="font-size:11px; color:rgba(255,255,255,0.6); text-transform:uppercase; letter-spacing:.05em;">{{ $boardingHouse->category ?? 'Kos' }}</p>
                            <h3 class="font-bold" style="color:#fff; font-size:16px; line-height:1.3; margin-top:2px;">{{ $boardingHouse->name }}</h3>
                            <p style="font-size:13px; color:rgba(255,255,255,0.65); margin-top:2px;">Kamar {{ $room->type_name }}</p>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-md flex flex-col gap-md">

                    {{-- Price breakdown --}}
                    <div class="flex flex-col">
                        <div class="price-row">
                            <p style="font-size:14px; color:var(--ux2-muted);">Biaya Sewa (Bulan {{ $payment->billing_month }})</p>
                            <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="price-row">
                            <p style="font-size:14px; color:var(--ux2-muted);">Biaya Admin</p>
                            <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-ink);">Rp {{ number_format($adminFee, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="flex justify-between items-center p-sm rounded-xl"
                        style="background:var(--ux2-primary-soft); border:1px solid var(--ux2-secondary-soft);">
                        <p class="font-label-md text-label-md font-bold" style="color:var(--ux2-primary);">Total Pembayaran</p>
                        <p style="font-size:22px; font-weight:800; color:var(--ux2-primary);">Rp {{ number_format($total, 0, ',', '.') }}</p>
                    </div>

                    {{-- Pay form (desktop) --}}
                    <div class="hidden lg:block">
                        <form action="{{ route('ux2.tenant.payment.process', $payment->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" id="selected_payment_method" value="bca_va">
                            <button type="submit"
                                class="pay-btn w-full py-md font-label-md text-label-md font-bold rounded-xl flex items-center justify-center gap-2"
                                style="background:var(--ux2-primary); color:#fff;">
                                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">lock</span>
                                Bayar Sekarang
                            </button>
                        </form>
                        <p class="text-center mt-sm" style="font-size:12px; color:var(--ux2-muted);">
                            Dengan menekan tombol di atas, Anda menyetujui
                            <a href="#" style="color:var(--ux2-secondary);" class="hover:underline">Syarat &amp; Ketentuan</a> KosKu.
                        </p>
                    </div>

                    {{-- KosKu guarantee --}}
                    <div class="flex items-center gap-2 pt-sm" style="border-top:1px solid var(--ux2-line);">
                        <span class="material-symbols-outlined flex-shrink-0" style="color:var(--ux2-secondary); font-variation-settings:'FILL' 1; font-size:20px;">verified_user</span>
                        <p style="font-size:12px; color:var(--ux2-muted); line-height:1.4;">
                            Properti ini telah diverifikasi dan dilindungi oleh <span style="font-weight:700; color:var(--ux2-primary);">Jaminan KosKu</span>
                        </p>
                    </div>

                </div>{{-- end panel body --}}
            </div>
        </div>{{-- end right --}}

    </div>{{-- end grid --}}
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Original: sync radio → hidden input ── */
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const hiddenDesktop = document.getElementById('selected_payment_method');
    const hiddenMobile  = document.getElementById('selected_payment_method_mobile');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked) {
                if (hiddenDesktop) hiddenDesktop.value = this.value;
                if (hiddenMobile)  hiddenMobile.value  = this.value;
            }
        });
    });

    /* ── New: scroll reveal ── */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
    document.querySelectorAll('.reveal').forEach(el => ro.observe(el));
});
</script>
@endsection
