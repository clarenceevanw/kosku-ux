@extends('layouts.ux2.owner')

@section('title', 'Pemesanan')

@section('content')
@php
    $visibleTransactions = $transactions instanceof \Illuminate\Pagination\AbstractPaginator
        ? $transactions->getCollection()
        : collect($transactions);

    $waitingApproval = $visibleTransactions->filter(function ($transaction) {
        $firstPayment = $transaction->monthlyPayments->first();
        $paymentStatus = $firstPayment ? ($firstPayment->payment_status->value ?? $firstPayment->payment_status) : 'pending';
        $contractStatus = $transaction->status->value ?? $transaction->status;

        return $contractStatus === 'pending' && $paymentStatus === 'paid_to_escrow';
    })->count();

    $paidCount = $visibleTransactions->filter(function ($transaction) {
        $firstPayment = $transaction->monthlyPayments->first();
        $paymentStatus = $firstPayment ? ($firstPayment->payment_status->value ?? $firstPayment->payment_status) : 'pending';

        return in_array($paymentStatus, ['paid_to_escrow', 'released_to_owner']);
    })->count();

    $pendingPaymentCount = $visibleTransactions->filter(function ($transaction) {
        $firstPayment = $transaction->monthlyPayments->first();
        $paymentStatus = $firstPayment ? ($firstPayment->payment_status->value ?? $firstPayment->payment_status) : 'pending';

        return $paymentStatus === 'pending';
    })->count();
@endphp

<div class="space-y-8" x-data="transactionPanel()">
    <section class="flex flex-col lg:flex-row lg:items-end justify-between gap-md">
        <div>
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider mb-xs">Owner Workspace</p>
            <h1 class="font-display-lg text-display-lg text-on-background mb-xs">Pemesanan</h1>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Kelola calon penyewa, pembayaran awal, dan persetujuan kontrak dari satu alur ringkas.</p>
        </div>

        <form method="GET" action="{{ route('ux2.owner.transactions.index') }}" class="w-full lg:w-auto">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">home_work</span>
                <select name="kos_id" onchange="this.form.submit()"
                    class="w-full lg:min-w-[280px] pl-12 pr-6 py-3 bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-secondary-container font-label-md text-label-md text-on-surface cursor-pointer">
                    @foreach ($boardingHouses as $kos)
                        <option value="{{ $kos->id }}" {{ $selectedKos?->id == $kos->id ? 'selected' : '' }}>
                            {{ $kos->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-md">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Total Masuk</p>
                    <h2 class="font-headline-lg text-headline-lg text-primary mt-xs">{{ $visibleTransactions->count() }}</h2>
                </div>
                <span class="material-symbols-outlined text-primary text-3xl">receipt_long</span>
            </div>
        </div>

        <div class="bg-tertiary-fixed rounded-2xl p-md shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-label-sm text-label-sm text-on-tertiary-container/70 uppercase tracking-wider">Siap Disetujui</p>
                    <h2 class="font-headline-lg text-headline-lg text-on-tertiary-container mt-xs">{{ $waitingApproval }}</h2>
                </div>
                <span class="material-symbols-outlined text-on-tertiary-container text-3xl">verified</span>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Menunggu Bayar</p>
                    <h2 class="font-headline-lg text-headline-lg text-error mt-xs">{{ $pendingPaymentCount }}</h2>
                </div>
                <span class="material-symbols-outlined text-error text-3xl">pending_actions</span>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-md items-start">
        <div class="space-y-md">
            @forelse ($transactions as $transaction)
                @php
                    $firstPayment = $transaction->monthlyPayments->first();
                    $totalAmount = $firstPayment ? $firstPayment->amount : ($transaction->deposit_fee + $transaction->monthly_fee);
                    $paymentStatus = $firstPayment ? ($firstPayment->payment_status->value ?? $firstPayment->payment_status) : 'pending';
                    $paymentMethod = $firstPayment ? $firstPayment->payment_method : null;
                    $contractStatus = $transaction->status->value ?? $transaction->status;
                    $duration = \Carbon\Carbon::parse($transaction->start_date)->diffInMonths(\Carbon\Carbon::parse($transaction->end_date)->addDay());
                    $duration = max(1, (int) round($duration));
                    $tenantName = $transaction->tenant->name ?? 'Unknown';
                    $initials = collect(explode(' ', $tenantName))->map(fn($name) => substr($name, 0, 1))->take(2)->join('');
                    $isReadyForApproval = $contractStatus === 'pending' && $paymentStatus === 'paid_to_escrow';
                @endphp

                <article class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-md">
                        <div class="flex items-start gap-md min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center font-bold shrink-0">
                                {{ strtoupper($initials ?: '?') }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-sm mb-xs">
                                    <h3 class="font-headline-md text-headline-md text-on-surface">{{ $tenantName }}</h3>
                                    @if ($isReadyForApproval)
                                        <span class="px-sm py-xs rounded-full bg-tertiary-fixed text-on-tertiary-container font-label-sm text-label-sm font-bold">Siap Disetujui</span>
                                    @elseif ($paymentStatus === 'pending')
                                        <span class="px-sm py-xs rounded-full bg-error-container text-error font-label-sm text-label-sm font-bold">Belum Bayar</span>
                                    @elseif (in_array($paymentStatus, ['paid_to_escrow', 'released_to_owner']))
                                        <span class="px-sm py-xs rounded-full bg-secondary-container/60 text-on-secondary-container font-label-sm text-label-sm font-bold">Sudah Bayar</span>
                                    @else
                                        <span class="px-sm py-xs rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold">{{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}</span>
                                    @endif
                                </div>
                                <p class="font-body-md text-body-md text-on-surface-variant truncate">{{ $transaction->tenant->email ?? '-' }}</p>
                                <div class="flex flex-wrap gap-sm mt-sm">
                                    <span class="inline-flex items-center gap-xs font-label-sm text-label-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-sm">bed</span>
                                        {{ $transaction->room->boardingHouse->name ?? 'Kos Tidak Diketahui' }} - {{ $transaction->room->type_name ?? 'Kamar Tidak Diketahui' }}
                                    </span>
                                    <span class="inline-flex items-center gap-xs font-label-sm text-label-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-sm">calendar_month</span>
                                        {{ $transaction->start_date->format('d M Y') }} / {{ $duration }} bulan
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-sm lg:items-end xl:items-center shrink-0">
                            <div class="lg:text-right xl:text-left">
                                <p class="font-label-sm text-label-sm text-on-surface-variant">Total awal</p>
                                <p class="font-headline-md text-headline-md text-primary">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                            </div>
                            <button type="button"
                                @click="openDetails({{ json_encode([
                                    'id' => $transaction->id,
                                    'tenant_name' => $tenantName,
                                    'tenant_email' => $transaction->tenant->email ?? '-',
                                    'kos_name' => $transaction->room->boardingHouse->name ?? 'Kos Tidak Diketahui',
                                    'room_name' => $transaction->room->type_name ?? 'Kamar Tidak Diketahui',
                                    'start_date' => $transaction->start_date->format('d M Y'),
                                    'end_date' => $transaction->end_date->format('d M Y'),
                                    'duration' => $duration . ' bulan',
                                    'total_amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                                    'payment_status' => $paymentStatus,
                                    'payment_label' => $paymentStatus === 'pending' ? 'Belum Bayar' : (in_array($paymentStatus, ['paid_to_escrow', 'released_to_owner']) ? 'Sudah Bayar' : ucfirst(str_replace('_', ' ', $paymentStatus))),
                                    'payment_method' => $paymentMethod ? str_replace('_', ' ', $paymentMethod) : 'transfer',
                                    'contract_status' => $contractStatus,
                                    'can_approve' => $isReadyForApproval,
                                    'monthly_payments' => $transaction->monthlyPayments
                                        ? $transaction->monthlyPayments->map(fn($payment) => [
                                            'month' => \Carbon\Carbon::parse($payment->due_date ?? now())->format('M Y'),
                                            'amount' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                                            'status' => $payment->payment_status->value ?? $payment->payment_status,
                                        ])->toArray()
                                        : [],
                                ]) }})"
                                class="inline-flex items-center justify-center gap-xs px-md py-sm rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-inverse-surface transition-colors">
                                <span class="material-symbols-outlined">visibility</span>
                                Detail
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="bg-surface-container-lowest border border-dashed border-outline-variant rounded-2xl p-xl text-center">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant opacity-40 mb-md block">receipt_long</span>
                    <h3 class="font-headline-md text-headline-md text-on-surface mb-xs">Belum ada pemesanan</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">Pemesanan dari calon penyewa akan muncul di sini.</p>
                </div>
            @endforelse

            @if (method_exists($transactions, 'hasPages') && $transactions->hasPages())
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        <aside class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md shadow-sm xl:sticky xl:top-8">
            <div class="flex items-center gap-sm mb-md">
                <span class="material-symbols-outlined text-secondary">rule</span>
                <h3 class="font-headline-md text-headline-md text-on-surface">Alur Persetujuan</h3>
            </div>
            <div class="space-y-md">
                <div class="flex gap-sm">
                    <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-sm text-on-surface-variant">person_add</span>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md font-bold text-on-surface">Pemesanan masuk</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant">Calon penyewa memilih kamar dan tanggal masuk.</p>
                    </div>
                </div>
                <div class="flex gap-sm">
                    <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-sm text-on-surface-variant">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md font-bold text-on-surface">Pembayaran escrow</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant">{{ $paidCount }} pemesanan pada halaman ini sudah memiliki pembayaran awal.</p>
                    </div>
                </div>
                <div class="flex gap-sm">
                    <div class="w-8 h-8 rounded-full bg-tertiary-fixed flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-sm text-on-tertiary-container">edit_document</span>
                    </div>
                    <div>
                        <p class="font-label-md text-label-md font-bold text-on-surface">Owner menyetujui</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant">Tombol persetujuan aktif hanya saat pembayaran sudah masuk escrow.</p>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <template x-teleport="body">
        <div x-show="isOpen" x-cloak class="relative z-50" role="dialog" aria-modal="true">
            <div x-show="isOpen" x-transition.opacity class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="closeDetails()"></div>

            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-8">
                        <div x-show="isOpen"
                            x-transition:enter="transform transition ease-in-out duration-500"
                            x-transition:enter-start="translate-x-full"
                            x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition ease-in-out duration-500"
                            x-transition:leave-start="translate-x-0"
                            x-transition:leave-end="translate-x-full"
                            class="pointer-events-auto w-screen max-w-xl">
                            <div class="flex h-full flex-col bg-surface-container-lowest shadow-xl overflow-y-auto">
                                <div class="px-md py-md border-b border-outline-variant sticky top-0 bg-surface-container-lowest z-10">
                                    <div class="flex items-start justify-between gap-md">
                                        <div>
                                            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">Detail Pemesanan</p>
                                            <h2 class="font-headline-lg text-headline-lg text-on-surface" x-text="selected?.tenant_name"></h2>
                                            <p class="font-body-md text-body-md text-on-surface-variant" x-text="selected?.tenant_email"></p>
                                        </div>
                                        <button type="button" @click="closeDetails()" class="p-sm rounded-full hover:bg-surface-container text-on-surface-variant">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex-1 p-md space-y-md">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-sm">
                                        <div class="bg-surface-container rounded-xl p-md">
                                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Kos</p>
                                            <p class="font-label-md text-label-md font-bold text-on-surface" x-text="selected?.kos_name"></p>
                                        </div>
                                        <div class="bg-surface-container rounded-xl p-md">
                                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Kamar</p>
                                            <p class="font-label-md text-label-md font-bold text-on-surface" x-text="selected?.room_name"></p>
                                        </div>
                                        <div class="bg-surface-container rounded-xl p-md">
                                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Tanggal Masuk</p>
                                            <p class="font-label-md text-label-md font-bold text-on-surface" x-text="selected?.start_date"></p>
                                        </div>
                                        <div class="bg-surface-container rounded-xl p-md">
                                            <p class="font-label-sm text-label-sm text-on-surface-variant mb-xs">Durasi</p>
                                            <p class="font-label-md text-label-md font-bold text-on-surface" x-text="selected?.duration"></p>
                                        </div>
                                    </div>

                                    <div class="bg-primary text-on-primary rounded-2xl p-md">
                                        <div class="flex items-start justify-between gap-md">
                                            <div>
                                                <p class="font-label-sm text-label-sm text-on-primary/70 uppercase tracking-wider">Pembayaran Awal</p>
                                                <p class="font-headline-lg text-headline-lg mt-xs" x-text="selected?.total_amount"></p>
                                            </div>
                                            <span class="material-symbols-outlined text-4xl">payments</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-sm mt-md pt-md border-t border-on-primary/20">
                                            <div>
                                                <p class="font-label-sm text-label-sm text-on-primary/70">Metode</p>
                                                <p class="font-label-md text-label-md font-bold capitalize" x-text="selected?.payment_method"></p>
                                            </div>
                                            <div>
                                                <p class="font-label-sm text-label-sm text-on-primary/70">Status</p>
                                                <p class="font-label-md text-label-md font-bold" x-text="selected?.payment_label"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden">
                                        <div class="px-md py-sm bg-surface-container border-b border-outline-variant">
                                            <h3 class="font-label-md text-label-md font-bold text-on-surface">Tagihan Bulanan</h3>
                                        </div>
                                        <template x-if="selected?.monthly_payments?.length">
                                            <div class="divide-y divide-outline-variant">
                                                <template x-for="(payment, index) in selected.monthly_payments" :key="index">
                                                    <div class="px-md py-sm flex items-center justify-between gap-sm">
                                                        <div>
                                                            <p class="font-label-md text-label-md font-bold text-on-surface" x-text="payment.month"></p>
                                                            <p class="font-label-sm text-label-sm text-on-surface-variant" x-text="payment.amount"></p>
                                                        </div>
                                                        <span class="px-sm py-xs rounded-full font-label-sm text-label-sm font-bold"
                                                            :class="{
                                                                'bg-error-container text-error': payment.status === 'pending',
                                                                'bg-secondary-container text-on-secondary-container': ['paid_to_escrow', 'released_to_owner'].includes(payment.status),
                                                                'bg-surface-container text-on-surface-variant': !['pending', 'paid_to_escrow', 'released_to_owner'].includes(payment.status)
                                                            }"
                                                            x-text="payment.status === 'pending' ? 'Belum Lunas' : (['paid_to_escrow', 'released_to_owner'].includes(payment.status) ? 'Lunas' : payment.status)">
                                                        </span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!selected?.monthly_payments?.length">
                                            <div class="p-md text-center font-body-md text-body-md text-on-surface-variant">Belum ada tagihan bulanan.</div>
                                        </template>
                                    </div>

                                    <div class="bg-surface-container rounded-2xl p-md flex gap-sm">
                                        <span class="material-symbols-outlined text-secondary">assignment</span>
                                        <div>
                                            <p class="font-label-md text-label-md font-bold text-on-surface">Status kontrak: <span class="capitalize" x-text="selected?.contract_status"></span></p>
                                            <p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Kontrak menjadi aktif setelah owner menyetujui pembayaran escrow.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-md py-md border-t border-outline-variant sticky bottom-0 bg-surface-container-lowest flex gap-sm">
                                    <button type="button" @click="closeDetails()" class="flex-1 px-md py-sm rounded-xl border border-outline-variant text-on-surface font-label-md text-label-md font-bold hover:bg-surface-container">Tutup</button>
                                    <template x-if="selected?.can_approve">
                                        <form :action="`{{ url('/ux2/owner/pemesanan') }}/${selected.id}/approve`" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full px-md py-sm rounded-xl bg-tertiary-fixed text-on-tertiary-container font-label-md text-label-md font-bold hover:bg-tertiary-fixed-dim">
                                                Setujui Kontrak
                                            </button>
                                        </form>
                                    </template>
                                </div>
                            </div>
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
    document.addEventListener('alpine:init', () => {
        Alpine.data('transactionPanel', () => ({
            isOpen: false,
            selected: null,
            openDetails(transaction) {
                this.selected = transaction;
                this.isOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            closeDetails() {
                this.isOpen = false;
                setTimeout(() => {
                    this.selected = null;
                }, 250);
                document.body.classList.remove('overflow-hidden');
            }
        }));
    });
</script>
@endpush
