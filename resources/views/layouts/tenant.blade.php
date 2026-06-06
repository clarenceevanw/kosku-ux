<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'Dashboard Penghuni') — KosKu</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "background": "#fcf8fa",
                        "primary": "#000000",
                        "secondary": "#5c5f60",
                        "tertiary-fixed-dim": "#dcc2a4",
                        "surface-tint": "#575e70",
                        "on-primary-container": "#7d8497",
                        "outline": "#76777d",
                        "error-container": "#ffdad6",
                        "surface-container-high": "#eae7e9",
                        "on-background": "#1b1b1d",
                        "on-tertiary": "#ffffff",
                        "surface-container": "#f0edee",
                        "primary-fixed": "#dce2f7",
                        "on-primary-fixed-variant": "#404758",
                        "on-error": "#ffffff",
                        "tertiary-container": "#261906",
                        "secondary-container": "#dee0e2",
                        "inverse-surface": "#303031",
                        "tertiary-fixed": "#f9debf",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-fixed-variant": "#444749",
                        "on-secondary-fixed": "#191c1e",
                        "on-primary": "#ffffff",
                        "surface-container-highest": "#e5e2e3",
                        "on-tertiary-fixed-variant": "#55442d",
                        "surface-dim": "#dcd9db",
                        "surface-bright": "#fcf8fa",
                        "secondary-fixed": "#e1e2e4",
                        "primary-fixed-dim": "#c0c6db",
                        "outline-variant": "#c6c6cd",
                        "secondary-fixed-dim": "#c5c6c8",
                        "surface-container-low": "#f6f3f4",
                        "inverse-on-surface": "#f3f0f1",
                        "surface-variant": "#e5e2e3",
                        "on-surface-variant": "#45464c",
                        "error": "#ba1a1a",
                        "on-secondary": "#ffffff",
                        "inverse-primary": "#c0c6db",
                        "on-error-container": "#93000a",
                        "surface": "#fcf8fa",
                        "on-tertiary-container": "#968065",
                        "on-surface": "#1b1b1d",
                        "on-primary-fixed": "#141b2b",
                        "tertiary": "#000000",
                        "on-tertiary-fixed": "#261906",
                        "on-secondary-container": "#606365",
                        "primary-container": "#141b2b"
                    },
                    "borderRadius": {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Inter", "sans-serif"],
                        "display": ["Inter", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                        "label": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #fcf8fa; color: #1b1b1d; font-family: 'Inter', sans-serif; }
        .nav-item-active { background-color: #000000; color: #ffffff; border-radius: 9999px; }
        .nav-item-active .material-symbols-outlined { color: #ffffff; }
    </style>

    @stack('styles')
</head>
<body class="flex min-h-screen">

    {{-- ── Mobile: Hamburger Topbar ─────────────────────────────── --}}
    <div class="md:hidden fixed top-0 left-0 right-0 z-40
                flex items-center gap-3 px-4 h-14
                bg-surface-container-lowest border-b border-outline-variant/50 shadow-sm">
        <button id="tenant-sidebar-open-btn"
                class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-on-surface text-[22px]">menu</span>
        </button>
        <a href="{{ route('home') }}" class="font-headline text-lg font-bold text-primary">KosKu</a>
        @if(!auth()->user()->is_verified)
        <a href="{{ route('verification.index') }}"
           class="ml-auto flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold
                  bg-amber-50 border border-amber-200 text-amber-700">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
            </span>
            Verifikasi
        </a>
        @else
        <div class="ml-auto flex items-center gap-1 text-[11px] font-bold text-green-700">
            <span class="material-symbols-outlined text-green-600 text-[14px]" style="font-variation-settings:'FILL' 1">verified</span>
            Terverifikasi
        </div>
        @endif
    </div>

    {{-- ── Mobile: Backdrop ────────────────────────────────────────── --}}
    <div id="tenant-sidebar-backdrop"
         class="md:hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300"></div>

    {{-- ══════════════════════════════════════════════
         Sidebar Panel — Desktop: fixed | Mobile: slide-over
    ══════════════════════════════════════════════ --}}
    <nav id="tenant-sidebar-panel"
         class="flex flex-col h-screen w-72 fixed left-0 top-0
                bg-surface-container-lowest border-r border-outline-variant/50
                z-[60] overflow-y-auto shadow-[4px_0_24px_rgba(0,0,0,0.05)]
                transition-transform duration-300 ease-in-out
                -translate-x-full md:translate-x-0 md:w-64">

        {{-- Brand + Close button --}}
        <div class="flex items-center justify-between px-6 pt-6 pb-4">
            <div>
                <a href="{{ route('home') }}" class="font-headline text-2xl font-bold text-primary hover:opacity-70 transition-opacity">KosKu</a>
                <div class="mt-1">
                    <p class="font-headline text-base font-semibold text-on-surface">Management Hub</p>
                    <p class="font-body text-xs text-on-surface-variant">Dashboard Penghuni</p>
                </div>
            </div>
            <button id="tenant-sidebar-close-btn"
                    class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl
                           hover:bg-surface-container transition-colors shrink-0">
                <span class="material-symbols-outlined text-on-surface-variant text-[20px]">close</span>
            </button>
        </div>

        <div class="px-4 flex flex-col flex-1 gap-2 pb-6">

        {{-- ── Verification Banner ────────────────────────────── --}}
        @if(!auth()->user()->is_verified)
        <a href="{{ route('verification.index') }}"
           class="group relative flex items-start gap-3 rounded-2xl p-3.5 mb-2 overflow-hidden transition-all duration-200
                  {{ auth()->user()->identityVerifications()->where('status','pending')->exists()
                      ? 'bg-yellow-50 border border-yellow-200 hover:bg-yellow-100'
                      : 'bg-amber-50 border border-amber-200 hover:bg-amber-100' }}">
            <span class="relative flex h-2.5 w-2.5 shrink-0 mt-1">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75
                    {{ auth()->user()->identityVerifications()->where('status','pending')->exists()
                        ? 'bg-yellow-400' : 'bg-amber-500' }}"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5
                    {{ auth()->user()->identityVerifications()->where('status','pending')->exists()
                        ? 'bg-yellow-400' : 'bg-amber-500' }}"></span>
            </span>
            <div class="flex-1 min-w-0">
                @if(auth()->user()->identityVerifications()->where('status','pending')->exists())
                    <p class="text-xs font-bold text-yellow-800 leading-tight">Menunggu Verifikasi</p>
                    <p class="text-[11px] text-yellow-700 mt-0.5 leading-tight">Dokumen sedang ditinjau admin</p>
                @else
                    <p class="text-xs font-bold text-amber-800 leading-tight">Identitas Belum Diverifikasi</p>
                    <p class="text-[11px] text-amber-700 mt-0.5 leading-tight">Upload KTP/KTM untuk verifikasi</p>
                @endif
            </div>
            <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5
                {{ auth()->user()->identityVerifications()->where('status','pending')->exists()
                    ? 'text-yellow-600' : 'text-amber-600' }}
                group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
        </a>
        @else
        <div class="flex items-center gap-2.5 px-3.5 py-3 mb-2 rounded-2xl bg-green-50 border border-green-200">
            <span class="material-symbols-outlined text-green-600 text-[18px]" style="font-variation-settings:'FILL' 1">verified</span>
            <p class="text-xs font-bold text-green-800">Identitas Terverifikasi</p>
        </div>
        @endif

        {{-- Navigation Items --}}
        <div class="flex-1 flex flex-col gap-1">
            <a href="{{ route('tenant.dashboard') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('tenant.dashboard') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]" @if(request()->routeIs('tenant.dashboard')) style="font-variation-settings:'FILL' 1" @endif>home</span>
                <span class="font-semibold">Beranda</span>
            </a>

            <a href="{{ route('tenant.payments') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('tenant.payments') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]" @if(request()->routeIs('tenant.payments')) style="font-variation-settings:'FILL' 1" @endif>receipt_long</span>
                <span class="font-semibold">Tagihan Saya</span>
            </a>

            <a href="{{ route('tenant.tickets') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('tenant.tickets*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]" @if(request()->routeIs('tenant.tickets*')) style="font-variation-settings:'FILL' 1" @endif>report_problem</span>
                <span class="font-semibold">Laporan Kerusakan</span>
            </a>

            <a href="{{ route('tenant.contract') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('tenant.contract') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]" @if(request()->routeIs('tenant.contract')) style="font-variation-settings:'FILL' 1" @endif>description</span>
                <span class="font-semibold">Kontrak Digital</span>
            </a>

            <a href="{{ route('tenant.rules') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('tenant.rules') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]" @if(request()->routeIs('tenant.rules')) style="font-variation-settings:'FILL' 1" @endif>gavel</span>
                <span class="font-semibold">Peraturan Kos</span>
            </a>

            <a href="{{ route('tenant.settings') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('tenant.settings') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[22px]" @if(request()->routeIs('tenant.settings')) style="font-variation-settings:'FILL' 1" @endif>settings</span>
                <span class="font-semibold">Pengaturan</span>
            </a>

            {{-- Divider --}}
            <div class="border-t border-outline-variant/40 my-1"></div>

            {{-- Verifikasi nav item --}}
            <a href="{{ route('verification.index') }}"
               class="flex items-center gap-4 px-4 py-3 rounded-full font-label text-sm transition-all duration-200
                      {{ request()->routeIs('verification.*') ? 'nav-item-active' : 'text-secondary hover:bg-surface-container hover:text-on-surface' }}">
                <span class="relative flex items-center">
                    <span class="material-symbols-outlined text-[22px]"
                        @if(request()->routeIs('verification.*')) style="font-variation-settings:'FILL' 1" @endif>verified_user</span>
                    @if(!auth()->user()->is_verified)
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-amber-500 rounded-full border-2 border-surface-container-lowest"></span>
                    @endif
                </span>
                <span class="font-semibold flex-1">Verifikasi Identitas</span>
                @if(!auth()->user()->is_verified)
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">Perlu</span>
                @endif
            </a>
        </div>{{-- /nav flex-col --}}

        {{-- Bottom: User info + Logout --}}
        <div class="mt-auto pt-4 border-t border-outline-variant/50 space-y-2">
            {{-- User avatar & name --}}
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-on-primary font-bold text-base shrink-0 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-sm text-on-surface truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            {{-- Hubungi Pemilik --}}
            @if(isset($activeContract) && $activeContract?->room?->boardingHouse?->owner?->phone_number)
            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $activeContract->room->boardingHouse->owner->phone_number) }}"
               target="_blank"
               class="w-full bg-primary text-on-primary font-label text-sm py-3 rounded-full hover:bg-primary/90 transition-colors shadow-sm flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">chat</span>
                Hubungi Pemilik
            </a>
            @endif

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-sm font-semibold text-on-surface-variant hover:bg-surface-container hover:text-error transition-all duration-200">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    Keluar
                </button>
            </form>
        </div>{{-- /bottom --}}

        </div>{{-- /px-4 wrapper --}}
    </nav>

    {{-- ── Hamburger JS ──────────────────────────────────────────── --}}
    <script>
    (function () {
        const openBtn  = document.getElementById('tenant-sidebar-open-btn');
        const closeBtn = document.getElementById('tenant-sidebar-close-btn');
        const panel    = document.getElementById('tenant-sidebar-panel');
        const backdrop = document.getElementById('tenant-sidebar-backdrop');
        function openSidebar() {
            panel.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            requestAnimationFrame(() => backdrop.classList.remove('opacity-0'));
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            panel.classList.add('-translate-x-full');
            backdrop.classList.add('opacity-0');
            setTimeout(() => backdrop.classList.add('hidden'), 300);
            document.body.style.overflow = '';
        }
        openBtn?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        backdrop?.addEventListener('click', closeSidebar);
    })();
    </script>


    {{-- ══════════════════════════════════════════════
         Main Content Area
    ══════════════════════════════════════════════ --}}
    <main class="flex-1 md:ml-64 p-4 pt-[72px] md:pt-8 md:p-8 max-w-7xl mx-auto w-full">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-sm text-green-700 font-medium">
            <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 px-5 py-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-sm text-red-700 font-medium">
            <span class="material-symbols-outlined text-red-500 text-[20px]">error</span>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
