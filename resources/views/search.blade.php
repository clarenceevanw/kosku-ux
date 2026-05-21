@extends('layouts.app')

@section('content')
<main class="pt-[100px] pb-32 px-5 md:px-16 max-w-[1600px] mx-auto flex flex-col md:flex-row gap-8">

    {{-- ══════════════════════════════════════
         Left Sidebar — Filters (static UI)
    ══════════════════════════════════════ --}}
    <aside class="w-full md:w-[260px] flex-shrink-0 space-y-10 hidden md:block">
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-[#111827]">Filter</h2>
            <a href="{{ route('search') }}" class="text-sm text-gray-500 hover:text-[#111827] font-medium transition-colors">Reset</a>
        </div>

        {{-- Gender Type --}}
        <div class="space-y-4">
            <h3 class="text-sm font-black uppercase tracking-wider text-gray-500">Tipe Kos</h3>
            <div class="space-y-3">
                @foreach(['campur' => 'Campur', 'putra' => 'Khusus Putra', 'putri' => 'Khusus Putri'] as $val => $label)
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="w-5 h-5 rounded border-2 border-gray-300 flex items-center justify-center group-hover:border-[#111827] transition-colors"></div>
                    <span class="text-sm text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- City --}}
        <div class="space-y-4">
            <h3 class="text-sm font-black uppercase tracking-wider text-gray-500">Kota</h3>
            <div class="space-y-3">
                @foreach(['Surabaya', 'Jakarta', 'Bandung', 'Yogyakarta'] as $cityOption)
                <a href="{{ route('search', ['q' => $cityOption]) }}"
                   class="flex items-center gap-3 cursor-pointer group text-sm {{ request('q') === $cityOption ? 'text-[#111827] font-bold' : 'text-gray-700 hover:text-[#111827]' }} transition-colors">
                    <span class="material-symbols-outlined text-sm">location_on</span>
                    {{ $cityOption }}
                </a>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- ══════════════════════════════════════
         Main Results Area
    ══════════════════════════════════════ --}}
    <div class="flex-1">

        {{-- Top Banner --}}
        <div class="bg-gray-50 rounded-2xl p-6 mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                @if($keyword)
                    <h1 class="text-2xl font-extrabold text-[#111827] mb-1">
                        Hasil pencarian "{{ $keyword }}"
                    </h1>
                    <p class="text-sm text-gray-500">
                        Ditemukan <span class="font-bold text-[#111827]">{{ $paginator->total() }}</span> kos yang sesuai.
                    </p>
                @else
                    <h1 class="text-2xl font-extrabold text-[#111827] mb-1">Semua Kos</h1>
                    <p class="text-sm text-gray-500">
                        Menampilkan <span class="font-bold text-[#111827]">{{ $paginator->total() }}</span> kos tersedia.
                    </p>
                @endif
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-full bg-white border border-gray-200 text-sm font-semibold flex items-center gap-2 hover:border-[#111827] transition-colors shadow-sm">
                    Urutkan: Terbaru
                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                </button>
                {{-- Mobile filter trigger --}}
                <button class="md:hidden px-4 py-2 rounded-full bg-[#111827] text-white text-sm font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">tune</span> Filter
                </button>
            </div>
        </div>

        {{-- Mobile search bar --}}
        <form action="{{ route('search') }}" method="GET" class="flex md:hidden items-center bg-gray-50 rounded-full px-4 py-3 border border-gray-200 mb-6 focus-within:border-[#111827] transition-colors">
            <span class="material-symbols-outlined text-gray-500 text-[18px] mr-2">search</span>
            <input class="bg-transparent border-none outline-none text-sm w-full placeholder-gray-400 text-[#111827] focus:ring-0"
                   placeholder="Cari lokasi atau nama kos..."
                   type="text" name="q" value="{{ $keyword }}">
        </form>

        {{-- Results Grid --}}
        @if(count($boardingHouses) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($boardingHouses as $kos)
            <a href="{{ route('kos.show', $kos['id']) }}"
               class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 block">
                <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                    {{-- Badges --}}
                    <div class="absolute top-4 left-4 z-10 flex gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[11px] font-bold rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">verified</span> Terverifikasi
                        </span>
                    </div>
                    <button class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center text-gray-500 hover:text-red-500 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">favorite_border</span>
                    </button>

                    @if($kos['primary_image'])
                        <img alt="{{ $kos['name'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                             src="{{ $kos['primary_image'] }}">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-400 text-5xl">home</span>
                        </div>
                    @endif
                </div>

                <div class="p-5">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-lg font-bold text-[#111827] leading-tight line-clamp-1">{{ $kos['name'] }}</h3>
                        @if($kos['avg_rating'])
                        <div class="flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs font-bold shrink-0 ml-2">
                            <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1">star</span>
                            {{ $kos['rating_formatted'] }}
                        </div>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mb-3 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">location_on</span>
                        {{ $kos['city'] }}
                    </p>

                    @if(!empty($kos['facility_preview']))
                    <div class="flex gap-2 mb-4 flex-wrap">
                        @foreach($kos['facility_preview'] as $f)
                        <span class="px-2.5 py-0.5 bg-slate-50 text-[10px] font-bold uppercase tracking-wider rounded-full text-slate-500 border border-slate-200">
                            {{ $f['name'] }}
                        </span>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex justify-between items-end border-t border-gray-50 pt-3">
                        <div>
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-bold block">Mulai dari</span>
                            <span class="text-lg font-extrabold text-[#0D9488]">
                                {{ $kos['min_price_formatted'] ?? '—' }}
                                <span class="text-sm font-normal text-gray-400">/bln</span>
                            </span>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">{{ $kos['gender_label'] }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($paginator->hasPages())
        <div class="mt-12 flex justify-center gap-2">
            {{-- Previous --}}
            @if($paginator->onFirstPage())
                <span class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed">
                    <span class="material-symbols-outlined">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="w-10 h-10 rounded-full bg-white border border-gray-200 hover:border-[#111827] flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
            @endif

            @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="w-10 h-10 rounded-full bg-[#111827] text-white font-bold flex items-center justify-center text-sm">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       class="w-10 h-10 rounded-full bg-white border border-gray-200 hover:border-[#111827] font-medium flex items-center justify-center text-sm transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="w-10 h-10 rounded-full bg-white border border-gray-200 hover:border-[#111827] flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            @else
                <span class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed">
                    <span class="material-symbols-outlined">chevron_right</span>
                </span>
            @endif
        </div>
        @endif

        @else
        {{-- Empty state --}}
        <div class="text-center py-24 text-gray-400">
            <span class="material-symbols-outlined text-7xl block mb-6 text-gray-300">search_off</span>
            <h3 class="text-xl font-bold text-gray-500 mb-2">Tidak ada kos ditemukan</h3>
            <p class="text-sm mb-6">Coba kata kunci lain atau hapus filter yang aktif.</p>
            <a href="{{ route('search') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-[#111827] text-white rounded-full text-sm font-bold hover:bg-opacity-90 transition-all">
                <span class="material-symbols-outlined text-sm">refresh</span>
                Lihat Semua Kos
            </a>
        </div>
        @endif
    </div>
</main>
@endsection
