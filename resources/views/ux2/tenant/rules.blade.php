@extends('layouts.ux2.tenant')

@section('title', 'Peraturan Kos - KosKu')

@section('content')
<div class="px-6 py-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="max-w-2xl">
            <div class="w-16 h-16 rounded-2xl bg-secondary-container text-on-secondary-container flex items-center justify-center mb-6 shadow-sm">
                <span class="material-symbols-outlined text-4xl">gavel</span>
            </div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary mb-3">
                Peraturan & Tata Tertib
            </h1>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Panduan hidup bersama di {{ $activeContract?->room?->boardingHouse?->name ?? 'KosKu' }} demi kenyamanan bersama.
            </p>
        </div>
        <!-- Search Pill -->
        <div class="relative w-full md:w-80 shrink-0">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input class="w-full bg-surface-container border border-outline-variant rounded-full py-3 pl-12 pr-6 focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant transition-all shadow-sm" placeholder="Cari peraturan..." type="text">
        </div>
    </div>

    {{-- Kos Selector --}}
    @if(isset($allActiveContracts) && count($allActiveContracts) > 1)
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="material-symbols-outlined text-primary">home_work</span>
            <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Pilih Kos</h3>
        </div>
        <p class="font-body-md text-body-md text-on-surface-variant mb-6">Pilih kos untuk melihat peraturannya.</p>
        
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

    <!-- Bento Grid of Rules -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
        @if($activeContract && $activeContract->room?->boardingHouse?->rules->count() > 0)
            @php
                $rulesByCategory = $activeContract->room->boardingHouse->rules->groupBy('category');
                $icons = [
                    'Akses & Keamanan' => 'security',
                    'Tamu & Kunjungan' => 'group',
                    'Kebersihan & Fasilitas' => 'cleaning_services',
                    'Larangan Keras' => 'warning',
                ];
            @endphp

            @foreach($rulesByCategory as $category => $rules)
                @php
                    $isHighPriority = str_contains(strtolower($category), 'larangan');
                    $cfg = $isHighPriority ? [
                        'bg' => 'bg-error-container text-on-error-container border border-error/20 hover:shadow-md',
                        'blob' => 'bg-error/10',
                        'icon_bg' => 'bg-error text-on-error',
                        'text_color' => 'text-on-error-container',
                        'bullet' => 'close',
                        'bullet_color' => 'text-error'
                    ] : [
                        'bg' => 'bg-surface-container-lowest border border-outline-variant/50 hover:shadow-md',
                        'blob' => 'bg-surface-container',
                        'icon_bg' => 'bg-secondary-container text-on-secondary-container',
                        'text_color' => 'text-primary',
                        'bullet' => 'check_circle',
                        'bullet_color' => 'text-secondary'
                    ];
                    $icon = $icons[$category] ?? 'gavel';
                @endphp
                <div class="{{ $cfg['bg'] }} rounded-2xl p-6 md:p-8 shadow-sm relative overflow-hidden transition-all duration-300">
                    <div class="absolute -top-10 -right-10 w-40 h-40 {{ $cfg['blob'] }} rounded-full blur-2xl -z-10"></div>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl {{ $cfg['icon_bg'] }} flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1;">{{ $icon }}</span>
                        </div>
                        <h2 class="font-headline-sm text-headline-sm font-bold {{ $cfg['text_color'] }}">{{ $category }}</h2>
                    </div>
                    <ul class="space-y-4 font-body-md text-body-md text-on-surface">
                        @foreach($rules as $rule)
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined {{ $cfg['bullet_color'] }} text-[20px] mt-0.5">{{ $cfg['bullet'] }}</span>
                                <span class="{{ $isHighPriority ? 'text-on-error-container font-medium' : 'text-on-surface' }}">{{ $rule->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        @else
            <div class="col-span-1 lg:col-span-2 bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-16 flex flex-col items-center justify-center text-center shadow-sm">
                <span class="material-symbols-outlined text-on-surface-variant text-7xl mb-6">gavel</span>
                <h3 class="font-headline-md text-headline-md font-semibold text-on-surface mb-2">Belum Ada Peraturan</h3>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-sm">
                    Saat ini belum ada peraturan yang ditambahkan untuk properti Anda, atau Anda belum memiliki hunian aktif.
                </p>
            </div>
        @endif
    </div>

    <!-- Contact Footer -->
    <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
        <div class="text-center md:text-left">
            <h3 class="font-headline-sm text-headline-sm font-bold text-primary mb-2">Butuh Bantuan?</h3>
            <p class="font-body-md text-body-md text-on-surface-variant">Ada pertanyaan atau keraguan? Hubungi pemilik untuk informasi lebih lanjut.</p>
        </div>
        @if(isset($activeContract) && $activeContract?->room?->boardingHouse?->owner?->phone_number)
        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $activeContract->room->boardingHouse->owner->phone_number) }}" target="_blank"
           class="w-full md:w-auto bg-primary text-on-primary py-3.5 px-8 rounded-xl font-label-md text-label-md font-semibold hover:bg-opacity-90 transition-opacity whitespace-nowrap text-center inline-flex items-center justify-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">chat</span>
            Hubungi Pemilik
        </a>
        @endif
    </div>

</div>
@endsection
