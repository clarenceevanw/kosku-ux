<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'KosKu - Masuk atau Daftar')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .form-input:focus {
            outline: none;
            border-color: #6cf8bb; /* secondary-container / Fresh Mint */
            box-shadow: 0 0 0 2px rgba(108, 248, 187, 0.3);
        }
    </style>
    @include('layouts.ux2.theme')
</head>
<body class="bg-background text-on-background min-h-screen flex items-center justify-center font-body-md px-margin-mobile py-margin-mobile">
    <div class="w-full max-w-[1180px] min-h-[calc(100vh-32px)] md:min-h-[760px] flex flex-col md:flex-row bg-surface-container-lowest overflow-hidden shadow-2xl md:rounded-2xl border border-outline-variant">
        
        <!-- Left Section: Image & Branding -->
        <div class="hidden md:flex md:w-[46%] relative ux2-dark-panel items-center justify-center overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-20"
                style="background-image: linear-gradient(rgba(255,255,255,.14) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.14) 1px, transparent 1px); background-size: 42px 42px;">
            </div>
            <!-- Branding Content -->
            <div class="relative z-10 px-lg text-on-primary max-w-md">
                <div class="w-20 h-20 rounded-2xl bg-secondary-container text-on-secondary-container flex items-center justify-center mb-md">
                    <span class="material-symbols-outlined text-5xl" style="font-variation-settings: 'FILL' 1;">home_work</span>
                </div>
                <h1 class="font-display-lg text-display-lg mb-md text-on-primary">KosKu</h1>
                <p class="font-body-lg text-body-lg opacity-90">
                    Masuk untuk melanjutkan pencarian, pembayaran, atau pengelolaan kos dengan ruang kerja yang lebih rapi.
                </p>
                <div class="mt-xl grid grid-cols-2 gap-sm">
                    <div class="border border-white/15 bg-white/10 rounded-lg p-sm">
                        <span class="material-symbols-outlined text-secondary-container mb-xs">verified</span>
                        <p class="font-label-sm text-label-sm text-white/80">Data kos terkurasi</p>
                    </div>
                    <div class="border border-white/15 bg-white/10 rounded-lg p-sm">
                        <span class="material-symbols-outlined text-secondary-container mb-xs">payments</span>
                        <p class="font-label-sm text-label-sm text-white/80">Pembayaran escrow</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Section: Form -->
        <div class="w-full md:w-[54%] flex flex-col justify-center px-margin-mobile md:px-xl py-lg bg-surface-container-lowest overflow-y-auto">
            @yield('content')
        </div>
    </div>
</body>
</html>
