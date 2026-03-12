<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-slate-100">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="/" class="flex items-center gap-3">
                <x-application-logo class="h-9 w-9 fill-current text-orange-500" />
                <div>
                    <p class="text-sm font-bold tracking-wide text-slate-900">SIMPEDA</p>
                    <p class="text-xs text-slate-500">Layanan Digital Desa</p>
                </div>
            </a>

            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ url('/') }}" class="rounded-md px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900">Beranda</a>
                <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900">Login</a>
                <a href="{{ route('register') }}" class="rounded-md bg-orange-500 px-3 py-2 font-semibold text-white hover:bg-orange-600">Daftar</a>
            </nav>
        </div>
    </header>

    <main class="px-4 py-8 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</body>
</html>
