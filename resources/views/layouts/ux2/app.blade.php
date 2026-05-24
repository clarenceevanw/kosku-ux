<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'KosKu - Sewa Kos Mudah & Cerdas')</title>
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
    </style>
    @yield('styles')
</head>
<body class="bg-background text-on-background font-body-md text-body-md antialiased selection:bg-secondary-container selection:text-on-secondary-container min-h-screen flex flex-col">
    <!-- TopNavBar (Shared Component) -->
    <nav class="bg-background dark:bg-primary-container w-full top-0 sticky z-50 border-b border-outline-variant dark:border-outline transition-all duration-300" id="global-nav">
        <div class="flex justify-between items-center h-20 px-margin-mobile md:px-margin-desktop max-w-[1440px] mx-auto">
            <!-- Brand -->
            <a class="font-headline-md text-headline-md font-bold text-primary dark:text-on-primary flex items-center gap-2" href="{{ route('ux2.home') }}">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">home_work</span>
                KosKu
            </a>
            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center gap-lg h-full">
                <!-- Active Item -->
                <a class="h-full flex items-center {{ request()->routeIs('ux2.search') ? 'text-secondary dark:text-secondary-container font-bold border-b-2 border-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:text-secondary dark:hover:text-secondary-fixed' }} font-body-md text-body-md cursor-pointer transition-colors active:opacity-80" href="{{ route('ux2.search') }}">
                    Cari Kos
                </a>
                <a class="h-full flex items-center {{ request()->routeIs('ux2.bot') ? 'text-secondary dark:text-secondary-container font-bold border-b-2 border-secondary' : 'text-on-surface-variant dark:text-on-primary-container hover:text-secondary dark:hover:text-secondary-fixed' }} font-body-md text-body-md cursor-pointer transition-colors active:opacity-80" href="{{ route('ux2.bot') }}">
                    KosBot AI
                </a>
                <a class="h-full flex items-center text-on-surface-variant dark:text-on-primary-container font-body-md text-body-md hover:text-secondary dark:hover:text-secondary-fixed transition-colors cursor-pointer active:opacity-80" href="{{ route('ux2.owner.dashboard') }}">
                    Untuk Pemilik
                </a>
                <a class="h-full flex items-center text-on-surface-variant dark:text-on-primary-container font-body-md text-body-md hover:text-secondary dark:hover:text-secondary-fixed transition-colors cursor-pointer active:opacity-80" href="{{ route('ux2.tenant.dashboard') }}">
                    Untuk Penghuni
                </a>
            </div>
            <!-- Trailing Actions -->
            <div class="hidden md:flex items-center gap-md">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="font-label-md text-label-md text-primary bg-surface-container hover:bg-surface-container-high px-4 py-2 rounded-lg transition-colors">Logout</button>
                    </form>
                @else
                    <a class="font-label-md text-label-md text-primary hover:text-secondary transition-colors cursor-pointer" href="{{ route('ux2.login') }}">Sign In</a>
                    <a class="font-label-md text-label-md bg-primary-container text-on-primary hover:bg-inverse-surface px-md py-sm rounded-lg transition-colors cursor-pointer" href="{{ route('ux2.signup') }}">Sign Up</a>
                @endauth
            </div>
            <!-- Mobile Menu Toggle -->
            <button class="md:hidden text-primary">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Shared Footer -->
    <footer class="bg-primary-container dark:bg-primary text-on-primary w-full py-xl px-margin-mobile md:px-margin-desktop flat no shadows">
        <div class="flex flex-col md:flex-row justify-between items-start max-w-[1440px] mx-auto gap-lg">
            <div class="flex flex-col gap-4 max-w-sm">
                <a class="font-headline-md text-headline-md font-bold text-on-primary flex items-center gap-2" href="{{ route('ux2.home') }}">
                    <span class="material-symbols-outlined text-secondary-container" style="font-variation-settings: 'FILL' 1;">home_work</span>
                    KosKu
                </a>
                <p class="font-body-md text-body-md text-on-primary-container opacity-80">
                    Platform pencarian properti premium terpercaya. Temukan hunian nyaman dengan fasilitas terbaik.
                </p>
            </div>
            <div class="flex flex-col md:flex-row gap-xl">
                <div class="flex flex-col gap-sm">
                    <h4 class="font-label-md text-label-md font-bold text-on-primary mb-xs uppercase tracking-wider">Perusahaan</h4>
                    <a class="font-body-md text-body-md text-on-primary-container opacity-80 hover:text-secondary-container transition-colors cursor-pointer" href="#">Tentang Kami</a>
                    <a class="font-body-md text-body-md text-on-primary-container opacity-80 hover:text-secondary-container transition-colors cursor-pointer" href="#">Karier</a>
                    <a class="font-body-md text-body-md text-on-primary-container opacity-80 hover:text-secondary-container transition-colors cursor-pointer" href="#">Blog</a>
                </div>
                <div class="flex flex-col gap-sm">
                    <h4 class="font-label-md text-label-md font-bold text-on-primary mb-xs uppercase tracking-wider">Dukungan</h4>
                    <a class="font-body-md text-body-md text-on-primary-container opacity-80 hover:text-secondary-container transition-colors cursor-pointer" href="#">Pusat Bantuan</a>
                    <a class="font-body-md text-body-md text-on-primary-container opacity-80 hover:text-secondary-container transition-colors cursor-pointer" href="#">Kebijakan Privasi</a>
                    <a class="font-body-md text-body-md text-on-primary-container opacity-80 hover:text-secondary-container transition-colors cursor-pointer" href="#">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
        <div class="max-w-[1440px] mx-auto mt-xl pt-md border-t border-on-primary-container/20 flex justify-between items-center">
            <p class="font-body-md text-body-md text-on-primary-container opacity-80">© {{ now()->year }} KosKu. All rights reserved.</p>
        </div>
    </footer>

    <!-- Micro-interaction script -->
    <script>
        // Simple nav shadow on scroll for premium feel
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('global-nav');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-[0_4px_20px_rgba(15,23,42,0.08)]');
            } else {
                nav.classList.remove('shadow-[0_4px_20px_rgba(15,23,42,0.08)]');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
