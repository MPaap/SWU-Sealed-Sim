<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite('resources/css/app.css')

    @yield('scripts')

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
                rgba(255, 255, 255, 0.55) 15%,
                rgba(0, 255, 255, 0.25) 35%,
                rgba(0, 255, 255, 0.0) 50%
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
                rgba(255, 0, 200, 0.1),
                rgba(255, 255, 0, 0.1),
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
    <div id="app" v-cloak>
        @yield('content')
    </div>
</body>
</html>
