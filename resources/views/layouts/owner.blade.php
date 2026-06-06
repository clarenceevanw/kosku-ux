<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'Dashboard Owner') — KosKu</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                        "primary-container": "#141b2b",
                        "accent-teal": "#0D9488"
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

    <x-owner-sidebar />

    {{-- ══════════════════════════════════════════════
         Main Content Area
    ══════════════════════════════════════════════ --}}
    <main class="flex-1 md:ml-64 p-4 pt-[72px] md:pt-8 md:p-8 max-w-7xl mx-auto w-full">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mb-6 px-5 py-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-sm text-green-700 font-medium shadow-sm">
            <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 px-5 py-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-sm text-red-700 font-medium shadow-sm">
            <span class="material-symbols-outlined text-red-500 text-[20px]">error</span>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
