<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'KosKu - Pembayaran Escrow')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        "headline": ["Inter"],
                        "display": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background font-body text-on-background antialiased min-h-screen flex flex-col">
    @yield('content')
</body>
</html>
