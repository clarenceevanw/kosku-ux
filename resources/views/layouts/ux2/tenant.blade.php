<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Dashboard Penghuni - KosKu')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    
    <!-- Theme Configuration -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary-container": "#9466ff",
                        "primary": "#000000",
                        "surface-tint": "#565e74",
                        "inverse-on-surface": "#ecf1ff",
                        "on-error": "#ffffff",
                        "surface-bright": "#f9f9ff",
                        "inverse-primary": "#bec6e0",
                        "on-secondary-fixed-variant": "#005236",
                        "on-secondary-fixed": "#002113",
                        "primary-container": "#131b2e",
                        "tertiary-container": "#23005c",
                        "surface-container": "#e7eeff",
                        "surface-container-high": "#dee8ff",
                        "on-primary": "#ffffff",
                        "tertiary-fixed": "#e9ddff",
                        "surface-container-lowest": "#ffffff",
                        "outline-variant": "#c6c6cd",
                        "surface-container-low": "#f0f3ff",
                        "primary-fixed-dim": "#bec6e0",
                        "surface": "#f9f9ff",
                        "secondary-fixed-dim": "#4edea3",
                        "on-tertiary-fixed-variant": "#5516be",
                        "surface-variant": "#d8e3fb",
                        "secondary-fixed": "#6ffbbe",
                        "on-tertiary-fixed": "#23005c",
                        "on-surface": "#111c2d",
                        "error": "#ba1a1a",
                        "on-primary-fixed-variant": "#3f465c",
                        "on-secondary-container": "#00714d",
                        "primary-fixed": "#dae2fd",
                        "tertiary": "#000000",
                        "on-background": "#111c2d",
                        "on-tertiary": "#ffffff",
                        "outline": "#76777d",
                        "on-primary-container": "#7c839b",
                        "on-error-container": "#93000a",
                        "error-container": "#ffdad6",
                        "secondary": "#006c49",
                        "on-secondary": "#ffffff",
                        "secondary-container": "#6cf8bb",
                        "on-surface-variant": "#45464d",
                        "background": "#f9f9ff",
                        "inverse-surface": "#263143",
                        "on-primary-fixed": "#131b2e",
                        "surface-container-highest": "#d8e3fb",
                        "surface-dim": "#cfdaf2",
                        "tertiary-fixed-dim": "#d0bcff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "md": "24px",
                        "xl": "80px",
                        "sm": "12px",
                        "gutter": "24px",
                        "base": "8px",
                        "xs": "4px",
                        "margin-desktop": "40px",
                        "lg": "48px"
                    },
                    "fontFamily": {
                        "headline-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "label-sm": ["Inter"],
                        "display-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "600"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
        body {
            background-color: #f9f9ff;
        }
    </style>
    @include('layouts.ux2.theme')
    @yield('styles')
</head>
<body class="flex flex-col md:flex-row h-screen font-body-md text-on-surface antialiased">
    @php
        $tenant = auth()->user();
        $roomName = $activeContract?->room?->type_name ?? ($activeTransaction?->room?->type_name ?? 'Belum ada kamar aktif');
    @endphp

    <!-- SideNavBar (Desktop) -->
    <nav class="hidden md:flex flex-col py-6 gap-2 ux2-dark-panel shadow-sm h-screen w-64 fixed left-0 top-0 overflow-y-auto">
        <div class="px-6 mb-8">
            <a href="{{ route('ux2.home') }}" class="inline-flex items-center gap-2 font-headline-md text-headline-md font-bold text-on-primary hover:text-secondary-container transition-colors">
                <span class="w-10 h-10 rounded-lg bg-secondary-container text-on-secondary-container inline-flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home_work</span>
                </span>
                KosKu
            </a>
        </div>
        <!-- User Profile -->
        <div class="px-6 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container overflow-hidden flex items-center justify-center font-label-md font-bold shrink-0">
                {{ strtoupper(substr($tenant->name ?? 'P', 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="font-label-md text-label-md text-on-primary font-semibold truncate">{{ $tenant->name ?? 'Penghuni' }}</p>
                <p class="font-label-sm text-label-sm text-white/70 truncate">{{ $roomName }}</p>
            </div>
        </div>
        
        <div class="flex-1 flex flex-col gap-2 px-2">
            <!-- Active Tab styling logic -->
            @php
                $navItems = [
                    ['route' => 'ux2.tenant.dashboard', 'icon' => 'home', 'label' => 'Beranda', 'match' => 'dashboard'],
                    ['route' => 'ux2.tenant.payments', 'icon' => 'payments', 'label' => 'Tagihan Saya', 'match' => 'tagihan'],
                    ['route' => 'ux2.tenant.tickets', 'icon' => 'build', 'label' => 'Laporan Kerusakan', 'match' => 'laporan'],
                    ['route' => 'ux2.tenant.contract', 'icon' => 'description', 'label' => 'Kontrak Digital', 'match' => 'kontrak'],
                    ['route' => 'ux2.tenant.rules', 'icon' => 'gavel', 'label' => 'Peraturan Kos', 'match' => 'peraturan'],
                    ['route' => 'ux2.verification.index', 'icon' => 'verified_user', 'label' => 'Verifikasi Identitas', 'match' => null],
                    ['route' => 'ux2.tenant.settings', 'icon' => 'settings', 'label' => 'Pengaturan', 'match' => 'pengaturan'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = $item['match']
                        ? request()->is("ux2/tenant/{$item['match']}*")
                        : request()->routeIs('ux2.verification.*');
                    $activeClass = $isActive 
                        ? 'bg-secondary-container text-on-secondary-container' 
                        : 'text-white/75 hover:bg-white/10 hover:text-white';
                @endphp
                <a class="flex items-center gap-3 rounded-lg px-4 py-3 mx-2 transition-all active:scale-95 duration-150 {{ $activeClass }}"
                    href="{{ route($item['route']) }}">
                    <span class="material-symbols-outlined" {{ $isActive ? 'style=font-variation-settings:\'FILL\'1;' : '' }}>{{ $item['icon'] }}</span>
                    <span class="font-label-md text-label-md">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
        
        <div class="px-4 mt-auto pt-4 border-t border-white/10">
            <a class="w-full bg-error text-on-error font-label-md text-label-md py-3 rounded-lg flex justify-center items-center gap-2 hover:bg-opacity-90 transition-colors mb-4"
                href="{{ route('ux2.tenant.tickets.create') }}">
                <span class="material-symbols-outlined text-[18px]">report_problem</span>
                Report Issue
            </a>
            
            <form method="POST" action="{{ route('ux2.logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 text-white/70 hover:text-error py-2 font-label-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 overflow-y-auto pb-20 md:pb-0">
        <!-- Top Navbar with Menu & Search -->
        <div class="bg-surface-container-lowest/95 backdrop-blur-xl border-b border-outline-variant sticky top-0 z-50 shadow-sm">
            <div class="max-w-[1440px] mx-auto px-margin-mobile md:px-margin-desktop">
                <div class="flex items-center justify-between h-16 gap-4">
                    <!-- Navigation Links (Desktop) -->
                    <div class="hidden md:flex items-center gap-2">
                        <a class="px-4 py-2 rounded-full {{ request()->routeIs('ux2.search') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-secondary' }} font-body-md text-body-md cursor-pointer transition-colors" href="{{ route('ux2.search') }}">
                            Cari Kos
                        </a>
                        <a class="px-4 py-2 rounded-full {{ request()->routeIs('ux2.bot') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-secondary' }} font-body-md text-body-md cursor-pointer transition-colors" href="{{ route('ux2.bot') }}">
                            KosBot AI
                        </a>
                        <a href="{{ route('ux2.tenant.dashboard') }}"
                            class="px-4 py-2 rounded-full {{ request()->routeIs('ux2.tenant.*') ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-secondary' }} font-body-md text-body-md cursor-pointer transition-colors">
                            Dashboard
                        </a>
                    </div>
                    
                    <!-- Search Bar -->
                    <form action="{{ route('ux2.search') }}" method="GET" class="flex-1 max-w-md">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                            <input type="text" name="q" placeholder="Cari kos..."
                                class="w-full pl-11 pr-4 py-2 rounded-xl border border-outline-variant bg-surface-container-low focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all font-body-md text-body-md">
                        </div>
                    </form>
                    
                    <!-- Logout Button (Desktop) -->
                    <form method="POST" action="{{ route('ux2.logout') }}" class="hidden md:block">
                        @csrf
                        <button type="submit" class="font-label-md text-label-md text-primary bg-surface-container hover:bg-surface-container-high px-4 py-2 rounded-lg transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Content Container -->
        <div class="p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto min-h-[calc(100vh-140px)]">
            
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-secondary-container text-on-secondary-container p-4 rounded-xl flex items-center gap-3 border border-secondary/20 shadow-sm">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <p class="font-body-md text-body-md font-medium">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-error-container text-on-error-container p-4 rounded-xl flex items-center gap-3 border border-error/20 shadow-sm">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">error</span>
                <p class="font-body-md text-body-md font-medium">{{ session('error') }}</p>
            </div>
            @endif

            @yield('content')
            
        </div>
        
        <!-- Minimal Footer for Main Canvas -->
        <footer class="mt-8 py-6 border-t border-outline-variant/50 text-center px-margin-mobile">
            <p class="font-body-md text-body-md text-on-surface-variant opacity-70">&copy; {{ now()->year }} KosKu. All rights reserved.</p>
        </footer>
    </main>

    <!-- Bottom Navigation Bar (Mobile) - Hidden on desktop -->
    <nav class="md:hidden fixed bottom-0 w-full bg-surface-container-lowest border-t border-outline-variant shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-50">
        <div class="flex justify-around items-center h-16">
            <a class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('ux2.tenant.dashboard') ? 'text-secondary' : 'text-on-surface-variant' }}" href="{{ route('ux2.tenant.dashboard') }}">
                <span class="material-symbols-outlined text-[20px]" {{ request()->routeIs('ux2.tenant.dashboard') ? 'style=font-variation-settings:\'FILL\'1;' : '' }}>home</span>
                <span class="font-label-sm text-[10px] mt-1">Beranda</span>
            </a>
            <a class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('ux2.tenant.payments*') ? 'text-secondary' : 'text-on-surface-variant' }}" href="{{ route('ux2.tenant.payments') }}">
                <span class="material-symbols-outlined text-[20px]" {{ request()->routeIs('ux2.tenant.payments*') ? 'style=font-variation-settings:\'FILL\'1;' : '' }}>payments</span>
                <span class="font-label-sm text-[10px] mt-1">Tagihan</span>
            </a>
            <a class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('ux2.tenant.tickets*') ? 'text-secondary' : 'text-on-surface-variant' }}" href="{{ route('ux2.tenant.tickets') }}">
                <span class="material-symbols-outlined text-[20px]" {{ request()->routeIs('ux2.tenant.tickets*') ? 'style=font-variation-settings:\'FILL\'1;' : '' }}>build</span>
                <span class="font-label-sm text-[10px] mt-1">Lapor</span>
            </a>
            <a class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('ux2.verification.*') ? 'text-secondary' : 'text-on-surface-variant' }}" href="{{ route('ux2.verification.index') }}">
                <span class="material-symbols-outlined text-[20px]" {{ request()->routeIs('ux2.verification.*') ? 'style=font-variation-settings:\'FILL\'1;' : '' }}>verified_user</span>
                <span class="font-label-sm text-[10px] mt-1">Verif</span>
            </a>
            <a class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('ux2.tenant.settings') ? 'text-secondary' : 'text-on-surface-variant' }}" href="{{ route('ux2.tenant.settings') }}">
                <span class="material-symbols-outlined text-[20px]" {{ request()->routeIs('ux2.tenant.settings') ? 'style=font-variation-settings:\'FILL\'1;' : '' }}>person</span>
                <span class="font-label-sm text-[10px] mt-1">Profil</span>
            </a>
        </div>
    </nav>
    
    @yield('scripts')
</body>
</html>
