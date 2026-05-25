@extends('layouts.ux2.owner')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-primary mb-xs">Laporan Keuangan</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">Analisis mendalam performa keuangan properti Anda</p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 px-6 py-3 bg-surface-container-lowest border border-outline-variant rounded-3xl font-label-md text-label-md font-semibold hover:shadow-lg transition-all">
                <span class="material-symbols-outlined">print</span>
                Cetak
            </button>
            <button class="flex items-center gap-2 px-6 py-3 bg-secondary-container text-on-secondary-container rounded-3xl font-label-md text-label-md font-semibold hover:shadow-lg transition-all">
                <span class="material-symbols-outlined">download</span>
                Ekspor PDF
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-surface-container-lowest p-lg rounded-3xl shadow-lg border border-outline-variant">
        <form method="GET" action="{{ route('ux2.owner.keuangan.laporan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-md">
            <div>
                <label class="font-label-md text-label-md text-on-surface-variant mb-xs block">Properti</label>
                <select name="kos_id" class="w-full px-4 py-3 bg-surface-container border border-outline-variant rounded-2xl font-body-md text-body-md text-on-surface focus:ring-2 focus:ring-secondary-container">
                    <option value="">Semua Properti</option>
                    @foreach ($boardingHouses as $kos)
                        <option value="{{ $kos->id }}" {{ request('kos_id') == $kos->id ? 'selected' : '' }}>
                            {{ $kos->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="font-label-md text-label-md text-on-surface-variant mb-xs block">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" 
                    class="w-full px-4 py-3 bg-surface-container border border-outline-variant rounded-2xl font-body-md text-body-md text-on-surface focus:ring-2 focus:ring-secondary-container">
            </div>
            <div>
                <label class="font-label-md text-label-md text-on-surface-variant mb-xs block">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" 
                    class="w-full px-4 py-3 bg-surface-container border border-outline-variant rounded-2xl font-body-md text-body-md text-on-surface focus:ring-2 focus:ring-secondary-container">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-3 bg-primary text-on-primary rounded-2xl font-label-md text-label-md font-semibold hover:shadow-lg transition-all">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-md">
        <div class="bg-gradient-to-br from-secondary-container to-secondary-container/70 p-lg rounded-3xl shadow-lg">
            <span class="material-symbols-outlined text-on-secondary-container text-3xl mb-sm block">payments</span>
            <p class="font-label-sm text-label-sm text-on-secondary-container/70 uppercase tracking-wider">Total Pemasukan</p>
            <h3 class="font-headline-md text-headline-md text-on-secondary-container mt-xs">Rp {{ number_format($summary['totalIncome'], 0, ',', '.') }}</h3>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-3xl shadow-lg border border-outline-variant">
            <span class="material-symbols-outlined text-error text-3xl mb-sm block">account_balance_wallet</span>
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Total Pengeluaran</p>
            <h3 class="font-headline-md text-headline-md text-error mt-xs">Rp {{ number_format($summary['totalExpense'], 0, ',', '.') }}</h3>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-3xl shadow-lg border border-outline-variant">
            <span class="material-symbols-outlined text-on-tertiary-container text-3xl mb-sm block">trending_up</span>
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Laba Bersih</p>
            <h3 class="font-headline-md text-headline-md text-on-tertiary-container mt-xs">Rp {{ number_format($summary['netProfit'], 0, ',', '.') }}</h3>
        </div>

        <div class="bg-surface-container-lowest p-lg rounded-3xl shadow-lg border border-outline-variant">
            <span class="material-symbols-outlined text-on-surface-variant text-3xl mb-sm block">percent</span>
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Margin Laba</p>
            <h3 class="font-headline-md text-headline-md text-on-surface mt-xs">{{ number_format($summary['profitMargin'], 1) }}%</h3>
        </div>
    </div>

    <!-- Income vs Expense Chart -->
    <div class="bg-surface-container-lowest p-lg rounded-3xl shadow-lg border border-outline-variant">
        <h3 class="font-headline-md text-headline-md text-primary mb-lg">Perbandingan Pemasukan & Pengeluaran</h3>
        <div class="relative h-80 w-full">
            <canvas id="incomeExpenseChart"></canvas>
        </div>
    </div>

    <!-- Detailed Transactions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
        <!-- Income Details -->
        <div class="bg-surface-container-lowest rounded-3xl shadow-lg border border-outline-variant overflow-hidden">
            <div class="p-lg border-b border-outline-variant">
                <h3 class="font-headline-md text-headline-md text-primary">Rincian Pemasukan</h3>
            </div>
            <div class="p-lg space-y-md max-h-96 overflow-y-auto">
                @forelse($incomeTransactions as $transaction)
                    <div class="flex items-center justify-between p-md bg-surface-container rounded-2xl">
                        <div class="flex items-center gap-sm">
                            <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-secondary-container">arrow_downward</span>
                            </div>
                            <div>
                                <p class="font-label-md text-label-md font-semibold text-on-surface">{{ $transaction->description }}</p>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ $transaction->date->format('d M Y') }}</p>
                            </div>
                        </div>
                        <p class="font-label-md text-label-md font-bold text-secondary-container">+Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="text-center text-on-surface-variant font-body-md text-body-md py-lg">Tidak ada data pemasukan</p>
                @endforelse
            </div>
        </div>

        <!-- Expense Details -->
        <div class="bg-surface-container-lowest rounded-3xl shadow-lg border border-outline-variant overflow-hidden">
            <div class="p-lg border-b border-outline-variant">
                <h3 class="font-headline-md text-headline-md text-primary">Rincian Pengeluaran</h3>
            </div>
            <div class="p-lg space-y-md max-h-96 overflow-y-auto">
                @forelse($expenseTransactions as $transaction)
                    <div class="flex items-center justify-between p-md bg-surface-container rounded-2xl">
                        <div class="flex items-center gap-sm">
                            <div class="w-10 h-10 rounded-full bg-error-container flex items-center justify-center">
                                <span class="material-symbols-outlined text-error">arrow_upward</span>
                            </div>
                            <div>
                                <p class="font-label-md text-label-md font-semibold text-on-surface">{{ $transaction->description }}</p>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ $transaction->date->format('d M Y') }}</p>
                            </div>
                        </div>
                        <p class="font-label-md text-label-md font-bold text-error">-Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="text-center text-on-surface-variant font-body-md text-body-md py-lg">Tidak ada data pengeluaran</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
    
    const labels = {!! json_encode($chartData['labels']) !!};
    const incomeData = {!! json_encode($chartData['income']) !!};
    const expenseData = {!! json_encode($chartData['expense']) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: incomeData,
                    borderColor: '#6cf8bb',
                    backgroundColor: 'rgba(108, 248, 187, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                },
                {
                    label: 'Pengeluaran',
                    data: expenseData,
                    borderColor: '#ba1a1a',
                    backgroundColor: 'rgba(186, 26, 26, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e7eeff',
                        drawBorder: false,
                    },
                    ticks: {
                        callback: function(value) {
                            if(value >= 1000000) {
                                return 'Rp ' + (value / 1000000) + 'JT';
                            }
                            return 'Rp ' + value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false,
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
