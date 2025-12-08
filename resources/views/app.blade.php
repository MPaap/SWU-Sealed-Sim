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
            filter: saturate(400%);
        }
    </style>
</head>
<body class="bg-gray-900 text-white font-bold">
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
