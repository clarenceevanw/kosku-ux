@extends('layouts.tenant', ['activeContract' => $activeContract ?? null])

@section('title', 'Peraturan & Tata Tertib')

@section('content')

{{-- ══════════════════════════════════════════════════════════
     Header
══════════════════════════════════════════════════════════ --}}
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
    <div class="max-w-2xl">
        <h1 class="font-display text-4xl md:text-5xl font-bold text-on-surface tracking-tight mb-4">
            Peraturan & Tata Tertib
        </h1>
        <p class="font-body text-base text-on-surface-variant mt-2">
            Panduan hidup bersama di {{ $activeContract?->room?->boardingHouse?->name ?? 'KosKu' }} demi kenyamanan bersama.
        </p>
    </div>
    {{-- Search Pill --}}
    <div class="relative w-full md:w-72 shrink-0">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
        <input class="w-full bg-surface-container-low text-on-surface placeholder:text-on-surface-variant rounded-full py-3.5 pl-12 pr-6 border border-outline-variant/30 focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none font-body text-sm transition-all" placeholder="Cari peraturan..." type="text">
    </div>
</div>

{{-- Kos Selector --}}
<x-kos-selector :activeContracts="$allActiveContracts" :selectedContract="$activeContract" />

{{-- ══════════════════════════════════════════════════════════
     Bento Grid of Rules
══════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-16">

    @if($activeContract && $activeContract->room?->boardingHouse?->rules->count() > 0)
        @php
            $rulesByCategory = $activeContract->room->boardingHouse->rules->groupBy('category');
            $icons = [
                'Akses & Keamanan' => 'security',
                'Tamu & Kunjungan' => 'group',
                'Kebersihan & Fasilitas' => 'cleaning_services',
                'Larangan Keras' => 'warning',
            ];
            $colorConfig = [
                'Larangan Keras' => [
                    'bg' => 'bg-red-50 border border-red-100 group-hover:bg-red-100/50',
                    'blob' => 'bg-red-100/50',
                    'icon_bg' => 'bg-red-100 border border-red-200 text-red-600',
                    'text_color' => 'text-red-600',
                    'bullet' => 'close',
                ]
            ];
        @endphp

        @foreach($rulesByCategory as $category => $rules)
            @php
                $isHighPriority = str_contains(strtolower($category), 'larangan');
                $cfg = $isHighPriority ? $colorConfig['Larangan Keras'] : [
                    'bg' => 'bg-surface-container-lowest border border-outline-variant/50 group-hover:shadow-lg',
                    'blob' => 'bg-surface-container-low opacity-50',
                    'icon_bg' => 'bg-surface-container text-primary',
                    'text_color' => 'text-primary',
                    'bullet' => 'check_circle',
                ];
                $icon = $icons[$category] ?? 'gavel';
            @endphp
            <div class="{{ $cfg['bg'] }} rounded-2xl p-8 shadow-sm relative overflow-hidden group transition-all duration-300">
                <div class="absolute top-0 right-0 w-32 h-32 {{ $cfg['blob'] }} rounded-bl-full -z-10 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full {{ $cfg['icon_bg'] }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1;">{{ $icon }}</span>
                    </div>
                    <h2 class="font-headline text-2xl font-bold {{ $cfg['text_color'] }}">{{ $category }}</h2>
                </div>
                <ul class="space-y-4 font-body text-sm text-on-surface">
                    @foreach($rules as $rule)
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined {{ $cfg['text_color'] }} text-[18px] mt-0.5">{{ $cfg['bullet'] }}</span>
                            <span>{{ $rule->rule_text }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @else
        <div class="col-span-1 lg:col-span-2 bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-16 flex flex-col items-center justify-center text-center shadow-sm">
            <span class="material-symbols-outlined text-on-surface-variant text-7xl mb-6">gavel</span>
            <h3 class="font-headline text-xl font-semibold text-on-surface mb-2">Belum Ada Peraturan</h3>
            <p class="font-body text-base text-on-surface-variant max-w-sm">
                Saat ini belum ada peraturan yang ditambahkan untuk properti Anda, atau Anda belum memiliki hunian aktif.
            </p>
        </div>
    @endif

</div>

{{-- ══════════════════════════════════════════════════════════
     Contact Box Footer
══════════════════════════════════════════════════════════ --}}
<div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-sm">
    <div>
        <h3 class="font-headline text-xl font-bold text-primary mb-2">Butuh Bantuan?</h3>
        <p class="font-body text-sm text-on-surface-variant">Ada pertanyaan atau keraguan? Hubungi pemilik untuk informasi lebih lanjut.</p>
    </div>
    @if(isset($activeContract) && $activeContract?->room?->boardingHouse?->owner?->phone_number)
    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $activeContract->room->boardingHouse->owner->phone_number) }}" target="_blank"
       class="w-full sm:w-auto bg-primary text-on-primary py-3.5 px-8 rounded-full font-label text-sm hover:opacity-90 transition-opacity whitespace-nowrap text-center inline-flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-[18px]">chat</span>
        Hubungi Pemilik
    </a>
    @endif
</div>

@endsection
