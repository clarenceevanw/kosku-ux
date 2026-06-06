<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'KosBot AI Chat')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
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
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-background text-on-background h-screen flex overflow-hidden">
    <!-- TopNavBar (Mobile Only) -->
    <header class="md:hidden w-full top-0 sticky z-50 border-b border-outline-variant bg-background flex justify-between items-center h-20 px-margin-mobile">
        <a href="{{ route('ux2.home') }}" class="font-headline-md text-headline-md font-bold text-primary">KosKu</a>
        <button class="material-symbols-outlined text-primary text-3xl">menu</button>
    </header>
    
    <!-- SideNavBar (Desktop Only) -->
    <aside class="hidden md:flex h-screen w-64 fixed left-0 top-0 bg-surface shadow-sm flex-col py-6 gap-2 z-40">
        <div class="px-6 mb-6">
            <a href="{{ route('ux2.home') }}" class="font-headline-md text-headline-md font-bold text-primary">KosKu</a>
        </div>
        <nav class="flex-1 overflow-y-auto px-2">
            <!-- Navigation items mapping from JSON, but prioritizing KosBot AI context -->
            <a class="flex items-center gap-3 text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container-high rounded-lg transition-all active:scale-95 duration-150 font-label-md text-label-md"
                href="{{ route('ux2.search') }}">
                <span class="material-symbols-outlined">search</span>
                Cari Kos
            </a>
            <a class="flex items-center gap-3 bg-secondary-container text-on-secondary-container rounded-lg px-4 py-3 mx-2 hover:bg-surface-container-high transition-all active:scale-95 duration-150 font-label-md text-label-md"
                href="{{ route('ux2.bot') }}">
                <span class="material-symbols-outlined">auto_awesome</span>
                KosBot AI
            </a>
            <a class="flex items-center gap-3 text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container-high rounded-lg transition-all active:scale-95 duration-150 font-label-md text-label-md"
                href="{{ route('ux2.owner.dashboard') }}">
                <span class="material-symbols-outlined">home_work</span>
                Untuk Pemilik
            </a>
            <a class="flex items-center gap-3 text-on-surface-variant px-4 py-3 mx-2 hover:bg-surface-container-high rounded-lg transition-all active:scale-95 duration-150 font-label-md text-label-md"
                href="{{ route('ux2.tenant.dashboard') }}">
                <span class="material-symbols-outlined">person</span>
                Untuk Penghuni
            </a>
        </nav>
        <div class="mt-auto px-6 pt-4 border-t border-outline-variant/30">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-secondary-container text-on-secondary-container shadow-sm flex items-center justify-center">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-primary font-semibold">{{ auth()->user()->name ?? 'Pengunjung' }}</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">{{ auth()->check() ? 'Kelola hunian Anda' : 'Mode pencarian kos' }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 flex flex-col h-screen md:h-auto overflow-hidden bg-surface-container-lowest">
        @yield('content')
    </main>
</body>
</html>
