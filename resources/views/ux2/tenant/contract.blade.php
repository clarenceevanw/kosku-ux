@extends('layouts.ux2.tenant')

@section('title', 'Kontrak Digital - KosKu')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary mb-2">Kontrak Digital</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Kelola dan lihat dokumen kontrak sewa Anda.</p>
        </div>
        @if(isset($activeContract) && $activeContract->contract && $activeContract->contract->pdf_url)
        <a href="{{ $activeContract->contract->pdf_url }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-secondary-container text-on-secondary-container rounded-xl font-label-md text-label-md font-medium hover:bg-secondary-container/80 transition-colors shadow-sm">
            <span class="material-symbols-outlined">download</span>
            Unduh PDF
        </a>
        @endif
    </div>

    {{-- Kos Selector --}}
    @if(isset($allActiveContracts) && count($allActiveContracts) > 1)
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="material-symbols-outlined text-primary">home_work</span>
            <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Pilih Kos yang Ingin Dilihat</h3>
        </div>
        <p class="font-body-md text-body-md text-on-surface-variant mb-6">Anda memiliki {{ count($allActiveContracts) }} kos aktif. Pilih salah satu untuk melihat kontraknya.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($allActiveContracts as $contract)
            <a href="?kos={{ $contract->id }}" 
               class="group relative flex items-center gap-4 p-4 border-2 {{ (isset($activeContract) && $activeContract->id == $contract->id) ? 'border-primary bg-primary/5' : 'border-outline-variant hover:border-outline' }} rounded-xl transition-all cursor-pointer">
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
                    @if($contract->room?->image_url)
                    <img src="{{ $contract->room->image_url }}" alt="{{ $contract->room->boardingHouse->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-surface-variant">bed</span>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-label-lg text-label-lg font-semibold text-on-surface truncate">{{ $contract->room->boardingHouse->name }}</h4>
                    <p class="font-body-md text-body-md text-on-surface-variant">{{ $contract->room->type_name }}</p>
                    <p class="font-label-md text-label-md text-on-surface-variant mt-1">{{ $contract->room->boardingHouse->city }}</p>
                </div>
                @if(isset($activeContract) && $activeContract->id == $contract->id)
                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-on-primary text-[16px]" style="font-variation-settings: 'FILL' 1;">check</span>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if(!$activeContract || !$activeContract->contract)
        {{-- No Contract Empty State --}}
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-16 flex flex-col items-center justify-center text-center shadow-sm">
            <span class="material-symbols-outlined text-on-surface-variant text-7xl mb-6">description</span>
            <h3 class="font-headline-md text-headline-md font-semibold text-on-surface mb-2">Belum Ada Kontrak</h3>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-sm">
                Anda belum memiliki kontrak sewa yang aktif.
            </p>
        </div>
    @else
        @php
            $contractData = $activeContract->contract;
            $room     = $activeContract->room;
            $house    = $room?->boardingHouse;
            $owner    = $house?->owner;
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Contract Document -->
            <div class="md:col-span-2 space-y-6">
                <!-- Contract Info Card -->
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6 pb-6 border-b border-outline-variant/30">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-label-sm text-label-sm font-bold uppercase tracking-widest text-on-surface-variant">No. Kontrak</span>
                                <span class="font-label-sm text-label-sm font-black text-primary bg-primary/10 px-3 py-1 rounded-full border border-primary/20">
                                    {{ $contractData->contract_number }}
                                </span>
                            </div>
                            <h3 class="font-headline-md text-headline-md font-bold text-on-surface">{{ $house?->name }}</h3>
                            <p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-1.5 mt-1">
                                <span class="material-symbols-outlined text-[18px]">location_on</span>
                                {{ $house?->address }}, {{ $house?->city }}
                            </p>
                        </div>
                        @php
                            $statusConf = match($contractData->status->value ?? $contractData->status) {
                                'active'    => ['label' => 'Aktif',     'class' => 'bg-secondary-container text-on-secondary-container border-secondary/30'],
                                'expired'   => ['label' => 'Berakhir',  'class' => 'bg-surface-variant text-on-surface-variant border-outline-variant'],
                                'cancelled' => ['label' => 'Dibatalkan','class' => 'bg-error-container text-on-error-container border-error/30'],
                                default     => ['label' => '—',         'class' => 'bg-surface-variant text-on-surface-variant border-outline-variant'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border self-start {{ $statusConf['class'] }}">
                            <span class="w-2 h-2 rounded-full bg-current"></span>
                            {{ $statusConf['label'] }}
                        </span>
                    </div>

                    <!-- Contract Grid Info -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Mulai Sewa</p>
                            <p class="font-body-md text-body-md font-semibold text-on-surface">
                                {{ $contractData->start_date?->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Berakhir</p>
                            <p class="font-body-md text-body-md font-semibold text-on-surface">
                                {{ $contractData->end_date?->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Durasi</p>
                            <p class="font-body-md text-body-md font-semibold text-on-surface">{{ $durationMonths }} Bulan</p>
                        </div>
                        <div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-1">Sisa Waktu</p>
                            <p class="font-body-md text-body-md font-semibold text-on-surface">{{ $remainingTime ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 pt-6 border-t border-outline-variant/30">
                        <div class="bg-surface-container rounded-xl p-4 sm:p-5">
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Biaya Bulanan</p>
                            <p class="font-headline-sm text-headline-sm font-bold text-primary">
                                Rp {{ number_format($contractData->monthly_fee, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-surface-container rounded-xl p-4 sm:p-5">
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Deposit</p>
                            <p class="font-headline-sm text-headline-sm font-bold text-primary">
                                Rp {{ number_format($contractData->deposit_fee, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Digital Signatures Info -->
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-headline-sm text-headline-sm font-semibold text-primary mb-6 border-b border-outline-variant/30 pb-2">Status Tanda Tangan Digital</h3>
                    <div class="space-y-6">
                        {{-- Tenant signature --}}
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
                                        {{ $contractData->tenant_signature_date ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container text-on-surface-variant' }}">
                                <span class="material-symbols-outlined text-[24px]">
                                    {{ $contractData->tenant_signature_date ? 'verified' : 'pending' }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="font-label-md text-label-md font-semibold text-on-surface">Tanda Tangan Penyewa</p>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">
                                    @if($contractData->tenant_signature_date)
                                        Ditandatangani pada {{ $contractData->tenant_signature_date->translatedFormat('d M Y, H:i') }} WIB
                                    @else
                                        Belum ditandatangani
                                    @endif
                                </p>
                            </div>
                            @if($contractData->tenant_signature_date)
                                <span class="text-sm font-semibold text-secondary flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">check</span> Selesai</span>
                            @else
                                <span class="text-sm font-semibold text-warning">Menunggu</span>
                            @endif
                        </div>

                        {{-- Owner signature --}}
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0
                                        {{ $contractData->owner_signature_date ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container text-on-surface-variant' }}">
                                <span class="material-symbols-outlined text-[24px]">
                                    {{ $contractData->owner_signature_date ? 'verified' : 'pending' }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="font-label-md text-label-md font-semibold text-on-surface">Tanda Tangan Pemilik</p>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">
                                    @if($contractData->owner_signature_date)
                                        Ditandatangani pada {{ $contractData->owner_signature_date->translatedFormat('d M Y, H:i') }} WIB
                                    @else
                                        Belum ditandatangani
                                    @endif
                                </p>
                            </div>
                            @if($contractData->owner_signature_date)
                                <span class="text-sm font-semibold text-secondary flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">check</span> Selesai</span>
                            @else
                                <span class="text-sm font-semibold text-warning">Menunggu</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                {{-- Room Card --}}
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-4">Detail Kamar</h3>
                    @if($room?->image_url)
                    <div class="aspect-video rounded-xl overflow-hidden bg-surface-container mb-4">
                        <img src="{{ $room->image_url }}" alt="{{ $room->type_name }}" class="w-full h-full object-cover">
                    </div>
                    @endif
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Tipe Kamar</span>
                            <span class="font-label-sm text-label-sm font-semibold text-on-surface">{{ $room?->type_name }}</span>
                        </div>
                        @if($room?->size)
                        <div class="flex justify-between items-center">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Ukuran</span>
                            <span class="font-label-sm text-label-sm font-semibold text-on-surface">{{ $room->size }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">Harga/Bulan</span>
                            <span class="font-label-sm text-label-sm font-semibold text-primary">
                                Rp {{ number_format($room?->price_per_month ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Owner Card --}}
                @if($owner)
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-4">Pemilik Kos</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-on-primary font-bold text-lg shrink-0">
                            {{ strtoupper(substr($owner->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-label-md text-label-md font-semibold text-on-surface">{{ $owner->name }}</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $owner->email }}</p>
                        </div>
                    </div>
                    @if($owner->phone_number)
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $owner->phone_number) }}"
                       target="_blank"
                       class="w-full flex items-center justify-center gap-2 py-3 bg-secondary-container text-on-secondary-container rounded-xl font-label-md text-label-md font-semibold hover:bg-secondary-container/80 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                        Hubungi via WhatsApp
                    </a>
                    @endif
                </div>
                @endif
                
                <!-- Actions -->
                <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
                    <h3 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-4">Tindakan</h3>
                    <div class="space-y-3">
                        <button class="w-full py-2.5 bg-surface text-primary border border-outline-variant rounded-xl font-label-md text-label-md hover:bg-surface-container transition-colors shadow-sm text-left px-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-on-surface-variant">history</span>
                            Riwayat Kontrak
                        </button>
                        <button class="w-full py-2.5 bg-surface text-primary border border-outline-variant rounded-xl font-label-md text-label-md hover:bg-surface-container transition-colors shadow-sm text-left px-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-on-surface-variant">edit_document</span>
                            Ajukan Perpanjangan
                        </button>
                        <button class="w-full py-2.5 bg-error-container text-on-error-container border border-error/20 rounded-xl font-label-md text-label-md hover:bg-error-container/80 transition-colors shadow-sm text-left px-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-error">logout</span>
                            Ajukan Pindah/Keluar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
