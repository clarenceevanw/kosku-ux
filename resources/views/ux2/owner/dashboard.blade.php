@extends('layouts.ux2.owner')

@section('title', 'Dashboard Owner')

@section('content')
<!-- Header Section -->
<section class="mb-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md">
        <div>
            <h1 class="font-display-lg text-display-lg text-on-background mb-xs">Dashboard Owner</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
        
        <!-- Kos Selector -->
        <form method="GET" action="{{ route('ux2.owner.dashboard') }}" class="w-full md:w-auto">
            <div class="flex items-center gap-sm bg-surface-container-lowest rounded-xl p-sm border border-outline-variant shadow-sm">
                <span class="material-symbols-outlined text-on-surface-variant">home_work</span>
                <select name="kos_id" onchange="this.form.submit()" class="bg-transparent border-none text-on-surface font-label-md text-label-md focus:ring-0 cursor-pointer min-w-[200px]">
                    <option value="">Semua Kos</option>
                    @foreach($boardingHouses as $kos)
                        <option value="{{ $kos->id }}" {{ $selectedKosId == $kos->id ? 'selected' : '' }}>{{ $kos->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</section>

<!-- Stats Cards -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-lg">
    <!-- Kamar Terisi -->
    <div class="bg-gradient-to-br from-secondary-container to-secondary-fixed rounded-2xl p-md shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-md">
            <div>
                <p class="font-label-sm text-label-sm text-on-secondary-container uppercase tracking-wider mb-xs">Kamar Terisi</p>
                <h2 class="font-headline-lg text-headline-lg text-on-secondary-container">{{ $kamarTerisi['occupied'] }}<span class="font-body-lg text-body-lg opacity-70">/ {{ $kamarTerisi['total'] }}</span></h2>
            </div>
            <div class="bg-on-secondary-container/10 p-sm rounded-xl">
                <span class="material-symbols-outlined text-on-secondary-container text-3xl">bed</span>
            </div>
        </div>
        <div class="w-full bg-on-secondary-container/20 rounded-full h-2">
            <div class="bg-on-secondary-container h-2 rounded-full" style="width: {{ $kamarTerisi['total'] > 0 ? ($kamarTerisi['occupied'] / $kamarTerisi['total'] * 100) : 0 }}%"></div>
        </div>
    </div>

    <!-- Pemasukan -->
    <div class="bg-gradient-to-br from-tertiary-fixed to-tertiary-fixed-dim rounded-2xl p-md shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-md">
            <div>
                <p class="font-label-sm text-label-sm text-on-tertiary-fixed uppercase tracking-wider mb-xs">Pemasukan Bulan Ini</p>
                <h2 class="font-headline-lg text-headline-lg text-on-tertiary-fixed">Rp {{ number_format($totalPendapatan / 1000000, 1) }}JT</h2>
            </div>
            <div class="bg-on-tertiary-fixed/10 p-sm rounded-xl">
                <span class="material-symbols-outlined text-on-tertiary-fixed text-3xl">payments</span>
            </div>
        </div>
        <p class="font-label-sm text-label-sm text-on-tertiary-fixed/70">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>

    <!-- Tagihan Tertunggak -->
    <div class="bg-gradient-to-br from-error-container to-error rounded-2xl p-md shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-md">
            <div>
                <p class="font-label-sm text-label-sm text-on-error uppercase tracking-wider mb-xs">Tagihan Tertunggak</p>
                <h2 class="font-headline-lg text-headline-lg text-on-error">{{ $tagihanBelumLunas }}</h2>
            </div>
            <div class="bg-on-error/10 p-sm rounded-xl">
                <span class="material-symbols-outlined text-on-error text-3xl">warning</span>
            </div>
        </div>
        <p class="font-label-sm text-label-sm text-on-error/70">Tagihan belum dibayar</p>
    </div>

    <!-- Keluhan Aktif -->
    <div class="bg-gradient-to-br from-primary-fixed to-primary-fixed-dim rounded-2xl p-md shadow-sm hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start mb-md">
            <div>
                <p class="font-label-sm text-label-sm text-on-primary-fixed uppercase tracking-wider mb-xs">Keluhan Aktif</p>
                <h2 class="font-headline-lg text-headline-lg text-on-primary-fixed">{{ $laporanAktif }}</h2>
            </div>
            <div class="bg-on-primary-fixed/10 p-sm rounded-xl">
                <span class="material-symbols-outlined text-on-primary-fixed text-3xl">build</span>
            </div>
        </div>
        <p class="font-label-sm text-label-sm text-on-primary-fixed/70">Tiket perlu ditangani</p>
    </div>
</section>

<!-- Quick Actions -->
<section class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant mb-lg">
    <h3 class="font-headline-md text-headline-md text-on-surface mb-md">Aksi Cepat</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-sm">
        <button class="flex items-center justify-center gap-sm bg-primary text-on-primary py-sm px-md rounded-xl hover:bg-inverse-surface transition-colors">
            <span class="material-symbols-outlined">send</span>
            <span class="font-label-md text-label-md">Kirim Pengingat Tagihan</span>
        </button>
        <button class="flex items-center justify-center gap-sm bg-surface-container text-on-surface py-sm px-md rounded-xl border border-outline-variant hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">payments</span>
            <span class="font-label-md text-label-md">Kelola Keuangan</span>
        </button>
    </div>
</section>

<!-- Room Map -->
<section class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant mb-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-md gap-md">
        <h3 class="font-headline-md text-headline-md text-on-surface">Peta Kamar</h3>
        
        <!-- Legend -->
        <div class="flex flex-wrap gap-md">
            <div class="flex items-center gap-xs">
                <div class="w-4 h-4 rounded bg-secondary-container"></div>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Terisi</span>
            </div>
            <div class="flex items-center gap-xs">
                <div class="w-4 h-4 rounded bg-error-container"></div>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Menunggak</span>
            </div>
            <div class="flex items-center gap-xs">
                <div class="w-4 h-4 rounded bg-surface-container border-2 border-outline-variant border-dashed"></div>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Kosong</span>
            </div>
        </div>
    </div>

    @if(!$selectedKosId)
        <div class="flex flex-col items-center justify-center py-xl bg-surface-container rounded-xl border-2 border-outline-variant border-dashed">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant opacity-30 mb-md">home_work</span>
            <p class="font-headline-md text-headline-md text-on-surface mb-xs">Pilih Kos Terlebih Dahulu</p>
            <p class="font-body-md text-body-md text-on-surface-variant text-center max-w-md">Silakan pilih kos dari dropdown di atas untuk melihat peta kamar secara detail.</p>
        </div>
    @else
        <div class="grid grid-cols-5 md:grid-cols-10 gap-sm">
            @forelse($visualRooms as $room)
                @if($room['status'] == 'lunas')
                    <div class="aspect-square bg-secondary-container rounded-xl flex flex-col items-center justify-center shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                        <span class="material-symbols-outlined text-on-secondary-container text-sm">person</span>
                        <span class="font-label-sm text-label-sm font-bold text-on-secondary-container">{{ $room['number'] }}</span>
                    </div>
                @elseif($room['status'] == 'menunggak')
                    <div class="aspect-square bg-error-container rounded-xl flex flex-col items-center justify-center shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                        <span class="material-symbols-outlined text-error text-sm">warning</span>
                        <span class="font-label-sm text-label-sm font-bold text-error">{{ $room['number'] }}</span>
                    </div>
                @else
                    <div class="aspect-square bg-surface-container border-2 border-outline-variant border-dashed rounded-xl flex flex-col items-center justify-center hover:bg-surface-container-high transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-outline text-sm opacity-30">bed</span>
                        <span class="font-label-sm text-label-sm font-bold text-outline">{{ $room['number'] }}</span>
                    </div>
                @endif
            @empty
                <div class="col-span-full py-lg text-center text-on-surface-variant">Belum ada kamar di kos ini.</div>
            @endforelse
        </div>
    @endif
</section>

<!-- Recent Tickets -->
<section class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant mb-lg">
    <div class="flex justify-between items-center mb-md">
        <h3 class="font-headline-md text-headline-md text-on-surface">Keluhan Terbaru</h3>
        <a href="{{ route('ux2.owner.tickets.index') }}" class="font-label-md text-label-md text-secondary hover:text-secondary-fixed">Lihat Semua</a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
        @forelse($recentTickets as $ticket)
            <div class="bg-surface-container rounded-xl p-md border border-outline-variant hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-sm">
                    <h4 class="font-label-md text-label-md font-bold text-on-surface flex-1">{{ \Illuminate\Support\Str::limit($ticket->issue_description, 50) }}</h4>
                    @if($ticket->priority === 'urgent' || $ticket->priority === 'high')
                        <span class="bg-error-container text-error px-sm py-xs rounded-full font-label-sm text-label-sm">Urgent</span>
                    @else
                        <span class="bg-surface-container-high text-on-surface px-sm py-xs rounded-full font-label-sm text-label-sm">Normal</span>
                    @endif
                </div>
                <div class="flex items-center gap-xs text-on-surface-variant mb-xs">
                    <span class="material-symbols-outlined text-sm">home_work</span>
                    <span class="font-label-sm text-label-sm">{{ $ticket->room->boardingHouse->name ?? 'Unknown' }}</span>
                </div>
                <div class="flex items-center gap-xs text-on-surface-variant mb-md">
                    <span class="material-symbols-outlined text-sm">schedule</span>
                    <span class="font-label-sm text-label-sm">{{ $ticket->created_at->diffForHumans() }}</span>
                </div>
                <button class="w-full bg-primary text-on-primary py-xs px-sm rounded-lg hover:bg-inverse-surface transition-colors font-label-md text-label-md" onclick="window.location.href='{{ route('ux2.owner.tickets.show', $ticket->id) }}'">
                    Follow Up
                </button>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-xl">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant opacity-30 mb-sm">task_alt</span>
                <p class="font-body-md text-body-md text-on-surface-variant">Tidak ada keluhan aktif saat ini.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Occupancy Chart -->
<section class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant">
    <h3 class="font-headline-md text-headline-md text-on-surface mb-md">Tingkat Okupansi (3 Bulan Terakhir)</h3>
    <div class="relative h-64 w-full">
        <canvas id="occupancyChart"></canvas>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('occupancyChart').getContext('2d');
        
        const labels = {!! json_encode($occupancyTrends['labels']) !!};
        const dataValues = {!! json_encode($occupancyTrends['values']) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tingkat Okupansi (%)',
                    data: dataValues,
                    borderColor: '#6cf8bb',
                    backgroundColor: 'rgba(108, 248, 187, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6cf8bb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: '#e7eeff',
                            drawBorder: false,
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
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
                                return 'Okupansi: ' + context.parsed.y + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
