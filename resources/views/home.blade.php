@extends('layouts.app')

@section('content')
    {{-- ═══════════════════════════════════════════════════════════════════
         Hero Section with 3D KosBot Mockup
    ═══════════════════════════════════════════════════════════════════ --}}
    <main class="w-full relative flex flex-col items-center min-h-[921px] overflow-hidden px-6 lg:px-16">
        <div class="flex flex-col items-center text-center pt-32 lg:pt-48 pb-8 mx-auto max-w-3xl z-10">
            <h1 class="font-display text-4xl lg:text-6xl font-extrabold text-[#111827] leading-[1.1] tracking-tight mb-6">
                Cari Kos &amp; Apartemen dengan Mudah
            </h1>
            <p class="font-body text-lg text-slate-600 mb-10 max-w-xl leading-relaxed">
                Ceritakan kebutuhanmu ke KosBot, dan temukan hunian dengan foto terverifikasi, tanpa takut penipuan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-4">
                <a href="{{ route('search') }}"
                   class="px-8 py-4 bg-[#111827] text-white rounded-full font-label text-base font-bold hover:bg-opacity-90 transition-all active:scale-95 shadow-lg flex items-center justify-center gap-2">
                    Cari Kos Sekarang
                </a>
                <a href="{{ route('bot') }}"
                   class="px-8 py-4 bg-white text-[#111827] border-2 border-[#111827] rounded-full font-label text-base font-bold hover:bg-gray-50 transition-all active:scale-95 flex items-center justify-center gap-2">
                    Chat KosBot
                </a>
            </div>
        </div>

        {{-- 3D KosBot Mockup --}}
        <div class="w-full mx-auto mt-12 pb-32 overflow-visible [mask-image:linear-gradient(to_bottom,black_75%,transparent_100%)] z-10 max-w-7xl">
            <div class="[perspective:2000px] flex justify-center overflow-visible">
                <div class="w-full overflow-visible max-w-4xl [transform:rotateX(15deg)_translateZ(0)] transition-transform duration-700 hover:[transform:rotateX(10deg)_translateZ(50px)]">
                    <div class="relative bg-white/70 backdrop-blur-2xl rounded-[2.5rem] shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15),0_30px_60px_-30px_rgba(0,0,0,0.2)] border border-white/60 p-10 lg:p-14 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/40 via-transparent to-black/[0.02] pointer-events-none"></div>
                        <div class="flex items-center gap-4 border-b border-black/[0.05] pb-6 mb-8">
                            <div class="w-12 h-12 bg-[#111827] rounded-2xl flex items-center justify-center shadow-lg transform -rotate-3">
                                <span class="material-symbols-outlined text-white text-2xl">smart_toy</span>
                            </div>
                            <div>
                                <h3 class="font-label font-extrabold text-[#111827] text-lg tracking-tight">KosBot AI <span class="text-amber-400">✨</span></h3>
                                <div class="flex items-center gap-1.5 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div> Online
                                </div>
                            </div>
                        </div>
                        <div class="space-y-6 relative">
                            <div class="flex justify-end">
                                <div class="bg-[#111827] text-white py-4 px-6 rounded-3xl rounded-tr-none text-sm md:text-base max-w-[85%] font-medium shadow-md">
                                    Cari kos putra dekat UK Petra, budget max 1.5jt, ada AC.
                                </div>
                            </div>
                            <div class="flex justify-start">
                                <div class="bg-white/90 border border-black/[0.05] text-[#111827] py-4 px-6 rounded-3xl rounded-tl-none text-sm md:text-base max-w-[85%] font-medium shadow-sm">
                                    Menemukan {{ count($recommendations) }} kos terverifikasi yang pas untukmu! Ini rekomendasinya:
                                </div>
                            </div>
                            {{-- Dynamic mockup cards from DB --}}
                            <div class="grid grid-cols-2 gap-6 mt-8 max-w-2xl mx-auto md:mx-0">
                                @foreach(array_slice($recommendations, 0, 2) as $kos)
                                <a href="{{ route('kos.show', $kos['id']) }}"
                                   class="bg-white rounded-3xl p-3 shadow-xl border border-black/[0.03] group cursor-pointer hover:shadow-2xl transition-all">
                                    <div class="h-24 md:h-32 bg-gray-100 rounded-2xl mb-3 bg-cover bg-center overflow-hidden"
                                         @if($kos['primary_image']) style="background-image: url('{{ $kos['primary_image'] }}')" @endif>
                                    </div>
                                    <h4 class="font-label text-sm font-bold text-[#111827] truncate px-1">{{ $kos['name'] }}</h4>
                                    <p class="text-xs font-bold text-slate-500 mt-1 px-1">{{ $kos['min_price_formatted'] }}/bln</p>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- ═══════════════════════════════════════════════════════════════════
         Payment Partners Logo Loop
    ═══════════════════════════════════════════════════════════════════ --}}
    <section class="bg-white border-y border-gray-100 py-10 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-16 mb-6 flex justify-center">
            <p class="font-label text-xs uppercase tracking-[0.2em] text-slate-500 font-extrabold text-center">
                Pembayaran Aman Terintegrasi Dengan</p>
        </div>
        <div class="relative w-full flex overflow-hidden group">
            <div class="absolute top-0 left-0 bottom-0 w-16 md:w-32 z-10 bg-gradient-to-r from-white to-transparent pointer-events-none"></div>
            <div class="flex w-max animate-marquee md:animate-marquee-mobile group-hover:[animation-play-state:paused] whitespace-nowrap gap-12 items-center">
                @php
                    $partners = ['BCA','Mandiri','BNI','BRI','GoPay','OVO','Dana','LinkAja','ShopeePay','QRIS','Mastercard','Visa'];
                @endphp
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($partners as $partner)
                        <div class="font-black text-2xl tracking-tighter text-[#111827] opacity-40 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300 cursor-default">
                            {{ $partner }}
                        </div>
                    @endforeach
                @endfor
            </div>
            <div class="absolute top-0 right-0 bottom-0 w-16 md:w-32 z-10 bg-gradient-to-l from-white to-transparent pointer-events-none"></div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         Rekomendasi Terbaru — Dynamic from DB
    ═══════════════════════════════════════════════════════════════════ --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-16">
            <div class="flex justify-between items-end mb-12">
                <h2 class="font-headline text-3xl font-extrabold text-[#111827]">Rekomendasi Terbaru</h2>
                <a class="font-label text-slate-800 hover:text-slate-600 transition-colors flex items-center gap-1 font-bold"
                   href="{{ route('search') }}">
                    Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($recommendations as $kos)
                <a href="{{ route('kos.show', $kos['id']) }}"
                   class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.12)] hover:-translate-y-2 transition-all duration-500 group cursor-pointer block">
                    <div class="aspect-[4/3] relative overflow-hidden bg-gray-100">
                        @if($kos['primary_image'])
                            <img alt="{{ $kos['name'] }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                 src="{{ $kos['primary_image'] }}">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                <span class="material-symbols-outlined text-slate-400 text-5xl">home</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md text-teal-800 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm border border-teal-100">
                            📸 Foto Terverifikasi
                        </div>
                        @if(!empty($kos['owner_is_verified']) && $kos['owner_is_verified'])
                        <div class="absolute bottom-4 left-4 bg-green-600 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm">
                             <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1">verified</span> Pemilik Terverifikasi
                        </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-[#111827] text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm">
                            {{ $kos['gender_label'] }}
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-label text-lg font-bold text-[#111827] mb-2 line-clamp-1">{{ $kos['name'] }}</h3>
                        <div class="flex items-center text-sm text-slate-500 mb-4 font-body font-medium gap-1">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            {{ $kos['city'] }}
                        </div>
                        @if(!empty($kos['facility_preview']))
                        <div class="flex gap-2 mb-6 flex-wrap">
                            @foreach($kos['facility_preview'] as $facility)
                            <span class="px-3 py-1 bg-slate-50 text-[10px] font-black uppercase tracking-wider rounded-full text-slate-600 border border-slate-200">
                                {{ $facility['name'] }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <p class="font-headline text-lg font-extrabold text-[#0D9488]">
                                {{ $kos['min_price_formatted'] ?? 'Hubungi Kami' }}
                                <span class="text-sm font-normal text-slate-400">/bln</span>
                            </p>
                            @if($kos['avg_rating'])
                            <div class="bg-teal-50 border border-teal-100 text-teal-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                ⭐ {{ $kos['rating_formatted'] }} ({{ $kos['review_count'] }})
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-3 text-center py-16 text-slate-400">
                    <span class="material-symbols-outlined text-5xl block mb-4">home_work</span>
                    <p>Belum ada rekomendasi. Silakan jalankan seeder terlebih dahulu.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         Kota Populer Section
    ═══════════════════════════════════════════════════════════════════ --}}
    <section class="py-12 bg-white border-t border-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-16">
            <div class="mb-8">
                <h2 class="font-headline text-3xl font-extrabold text-[#111827]">Cari Kos di Kota Populer</h2>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $cities = [
                        ['name' => 'Surabaya',   'img' => 'https://images.unsplash.com/photo-1598970605070-a38a6ccd3a2d?w=600'],
                        ['name' => 'Jakarta',     'img' => 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?w=600'],
                        ['name' => 'Bandung',     'img' => 'https://images.unsplash.com/photo-1619540402154-ab46fa36d04e?w=600'],
                        ['name' => 'Yogyakarta',  'img' => 'https://images.unsplash.com/photo-1612487060568-c0de35d94424?w=600'],
                    ];
                @endphp
                @foreach($cities as $city)
                <a class="group relative h-48 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300"
                   href="{{ route('search', ['q' => $city['name']]) }}">
                    <img alt="{{ $city['name'] }}"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                         src="{{ $city['img'] }}">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <h3 class="font-display text-2xl font-extrabold text-white tracking-wide">{{ $city['name'] }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         Pilihan Terbaik di Surabaya
    ═══════════════════════════════════════════════════════════════════ --}}
    @php $surabayaKos = array_filter($recommendations, fn($k) => str_contains($k['city'], 'Surabaya')); @endphp
    @if(!empty($surabayaKos))
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-16">
            <div class="flex justify-between items-end mb-12">
                <h2 class="font-headline text-3xl font-extrabold text-[#111827]">Pilihan Terbaik di Surabaya</h2>
                <a class="font-label text-slate-800 hover:text-slate-600 transition-colors flex items-center gap-1 font-bold"
                   href="{{ route('search', ['q' => 'Surabaya']) }}">
                    Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(array_slice($surabayaKos, 0, 3) as $kos)
                <a href="{{ route('kos.show', $kos['id']) }}"
                   class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.12)] hover:-translate-y-2 transition-all duration-500 group cursor-pointer block">
                    <div class="aspect-[4/3] relative overflow-hidden bg-gray-100">
                        @if($kos['primary_image'])
                            <img alt="{{ $kos['name'] }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                 src="{{ $kos['primary_image'] }}">
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="font-label text-lg font-bold text-[#111827] mb-2">{{ $kos['name'] }}</h3>
                        <p class="text-sm text-slate-500 mb-4">{{ $kos['city'] }}</p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <p class="font-headline text-lg font-extrabold text-[#0D9488]">
                                {{ $kos['min_price_formatted'] }}
                                <span class="text-sm font-normal text-slate-400">/bln</span>
                            </p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════
         Testimonials (Static — will come from reviews in future sprint)
    ═══════════════════════════════════════════════════════════════════ --}}
    <section class="py-24 bg-surface">
        <div class="max-w-7xl mx-auto px-6 lg:px-16 text-center">
            <h2 class="font-headline text-3xl font-bold text-[#111827] mb-4">Apa Kata Mereka?</h2>
            <p class="font-body text-lg text-gray-500 mb-12">Ribuan penyewa telah menemukan hunian impian mereka melalui KosKu.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                @php
                    $testimonials = [
                        ['quote' => 'Cari kos jadi jauh lebih tenang karena ada fitur Foto Terverifikasi. Apa yang saya lihat di aplikasi sama persis dengan aslinya.', 'name' => 'Budi Santoso', 'sub' => 'Penyewa di Senopati'],
                        ['quote' => 'KosBot sangat membantu! Saya cuma bilang butuh kos dekat kantor dengan budget tertentu, dan langsung dikasih opsi terbaik.', 'name' => 'Sarah Jenkins', 'sub' => 'Penyewa di Sudirman'],
                        ['quote' => 'Proses pembayaran escrow bikin saya merasa aman. Tidak perlu takut uang dibawa lari pemilik kos yang tidak bertanggung jawab.', 'name' => 'Andi Wijaya', 'sub' => 'Penyewa di Dago'],
                    ];
                @endphp
                @foreach($testimonials as $t)
                <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm flex flex-col hover:shadow-md transition-all">
                    <div class="flex mb-4 text-[#0D9488]">
                        @for($s = 0; $s < 5; $s++)
                        <span class="material-symbols-outlined text-sm">star</span>
                        @endfor
                    </div>
                    <p class="font-body italic text-gray-600 mb-8 flex-grow">"{{ $t['quote'] }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="material-symbols-outlined text-gray-400">person</span>
                        </div>
                        <div>
                            <p class="font-bold text-[#111827] text-sm">{{ $t['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $t['sub'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
