<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>KosKu - Premium Rental Platform</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "surface-container-low": "#f6f3f4",
                      "on-primary-fixed-variant": "#404758",
                      "primary-fixed": "#dce2f7",
                      "on-tertiary-fixed": "#261906",
                      "surface-container-highest": "#e5e2e3",
                      "tertiary-fixed-dim": "#dcc2a4",
                      "primary-fixed-dim": "#c0c6db",
                      "primary-container": "#141b2b",
                      "primary": "#111827",
                      "outline-variant": "#c6c6cd",
                      "on-error": "#ffffff",
                      "on-secondary-container": "#606365",
                      "inverse-surface": "#303031",
                      "error": "#ba1a1a",
                      "surface": "#fcf8fa",
                      "tertiary-container": "#261906",
                      "on-background": "#1b1b1d",
                      "surface-dim": "#dcd9db",
                      "surface-container": "#f0edee",
                      "on-surface-variant": "#45464c",
                      "on-secondary-fixed-variant": "#444749",
                      "on-primary": "#ffffff",
                      "secondary": "#5c5f60",
                      "on-primary-fixed": "#141b2b",
                      "on-surface": "#1b1b1d",
                      "secondary-container": "#dee0e2",
                      "tertiary-fixed": "#f9debf",
                      "surface-bright": "#fcf8fa",
                      "on-tertiary-container": "#968065",
                      "on-error-container": "#93000a",
                      "secondary-fixed": "#e1e2e4",
                      "on-tertiary-fixed-variant": "#55442d",
                      "error-container": "#ffdad6",
                      "on-secondary-fixed": "#191c1e",
                      "secondary-fixed-dim": "#c5c6c8",
                      "surface-container-high": "#eae7e9",
                      "inverse-primary": "#c0c6db",
                      "background": "#fcf8fa",
                      "surface-variant": "#e5e2e3",
                      "surface-container-lowest": "#ffffff",
                      "tertiary": "#111827",
                      "on-primary-container": "#7d8497",
                      "on-secondary": "#ffffff",
                      "inverse-on-surface": "#f3f0f1",
                      "on-tertiary": "#ffffff",
                      "outline": "#76777d",
                      "surface-tint": "#575e70"
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
              },
              "animation": {
                "marquee": "marquee 40s linear infinite",
                "marquee-mobile": "marquee 30s linear infinite"
              },
              "keyframes": {
                "marquee": {
                  "0%": { transform: "translateX(0)" },
                  "100%": { transform: "translateX(calc(-50% - 24px))" }
                }
              }
            }
          }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-panel { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); }
    </style>
    @stack('styles')
</head>
<body class="bg-surface-bright text-on-background antialiased selection:bg-primary-container selection:text-on-primary-container">

    <x-navbar />

    @yield('content')

    <x-footer />

    @stack('scripts')
</body>
</html>
