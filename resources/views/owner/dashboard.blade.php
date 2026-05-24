
@extends('layouts.owner')

@section('title', 'Owner Dashboard')

@section('content')
<!-- Header & Filter Section -->
<section class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
    <div>
        <h1 class="font-display text-on-background font-bold tracking-tight text-4xl font-extrabold">Owner Dashboard</h1>
        <p class="text-base font-body text-on-surface-variant mt-2">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <div class="w-full md:w-auto">
        <form method="GET" action="{{ route('owner.dashboard') }}" class="flex items-center gap-2 bg-surface-container-low rounded-2xl p-2 border border-outline-variant shadow-sm">
            <span class="material-symbols-outlined text-on-surface-variant ml-2">home_work</span>
            <select name="kos_id" onchange="this.form.submit()" class="bg-transparent border-none text-on-surface font-headline font-semibold focus:ring-0 cursor-pointer min-w-[200px]">
                <option value="">Semua Kos (Portofolio)</option>
                @foreach($boardingHouses as $kos)
                    <option value="{{ $kos->id }}" {{ $selectedKosId == $kos->id ? 'selected' : '' }}>{{ $kos->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
</section>

<!-- Metrics 2x2 Grid -->
<section class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:grid-cols-2 mb-8">
    <!-- Metric: Kamar Terisi -->
    <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant flex flex-col justify-between cursor-default hover:shadow-md transition-shadow h-full">
        <div class="flex justify-between items-start mb-4">
            <p class="text-xl font-headline font-bold text-on-surface-variant">Kamar Terisi</p>
            <div class="bg-secondary-container p-3 rounded-2xl text-on-secondary-container">
                <span class="material-symbols-outlined text-[32px]">bed</span>
            </div>
        </div>
        <div>
            <h2 class="font-display font-bold text-on-background text-6xl">{{ $kamarTerisi['occupied'] }}<span class="text-2xl font-headline text-outline ml-1">/ {{ $kamarTerisi['total'] }}</span></h2>
        </div>
    </div>

    <!-- Metric: Pemasukan -->
    <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant flex flex-col justify-between cursor-default hover:shadow-md transition-shadow h-full">
        <div class="flex justify-between items-start mb-4">
            <p class="text-xl font-headline font-bold text-on-surface-variant">Pemasukan Bulan Ini</p>
            <div class="bg-green-100 p-3 rounded-2xl text-green-800">
                <span class="material-symbols-outlined text-[32px]">payments</span>
            </div>
        </div>
        <div>
            <h2 class="text-4xl md:text-5xl font-display font-bold text-on-background md:text-6xl truncate" title="Rp {{ number_format($totalPendapatan, 0, ',', '.') }}">
                Rp {{ number_format($totalPendapatan / 1000000, 1, ',', '.') }}<span class="text-2xl">JT</span>
            </h2>
        </div>
    </div>

    <!-- Metric: Tertunggak -->
    <div class="bg-error-container rounded-3xl p-6 shadow-sm border border-error/30 flex flex-col justify-between cursor-default hover:shadow-md transition-shadow h-full">
        <div class="flex justify-between items-start mb-4">
            <p class="text-xl font-headline font-bold text-error">Tagihan Tertunggak</p>
            <div class="bg-white/50 p-3 rounded-2xl text-error">
                <span class="material-symbols-outlined text-[32px]">warning</span>
            </div>
        </div>
        <div>
            <h2 class="font-display font-bold text-error text-6xl">{{ $tagihanBelumLunas }}<span class="text-2xl font-headline text-error/70 ml-1">Tagihan</span></h2>
        </div>
    </div>

    <!-- Metric: Tiket Aktif -->
    <div class="bg-surface-container-lowest rounded-3xl p-6 shadow-sm border border-outline-variant flex flex-col justify-between cursor-default hover:shadow-md transition-shadow h-full">
        <div class="flex justify-between items-start mb-4">
            <p class="text-xl font-headline font-bold text-on-surface-variant">Keluhan Aktif</p>
            <div class="bg-blue-50 p-3 rounded-2xl text-blue-600">
                <span class="material-symbols-outlined text-[32px]">build</span>
            </div>
        </div>
        <div>
            <h2 class="font-display font-bold text-on-background text-6xl">{{ $laporanAktif }}<span class="text-2xl font-headline text-outline ml-1">Tiket</span></h2>
        </div>
    </div>
</section>

<!-- Actions Panel -->
<section class="bg-surface-container-lowest p-6 rounded-3xl shadow-sm border border-outline-variant mb-8">
    <h3 class="text-xl font-headline font-bold text-on-background mb-4">Tindakan Cepat</h3>
    <div class="flex flex-col sm:flex-row gap-4">
        <button class="flex-1 bg-primary text-on-primary text-base font-headline py-4 px-6 rounded-2xl shadow-sm hover:bg-inverse-surface transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">send</span>
            Kirim Pengingat Tagihan
        </button>
        <button class="flex-1 bg-surface-container text-on-surface text-base font-headline py-4 px-6 rounded-2xl border border-outline-variant hover:bg-surface-container-highest transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">receipt_long</span>
            Catat Pengeluaran
        </button>
    </div>
</section>

<!-- Map: Room Grid -->
<section class="bg-surface-container-lowest p-6 rounded-3xl shadow-sm border border-outline-variant mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-xl font-headline font-bold text-on-background">Peta Kamar</h3>
        <!-- Legend -->
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-green-50 border border-green-500"></div>
                <span class="text-sm font-body text-on-surface-variant">Lunas / Terisi</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-error-container border border-error"></div>
                <span class="text-sm font-body text-on-surface-variant">Menunggak</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 rounded bg-surface-container border border-outline border-dashed"></div>
                <span class="text-sm font-body text-on-surface-variant">Kosong</span>
            </div>
        </div>
    </div>

    @if(!$selectedKosId)
        <div class="flex flex-col items-center justify-center py-12 bg-surface-container-lowest rounded-2xl border border-outline-variant border-dashed">
            <span class="material-symbols-outlined text-[48px] text-on-surface-variant opacity-50 mb-4">home_work</span>
            <p class="font-headline font-bold text-on-surface text-lg">Pilih Kos Terlebih Dahulu</p>
            <p class="font-body text-on-surface-variant text-sm mt-2 text-center max-w-md">Silakan pilih spesifik Kos dari dropdown di bagian atas halaman untuk melihat Peta Kamar secara mendetail.</p>
        </div>
    @else
        <!-- Grid Layout -->
        <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-10 gap-3">
            @forelse($visualRooms as $room)
                @if($room['status'] == 'lunas')
                    <div class="aspect-square bg-green-50 border border-green-500 rounded-xl flex flex-col items-center justify-center text-sm font-headline font-bold text-green-700 shadow-sm cursor-pointer hover:bg-green-100 transition-colors">
                        <span class="material-symbols-outlined text-[20px] mb-1 opacity-50">person</span>
                        {{ $room['number'] }}
                    </div>
                @elseif($room['status'] == 'menunggak')
                    <div class="aspect-square bg-error-container border border-error rounded-xl flex flex-col items-center justify-center text-sm font-headline font-bold text-error shadow-sm cursor-pointer hover:bg-red-200 transition-colors relative">
                        <span class="material-symbols-outlined text-[20px] mb-1">warning</span>
                        {{ $room['number'] }}
                    </div>
                @else
                    <div class="aspect-square bg-surface-container border border-outline rounded-xl flex flex-col items-center justify-center text-sm font-headline font-bold text-outline border-dashed cursor-pointer hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-[20px] mb-1 opacity-30">bed</span>
                        {{ $room['number'] }}
                    </div>
                @endif
            @empty
                <div class="col-span-full py-8 text-center text-on-surface-variant">Belum ada kamar di kos ini.</div>
            @endforelse
        </div>
    @endif
</section>

<!-- Daftar Komplain & Perbaikan -->
<section class="bg-surface-container-lowest p-6 rounded-3xl shadow-sm border border-outline-variant mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-headline font-bold text-on-background">Daftar Komplain &amp; Perbaikan</h3>
        <a href="#" class="text-sm font-bold text-[#0D9488] hover:underline">Lihat Semua</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($recentTickets as $ticket)
            <div class="bg-white border border-outline-variant rounded-2xl p-5 flex flex-col gap-4">
                <div class="flex justify-between items-start">
                    <h4 class="text-lg font-headline font-bold text-on-background truncate pr-2" title="{{ $ticket->issue_description }}">{{ \Illuminate\Support\Str::limit($ticket->issue_description, 40) }}</h4>
                    @if($ticket->priority === 'urgent' || $ticket->priority === 'high')
                        <span class="bg-error-container text-error px-3 py-1 rounded-full text-xs font-label font-bold whitespace-nowrap">Urgent</span>
                    @else
                        <span class="bg-surface-container-high text-on-surface px-3 py-1 rounded-full text-xs font-label font-bold whitespace-nowrap">Normal</span>
                    @endif
                </div>
                <div class="text-sm font-body text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">home_work</span>
                    <span class="truncate">{{ $ticket->room->boardingHouse->name ?? 'Unknown' }}</span>
                </div>
                <div class="text-sm font-body text-on-surface-variant flex items-center gap-2 mt-[-8px]">
                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    <span>{{ $ticket->created_at->diffForHumans() }}</span>
                </div>
                <button class="mt-auto w-full bg-primary hover:bg-inverse-surface text-on-primary text-sm font-headline py-3 px-6 rounded-xl transition-colors">
                    Follow Up
                </button>
            </div>
        @empty
            <div class="col-span-full py-8 text-center text-on-surface-variant flex flex-col items-center">
                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">task_alt</span>
                <p class="font-body text-sm">Tidak ada komplain aktif saat ini.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Occupancy Trends (Dummy Data) -->
<section class="bg-surface-container-lowest p-6 rounded-3xl shadow-sm border border-outline-variant">
    <h3 class="text-xl font-headline font-bold text-on-background mb-6">Occupancy Trends (Simulasi)</h3>
    <div class="flex flex-col gap-4">
        <div class="flex items-center gap-4">
            <span class="w-24 text-sm font-headline font-bold text-on-surface-variant">Bulan Ini</span>
            <div class="flex-1 bg-surface-container-high rounded-full h-4 overflow-hidden">
                <div class="bg-primary h-full rounded-full" style="width: 84%"></div>
            </div>
            <span class="w-12 text-right text-sm font-headline font-bold text-on-background">84%</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="w-24 text-sm font-headline font-bold text-on-surface-variant">Bulan Lalu</span>
            <div class="flex-1 bg-surface-container-high rounded-full h-4 overflow-hidden">
                <div class="bg-primary h-full rounded-full" style="width: 82%"></div>
            </div>
            <span class="w-12 text-right text-sm font-headline font-bold text-on-background">82%</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="w-24 text-sm font-headline font-bold text-on-surface-variant">2 Bulan Lalu</span>
            <div class="flex-1 bg-surface-container-high rounded-full h-4 overflow-hidden">
                <div class="bg-primary h-full rounded-full" style="width: 85%"></div>
            </div>
            <span class="w-12 text-right text-sm font-headline font-bold text-on-background">85%</span>
        </div>
    </div>
</section>
@endsection
