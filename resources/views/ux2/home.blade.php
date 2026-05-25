@extends('layouts.ux2.app')

@section('title', 'Beranda KosKu - Sewa Kos Mudah & Cerdas')

@section('styles')
<style>
    /* Ambient floating animation */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float-delayed {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }
    .animate-float-delayed {
        animation: float-delayed 8s ease-in-out infinite;
        animation-delay: 2s;
    }
</style>
@endsection

@section('content')
<!-- Hero Section (Midnight Navy & Fresh Mint Focus) -->
<section class="relative bg-primary-container pt-xl pb-[120px] px-margin-mobile md:px-margin-desktop overflow-hidden border-b border-surface-tint/20">
<!-- Decorative Background Elements -->
<div class="absolute inset-0 z-0 pointer-events-none">
<div class="absolute top-0 right-0 w-[800px] h-[800px] bg-[radial-gradient(circle,rgba(108,248,187,0.05)_0%,transparent_60%)] -translate-y-1/4 translate-x-1/4 rounded-full"></div>
<div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-[radial-gradient(circle,rgba(148,102,255,0.05)_0%,transparent_60%)] translate-y-1/4 -translate-x-1/4 rounded-full"></div>
<!-- Abstract floating geometry -->
<div class="absolute top-20 right-40 opacity-20 animate-float mix-blend-screen hidden md:block">
<svg fill="none" height="120" viewbox="0 0 120 120" width="120" xmlns="http://www.w3.org/2000/svg">
<path d="M60 0L111.962 30V90L60 120L8.03848 90V30L60 0Z" stroke="#6cf8bb" stroke-width="2"></path>
</svg>
</div>
</div>
<div class="max-w-[1440px] mx-auto relative z-10 flex flex-col items-center text-center">
<!-- Badge -->
<div class="inline-flex items-center gap-2 px-sm py-xs rounded-full bg-surface-tint/20 border border-surface-tint/30 backdrop-blur-md mb-lg">
<span class="w-2 h-2 rounded-full bg-secondary-container animate-pulse"></span>
<span class="font-label-sm text-label-sm text-inverse-primary uppercase tracking-wider">Navigasi Properti Premium</span>
</div>
<!-- Headline -->
<h1 class="font-display-lg text-display-lg text-on-primary max-w-4xl mb-md leading-tight">
                    Temukan Hunian Kos Sempurna dengan <span class="text-secondary-container">Akurasi Institusional</span>.
                </h1>
<!-- Subheadline -->
<p class="font-body-lg text-body-lg text-inverse-primary max-w-2xl mb-xl">
                    Platform pencarian properti berbasis data yang dirancang untuk memberikan transparansi, efisiensi, dan kenyamanan tanpa kompromi.
                </p>
<!-- Giant Search Bar Component -->
<form action="{{ route('ux2.search') }}" class="w-full max-w-5xl bg-surface-container-lowest rounded-[20px] p-sm flex flex-col md:flex-row gap-sm shadow-[0_20px_40px_rgba(0,0,0,0.3)] border border-surface-tint/30 animate-float-delayed" method="GET">
<div class="flex-1 flex items-center gap-sm px-sm py-xs border-b md:border-b-0 md:border-r border-outline-variant/30">
<span class="material-symbols-outlined text-outline">location_on</span>
<div class="flex flex-col flex-1 text-left">
<label class="font-label-sm text-label-sm text-outline">Lokasi atau Kampus</label>
<input class="w-full bg-transparent border-none p-0 focus:ring-0 font-body-md text-body-md text-on-surface placeholder-outline-variant" name="q" placeholder="Cari area..." type="text"/>
</div>
</div>
<div class="flex-1 flex items-center gap-sm px-sm py-xs border-b md:border-b-0 md:border-r border-outline-variant/30">
<span class="material-symbols-outlined text-outline">calendar_today</span>
<div class="flex flex-col flex-1 text-left">
<label class="font-label-sm text-label-sm text-outline">Durasi Sewa</label>
<select class="w-full bg-transparent border-none p-0 focus:ring-0 font-body-md text-body-md text-on-surface text-left cursor-pointer appearance-none">
<option>Bulanan</option>
<option>Tahunan</option>
</select>
</div>
</div>
<div class="flex-1 flex items-center gap-sm px-sm py-xs">
<span class="material-symbols-outlined text-outline">sell</span>
<div class="flex flex-col flex-1 text-left">
<label class="font-label-sm text-label-sm text-outline">Batas Harga</label>
<select class="w-full bg-transparent border-none p-0 focus:ring-0 font-body-md text-body-md text-on-surface text-left cursor-pointer appearance-none">
<option>Hingga Rp 3 Juta</option>
<option>Hingga Rp 5 Juta</option>
<option>Tanpa Batas</option>
</select>
</div>
</div>
<!-- CTA Primary Action -->
<button class="bg-secondary-container hover:bg-secondary-fixed text-on-secondary-container font-label-md text-label-md font-bold px-xl py-md rounded-xl flex items-center justify-center gap-2 transition-all duration-200 active:scale-95 shadow-[0_0_20px_rgba(108,248,187,0.3)]">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">search</span>
                        Cari Kos
                    </button>
</form>
</div>
</section>
<!-- KosBot AI Section (Soft Purple Accent) -->
<section class="py-xl px-margin-mobile md:px-margin-desktop bg-surface relative -mt-lg z-20">
<div class="max-w-[1440px] mx-auto">
<div class="bg-tertiary-fixed rounded-[32px] p-lg md:p-[64px] flex flex-col lg:flex-row items-center gap-xl relative overflow-hidden shadow-[0_10px_40px_rgba(35,0,92,0.08)] border border-tertiary-fixed-dim/30">
<!-- Decorative Background -->
<div class="absolute -right-40 -top-40 w-96 h-96 bg-surface-container-lowest opacity-40 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute left-0 bottom-0 w-64 h-64 bg-on-tertiary-container opacity-10 rounded-full blur-2xl pointer-events-none"></div>
<!-- Content -->
<div class="flex-1 z-10">
<div class="inline-flex items-center gap-2 bg-surface-container-lowest/60 backdrop-blur-sm px-sm py-xs rounded-lg mb-md border border-outline-variant/20">
<span class="material-symbols-outlined text-on-tertiary-container" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
<span class="font-label-sm text-label-sm font-bold text-on-tertiary-container uppercase tracking-widest">Inovasi Terbaru</span>
</div>
<h2 class="font-display-lg text-display-lg text-on-tertiary-fixed mb-md leading-tight">
                            Tanya <span class="text-on-tertiary-container">KosBot AI</span>
</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-lg max-w-xl">
                            Berhenti membuang waktu memfilter ribuan daftar. Ceritakan kebutuhan spesifik Anda kepada KosBot, dan biarkan AI kami mencocokkan Anda dengan properti yang tepat dalam hitungan detik.
                        </p>
<ul class="flex flex-col gap-sm mb-lg">
<li class="flex items-start gap-sm">
<span class="material-symbols-outlined text-on-tertiary-container">check_circle</span>
<span class="font-body-md text-body-md text-on-surface">Analisis preferensi lokasi dan gaya hidup.</span>
</li>
<li class="flex items-start gap-sm">
<span class="material-symbols-outlined text-on-tertiary-container">check_circle</span>
<span class="font-body-md text-body-md text-on-surface">Estimasi biaya komprehensif termasuk utilitas.</span>
</li>
</ul>
<a class="bg-tertiary-container text-on-tertiary font-label-md text-label-md font-bold px-lg py-sm rounded-xl inline-flex items-center gap-2 hover:bg-on-tertiary-fixed transition-colors" href="{{ route('ux2.bot') }}">
                            Mulai Percakapan <span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
</div>
<!-- Abstract Visual Representation of Chat -->
<div class="flex-1 w-full max-w-md relative z-10 h-[400px]">
<div class="absolute inset-0 bg-surface-container-lowest rounded-[24px] shadow-lg border border-outline-variant/20 overflow-hidden flex flex-col p-md">
<!-- Header -->
<div class="flex items-center gap-sm pb-sm border-b border-outline-variant/20 mb-md">
<div class="w-10 h-10 rounded-full bg-tertiary-fixed flex items-center justify-center">
<span class="material-symbols-outlined text-on-tertiary-container" style="font-variation-settings: 'FILL' 1;">smart_toy</span>
</div>
<div>
<div class="font-label-md text-label-md font-bold text-on-surface">KosBot Assistant</div>
<div class="font-label-sm text-label-sm text-secondary flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-secondary inline-block"></span> Online</div>
</div>
</div>
<!-- Chat bubbles -->
<div class="flex flex-col gap-md flex-1">
<div class="self-start bg-tertiary-container text-on-tertiary px-md py-sm rounded-2xl rounded-tl-sm font-body-md text-body-md max-w-[85%] shadow-sm relative">
<div class="absolute -left-2 -top-2 w-4 h-4 rounded-full bg-secondary-container flex items-center justify-center"><span class="material-symbols-outlined text-[10px] text-on-secondary-container">bolt</span></div>
                                    Mulai percakapan dengan KosBot untuk mencari hunian berdasarkan lokasi, budget, fasilitas, dan kebutuhan sewa.
                                </div>
</div>
<!-- Input fake -->
<div class="mt-auto bg-surface rounded-xl border border-outline-variant/30 p-sm flex items-center justify-between text-outline-variant">
<span class="font-body-md text-body-md">Ketik balasan...</span>
<span class="material-symbols-outlined text-on-tertiary-container">send</span>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Bento Grid / Curated Selection -->
<section class="py-xl px-margin-mobile md:px-margin-desktop bg-background">
<div class="max-w-[1440px] mx-auto">
<div class="flex justify-between items-end mb-lg">
<div>
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-xs">Kurasi Eksklusif</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Properti pilihan dengan standar kualitas institusional.</p>
</div>
<a class="hidden md:flex items-center gap-1 text-secondary font-label-md text-label-md font-bold hover:text-secondary-fixed transition-colors" href="{{ route('ux2.search') }}">
                        Lihat Semua <span class="material-symbols-outlined">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
@forelse ($recommendations as $house)
<a class="group rounded-[20px] overflow-hidden bg-surface-container-lowest border border-outline-variant/20 shadow-[0_4px_20px_rgba(15,23,42,0.05)] transition-all hover:shadow-[0_10px_30px_rgba(15,23,42,0.12)] flex flex-col" href="{{ route('ux2.kos.show', $house['id']) }}">
<div class="h-[220px] w-full overflow-hidden relative bg-surface-variant">
@if (!empty($house['primary_image']))
<img alt="{{ $house['name'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $house['primary_image'] }}"/>
@else
<div class="w-full h-full flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined text-5xl">home_work</span></div>
@endif
<div class="absolute top-sm left-sm z-20 flex gap-2">
<span class="bg-surface-container-lowest text-on-surface font-label-sm text-label-sm px-2 py-1 rounded-md shadow-sm">{{ $house['gender_label'] }}</span>
</div>
</div>
<div class="p-md flex flex-col gap-sm">
<h3 class="font-headline-md text-headline-md text-on-surface">{{ $house['name'] }}</h3>
<div class="flex items-center gap-1 font-body-md text-body-md text-on-surface-variant">
<span class="material-symbols-outlined text-[18px]">location_on</span> {{ $house['city'] }}
</div>
<div class="flex flex-wrap gap-2">
@foreach (($house['facility_preview'] ?? []) as $facility)
<span class="bg-surface-container px-2 py-1 rounded-md text-on-primary-container font-label-sm text-label-sm">{{ $facility['name'] }}</span>
@endforeach
</div>
<div class="mt-auto pt-sm border-t border-outline-variant/40">
<span class="font-label-sm text-label-sm text-on-surface-variant">Mulai dari</span>
<p class="font-headline-md text-headline-md text-secondary">{{ $house['min_price_formatted'] ?? 'Hubungi pemilik' }}</p>
</div>
</div>
</a>
@empty
<div class="md:col-span-3 bg-surface-container-lowest rounded-xl border border-outline-variant p-lg text-center">
<span class="material-symbols-outlined text-5xl text-outline mb-2">home_work</span>
<h3 class="font-headline-md text-headline-md text-primary">Belum ada rekomendasi kos</h3>
<p class="font-body-md text-body-md text-on-surface-variant mt-2">Data rekomendasi akan tampil setelah tersedia dari backend.</p>
</div>
@endforelse
</div>
</div>
</section>
@endsection
