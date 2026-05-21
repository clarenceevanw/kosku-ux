@extends('layouts.app')

@section('content')
<main class="pt-20">

    {{-- ══════════════════════════════════════
         Hero Gallery
    ══════════════════════════════════════ --}}
    <section class="w-full relative h-[520px] min-h-[360px] bg-gray-100">
        @php $heroRoom = collect($boardingHouse['rooms'] ?? [])->first(); @endphp
        @if($heroRoom && $heroRoom['image_url'])
            <img alt="{{ $boardingHouse['name'] }}"
                 class="w-full h-full object-cover"
                 src="{{ $heroRoom['image_url'] }}">
        @else
            <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                <span class="material-symbols-outlined text-slate-400 text-8xl">home</span>
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        <div class="absolute bottom-6 right-6 flex gap-3">
            <button class="bg-white/90 backdrop-blur-md text-[#111827] px-6 py-3 rounded-full text-sm font-bold flex items-center gap-2 hover:bg-white transition-colors shadow-lg">
                <span class="material-symbols-outlined">grid_view</span>
                Lihat Semua Foto
            </button>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         Main Content
    ══════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-5 md:px-16 py-12">
        <div class="flex flex-col lg:flex-row gap-10">

            {{-- ── Left: Details (2/3) ──────────────────────────────── --}}
            <div class="lg:w-2/3 flex flex-col gap-10">

                {{-- Header --}}
                <div>
                    <div class="flex items-center gap-2 mb-4 flex-wrap">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">verified</span> Terverifikasi
                        </span>
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $boardingHouse['gender_label'] }}
                        </span>
                        @if($boardingHouse['avg_rating'])
                        <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1">star</span>
                            {{ $boardingHouse['rating_formatted'] }} ({{ $boardingHouse['review_count'] }} ulasan)
                        </span>
                        @endif
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-extrabold text-[#111827] mb-3 leading-tight">
                        {{ $boardingHouse['name'] }}
                    </h1>
                    <p class="text-gray-500 flex items-center gap-2 text-base">
                        <span class="material-symbols-outlined text-[20px]">location_on</span>
                        {{ $boardingHouse['address'] }}, {{ $boardingHouse['city'] }}
                    </p>

                    {{-- Highlight Bento --}}
                    @if(!empty($boardingHouse['facility_preview']))
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                        @foreach(array_slice($boardingHouse['facility_preview'], 0, 4) as $f)
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center gap-2">
                            <span class="material-symbols-outlined text-[28px] text-[#111827]">{{ $f['icon'] }}</span>
                            <span class="text-xs font-bold text-gray-600">{{ $f['name'] }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <hr class="border-gray-100">

                {{-- Tentang Kos --}}
                <div>
                    <h2 class="text-xl font-bold text-[#111827] mb-4">Tentang Kos Ini</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $boardingHouse['description'] ?? 'Tidak ada deskripsi tersedia.' }}
                    </p>
                </div>

                <hr class="border-gray-100">

                {{-- Tipe Kamar --}}
                @if(!empty($boardingHouse['rooms']))
                <div>
                    <h2 class="text-xl font-bold text-[#111827] mb-6">Tipe Kamar</h2>
                    <div class="space-y-4">
                        @foreach($boardingHouse['rooms'] as $room)
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row gap-5">
                            @if($room['image_url'])
                            <div class="w-full md:w-36 h-32 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                <img alt="{{ $room['type_name'] }}" class="w-full h-full object-cover" src="{{ $room['image_url'] }}">
                            </div>
                            @endif
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-[#111827] text-base">{{ $room['type_name'] }}</h3>
                                    @if($room['is_available'])
                                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Tersedia {{ $room['stock'] }} unit</span>
                                    @else
                                        <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-[10px] font-bold">Penuh</span>
                                    @endif
                                </div>
                                @if($room['size'])
                                <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">straighten</span> {{ $room['size'] }}
                                </p>
                                @endif
                                @if(!empty($room['facilities']))
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    @foreach($room['facilities'] as $rf)
                                    <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded-full text-[10px] font-semibold text-slate-500">{{ $rf['name'] }}</span>
                                    @endforeach
                                </div>
                                @endif
                                <p class="text-lg font-extrabold text-[#0D9488]">
                                    {{ $room['price_formatted'] }}<span class="text-sm font-normal text-gray-400">/bln</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <hr class="border-gray-100">
                @endif

                {{-- Fasilitas --}}
                @if(!empty($boardingHouse['facilities']))
                <div>
                    <h2 class="text-xl font-bold text-[#111827] mb-6">Fasilitas</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($boardingHouse['facilities'] as $facility)
                        <div class="flex items-center gap-3 text-gray-600 text-sm">
                            <span class="material-symbols-outlined text-gray-400 text-[20px]">{{ $facility['icon'] }}</span>
                            {{ $facility['name'] }}
                        </div>
                        @endforeach
                    </div>
                </div>
                <hr class="border-gray-100">
                @endif

                {{-- Aturan Kos --}}
                @if(!empty($boardingHouse['rules']))
                <div>
                    <h2 class="text-xl font-bold text-[#111827] mb-6">Aturan Kos</h2>
                    <ul class="flex flex-col gap-3">
                        @foreach($boardingHouse['rules'] as $rule)
                        <li class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <span class="material-symbols-outlined text-[#111827] mt-0.5 text-[20px]">do_not_disturb</span>
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ $rule['category'] }}</h4>
                                <p class="text-sm text-gray-600">{{ $rule['rule_text'] }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <hr class="border-gray-100">
                @endif

                {{-- Ulasan --}}
                @if(!empty($boardingHouse['reviews']))
                <div>
                    <h2 class="text-xl font-bold text-[#111827] mb-6">Ulasan Penyewa</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($boardingHouse['reviews'] as $review)
                        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-gray-400 text-sm">person</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#111827]">{{ $review['tenant_name'] ?? 'Penyewa Anonim' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $review['created_at'] }}</p>
                                </div>
                                <div class="ml-auto flex items-center gap-0.5 text-amber-400">
                                    @for($s = 0; $s < $review['rating']; $s++)
                                    <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">star</span>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 italic">{{ $review['comment'] ?? '' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- ── Right: Booking Sidebar (1/3) ──────────────────── --}}
            <div class="lg:w-1/3 relative">
                <div class="sticky top-28 bg-white rounded-2xl p-8 shadow-xl border border-gray-100 flex flex-col gap-6">
                    <div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider font-bold block mb-1">Mulai dari</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-extrabold text-[#111827]">
                                {{ $boardingHouse['min_price_formatted'] ?? '—' }}
                            </span>
                            <span class="text-gray-400 text-sm">/ bulan</span>
                        </div>
                        @if($boardingHouse['avg_rating'])
                        <div class="flex items-center gap-1 mt-2 text-sm text-gray-500">
                            <span class="material-symbols-outlined text-amber-400 text-[16px]" style="font-variation-settings:'FILL' 1">star</span>
                            <span class="font-bold text-[#111827]">{{ $boardingHouse['rating_formatted'] }}</span>
                            &middot; {{ $boardingHouse['review_count'] }} ulasan
                        </div>
                        @endif
                    </div>

                    {{-- Pilih Tanggal --}}
                    <div class="border border-gray-200 rounded-2xl p-4 flex justify-between items-center bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors">
                        <div>
                            <span class="text-xs font-bold text-[#111827] block">Mulai Sewa</span>
                            <span class="text-sm text-gray-400">Pilih Tanggal</span>
                        </div>
                        <span class="material-symbols-outlined text-gray-400">calendar_month</span>
                    </div>

                    <button class="w-full bg-[#111827] text-white font-bold py-4 rounded-full hover:bg-opacity-90 transition-colors shadow-lg active:scale-95 text-sm">
                        Booking &amp; Bayar Aman
                    </button>
                    <p class="text-center text-xs text-gray-400">Anda belum dikenakan biaya saat ini</p>

                    {{-- Owner info --}}
                    @if(!empty($boardingHouse['owner']))
                    <div class="border-t border-gray-100 pt-5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-400">person</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Pemilik</p>
                            <p class="text-sm font-bold text-[#111827]">{{ $boardingHouse['owner']['name'] }}</p>
                        </div>
                        @if($boardingHouse['owner']['phone_number'])
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $boardingHouse['owner']['phone_number']) }}"
                           target="_blank"
                           class="ml-auto w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-200 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">phone</span>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
