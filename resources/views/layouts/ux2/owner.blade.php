<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Dashboard Owner') — KosKu</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-variant": "#d8e3fb",
                        "primary-fixed-dim": "#bec6e0",
                        "on-primary-fixed-variant": "#3f465c",
                        "secondary": "#006c49",
                        "tertiary-fixed": "#e9ddff",
                        "tertiary": "#000000",
                        "on-surface-variant": "#45464d",
                        "on-secondary-fixed-variant": "#005236",
                        "on-error-container": "#93000a",
                        "on-secondary-container": "#00714d",
                        "surface-bright": "#f9f9ff",
                        "background": "#f9f9ff",
                        "surface-container-lowest": "#ffffff",
                        "surface": "#f9f9ff",
                        "on-primary-container": "#7c839b",
                        "outline": "#76777d",
                        "on-tertiary-fixed": "#23005c",
                        "on-error": "#ffffff",
                        "surface-dim": "#cfdaf2",
                        "secondary-container": "#6cf8bb",
                        "on-tertiary-container": "#9466ff",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f0f3ff",
                        "on-primary": "#ffffff",
                        "outline-variant": "#c6c6cd",
                        "surface-tint": "#565e74",
                        "surface-container": "#e7eeff",
                        "on-background": "#111c2d",
                        "surface-container-highest": "#d8e3fb",
                        "on-surface": "#111c2d",
                        "on-secondary-fixed": "#002113",
                        "on-tertiary": "#ffffff",
                        "primary": "#000000",
                        "error-container": "#ffdad6",
                        "tertiary-fixed-dim": "#d0bcff",
                        "inverse-surface": "#263143",
                        "inverse-on-surface": "#ecf1ff",
                        "surface-container-high": "#dee8ff",
                        "secondary-fixed": "#6ffbbe",
                        "primary-container": "#131b2e",
                        "on-secondary": "#ffffff",
                        "on-primary-fixed": "#131b2e",
                        "inverse-primary": "#bec6e0",
                        "on-tertiary-fixed-variant": "#5516be",
                        "tertiary-container": "#23005c",
                        "secondary-fixed-dim": "#4edea3",
                        "primary-fixed": "#dae2fd"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "3xl": "1.5rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "md": "24px",
                        "margin-desktop": "40px",
                        "gutter": "24px",
                        "lg": "48px",
                        "base": "8px",
                        "sm": "12px",
                        "xs": "4px",
                        "margin-mobile": "16px",
                        "xl": "80px"
                    },
                    "fontFamily": {
                        "label-sm": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-md": ["Inter"],
                        "display-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-lg": ["Inter"]
                    },
                    "fontSize": {
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .nav-item-active {
            background-color: #6cf8bb;
            color: #000000;
        }
    </style>
    @include('layouts.ux2.theme')

    @stack('styles')
</head>
<body class="bg-background text-on-background font-body-md antialiased flex min-h-screen">

    <!-- Sidebar -->
    <aside class="hidden md:flex md:flex-col md:w-64 ux2-dark-panel border-r border-white/10 fixed h-screen z-40">
        <!-- Brand -->
        <div class="p-lg border-b border-white/10">
            <a href="{{ route('ux2.home') }}" class="flex items-center gap-sm">
                <span class="w-11 h-11 rounded-lg bg-secondary-container text-on-secondary-container inline-flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">home_work</span>
                </span>
                <span class="font-headline-md text-headline-md font-bold text-on-primary">KosKu</span>
            </a>
            <p class="font-label-sm text-label-sm text-on-primary-container mt-xs">Owner Dashboard</p>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-md overflow-y-auto">
            <ul class="flex flex-col gap-xs">
                <li>
                    <a href="{{ route('ux2.owner.dashboard') }}" class="flex items-center gap-sm px-md py-sm rounded-xl transition-colors {{ request()->routeIs('ux2.owner.dashboard') ? 'nav-item-active' : 'hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ux2.owner.kos.index') }}" class="flex items-center gap-sm px-md py-sm rounded-xl transition-colors {{ request()->routeIs('ux2.owner.kos.*') ? 'nav-item-active' : 'hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">home_work</span>
                        <span class="font-label-md text-label-md">Kelola Kos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ux2.owner.rooms.index') }}" class="flex items-center gap-sm px-md py-sm rounded-xl transition-colors {{ request()->routeIs('ux2.owner.rooms.*') ? 'nav-item-active' : 'hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">bed</span>
                        <span class="font-label-md text-label-md">Kelola Kamar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ux2.owner.keuangan.index') }}" class="flex items-center gap-sm px-md py-sm rounded-xl transition-colors {{ request()->routeIs('ux2.owner.keuangan.*') ? 'nav-item-active' : 'hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">payments</span>
                        <span class="font-label-md text-label-md">Keuangan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ux2.owner.transactions.index') }}" class="flex items-center gap-sm px-md py-sm rounded-xl transition-colors {{ request()->routeIs('ux2.owner.transactions.*') ? 'nav-item-active' : 'hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">receipt_long</span>
                        <span class="font-label-md text-label-md">Pemesanan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ux2.owner.tickets.index') }}" class="flex items-center gap-sm px-md py-sm rounded-xl transition-colors {{ request()->routeIs('ux2.owner.tickets.*') ? 'nav-item-active' : 'hover:bg-surface-container' }}">
                        <span class="material-symbols-outlined">build</span>
                        <span class="font-label-md text-label-md">Laporan Kerusakan</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Profile -->
        <div class="p-md border-t border-white/10">
            <div class="flex items-center gap-sm mb-md">
                <div class="w-10 h-10 rounded-lg bg-secondary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-secondary-container">person</span>
                </div>
                <div class="flex-1">
                    <p class="font-label-md text-label-md font-bold text-on-primary">{{ auth()->user()->name }}</p>
                    <p class="font-label-sm text-label-sm text-on-primary-container">Owner</p>
                </div>
            </div>
            <form method="POST" action="{{ route('ux2.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-sm px-md py-sm bg-error-container text-error rounded-xl hover:bg-error hover:text-on-error transition-colors">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-label-md text-label-md">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Top Bar -->
    <header class="md:hidden fixed top-0 inset-x-0 z-40 bg-surface-container-lowest/95 backdrop-blur-xl border-b border-outline-variant px-margin-mobile py-sm flex items-center justify-between">
        <a href="{{ route('ux2.home') }}" class="flex items-center gap-xs font-headline-md text-headline-md font-bold text-primary">
            <span class="w-9 h-9 rounded-lg bg-secondary-container text-on-secondary-container inline-flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home_work</span>
            </span>
            KosKu
        </a>
        <form method="POST" action="{{ route('ux2.logout') }}">
            @csrf
            <button type="submit" class="w-10 h-10 rounded-lg border border-outline-variant text-error flex items-center justify-center">
                <span class="material-symbols-outlined">logout</span>
            </button>
        </form>
    </header>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-margin-mobile pt-24 pb-24 md:pt-lg md:pb-lg md:p-lg max-w-7xl mx-auto w-full">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-md px-md py-sm bg-secondary-container/20 border border-secondary-container rounded-xl flex items-center gap-sm">
            <span class="material-symbols-outlined text-secondary">check_circle</span>
            <span class="font-body-md text-body-md text-on-surface">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-md px-md py-sm bg-error-container border border-error rounded-xl flex items-center gap-sm">
            <span class="material-symbols-outlined text-error">error</span>
            <span class="font-body-md text-body-md text-on-error-container">{{ session('error') }}</span>
        </div>
        @endif

        @yield('content')
    </main>

    <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-surface-container-lowest/95 backdrop-blur-xl border-t border-outline-variant shadow-lg">
        <div class="grid grid-cols-6 h-16">
            <a href="{{ route('ux2.owner.dashboard') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('ux2.owner.dashboard') ? 'text-secondary' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                <span class="text-[10px] font-semibold">Home</span>
            </a>
            <a href="{{ route('ux2.owner.kos.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('ux2.owner.kos.*') ? 'text-secondary' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[20px]">home_work</span>
                <span class="text-[10px] font-semibold">Kos</span>
            </a>
            <a href="{{ route('ux2.owner.rooms.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('ux2.owner.rooms.*') ? 'text-secondary' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[20px]">bed</span>
                <span class="text-[10px] font-semibold">Kamar</span>
            </a>
            <a href="{{ route('ux2.owner.keuangan.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('ux2.owner.keuangan.*') ? 'text-secondary' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[20px]">payments</span>
                <span class="text-[10px] font-semibold">Uang</span>
            </a>
            <a href="{{ route('ux2.owner.transactions.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('ux2.owner.transactions.*') ? 'text-secondary' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                <span class="text-[10px] font-semibold">Pesan</span>
            </a>
            <a href="{{ route('ux2.owner.tickets.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('ux2.owner.tickets.*') ? 'text-secondary' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-[20px]">build</span>
                <span class="text-[10px] font-semibold">Tiket</span>
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
