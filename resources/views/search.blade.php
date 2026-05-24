@extends('layouts.app')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     MOBILE FILTER SLIDE-OVER MODAL
     Hidden by default; toggled by the mobile "Filter" button.
     All five filter sections live here, identical to the desktop sidebar.
══════════════════════════════════════════════════════════════ --}}
<div id="mobile-filter-overlay"
     class="fixed inset-0 z-50 flex justify-end"
     style="display:none !important;">

    {{-- Semi-transparent backdrop --}}
    <div id="mobile-filter-backdrop"
         class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    {{-- Slide-over panel --}}
    <div id="mobile-filter-panel"
         class="relative z-10 w-[88%] max-w-sm bg-white h-full flex flex-col shadow-2xl
                translate-x-full transition-transform duration-300 ease-in-out">

        {{-- Panel header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 shrink-0">
            <h2 class="text-base font-bold text-[#111827]">Filter Pencarian</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('search', $keyword ? ['q' => $keyword] : []) }}"
                   class="text-xs text-[#0D9488] hover:underline font-semibold transition-colors">
                    Reset Semua
                </a>
                <button id="mobile-filter-close"
                        class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center
                               hover:bg-gray-200 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-gray-600">close</span>
                </button>
            </div>
        </div>

        {{-- Scrollable filter body --}}
        <div class="flex-1 overflow-y-auto">
            <form id="mobile-filter-form"
                  action="{{ route('search') }}"
                  method="GET"
                  class="p-6 space-y-7">

                {{-- Carry keyword --}}
                @if($keyword)
                    <input type="hidden" name="q" value="{{ $keyword }}">
                @endif

                {{-- ── KOTA ── --}}
                @if($cities->isNotEmpty())
                @php $activeMobileCity = $activeFilters['city'] ?? null; @endphp
                <div class="space-y-3">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">Kota</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Pilih satu kota</p>
                    </div>
                    <div class="space-y-2.5" data-show-more-container data-limit="5">
                        @foreach($cities as $city)
                        @php $isActive = $activeMobileCity === $city; @endphp
                        <label class="flex items-center gap-3 cursor-pointer group" data-show-more-item="{{ $loop->index >= 5 ? '1' : '0' }}">
                            <input type="radio" name="city" value="{{ $city }}"
                                   {{ $isActive ? 'checked' : '' }} class="hidden">
                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300' }}">
                                <div class="w-1.5 h-1.5 rounded-full bg-white {{ $isActive ? '' : 'hidden' }}"></div>
                            </div>
                            <span class="text-sm {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600' }} flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px] text-gray-400">location_on</span>
                                {{ $city }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── TIPE KOS ── --}}
                <div class="space-y-3">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">Tipe Kos</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Peruntukan penghuni</p>
                    </div>
                    <div class="space-y-2.5">
                        @foreach(['campur' => ['label'=>'Campur','icon'=>'groups'], 'putra' => ['label'=>'Khusus Putra','icon'=>'man'], 'putri' => ['label'=>'Khusus Putri','icon'=>'woman']] as $val => $item)
                        @php $isActive = ($activeFilters['gender_type'] ?? null) === $val; @endphp
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="gender_type" value="{{ $val }}"
                                   {{ $isActive ? 'checked' : '' }} class="hidden">
                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300' }}">
                                <div class="w-1.5 h-1.5 rounded-full bg-white {{ $isActive ? '' : 'hidden' }}"></div>
                            </div>
                            <span class="text-sm {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600' }} flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px] text-gray-400">{{ $item['icon'] }}</span>
                                {{ $item['label'] }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ── RENTANG HARGA ── --}}
                <div class="space-y-3">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">Rentang Harga</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Harga kamar per bulan</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Min</label>
                            <div class="relative mt-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] text-gray-400 font-bold">Rp</span>
                                <input type="number" name="min_price"
                                       value="{{ $activeFilters['min_price'] ?? '' }}"
                                       placeholder="0" min="0"
                                       class="w-full pl-7 pr-2 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50
                                              text-[#111827] placeholder-gray-300
                                              focus:outline-none focus:ring-2 focus:ring-[#111827] focus:border-transparent transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Max</label>
                            <div class="relative mt-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] text-gray-400 font-bold">Rp</span>
                                <input type="number" name="max_price"
                                       value="{{ $activeFilters['max_price'] ?? '' }}"
                                       placeholder="∞" min="0"
                                       class="w-full pl-7 pr-2 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50
                                              text-[#111827] placeholder-gray-300
                                              focus:outline-none focus:ring-2 focus:ring-[#111827] focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── FASILITAS BERSAMA (type=bersama from DB) ── --}}
                @php $besamaFacilities = $facilitiesByType['bersama'] ?? collect(); @endphp
                @if($besamaFacilities->isNotEmpty())
                <div class="space-y-3">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">Fasilitas Bersama</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Area umum kos — parkir, dapur, laundry, dll</p>
                    </div>
                    <div class="space-y-2.5" data-show-more-container data-limit="5">
                        @foreach($besamaFacilities as $facility)
                        @php $isActive = in_array($facility->id, $activeFilters['facilities'] ?? []); @endphp
                        <label class="flex items-center gap-3 cursor-pointer group" data-show-more-item="{{ $loop->index >= 5 ? '1' : '0' }}">
                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                   {{ $isActive ? 'checked' : '' }} class="hidden">
                            <div class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300' }}">
                                <span class="material-symbols-outlined text-white {{ $isActive ? '' : 'hidden' }}"
                                      style="font-size:11px; font-variation-settings:'wght' 700">check</span>
                            </div>
                            <span class="text-sm {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600' }} flex items-center gap-1.5">
                                @if($facility->icon)<span class="material-symbols-outlined text-[13px] text-gray-400">{{ $facility->icon }}</span>@endif
                                {{ $facility->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── FASILITAS KAMAR (type=ruang from DB) ── --}}
                @php $ruangFacilities = $facilitiesByType['ruang'] ?? collect(); @endphp
                @if($ruangFacilities->isNotEmpty())
                <div class="space-y-3">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">Fasilitas Kamar</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Di dalam kamar — AC, kasur, KM dalam, dll</p>
                    </div>
                    <div class="space-y-2.5" data-show-more-container data-limit="5">
                        @foreach($ruangFacilities as $facility)
                        @php $isActive = in_array($facility->id, $activeFilters['room_facilities'] ?? []); @endphp
                        <label class="flex items-center gap-3 cursor-pointer group" data-show-more-item="{{ $loop->index >= 5 ? '1' : '0' }}">
                            <input type="checkbox" name="room_facilities[]" value="{{ $facility->id }}"
                                   {{ $isActive ? 'checked' : '' }} class="hidden">
                            <div class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300' }}">
                                <span class="material-symbols-outlined text-white {{ $isActive ? '' : 'hidden' }}"
                                      style="font-size:11px; font-variation-settings:'wght' 700">check</span>
                            </div>
                            <span class="text-sm {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600' }} flex items-center gap-1.5">
                                @if($facility->icon)<span class="material-symbols-outlined text-[13px] text-gray-400">{{ $facility->icon }}</span>@endif
                                {{ $facility->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── ATURAN KOS (from master rules table, grouped by category) ── --}}
                @if($rules->isNotEmpty())
                <div class="space-y-3">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400">Aturan Kos</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Tampilkan kos dengan peraturan tertentu</p>
                    </div>
                    {{-- Filter by category (each category = one checkbox) --}}
                    <div class="space-y-2.5" data-show-more-container data-limit="5">
                        @foreach($rules as $category => $categoryRules)
                        @php $isActive = in_array($category, $activeFilters['rule_categories'] ?? []);
                             $icon = $categoryRules->first()?->icon ?? 'gavel'; @endphp
                        <label class="flex items-center gap-3 cursor-pointer group" data-show-more-item="{{ $loop->index >= 5 ? '1' : '0' }}">
                            <input type="checkbox" name="rule_categories[]" value="{{ $category }}"
                                   {{ $isActive ? 'checked' : '' }} class="hidden">
                            <div class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300' }}">
                                <span class="material-symbols-outlined text-white {{ $isActive ? '' : 'hidden' }}"
                                      style="font-size:11px; font-variation-settings:'wght' 700">check</span>
                            </div>
                            <span class="text-sm {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600' }} flex flex-col">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[13px] text-gray-400">{{ $icon }}</span>
                                    {{ $category }}
                                </span>
                                <span class="text-[10px] text-gray-400 mt-0.5">{{ $categoryRules->count() }} aturan</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Apply button --}}
                <button type="submit"
                        class="w-full py-3.5 bg-[#111827] text-white text-sm font-bold rounded-xl
                               hover:bg-opacity-90 active:scale-95 transition-all shadow-lg shadow-gray-900/20
                               flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Terapkan Filter
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     MAIN PAGE LAYOUT
══════════════════════════════════════════════════════════════ --}}
<main class="pt-[100px] pb-32 px-5 md:px-16 max-w-[1600px] mx-auto flex flex-col md:flex-row gap-8">

    {{-- ══════════════════════════════════════
         Left Sidebar — Filters (Desktop only)
    ══════════════════════════════════════ --}}
    <aside class="w-full md:w-[256px] flex-shrink-0 hidden md:block">
        <form id="desktop-filter-form" action="{{ route('search') }}" method="GET">

            {{-- Carry keyword --}}
            @if($keyword)
                <input type="hidden" name="q" value="{{ $keyword }}">
            @endif

            <div class="sticky top-[88px] space-y-0 divide-y divide-gray-100 border border-gray-100 rounded-2xl overflow-hidden bg-white shadow-sm">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 bg-gray-50">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-[#111827]">tune</span>
                        <h2 class="text-sm font-bold text-[#111827]">Filter</h2>
                    </div>
                    <a href="{{ route('search', $keyword ? ['q' => $keyword] : []) }}"
                       class="text-xs text-[#0D9488] hover:underline font-semibold transition-colors">
                        Reset Semua
                    </a>
                </div>

                {{-- ── KOTA ── --}}
                @if($cities->isNotEmpty())
                @php $activeCity = $activeFilters['city'] ?? null; @endphp
                <div class="px-5 py-5 space-y-3">
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-wider text-gray-400">Kota</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Pilih satu kota</p>
                    </div>
                    <div class="space-y-2" data-show-more-container data-limit="5">
                        @foreach($cities as $city)
                        @php $isActive = $activeCity === $city; @endphp
                        <label class="flex items-center gap-2.5 cursor-pointer group" data-show-more-item="{{ $loop->index >= 5 ? '1' : '0' }}">
                            <input type="radio" name="city" value="{{ $city }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   onchange="this.closest('form').submit()" class="hidden">
                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300 group-hover:border-[#111827]' }}">
                                <div class="w-1.5 h-1.5 rounded-full bg-white {{ $isActive ? '' : 'hidden' }}"></div>
                            </div>
                            <span class="text-sm leading-none flex items-center gap-1.5
                                         {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600 group-hover:text-[#111827]' }} transition-colors">
                                <span class="material-symbols-outlined text-[13px] text-gray-400">location_on</span>
                                {{ $city }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── TIPE KOS ── --}}
                <div class="px-5 py-5 space-y-3">
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-wider text-gray-400">Tipe Kos</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Peruntukan penghuni</p>
                    </div>
                    <div class="space-y-2">
                        @foreach(['campur' => ['label'=>'Campur','icon'=>'groups'], 'putra' => ['label'=>'Khusus Putra','icon'=>'man'], 'putri' => ['label'=>'Khusus Putri','icon'=>'woman']] as $val => $item)
                        @php $isActive = ($activeFilters['gender_type'] ?? null) === $val; @endphp
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="radio" name="gender_type" value="{{ $val }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   onchange="this.closest('form').submit()" class="hidden">
                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300 group-hover:border-[#111827]' }}">
                                <div class="w-1.5 h-1.5 rounded-full bg-white {{ $isActive ? '' : 'hidden' }}"></div>
                            </div>
                            <span class="text-sm leading-none flex items-center gap-1.5
                                         {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600 group-hover:text-[#111827]' }} transition-colors">
                                <span class="material-symbols-outlined text-[13px] text-gray-400">{{ $item['icon'] }}</span>
                                {{ $item['label'] }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- ── RENTANG HARGA ── --}}
                <div class="px-5 py-5 space-y-3">
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-wider text-gray-400">Rentang Harga</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Harga kamar per bulan</p>
                    </div>
                    <div class="space-y-2">
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Minimum</label>
                            <div class="relative mt-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] text-gray-400 font-bold select-none">Rp</span>
                                <input type="number" name="min_price" id="desktop_min_price"
                                       value="{{ $activeFilters['min_price'] ?? '' }}"
                                       placeholder="0" min="0"
                                       class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50
                                              text-[#111827] placeholder-gray-300
                                              focus:outline-none focus:ring-2 focus:ring-[#111827] focus:border-transparent transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Maksimum</label>
                            <div class="relative mt-1">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[11px] text-gray-400 font-bold select-none">Rp</span>
                                <input type="number" name="max_price" id="desktop_max_price"
                                       value="{{ $activeFilters['max_price'] ?? '' }}"
                                       placeholder="Tidak terbatas" min="0"
                                       class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50
                                              text-[#111827] placeholder-gray-300
                                              focus:outline-none focus:ring-2 focus:ring-[#111827] focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full py-2 text-xs font-bold text-white bg-[#111827] rounded-lg
                                   hover:bg-opacity-90 transition-all active:scale-95">
                        Terapkan Harga
                    </button>
                </div>

                {{-- ── FASILITAS BERSAMA (type=bersama from DB) ── --}}
                @php $besamaFacilitiesDesktop = $facilitiesByType['bersama'] ?? collect(); @endphp
                @if($besamaFacilitiesDesktop->isNotEmpty())
                <div class="px-5 py-5 space-y-3">
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-wider text-gray-400">Fasilitas Bersama</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5 leading-relaxed">Area umum kos<br>(parkir, dapur, laundry, dll)</p>
                    </div>
                    <div class="space-y-2" data-show-more-container data-limit="5">
                        @foreach($besamaFacilitiesDesktop as $facility)
                        @php $isActive = in_array($facility->id, $activeFilters['facilities'] ?? []); @endphp
                        <label class="flex items-center gap-2.5 cursor-pointer group" data-show-more-item="{{ $loop->index >= 5 ? '1' : '0' }}">
                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   onchange="this.closest('form').submit()" class="hidden">
                            <div class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300 group-hover:border-[#111827]' }}">
                                <span class="material-symbols-outlined text-white {{ $isActive ? '' : 'hidden' }}"
                                      style="font-size:11px;">check</span>
                            </div>
                            <span class="text-sm leading-none flex items-center gap-1.5
                                         {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600 group-hover:text-[#111827]' }} transition-colors">
                                @if($facility->icon)<span class="material-symbols-outlined text-[12px] text-gray-400">{{ $facility->icon }}</span>@endif
                                {{ $facility->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── FASILITAS KAMAR (type=ruang from DB) ── --}}
                @php $ruangFacilitiesDesktop = $facilitiesByType['ruang'] ?? collect(); @endphp
                @if($ruangFacilitiesDesktop->isNotEmpty())
                <div class="px-5 py-5 space-y-3">
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-wider text-gray-400">Fasilitas Kamar</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5 leading-relaxed">Di dalam kamar<br>(AC, kasur, KM dalam, dll)</p>
                    </div>
                    <div class="space-y-2" data-show-more-container data-limit="5">
                        @foreach($ruangFacilitiesDesktop as $facility)
                        @php $isActive = in_array($facility->id, $activeFilters['room_facilities'] ?? []); @endphp
                        <label class="flex items-center gap-2.5 cursor-pointer group" data-show-more-item="{{ $loop->index >= 5 ? '1' : '0' }}">
                            <input type="checkbox" name="room_facilities[]" value="{{ $facility->id }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   onchange="this.closest('form').submit()" class="hidden">
                            <div class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-all
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300 group-hover:border-[#111827]' }}">
                                <span class="material-symbols-outlined text-white {{ $isActive ? '' : 'hidden' }}"
                                      style="font-size:11px;">check</span>
                            </div>
                            <span class="text-sm leading-none flex items-center gap-1.5
                                         {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600 group-hover:text-[#111827]' }} transition-colors">
                                @if($facility->icon)<span class="material-symbols-outlined text-[12px] text-gray-400">{{ $facility->icon }}</span>@endif
                                {{ $facility->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ── ATURAN KOS (master rules, grouped by category) ── --}}
                @if($rules->isNotEmpty())
                <div class="px-5 py-5 space-y-3">
                    <div>
                        <h3 class="text-[11px] font-black uppercase tracking-wider text-gray-400">Aturan Kos</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Tampilkan kos dengan peraturan tertentu</p>
                    </div>
                    <div class="space-y-2" data-show-more-container data-limit="5">
                        @foreach($rules as $category => $categoryRules)
                        @php $isActive = in_array($category, $activeFilters['rule_categories'] ?? []);
                             $icon = $categoryRules->first()?->icon ?? 'gavel'; @endphp
                        <label class="flex items-center gap-2.5 cursor-pointer group" data-show-more-item="{{ $loop->index >= 5 ? '1' : '0' }}">
                            <input type="checkbox" name="rule_categories[]" value="{{ $category }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   onchange="this.closest('form').submit()" class="hidden">
                            <div class="w-4 h-4 rounded border-2 flex items-center justify-center shrink-0 transition-all mt-0.5
                                        {{ $isActive ? 'border-[#111827] bg-[#111827]' : 'border-gray-300 group-hover:border-[#111827]' }}">
                                <span class="material-symbols-outlined text-white {{ $isActive ? '' : 'hidden' }}"
                                      style="font-size:11px;">check</span>
                            </div>
                            <span class="flex flex-col">
                                <span class="text-sm leading-none flex items-center gap-1.5
                                             {{ $isActive ? 'text-[#111827] font-semibold' : 'text-gray-600 group-hover:text-[#111827]' }} transition-colors">
                                    <span class="material-symbols-outlined text-[12px] text-gray-400">{{ $icon }}</span>
                                    {{ $category }}
                                </span>
                                <span class="text-[10px] text-gray-400 mt-0.5">{{ $categoryRules->count() }} aturan</span>
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>{{-- /sticky card --}}
        </form>
    </aside>

    {{-- ══════════════════════════════════════
         Main Results Area
    ══════════════════════════════════════ --}}
    <div class="flex-1 min-w-0">

        {{-- Top Banner --}}
        <div class="bg-gray-50 rounded-2xl p-5 md:p-6 mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                @if($keyword)
                    <h1 class="text-2xl font-extrabold text-[#111827] mb-1">
                        Hasil pencarian &ldquo;{{ $keyword }}&rdquo;
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

                {{-- Active filter badges --}}
                @php
                    $hasActiveFilters = !empty($activeFilters['city'])
                        || !empty($activeFilters['gender_type'])
                        || !empty($activeFilters['min_price'])
                        || !empty($activeFilters['max_price'])
                        || !empty($activeFilters['facilities'])
                        || !empty($activeFilters['room_facilities'])
                        || !empty($activeFilters['rule_categories']);
                @endphp
                @if($hasActiveFilters)
                <div class="flex flex-wrap gap-2 mt-3">
                    @if(!empty($activeFilters['city']))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#111827] text-white text-xs font-bold rounded-full">
                        <span class="material-symbols-outlined text-[11px]">location_on</span>
                        {{ $activeFilters['city'] }}
                    </span>
                    @endif
                    @if(!empty($activeFilters['gender_type']))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#111827] text-white text-xs font-bold rounded-full">
                        <span class="material-symbols-outlined text-[11px]">person</span>
                        {{ ['campur'=>'Campur','putra'=>'Khusus Putra','putri'=>'Khusus Putri'][$activeFilters['gender_type']] ?? $activeFilters['gender_type'] }}
                    </span>
                    @endif
                    @if(!empty($activeFilters['min_price']) || !empty($activeFilters['max_price']))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#0D9488] text-white text-xs font-bold rounded-full">
                        <span class="material-symbols-outlined text-[11px]">payments</span>
                        Rp {{ number_format($activeFilters['min_price'] ?? 0, 0, ',', '.') }}
                        @if(!empty($activeFilters['max_price'])) – Rp {{ number_format($activeFilters['max_price'], 0, ',', '.') }}@endif
                    </span>
                    @endif
                    @if(!empty($activeFilters['facilities']))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#0D9488] text-white text-xs font-bold rounded-full">
                        <span class="material-symbols-outlined text-[11px]">apartment</span>
                        {{ count($activeFilters['facilities']) }} Fasilitas Bersama
                    </span>
                    @endif
                    @if(!empty($activeFilters['room_facilities']))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#0D9488] text-white text-xs font-bold rounded-full">
                        <span class="material-symbols-outlined text-[11px]">bed</span>
                        {{ count($activeFilters['room_facilities']) }} Fasilitas Kamar
                    </span>
                    @endif
                    @if(!empty($activeFilters['rule_categories']))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#111827] text-white text-xs font-bold rounded-full">
                        <span class="material-symbols-outlined text-[11px]">gavel</span>
                        {{ count($activeFilters['rule_categories']) }} Aturan
                    </span>
                    @endif
                </div>
                @endif
            </div>

            <div class="flex gap-2 shrink-0">
                <button class="px-4 py-2 rounded-full bg-white border border-gray-200 text-sm font-semibold
                               flex items-center gap-2 hover:border-[#111827] transition-colors shadow-sm">
                    Terbaru
                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                </button>

                {{-- Mobile filter trigger --}}
                <button id="mobile-filter-open"
                        class="md:hidden px-4 py-2 rounded-full bg-[#111827] text-white text-sm font-semibold
                               flex items-center gap-2 hover:bg-opacity-90 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    Filter
                    @if($hasActiveFilters)
                    @php
                        $totalActive = (int)(!empty($activeFilters['city']))
                                     + (int)(!empty($activeFilters['gender_type']))
                                     + (int)(!empty($activeFilters['min_price']) || !empty($activeFilters['max_price']))
                                     + count($activeFilters['facilities'] ?? [])
                                     + count($activeFilters['room_facilities'] ?? [])
                                     + count($activeFilters['rule_categories'] ?? []);
                    @endphp
                    <span class="w-5 h-5 rounded-full bg-[#0D9488] text-white text-[10px] font-black flex items-center justify-center">
                        {{ $totalActive }}
                    </span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Mobile search bar --}}
        <form action="{{ route('search') }}" method="GET"
              class="flex md:hidden items-center bg-gray-50 rounded-full px-4 py-3 border border-gray-200 mb-6
                     focus-within:border-[#111827] transition-colors">
            <span class="material-symbols-outlined text-gray-400 text-[18px] mr-2">search</span>
            <input class="bg-transparent border-none outline-none text-sm w-full placeholder-gray-400 text-[#111827] focus:ring-0"
                   placeholder="Cari lokasi atau nama kos..."
                   type="text" name="q" value="{{ $keyword }}">
        </form>

        {{-- Results Grid --}}
        @if(count($boardingHouses) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($boardingHouses as $kos)
            <a href="{{ route('kos.show', $kos['id']) }}"
               class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group
                      cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all duration-300 block">

                <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                    <div class="absolute top-4 left-4 z-10 flex gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[11px] font-bold rounded-full flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">verified</span> Terverifikasi
                        </span>
                    </div>
                    <button class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm
                                   flex items-center justify-center text-gray-500 hover:text-red-500 transition-colors shadow-sm">
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
               class="inline-flex items-center gap-2 px-6 py-3 bg-[#111827] text-white rounded-full text-sm font-bold
                      hover:bg-opacity-90 transition-all">
                <span class="material-symbols-outlined text-sm">refresh</span>
                Lihat Semua Kos
            </a>
        </div>
        @endif
    </div>
</main>

{{-- ══════════════════════════════════════════════════════════════
     VANILLA JS — Mobile modal + Show More toggle + label interactions
══════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /* ─── 1. MOBILE MODAL ─────────────────────────────────────────────────── */
    var overlay  = document.getElementById('mobile-filter-overlay');
    var panel    = document.getElementById('mobile-filter-panel');
    var backdrop = document.getElementById('mobile-filter-backdrop');
    var openBtn  = document.getElementById('mobile-filter-open');
    var closeBtn = document.getElementById('mobile-filter-close');

    function lockScroll()   { document.body.style.overflow = 'hidden'; }
    function unlockScroll() { document.body.style.overflow = ''; }

    function openModal() {
        overlay.style.removeProperty('display');
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                panel.classList.remove('translate-x-full');
            });
        });
        lockScroll();
    }

    function closeModal() {
        panel.classList.add('translate-x-full');
        panel.addEventListener('transitionend', function handler() {
            overlay.style.display = 'none';
            unlockScroll();
            panel.removeEventListener('transitionend', handler);
        }, { once: true });
    }

    if (openBtn)  openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay && overlay.style.display !== 'none') closeModal();
    });

    /* ─── 2. SHOW MORE / SHOW LESS ───────────────────────────────────────── */
    /**
     * For every [data-show-more-container] element:
     *   - hide items with data-show-more-item="1"
     *   - inject a "Tampilkan X lagi" button below the list
     *   - toggle on click
     */
    document.querySelectorAll('[data-show-more-container]').forEach(function (container) {
        var hiddenItems = Array.from(container.querySelectorAll('[data-show-more-item="1"]'));
        if (hiddenItems.length === 0) return;

        // Initially hide overflow items
        hiddenItems.forEach(function (el) { el.style.display = 'none'; });

        var expanded = false;
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'mt-2 flex items-center gap-1 text-xs font-semibold text-[#0D9488] hover:underline transition-all';

        function updateBtn() {
            btn.innerHTML = expanded
                ? '<span class="material-symbols-outlined" style="font-size:15px">expand_less</span> Tampilkan lebih sedikit'
                : '<span class="material-symbols-outlined" style="font-size:15px">expand_more</span> Tampilkan ' + hiddenItems.length + ' lagi';
        }

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            expanded = !expanded;
            hiddenItems.forEach(function (el) {
                el.style.display = expanded ? '' : 'none';
            });
            updateBtn();
        });

        updateBtn();
        container.appendChild(btn);
    });

    /* ─── 3. DESKTOP FILTER — label click → hidden input → submit ─────────── */
    /**
     * Since inputs are hidden (for custom styling), clicking the label
     * needs to manually toggle the input and submit the form.
     * We only handle desktop form here; mobile has a manual submit button.
     */
    document.querySelectorAll('#desktop-filter-form label').forEach(function (label) {
        label.addEventListener('click', function (e) {
            if (e.target.tagName === 'INPUT') return;
            e.preventDefault();

            var input = label.querySelector('input[type="radio"], input[type="checkbox"]');
            if (!input) return;

            if (input.type === 'radio') {
                input.checked = true;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                input.checked = !input.checked;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });

    /* ─── 4. MOBILE FILTER — label click → toggle visual state ───────────── */
    /**
     * In the mobile modal the user hits "Terapkan Filter" to submit,
     * so we only need to keep the visual state in sync on click.
     */
    document.querySelectorAll('#mobile-filter-form label').forEach(function (label) {
        label.addEventListener('click', function (e) {
            if (e.target.tagName === 'INPUT') return;
            e.preventDefault();

            var input  = label.querySelector('input[type="radio"], input[type="checkbox"]');
            if (!input) return;

            if (input.type === 'radio') {
                // Uncheck siblings in the same named group
                document.querySelectorAll('#mobile-filter-form input[name="' + input.name + '"]').forEach(function (sibling) {
                    sibling.checked = false;
                    syncMobileLabel(sibling);
                });
                input.checked = true;
            } else {
                input.checked = !input.checked;
            }
            syncMobileLabel(input);
        });
    });

    function syncMobileLabel(input) {
        var label     = input.closest('label');
        if (!label) return;
        var indicator = label.querySelector('.rounded-full.border-2, .rounded.border-2');
        var dot       = label.querySelector('.w-1\\.5.h-1\\.5');
        var checkIcon = label.querySelector('.material-symbols-outlined.text-white');
        var text      = label.querySelector('span:last-child');

        var active = input.checked;

        if (indicator) {
            indicator.classList.toggle('border-[#111827]', active);
            indicator.classList.toggle('bg-[#111827]',     active);
            indicator.classList.toggle('border-gray-300',  !active);
        }
        if (dot) {
            dot.classList.toggle('hidden', !active);
        }
        if (checkIcon) {
            checkIcon.classList.toggle('hidden', !active);
        }
        if (text) {
            text.classList.toggle('text-[#111827]',  active);
            text.classList.toggle('font-semibold',    active);
            text.classList.toggle('text-gray-600',   !active);
        }
    }

}());
</script>

@endsection
