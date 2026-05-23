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

    {{-- ══════════════════════════════════════════════
         Desktop Sidebar Navigation
    ══════════════════════════════════════════════ --}}
    <nav class="hidden md:flex flex-col h-screen w-64 fixed left-0 top-0 bg-surface-container-lowest border-r border-outline-variant/50 p-6 gap-2 z-40 overflow-y-auto">

        {{-- Brand --}}
        <div class="mb-8 px-4 pt-2">
            <a href="{{ route('home') }}" class="font-headline text-2xl font-bold text-primary hover:opacity-70 transition-opacity">KosKu</a>
            <div class="mt-2">
                <p class="font-headline text-lg font-semibold text-on-surface">Management Hub</p>
                <p class="font-body text-sm text-on-surface-variant">Dashboard Penghuni</p>
            </div>
        </div>

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
        </div>

        {{-- Bottom: User info + Logout --}}
        <div class="mt-auto pt-6 border-t border-outline-variant/50 space-y-3">
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
        </div>
    </nav>

    {{-- ══════════════════════════════════════════════
         Mobile Bottom Navigation
    ══════════════════════════════════════════════ --}}
    <nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center py-2 px-2 bg-surface-container-lowest border-t border-outline-variant/50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.06)] z-50">
        <a href="{{ route('tenant.dashboard') }}"
           class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
                  {{ request()->routeIs('tenant.dashboard') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined" @if(request()->routeIs('tenant.dashboard')) style="font-variation-settings:'FILL' 1" @endif>home</span>
            <span class="font-label text-[10px] mt-0.5 font-semibold">Home</span>
        </a>
        <a href="{{ route('tenant.payments') }}"
           class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
                  {{ request()->routeIs('tenant.payments') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined" @if(request()->routeIs('tenant.payments')) style="font-variation-settings:'FILL' 1" @endif>receipt_long</span>
            <span class="font-label text-[10px] mt-0.5 font-semibold">Tagihan</span>
        </a>
        <a href="{{ route('tenant.tickets') }}"
           class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
                  {{ request()->routeIs('tenant.tickets*') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined" @if(request()->routeIs('tenant.tickets*')) style="font-variation-settings:'FILL' 1" @endif>report_problem</span>
            <span class="font-label text-[10px] mt-0.5 font-semibold">Laporan</span>
        </a>
        <a href="{{ route('tenant.contract') }}"
           class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
                  {{ request()->routeIs('tenant.contract') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined" @if(request()->routeIs('tenant.contract')) style="font-variation-settings:'FILL' 1" @endif>description</span>
            <span class="font-label text-[10px] mt-0.5 font-semibold">Kontrak</span>
        </a>
        <a href="{{ route('tenant.rules') }}"
           class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
                  {{ request()->routeIs('tenant.rules') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined" @if(request()->routeIs('tenant.rules')) style="font-variation-settings:'FILL' 1" @endif>gavel</span>
            <span class="font-label text-[10px] mt-0.5 font-semibold">Aturan</span>
        </a>
        <a href="{{ route('tenant.settings') }}"
           class="flex flex-col items-center justify-center p-2 rounded-xl transition-colors min-w-0
                  {{ request()->routeIs('tenant.settings') ? 'text-primary' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
            <span class="material-symbols-outlined" @if(request()->routeIs('tenant.settings')) style="font-variation-settings:'FILL' 1" @endif>settings</span>
            <span class="font-label text-[10px] mt-0.5 font-semibold">Setting</span>
        </a>
    </nav>

    {{-- ══════════════════════════════════════════════
         Main Content Area
    ══════════════════════════════════════════════ --}}
    <main class="flex-1 md:ml-64 p-4 md:p-8 max-w-7xl mx-auto w-full pb-24 md:pb-8">

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
