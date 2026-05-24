@extends('layouts.owner')

@section('title', 'Manajemen Pemesanan')

@section('content')
    <div class="space-y-10" x-data="transactionManager()">
        <!-- Page Header & Filter -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div class="flex-grow">
                <h2 class="font-display text-4xl md:text-5xl font-extrabold tracking-tighter text-primary">Pemesanan Masuk
                </h2>
                <p class="font-body text-base text-on-surface-variant mt-2 mb-6">Pantau registrasi dan pembayaran awal calon
                    penyewa baru.</p>

                <form method="GET" action="{{ route('owner.transactions.index') }}" class="max-w-xs">
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">tune</span>
                        <select name="kos_id" onchange="this.form.submit()"
                            class="w-full pl-12 pr-6 py-4 bg-surface-container-lowest border border-outline-variant/50 rounded-full shadow-sm appearance-none focus:ring-2 focus:ring-primary text-sm font-label font-semibold text-on-surface cursor-pointer">
                            @foreach ($boardingHouses as $kos)
                                <option value="{{ $kos->id }}" {{ $selectedKos?->id == $kos->id ? 'selected' : '' }}>
                                    {{ $kos->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="flex gap-3">
                <button
                    class="flex items-center gap-2 px-6 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-full font-semibold hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-xl" data-icon="download">download</span>
                    Ekspor Data
                </button>
            </div>
        </header>

        <!-- Transactions List -->
        <section class="bg-surface-container-lowest rounded-3xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low/50 border-b border-surface-container">
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Calon
                                Penyewa</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kamar &
                                Durasi</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Tanggal
                                Masuk</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total
                                Awal</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status
                                Pembayaran</th>
                            <th class="px-6 py-4 text-xs font-bold text-on-surface-variant uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container">
                        @forelse ($transactions as $transaction)
                            @php
                                $firstPayment = $transaction->monthlyPayments->first();
                                $totalAmount = $firstPayment ? $firstPayment->amount : ($transaction->deposit_fee + $transaction->monthly_fee);
                                $paymentStatus = $firstPayment ? ($firstPayment->payment_status->value ?? $firstPayment->payment_status) : 'pending';
                                $paymentMethod = $firstPayment ? $firstPayment->payment_method : null;
                                $contractStatus = $transaction->status->value ?? $transaction->status;
                            @endphp
                            <tr class="hover:bg-surface-container-lowest/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                            {{ strtoupper(substr($transaction->tenant->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-on-surface">
                                                {{ $transaction->tenant->name ?? 'Unknown' }}</p>
                                            <p class="text-xs text-on-surface-variant">
                                                {{ $transaction->tenant->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-on-surface">
                                        {{ $transaction->room->boardingHouse->name ?? 'Kos Tidak Diketahui' }} -
                                        {{ $transaction->room->type_name ?? 'Kamar Tidak Diketahui' }}</p>
                                    @php
                                        $duration = \Carbon\Carbon::parse($transaction->start_date)->diffInMonths(
                                            \Carbon\Carbon::parse($transaction->end_date)->addDay(),
                                        );
                                        $duration = max(1, (int) round($duration));
                                    @endphp
                                    <p class="text-xs text-on-surface-variant">Durasi:
                                        {{ $duration }} Bulan</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-on-surface font-medium">{{ $transaction->start_date->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-on-surface">Rp
                                        {{ number_format($totalAmount, 0, ',', '.') }}</p>
                                    <p class="text-xs text-on-surface-variant capitalize">
                                        {{ str_replace('_', ' ', $paymentMethod ?? 'transfer') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($paymentStatus === 'pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-error-container text-error">
                                            Belum Bayar
                                        </span>
                                    @elseif (
                                        $paymentStatus === 'paid_to_escrow' ||
                                            $paymentStatus === 'released_to_owner')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                            Sudah Bayar
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-surface-container-high text-on-surface">
                                            {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button
                                        @click="openDetails({{ json_encode([
                                            'id' => $transaction->id,
                                            'tenant_name' => $transaction->tenant->name ?? 'Unknown',
                                            'tenant_email' => $transaction->tenant->email ?? '-',
                                            'room_name' => $transaction->room->type_name ?? 'Unknown',
                                            'start_date' => $transaction->start_date->format('d M Y'),
                                            'end_date' => $transaction->end_date->format('d M Y'),
                                            'total_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                                            'payment_status' => $paymentStatus,
                                            'payment_method' => $paymentMethod,
                                            'contract_status' => $contractStatus,
                                            'monthly_payments' => $transaction->monthlyPayments
                                                ? $transaction->monthlyPayments->map(
                                                        fn($mp) => [
                                                            'month' => \Carbon\Carbon::parse($mp->due_date ?? now())->format('M Y'),
                                                            'amount' => 'Rp ' . number_format($mp->amount, 0, ',', '.'),
                                                            'status' => $mp->payment_status->value ?? $mp->payment_status,
                                                        ],
                                                    )->toArray()
                                                : [],
                                        ]) }})"
                                        class="text-primary hover:text-primary/80 font-bold text-sm bg-primary/10 px-4 py-2 rounded-xl transition-colors">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center text-on-surface-variant">
                                    <span
                                        class="material-symbols-outlined text-5xl mb-4 block opacity-50">shopping_cart_checkout</span>
                                    <p class="font-headline font-bold text-lg text-on-surface mb-1">Belum ada pemesanan</p>
                                    <p class="text-sm">Pemesanan baru dari calon penyewa akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transactions->hasPages())
                <div class="p-6 border-t border-surface-container bg-surface-container-low/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </section>

        <!-- Transaction Details Slide-over Modal -->
        <div x-show="isSlideoverOpen" x-cloak class="relative z-50" aria-labelledby="slide-over-title" role="dialog"
            aria-modal="true">
            <!-- Background backdrop -->
            <div x-show="isSlideoverOpen" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="closeDetails()">
            </div>

            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <!-- Slide-over panel -->
                        <div x-show="isSlideoverOpen"
                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                            class="pointer-events-auto w-screen max-w-md">

                            <div class="flex h-full flex-col overflow-y-scroll bg-surface-container-lowest shadow-2xl">
                                <!-- Header -->
                                <div class="px-6 py-6 border-b border-outline-variant/50 bg-white sticky top-0 z-10">
                                    <div class="flex items-start justify-between">
                                        <h2 class="text-2xl font-display font-extrabold text-on-surface"
                                            id="slide-over-title">Detail Pemesanan</h2>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button" @click="closeDetails()"
                                                class="relative rounded-md text-on-surface-variant hover:text-on-surface focus:outline-none focus:ring-2 focus:ring-primary">
                                                <span class="absolute -inset-2.5"></span>
                                                <span class="sr-only">Tutup panel</span>
                                                <span class="material-symbols-outlined">close</span>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-sm text-on-surface-variant"
                                        x-text="selectedTransaction?.tenant_name"></p>
                                </div>

                                <!-- Body -->
                                <div class="relative flex-1 px-6 py-8 space-y-8">
                                    <!-- Tenant Info -->
                                    <div>
                                        <h3 class="text-sm font-bold text-primary tracking-wider uppercase mb-4">Profil
                                            Calon Penyewa</h3>
                                        <div
                                            class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4">
                                            <div class="flex items-center gap-4 mb-4">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xl">
                                                    <span
                                                        x-text="selectedTransaction?.tenant_name.charAt(0).toUpperCase()"></span>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-on-surface text-lg"
                                                        x-text="selectedTransaction?.tenant_name"></p>
                                                    <p class="text-sm text-on-surface-variant flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[16px]">mail</span>
                                                        <span x-text="selectedTransaction?.tenant_email"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Room Info -->
                                    <div>
                                        <h3 class="text-sm font-bold text-primary tracking-wider uppercase mb-4">Informasi
                                            Kamar</h3>
                                        <div
                                            class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4 space-y-3">
                                            <div
                                                class="flex justify-between items-center pb-3 border-b border-outline-variant/50">
                                                <span class="text-on-surface-variant text-sm">Kamar Dipilih</span>
                                                <span class="font-bold text-on-surface"
                                                    x-text="selectedTransaction?.room_name"></span>
                                            </div>
                                            <div
                                                class="flex justify-between items-center pb-3 border-b border-outline-variant/50">
                                                <span class="text-on-surface-variant text-sm">Tgl Mulai</span>
                                                <span class="font-bold text-on-surface"
                                                    x-text="selectedTransaction?.start_date"></span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-on-surface-variant text-sm">Tgl Selesai</span>
                                                <span class="font-bold text-on-surface"
                                                    x-text="selectedTransaction?.end_date"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Financial Breakdown -->
                                    <div>
                                        <h3 class="text-sm font-bold text-primary tracking-wider uppercase mb-4">Rincian
                                            Pembayaran Awal</h3>
                                        <div
                                            class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-4">
                                            <div class="space-y-3 mb-4">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-on-surface-variant text-sm flex items-center gap-2">
                                                        <span
                                                            class="material-symbols-outlined text-[16px]">credit_card</span>
                                                        Metode
                                                    </span>
                                                    <span class="font-semibold text-on-surface capitalize"
                                                        x-text="selectedTransaction?.payment_method?.replace('_', ' ')"></span>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-on-surface-variant text-sm flex items-center gap-2">
                                                        <span class="material-symbols-outlined text-[16px]">info</span>
                                                        Status
                                                    </span>
                                                    <span class="font-bold text-sm px-2 py-1 rounded-md"
                                                        :class="{
                                                            'bg-error-container text-error': selectedTransaction
                                                                ?.payment_status === 'pending',
                                                            'bg-green-100 text-green-800': ['paid_to_escrow',
                                                                'released_to_owner'
                                                            ].includes(selectedTransaction?.payment_status),
                                                            'bg-surface-container text-on-surface': !['pending',
                                                                'paid_to_escrow', 'released_to_owner'
                                                            ].includes(selectedTransaction?.payment_status)
                                                        }"
                                                        x-text="selectedTransaction?.payment_status === 'pending' ? 'Belum Bayar' : (['paid_to_escrow', 'released_to_owner'].includes(selectedTransaction?.payment_status) ? 'Sudah Bayar' : selectedTransaction?.payment_status)">
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="pt-4 border-t border-outline-variant/50">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-bold text-on-surface">Total Dibayar</span>
                                                    <span class="font-display font-bold text-2xl text-primary"
                                                        x-text="selectedTransaction?.total_amount"></span>
                                                </div>
                                                <p class="text-xs text-on-surface-variant mt-1 text-right">(Termasuk Sewa
                                                    Bulan 1 & Deposit)</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Monthly Payments -->
                                    <template
                                        x-if="selectedTransaction?.monthly_payments && selectedTransaction.monthly_payments.length > 0">
                                        <div>
                                            <h3 class="text-sm font-bold text-primary tracking-wider uppercase mb-4">
                                                Tagihan Bulanan</h3>
                                            <div
                                                class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden">
                                                <template x-for="(mp, index) in selectedTransaction.monthly_payments"
                                                    :key="index">
                                                    <div
                                                        class="p-4 border-b border-outline-variant/50 last:border-0 flex justify-between items-center">
                                                        <div>
                                                            <p class="font-bold text-on-surface" x-text="mp.month"></p>
                                                            <p class="text-sm text-on-surface-variant" x-text="mp.amount">
                                                            </p>
                                                        </div>
                                                        <span class="font-bold text-xs px-2 py-1 rounded-md"
                                                            :class="{
                                                                'bg-error-container text-error': mp
                                                                    .status === 'pending',
                                                                'bg-green-100 text-green-800': ['paid_to_escrow',
                                                                    'released_to_owner'
                                                                ].includes(mp.status),
                                                                'bg-surface-container text-on-surface': !['pending',
                                                                    'paid_to_escrow', 'released_to_owner'
                                                                ].includes(mp.status)
                                                            }"
                                                            x-text="mp.status === 'pending' ? 'Belum Lunas' : (['paid_to_escrow', 'released_to_owner'].includes(mp.status) ? 'Lunas' : mp.status)">
                                                        </span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Contract Action -->
                                    <div class="mt-8 p-4 bg-primary/5 rounded-2xl border border-primary/20">
                                        <div class="flex items-start gap-3">
                                            <span class="material-symbols-outlined text-primary mt-0.5">assignment</span>
                                            <div>
                                                <p class="font-bold text-primary text-sm">Status Kontrak: <span
                                                        class="capitalize"
                                                        x-text="selectedTransaction?.contract_status"></span></p>
                                                <p class="text-xs text-on-surface-variant mt-1">Kontrak digital akan
                                                    otomatis dibuat setelah pembayaran diverifikasi oleh sistem. Anda dapat
                                                    melihatnya nanti.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Actions -->
                                <div
                                    class="px-6 py-4 border-t border-outline-variant/50 bg-surface-container-lowest sticky bottom-0 z-10 flex gap-3">
                                    <button @click="closeDetails()"
                                        class="flex-1 px-4 py-3 border border-outline-variant rounded-xl font-bold text-on-surface hover:bg-surface-container transition-colors">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('transactionManager', () => ({
                isSlideoverOpen: false,
                selectedTransaction: null,
                openDetails(transaction) {
                    this.selectedTransaction = transaction;
                    this.isSlideoverOpen = true;
                    document.body.classList.add('overflow-hidden');
                },
                closeDetails() {
                    this.isSlideoverOpen = false;
                    setTimeout(() => {
                        this.selectedTransaction = null;
                    }, 300);
                    document.body.classList.remove('overflow-hidden');
                }
            }));
        });
    </script>
@endpush
