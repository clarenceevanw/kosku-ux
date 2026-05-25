@extends('layouts.ux2.owner')

@section('title', 'Keuangan & Tagihan')

@section('content')
<div class="space-y-8">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="grow">
            <h2 class="font-headline-lg text-headline-lg text-primary mb-xs">Keuangan & Tagihan</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mb-md">Pantau arus kas properti Anda secara real-time</p>
            
            <form method="GET" action="{{ route('ux2.owner.keuangan.index') }}" class="max-w-xs">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">tune</span>
                    <select name="kos_id" onchange="this.form.submit()"
                        class="w-full pl-12 pr-6 py-3 bg-surface-container-lowest border border-outline-variant rounded-3xl appearance-none focus:ring-2 focus:ring-secondary-container font-label-md text-label-md text-on-surface cursor-pointer">
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
            <a href="{{ route('ux2.owner.keuangan.laporan') }}" 
                class="flex items-center gap-2 px-6 py-3 bg-secondary-container text-on-secondary-container rounded-3xl font-label-md text-label-md font-semibold hover:shadow-lg transition-all">
                <span class="material-symbols-outlined">description</span>
                Laporan Keuangan
            </a>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
        <!-- Total Pendapatan -->
        <div class="bg-linear-to-br from-secondary-container to-secondary-container/70 p-lg rounded-3xl shadow-lg">
            <div class="flex items-start justify-between mb-md">
                <div>
                    <p class="font-label-sm text-label-sm text-on-secondary-container/70 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="font-headline-md text-headline-md text-on-secondary-container mt-xs">Rp {{ number_format($metrics['totalIncome'], 0, ',', '.') }}</h3>
                </div>
                <span class="material-symbols-outlined text-on-secondary-container text-3xl">trending_up</span>
            </div>
            <p class="font-label-sm text-label-sm text-on-secondary-container/70">Bulan ini</p>
        </div>

        <!-- Tagihan Pending -->
        <div class="bg-surface-container-lowest p-lg rounded-3xl shadow-lg border border-outline-variant">
            <div class="flex items-start justify-between mb-md">
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Tagihan Pending</p>
                    <h3 class="font-headline-md text-headline-md text-error mt-xs">Rp {{ number_format($metrics['pendingBillsAmount'], 0, ',', '.') }}</h3>
                </div>
                <span class="material-symbols-outlined text-error text-3xl">schedule</span>
            </div>
            <p class="font-label-sm text-label-sm text-on-surface-variant">{{ $metrics['pendingBillsCount'] }} tagihan belum terbayar</p>
        </div>

        <!-- Penyewa Aktif -->
        <div class="bg-surface-container-lowest p-lg rounded-3xl shadow-lg border border-outline-variant">
            <div class="flex items-start justify-between mb-md">
                <div>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Penyewa Aktif</p>
                    <h3 class="font-headline-md text-headline-md text-on-surface mt-xs">{{ $metrics['activeTenantsCount'] }}</h3>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant text-3xl">group</span>
            </div>
            <p class="font-label-sm text-label-sm text-on-surface-variant">Dari {{ $metrics['totalRoomCapacity'] }} kapasitas</p>
        </div>
    </div>

    <!-- Income Chart -->
    <div class="bg-surface-container-lowest p-lg rounded-3xl shadow-lg border border-outline-variant">
        <div class="flex items-center justify-between mb-lg">
            <h3 class="font-headline-md text-headline-md text-primary">Performa Pendapatan (6 Bulan Terakhir)</h3>
            <select class="bg-surface-container border-none rounded-3xl font-label-md text-label-md px-4 py-2 outline-none focus:ring-2 focus:ring-secondary-container">
                <option>Tahun {{ date('Y') }}</option>
            </select>
        </div>
        <div class="relative h-64 w-full">
            <canvas id="incomeChart"></canvas>
        </div>
    </div>

    <!-- Pending Bills Table -->
    <div class="bg-surface-container-lowest rounded-3xl shadow-lg border border-outline-variant overflow-hidden">
        <div class="p-lg border-b border-outline-variant flex items-center justify-between">
            <h3 class="font-headline-md text-headline-md text-primary">Daftar Tagihan Belum Lunas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container">
                    <tr>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Nama Penyewa</th>
                        <th class="px-md py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Tipe Kamar</th>
                        <th class="px-md py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-md py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Jumlah</th>
                        <th class="px-md py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Status</th>
                        <th class="px-lg py-md font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($pendingBills as $bill)
                        @php
                            $isOverdue = \Carbon\Carbon::now()->startOfDay()->greaterThan($bill->due_date->startOfDay());
                            $initials = collect(explode(' ', $bill->contract->tenant->name ?? 'Unknown'))
                                ->map(fn($n) => substr($n, 0, 1))
                                ->take(2)
                                ->join('');
                        @endphp
                        <tr class="hover:bg-surface-container/30 transition-colors {{ $isOverdue ? 'bg-error-container/10' : '' }}">
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-sm">
                                    <div class="w-10 h-10 rounded-full {{ $isOverdue ? 'bg-error-container text-error' : 'bg-primary-container text-on-primary-container' }} flex items-center justify-center font-bold font-label-sm text-label-sm">
                                        {{ strtoupper($initials) }}
                                    </div>
                                    <p class="font-label-md text-label-md font-semibold text-on-surface">
                                        {{ $bill->contract->tenant->name ?? 'Unknown' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-md py-md font-body-md text-body-md text-on-surface-variant">
                                {{ $bill->contract->room->type_name ?? '-' }}
                            </td>
                            <td class="px-md py-md font-body-md text-body-md text-on-surface-variant">
                                {{ $bill->due_date->format('d M Y') }}
                            </td>
                            <td class="px-md py-md font-label-md text-label-md font-semibold text-on-surface">
                                Rp {{ number_format($bill->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-md py-md">
                                @if ($isOverdue)
                                    <span class="px-3 py-1 bg-error-container text-error rounded-full font-label-sm text-label-sm font-bold uppercase">Terlambat</span>
                                @else
                                    <span class="px-3 py-1 bg-tertiary-container text-on-tertiary-container rounded-full font-label-sm text-label-sm font-bold uppercase">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-lg py-md text-right">
                                <form action="{{ route('ux2.owner.keuangan.remind', $bill->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-secondary-container hover:bg-secondary-container/20 rounded-full transition-colors" title="Ingatkan">
                                        <span class="material-symbols-outlined">notifications</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-lg py-xl text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-sm block opacity-50">task_alt</span>
                                <p class="font-body-md text-body-md">Belum ada tagihan pending saat ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pendingBills->hasPages())
            <div class="p-md border-t border-outline-variant bg-surface-container/50">
                {{ $pendingBills->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('incomeChart').getContext('2d');
    
    const labels = {!! json_encode($chartData['labels']) !!};
    const dataValues = {!! json_encode($chartData['rawValues']) !!};
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(108, 248, 187, 0.8)');
    gradient.addColorStop(1, 'rgba(108, 248, 187, 0.2)');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: dataValues,
                backgroundColor: gradient,
                borderRadius: 12,
                hoverBackgroundColor: '#6cf8bb',
            }]
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
                    display: false
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
