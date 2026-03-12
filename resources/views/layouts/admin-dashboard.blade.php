<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' | Admin')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans antialiased overflow-x-hidden">
    @php
    $isPetugas = (auth()->user()->role ?? null) === 'petugas';
    $routePrefix = $isPetugas ? 'petugas' : 'admin';
    @endphp
    <div class="min-h-screen">
        <header
            class="fixed inset-x-0 top-0 z-40 h-16 border-b border-gray-500 bg-gradient-to-r from-gray-900 via-gray-900 to-gray-800 px-6">
            <div class="flex h-full items-center justify-between">
                <div class="flex items-center gap-4 px-5 py-3">
                    <button id="sidebarOpenBtn" type="button"
                        class="inline-flex h-15 w-15 items-center justify-center text-white hover:text-orange-500 md:hidden">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="{{ url('/') }}" class="hidden items-center gap-3 sm:flex">
                        <img src="{{ asset('img/logo_TA.png') }}" alt="Logo Desa Wonorejo"
                            class="h-10 w-auto rounded-full object-cover">
                        <div class="leading-tight leading-relaxed text-white">
                            <p class="text-[10px] font-extrabold uppercase tracking-[2px]">Desa Wonorejo</p>
                            <p class="text-[7px] font-semibold uppercase tracking-[2px]">Kecamatan Sumbergempol</p>
                            <p class="text-[7px] font-semibold uppercase tracking-[2px]">Kabupaten Tulungagung</p>
                        </div>
                    </a>
                </div>

                <details class="group relative">
                    <summary
                        class="flex cursor-pointer list-none items-center gap-2 text-xs font-semibold tracking-[1px] text-white hover:text-gray-300">
                        {{ $isPetugas ? 'Dashboard Petugas' : 'Dashboard Admin' }}
                        <i
                            class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 group-open:rotate-180"></i>
                    </summary>
                    <div
                        class="absolute right-0 z-50 hidden w-56 rounded-md border border-gray-200 bg-white p-2 shadow-md group-open:block">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full rounded-lg px-3 py-1.5 text-left text-sm font-semibold tracking-[1px] text-red-700 hover:bg-red-200">
                                Keluar
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </header>

        <div class="flex min-h-screen pt-16">
            <div id="sidebarBackdrop" class="fixed inset-0 z-30 hidden bg-black/50 md:hidden"></div>

            <aside id="sidebarDrawer"
                class="fixed left-0 top-0 bottom-0 z-50 w-72 -translate-x-full overflow-y-auto bg-gradient-to-b from-gray-900 via-gray-800 to-gray-700 text-white transition-transform duration-300 md:top-16 md:z-30 md:translate-x-0">

                <div class="md:hidden flex items-center gap-3 border-b border-gray-500 px-5 py-3">
                    <a href="{{ url('/') }}" class="flex">
                        <img src="{{ asset('img/logo_TA.png') }}" alt="Logo Desa Wonorejo"
                            class="h-10 w-auto rounded-full object-cover">
                        <div class="leading-tight leading-relaxed text-white">
                            <p class="text-[10px] font-extrabold uppercase tracking-[2px]">Desa Wonorejo</p>
                            <p class="text-[7px] font-semibold uppercase tracking-[2px]">Kecamatan Sumbergempol</p>
                            <p class="text-[7px] font-semibold uppercase tracking-[2px]">Kabupaten Tulungagung</p>
                        </div>
                    </a>
                </div>

                <div class="mt-5 flex flex-col items-center justify-center gap-3 text-center">
                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-orange-600 text-white">
                        <i class="fa-solid fa-user-shield text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white tracking-[1px] uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-medium text-white tracking-[1px]">Role:
                            {{ auth()->user()->role ?? 'admin' }}</p>
                    </div>
                </div>

                <nav class="space-y-1 px-4 py-5">
                    <a href="{{ route($routePrefix . '.dashboard') }}"
                        class="flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.dashboard', 'petugas.dashboard') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-gauge-high"></i>
                        {{ $isPetugas ? 'Dashboard Petugas' : 'Dashboard Admin' }}
                    </a>

                    <a href="{{ route($routePrefix . '.users.index') }}"
                        class="flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.users.*', 'petugas.users.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-users"></i>
                        Kelola Pengguna
                    </a>

                    <a href="{{ route($routePrefix . '.surat-pengajuan.index') }}"
                        class="flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.surat-pengajuan.*', 'petugas.surat-pengajuan.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-file-signature"></i>
                        Pengajuan Surat
                    </a>

                    @unless($isPetugas)
                    <a href="{{ route('admin.hero-slides.index') }}"
                        class="flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.hero-slides.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-images"></i>
                        Banner Utama
                    </a>

                    <a href="{{ route('admin.perangkat.index') }}"
                        class="flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.perangkat.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-users-gear"></i>
                        Perangkat Desa
                    </a>
                    @endunless

                    <a href="{{ route($routePrefix . '.berita.index') }}"
                        class="flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.berita.*', 'petugas.berita.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-newspaper"></i>
                        Berita
                    </a>

                    <a href="{{ route($routePrefix . '.artikel.index') }}"
                        class="flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.artikel.*', 'petugas.artikel.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-book-open"></i>
                        Artikel
                    </a>

                    @unless($isPetugas)
                    <a href="{{ route('admin.faq.index') }}"
                        class="flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.faq.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-circle-question"></i>
                        FAQ Chatbot
                    </a>
                    @endunless
                </nav>
            </aside>

            <div class="flex min-w-0 flex-1 flex-col md:ml-72">
                <main class="flex-1 overflow-x-hidden bg-gray-100 p-4 md:p-6">
                    @yield('dashboard_content')
                    @yield('content')
                </main>
                @include('partials.footer-dashboard')
            </div>
        </div>
    </div>

    <script>
    (() => {
        const openBtn = document.getElementById('sidebarOpenBtn');
        const drawer = document.getElementById('sidebarDrawer');
        const backdrop = document.getElementById('sidebarBackdrop');

        if (!openBtn || !drawer || !backdrop) return;

        const open = () => {
            drawer.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const close = () => {
            drawer.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        openBtn.addEventListener('click', open);
        backdrop.addEventListener('click', close);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                drawer.classList.remove('-translate-x-full');
            } else {
                drawer.classList.add('-translate-x-full');
            }
        });
    })();
    </script>

    @stack('scripts')
</body>

</html>