@extends('layouts.owner')

@section('title', 'Keuangan & Tagihan')

@push('styles')
    <style>
        .chart-bar {
            transition: height 1s ease-out, opacity 0.3s ease;
        }

        .chart-bar:hover {
            opacity: 0.8;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-10">
        <!-- Page Header & Filter -->
    <header class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div class="flex-grow">
            <h2 class="font-display text-4xl md:text-5xl font-extrabold tracking-tighter text-primary">Keuangan &amp; Tagihan</h2>
            <p class="font-body text-base text-on-surface-variant mt-2 mb-6">Pantau arus kas properti Anda secara real-time.
            </p>
            
            <form method="GET" action="{{ route('owner.keuangan.index') }}" class="max-w-xs">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">tune</span>
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
                    Ekspor Laporan
                </button>
            </div>
    </header>
        <!-- Top Metrics Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Metric 1 -->
            <div
                class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_20px_40px_rgba(0,0,0,0.04)] border border-surface-container flex flex-col justify-between">
                <div>
                    <p class="text-label-md text-on-surface-variant uppercase tracking-widest">Total Pendapatan
                        Bulan Ini</p>
                    <h3 class="text-headline-md font-extrabold text-accent-teal mt-2">Rp
                        {{ number_format($metrics['totalIncome'], 0, ',', '.') }}</h3>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-accent-teal font-semibold">
                    <span class="material-symbols-outlined text-sm" data-icon="trending_up">trending_up</span>
                    <span>Diperbarui secara real-time</span>
                </div>
            </div>
            <!-- Metric 2 -->
            <div
                class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_20px_40px_rgba(0,0,0,0.04)] border border-surface-container flex flex-col justify-between">
                <div>
                    <p class="text-label-md text-on-surface-variant uppercase tracking-widest">Tagihan Pending</p>
                    <h3 class="text-headline-md font-extrabold text-amber-500 mt-2">Rp
                        {{ number_format($metrics['pendingBillsAmount'], 0, ',', '.') }}</h3>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-amber-500 font-semibold">
                    <span class="material-symbols-outlined text-sm" data-icon="schedule">schedule</span>
                    <span>{{ $metrics['pendingBillsCount'] }} tagihan belum terbayar</span>
                </div>
            </div>
            <!-- Metric 3 -->
            <div
                class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_20px_40px_rgba(0,0,0,0.04)] border border-surface-container flex flex-col justify-between">
                <div>
                    <p class="text-label-md text-on-surface-variant uppercase tracking-widest">Total Penyewa Aktif
                    </p>
                    <h3 class="text-headline-md font-extrabold text-on-surface mt-2">{{ $metrics['activeTenantsCount'] }}
                    </h3>
                </div>
                <div class="mt-4 flex items-center gap-2 text-sm text-on-surface-variant font-semibold">
                    <span class="material-symbols-outlined text-sm" data-icon="group">group</span>
                    <span>Dari total {{ $metrics['totalRoomCapacity'] }} kapasitas kamar</span>
                </div>
            </div>
        </div>
        <!-- Monthly Income Chart (Section A) -->
        <section
            class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_20px_40px_rgba(0,0,0,0.08)] border border-surface-container">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-extrabold text-primary">Performa Pendapatan (6 Bulan Terakhir)</h3>
                <select
                    class="bg-surface-container border-none rounded-full text-sm font-semibold px-4 py-2 outline-none focus:ring-2 focus:ring-primary/20">
                    <option>Tahun {{ date('Y') }}</option>
                </select>
            </div>
            <div class="relative h-64 w-full">
                <!-- Chart.js Canvas -->
                <canvas id="incomeChart"></canvas>
            </div>
        </section>

        <!-- Tagihan Belum Lunas Table (Section B) -->
        <section
            class="bg-surface-container-lowest rounded-xl shadow-[0px_20px_40px_rgba(0,0,0,0.08)] border border-surface-container overflow-hidden">
            <div class="p-8 border-b border-surface-container flex items-center justify-between">
                <h3 class="text-xl font-extrabold text-primary">Daftar Tagihan Belum Lunas</h3>
                <div class="flex gap-2">
                    <button class="p-2 text-on-surface-variant hover:bg-surface-container rounded-full transition-colors">
                        <span class="material-symbols-outlined" data-icon="filter_list">filter_list</span>
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-8 py-4 text-label-md text-on-surface-variant uppercase tracking-wider">
                                Nama Penyewa</th>
                            <th class="px-6 py-4 text-label-md text-on-surface-variant uppercase tracking-wider">
                                Tipe Kamar</th>
                            <th class="px-6 py-4 text-label-md text-on-surface-variant uppercase tracking-wider">
                                Jatuh Tempo</th>
                            <th class="px-6 py-4 text-label-md text-on-surface-variant uppercase tracking-wider">
                                Jumlah Tagihan</th>
                            <th class="px-6 py-4 text-label-md text-on-surface-variant uppercase tracking-wider">
                                Status</th>
                            <th class="px-8 py-4 text-label-md text-on-surface-variant uppercase tracking-wider text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container">
                        @forelse($pendingBills as $bill)
                            @php
                                $isOverdue = \Carbon\Carbon::now()
                                    ->startOfDay()
                                    ->greaterThan($bill->due_date->startOfDay());
                                $initials = collect(
                                    explode(' ', $bill->contract->tenant->name ?? 'Unknown'),
                                )
                                    ->map(fn($n) => substr($n, 0, 1))
                                    ->take(2)
                                    ->join('');
                            @endphp
                            <tr
                                class="hover:bg-surface-container/30 transition-colors {{ $isOverdue ? 'bg-red-50/30' : '' }}">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full {{ $isOverdue ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper($initials) }}
                                        </div>
                                        <p class="font-bold text-on-surface">
                                            {{ $bill->contract->tenant->name ?? 'Unknown' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-on-surface-variant">
                                    {{ $bill->contract->room->type_name ?? '-' }}</td>
                                <td class="px-6 py-5 text-on-surface-variant">{{ $bill->due_date->format('d M Y') }}</td>
                                <td class="px-6 py-5 font-bold text-on-surface">Rp
                                    {{ number_format($bill->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-5">
                                    @if ($isOverdue)
                                        <span
                                            class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-xs font-bold border border-red-100 uppercase">Terlambat</span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-bold border border-amber-100 uppercase">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right flex items-center justify-end gap-3">
                                    <form action="{{ route('owner.keuangan.remind', $bill->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="p-2 text-accent-teal hover:bg-accent-teal/10 rounded-full transition-colors flex items-center gap-1 group"
                                            title="Ingatkan">
                                            <span class="material-symbols-outlined text-xl" data-icon="chat">chat</span>
                                            <span
                                                class="text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">Ingatkan</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl mb-3 block opacity-50">task_alt</span>
                                    Belum ada tagihan pending saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pendingBills->hasPages())
                <div class="p-6 border-t border-surface-container bg-surface-container-low/50">
                    {{ $pendingBills->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('incomeChart').getContext('2d');
            
            const labels = {!! json_encode($chartData['labels']) !!};
            const dataValues = {!! json_encode($chartData['rawValues']) !!};
            
            // Create gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(13, 148, 136, 0.8)'); // accent-teal
            gradient.addColorStop(1, 'rgba(13, 148, 136, 0.2)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: dataValues,
                        backgroundColor: gradient,
                        borderRadius: 8,
                        hoverBackgroundColor: '#0D9488', // accent-teal solid
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0edee',
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
