<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="admin-theme-light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' | Admin')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_TAhead.png') }}"
        class="h-10 w-auto rounded-full object-cover">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
    (() => {
        const savedTheme = localStorage.getItem('admin-theme');
        const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = savedTheme || (prefersDark ? 'dark' : 'light');
        document.documentElement.classList.toggle('admin-theme-dark', theme === 'dark');
        document.documentElement.classList.toggle('admin-theme-light', theme !== 'dark');
    })();
    </script>
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans antialiased overflow-x-hidden transition-colors duration-200"
    style="font-family: 'Space Grotesk', sans-serif;">
    @php
    $isPetugas = (auth()->user()->role ?? null) === 'petugas';
    $routePrefix = $isPetugas ? 'petugas' : 'admin';
    @endphp
    <div class="min-h-screen">
        <header
            class="fixed inset-x-0 top-0 z-40 h-16 border-b border-gray-700 bg-gradient-to-r from-gray-950 via-gray-900 to-gray-800 px-6">
            <div class="flex h-full items-center justify-between">
                <div class="flex items-center gap-4 py-3">
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

                <div class="flex items-center gap-3">
                    <details class="group relative" data-notification-menu data-notification-scope="admin-{{ $routePrefix }}">
                        <summary class="relative flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-md border border-white/20 bg-white/10 text-white transition hover:bg-white/20">
                            <i class="fa-solid fa-bell text-sm"></i>
                            @if(count($notifications ?? []))
                            <span data-notification-total-badge class="notification-badge-pending absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 p-0 text-[9px] font-bold text-white">{{ count($notifications) }}</span>
                            @endif
                        </summary>
                        <div class="absolute right-0 z-50 mt-3 w-80 rounded-lg border border-gray-200 bg-white p-2 text-gray-800 shadow-xl">
                            <div class="flex items-center justify-between px-3 py-2">
                                <p class="text-sm font-bold">Notifikasi</p>
                                <div class="flex items-center gap-2">
                                    <span data-notification-count-label class="text-[11px] text-slate-500">{{ count($notifications ?? []) }} terbaru</span>
                                    @if(count($notifications ?? []))
                                    <button type="button" data-notification-clear-all
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-md text-slate-500 transition hover:bg-red-50 hover:text-red-600"
                                        aria-label="Hapus semua notifikasi" title="Hapus semua notifikasi">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @foreach($notifications ?? [] as $notification)
                            <a href="{{ $notification['url'] }}" data-notification-item data-notification-group="{{ $notification['key'] ?? '' }}" data-notification-count="{{ $notification['count'] }}" data-notification-version="{{ $notification['version'] ?? $notification['count'] }}" class="flex items-start gap-3 rounded-md px-3 py-2 transition hover:bg-slate-100">
                                <i class="fa-solid {{ $notification['icon'] }} mt-1 text-sm text-orange-500"></i>
                                <span class="min-w-0 flex-1 text-xs leading-5 text-slate-700">{{ $notification['label'] }}<strong class="ml-1 text-slate-900">({{ number_format($notification['count']) }})</strong></span>
                            </a>
                            @endforeach
                            <p data-notification-empty class="hidden px-3 py-4 text-center text-xs text-slate-500">Belum ada notifikasi terbaru.</p>
                        </div>
                    </details>
                    <button id="adminThemeToggle" type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-white/20 bg-white/10 text-white transition hover:bg-white/20"
                        aria-label="Ganti mode tampilan">
                        <i id="adminThemeIcon" class="fa-solid fa-moon text-sm"></i>
                    </button>

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
            </div>
        </header>

        <div class="flex min-h-screen pt-16">
            <div id="sidebarBackdrop" class="fixed inset-0 z-30 hidden bg-black/50 md:hidden"></div>

            <aside id="sidebarDrawer"
                class="dashboard-sidebar fixed left-0 top-0 bottom-0 z-50 w-64 -translate-x-full overflow-y-scroll bg-gradient-to-b from-gray-950 via-gray-800 to-gray-800 text-white transition-all duration-300 md:top-16 md:z-30 md:translate-x-0"
                style="scrollbar-gutter: stable;">

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

                <div class="sidebar-profile mt-5 flex flex-col items-center justify-center gap-3 text-center">
                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-orange-600 text-white">
                        <i class="fa-solid fa-user-shield text-3xl"></i>
                    </div>
                    <div class="text-white">
                        <p class="text-sm font-bold tracking-[1px] uppercase">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-medium tracking-[1px]">Role: {{ auth()->user()->role ?? 'admin' }}</p>
                    </div>
                </div>

                <nav class="space-y-2 px-4 py-5">
                    <a href="{{ route($routePrefix . '.dashboard') }}"
                        title="{{ $isPetugas ? 'Dashboard Petugas' : 'Dashboard Admin' }}"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.dashboard', 'petugas.dashboard') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-gauge-high sidebar-menu-icon"></i>
                        <span class="sidebar-label">{{ $isPetugas ? 'Dashboard Petugas' : 'Dashboard Admin' }}</span>
                    </a>

                    @unless($isPetugas)
                    <a href="{{ route($routePrefix . '.users.index') }}"
                        title="Kelola Pengguna"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.users.*', 'petugas.users.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-users sidebar-menu-icon"></i>
                        <span class="sidebar-label">Kelola Pengguna</span>
                    </a>
                    @endunless

                    <a href="{{ route($routePrefix . '.surat-pengajuan.index') }}"
                        title="Pengajuan Surat"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.surat-pengajuan.*', 'petugas.surat-pengajuan.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-file-signature sidebar-menu-icon"></i>
                        <span class="sidebar-label">Pengajuan Surat</span>
                    </a>

                    <a href="{{ route($routePrefix . '.aspirasi.index') }}"
                        title="Aspirasi / Keluhan"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.aspirasi.*', 'petugas.aspirasi.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-comments sidebar-menu-icon"></i>
                        <span class="sidebar-label">Aspirasi / Keluhan</span>
                    </a>

                    <a href="{{ route($routePrefix . '.comments.index') }}"
                        title="Kelola Komentar"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.comments.*', 'petugas.comments.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-comment-dots sidebar-menu-icon"></i>
                        <span class="sidebar-label">Kelola Komentar</span>
                    </a>

                    @unless($isPetugas)
                    <a href="{{ route('admin.hero-slides.index') }}"
                        title="Banner Utama"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.hero-slides.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-images sidebar-menu-icon"></i>
                        <span class="sidebar-label flex min-w-0 flex-1 items-center justify-between gap-2">Banner Utama</span>
                    </a>

                    <a href="{{ route('admin.perangkat.index') }}"
                        title="Perangkat Desa"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.perangkat.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-users-gear sidebar-menu-icon"></i>
                        <span class="sidebar-label flex min-w-0 flex-1 items-center justify-between gap-2">Perangkat Desa</span>
                    </a>
                    <a href="{{ route('admin.anggaran.index') }}"
                        title="Anggaran Dana Desa"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.anggaran.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-money-bill-trend-up sidebar-menu-icon"></i>
                        <span class="sidebar-label">Anggaran Dana Desa</span>
                    </a>
                    @endunless

                    <a href="{{ route($routePrefix . '.berita.index') }}"
                        title="Berita"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.berita.*', 'petugas.berita.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-newspaper sidebar-menu-icon"></i>
                        <span class="sidebar-label">Berita</span>
                    </a>

                    <a href="{{ route($routePrefix . '.artikel.index') }}"
                        title="Artikel"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('admin.artikel.*', 'petugas.artikel.*') ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-book-open sidebar-menu-icon"></i>
                        <span class="sidebar-label">Artikel</span>
                    </a>

                </nav>
            </aside>

            <button id="sidebarCollapseBtn" type="button"
                class="sidebar-edge-toggle hidden h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-md transition hover:border-orange-500 hover:bg-orange-500 hover:text-white md:flex"
                aria-label="Collapse sidebar" title="Collapse sidebar">
                <i id="sidebarCollapseIcon" class="fa-solid fa-angles-left text-sm"></i>
            </button>

            <div id="dashboardContentWrap" class="dashboard-content-wrap flex min-w-0 flex-1 flex-col md:ml-64">
                <main class="flex-1 overflow-x-hidden bg-gray-100 p-2 md:py-4 md:pr-4 md:pl-4">
                    @include('partials.admin-flash-toast')
                    @include('partials.admin-delete-confirm')
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

    <script>
    (() => {
        const toggle = document.getElementById('adminThemeToggle');
        const icon = document.getElementById('adminThemeIcon');

        if (!toggle || !icon) return;

        const applyTheme = (theme) => {
            const isDark = theme === 'dark';
            document.documentElement.classList.toggle('admin-theme-dark', isDark);
            document.documentElement.classList.toggle('admin-theme-light', !isDark);
            icon.classList.toggle('fa-moon', !isDark);
            icon.classList.toggle('fa-sun', isDark);
            toggle.setAttribute('aria-label', isDark ? 'Ganti ke mode light' : 'Ganti ke mode dark');
            localStorage.setItem('admin-theme', theme);
        };

        applyTheme(document.documentElement.classList.contains('admin-theme-dark') ? 'dark' : 'light');

        toggle.addEventListener('click', () => {
            applyTheme(document.documentElement.classList.contains('admin-theme-dark') ? 'light' : 'dark');
        });
    })();
    </script>

    @stack('scripts')
</body>

</html>
