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
</head>
<body class="bg-background text-on-background min-h-screen flex items-center justify-center font-body-md">
    <div class="w-full max-w-[1440px] h-screen md:h-[900px] flex flex-col md:flex-row bg-surface-container-lowest overflow-hidden shadow-2xl md:rounded-2xl">
        
        <!-- Left Section: Image & Branding -->
        <div class="hidden md:flex md:w-1/2 relative bg-primary-container items-center justify-center overflow-hidden">
            <!-- Background Visual -->
            <div class="absolute inset-0 z-0 bg-primary-container">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(108,248,187,0.22),transparent_32%),radial-gradient(circle_at_75%_70%,rgba(208,188,255,0.18),transparent_28%)]"></div>
            </div>
            <!-- Branding Content -->
            <div class="relative z-10 text-center px-lg text-on-primary">
                <div class="w-20 h-20 rounded-2xl bg-secondary-container text-on-secondary-container flex items-center justify-center mx-auto mb-md">
                    <span class="material-symbols-outlined text-5xl" style="font-variation-settings: 'FILL' 1;">home_work</span>
                </div>
                <h1 class="font-display-lg text-display-lg mb-md text-on-primary">KosKu</h1>
                <p class="font-body-lg text-body-lg opacity-90 max-w-md mx-auto">
                    Temukan hunian nyaman dan premium dengan mudah. Solusi cerdas untuk pencarian kos Anda.
                </p>
                <!-- Decorative Elements -->
                <div class="mt-xl flex justify-center gap-sm">
                    <div class="w-16 h-1 bg-secondary-container rounded-full"></div>
                    <div class="w-4 h-1 bg-surface-variant/30 rounded-full"></div>
                    <div class="w-4 h-1 bg-surface-variant/30 rounded-full"></div>
                </div>
            </div>
        </div>
        
        <!-- Right Section: Form -->
        <div class="w-full md:w-1/2 flex flex-col justify-center px-margin-mobile md:px-xl py-lg bg-surface-container-lowest overflow-y-auto">
            @yield('content')
        </div>
    </div>
</body>
</html>
