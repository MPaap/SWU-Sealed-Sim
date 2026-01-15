<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite('resources/css/app.css')

    @yield('scripts')

    <link rel="icon" type="image/png" href="/images/favicons/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/images/favicons/favicon.svg" />
    <link rel="shortcut icon" href="/images/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="SWU Sealed" />
    <link rel="manifest" href="/images/favicons/site.webmanifest" />

    <style>
        .holo {
            position: relative;
            overflow: hidden;
        }

        /* Main foil shine sweep (bright but smooth) */
        .holo::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                120deg,
                rgba(0, 255, 255, 0.0) 0%,
                rgba(0, 255, 255, 0.25) 10%,
                rgba(255, 255, 255, 0.55) 35%,
                rgba(0, 255, 255, 0.25) 45%,
                rgba(0, 255, 255, 0.0) 70%
            );
            mix-blend-mode: screen;
            transform: translateX(0) rotate(0deg);
            pointer-events: none;
        }

        /* Subtle kyber-crystal rainbow shimmer */
        .holo::after {
            content: "";
            position: absolute;
            inset: 0;
            background: conic-gradient(
                from 0deg,
                rgba(0, 180, 255, 0.1),
                rgba(255, 0, 200, 0.4),
                rgba(255, 255, 0, 0.2),
                rgba(0, 180, 255, 0.1)
            );
            mix-blend-mode: color-dodge;
            transform: rotate(180deg);
            pointer-events: none;
        }

        [v-cloak] {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-900 text-white font-bold">
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
