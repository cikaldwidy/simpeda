<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name') . ' | Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo_TAhead.png') }}"
        class="h-10 w-auto rounded-full object-cover">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans antialiased overflow-x-hidden">
    @php
    $isAjukanOpen = request()->routeIs('layanan.pengajuan.*');
    $jenisAktif = request('jenis');
    $activeNotificationGroup = match (true) {
        request()->routeIs('dashboard.status') => 'status',
        request()->routeIs('dashboard.dokumen', 'dashboard.riwayat') => 'dokumen',
        default => '',
    };
    @endphp

    <div class="min-h-screen" data-active-notification-group="{{ $activeNotificationGroup }}">
        <header
            class="fixed inset-x-0 top-0 z-40 h-16 border-b border-gray-700 bg-gradient-to-r from-gray-950 via-gray-900 to-gray-800 px-6">
            <div class="flex h-full items-center justify-between">
                <div class="flex items-center gap-4 py-3">
                    <button id="sidebarOpenBtn" type="button"
                        class="inline-flex h-15 w-15 items-center justify-center text-white hover:text-orange-500 md:hidden">
                        <i class=" fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="{{ url('/') }}" class="hidden items-center gap-2 sm:flex">
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
                <details class="group relative" data-notification-menu data-notification-scope="warga">
                    <summary class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:bg-white/20">
                        <i class="fa-solid fa-bell text-sm"></i>
                        @if(count($notifications ?? []))
                        <span data-notification-total-badge class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 p-0 text-[9px] font-bold text-white">{{ count($notifications) }}</span>
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
                        <a href="{{ $notification['url'] }}" data-notification-item data-notification-group="{{ $notification['key'] ?? '' }}" data-notification-count="{{ $notification['count'] }}" data-notification-version="{{ $notification['version'] ?? $notification['count'] }}" class="flex items-start gap-3 rounded-md px-3 py-2 transition hover:bg-gray-100">
                            <i class="fa-solid {{ $notification['icon'] }} mt-1 text-sm text-orange-500"></i>
                            <span class="min-w-0 flex-1 text-xs leading-5 text-slate-700">{{ $notification['label'] }}</span>
                        </a>
                        @endforeach
                        <p data-notification-empty class="{{ count($notifications ?? []) ? 'hidden' : '' }} px-3 py-4 text-center text-xs text-slate-500">Belum ada notifikasi terbaru.</p>
                    </div>
                </details>
                <details class="group relative">
                    <summary
                        class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-full bg-orange-600 text-white">
                        <i class="fa-solid fa-user text-md"></i>
                    </summary>
                    <div class="absolute right-0 mt-3 w-48 border border-gray-200 bg-white p-2 shadow-md">
                        <a href="{{ route('dashboard.profile') }}"
                            class="block rounded-lg px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-200 tracking-[1px]">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="mt-1 block w-full rounded-lg px-3 py-2 text-left text-xs font-semibold text-red-700 hover:bg-red-200 tracking-[1px]">
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
                class="dashboard-sidebar fixed left-0 top-0 bottom-0 z-[60] w-64 -translate-x-full overflow-y-scroll bg-gradient-to-b from-gray-950 via-gray-800 to-gray-800 text-white transition-all duration-300 md:top-16 md:translate-x-0"
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
                        <i class="fa-solid fa-user text-3xl"></i>
                    </div>
                    <div class="text-white">
                        <p class="text-sm font-bold  tracking-[1px] uppercase">{{ auth()->user()->name }}
                        </p>
                        <p class="text-xs font-medium  tracking-[1px]">NIK. {{ auth()->user()->nik }}</p>
                    </div>
                </div>

                <nav class="space-y-2 px-4 py-5">
                    <details class="group rounded-sm" @if($isAjukanOpen) open @endif>
                        <summary
                            title="Pengajuan Surat"
                            class="sidebar-menu-item flex cursor-pointer list-none items-center justify-between rounded-sm px-4 py-2 text-xs font-semibold {{ $isAjukanOpen ? 'bg-orange-500 text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                            <span class="flex items-center gap-3">
                                <i class="fa-solid fa-file-circle-plus sidebar-menu-icon"></i>
                                <span class="sidebar-label">Pengajuan Surat</span>
                            </span><!--  -->
                            <i class="sidebar-label fa-solid fa-chevron-down text-xs transition group-open:rotate-180"></i>
                        </summary>
                        <div class="sidebar-submenu space-y-1 px-2 pb-2">
                            <a href="{{ route('layanan.pengajuan.create', ['jenis' => 'domisili']) }}"
                                data-sidebar-navigation
                                title="Surat Domisili"
                                class="mt-1 block rounded-sm px-3 py-2 text-xs font-semibold {{ $jenisAktif === 'domisili' ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                                Surat Domisili
                            </a>
                            <a href="{{ route('layanan.pengajuan.create', ['jenis' => 'tidak_mampu']) }}"
                                data-sidebar-navigation
                                title="Surat Keterangan Tidak Mampu"
                                class="block rounded-sm px-3 py-2 text-xs font-semibold {{ $jenisAktif === 'tidak_mampu' ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                                Surat Keterangan Tidak Mampu
                            </a>
                            <a href="{{ route('layanan.pengajuan.create', ['jenis' => 'kematian']) }}"
                                data-sidebar-navigation
                                title="Surat Kematian"
                                class="block rounded-sm px-3 py-2 text-xs font-semibold {{ $jenisAktif === 'kematian' ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                                Surat Kematian
                            </a>
                            <a href="{{ route('layanan.pengajuan.create', ['jenis' => 'kelahiran']) }}"
                                data-sidebar-navigation
                                title="Surat Kelahiran"
                                class="block rounded-sm px-3 py-2 text-xs font-semibold {{ $jenisAktif === 'kelahiran' ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                                Surat Kelahiran
                            </a>
                            <a href="{{ route('layanan.pengajuan.create', ['jenis' => 'usaha']) }}"
                                data-sidebar-navigation
                                title="Surat Keterangan Usaha"
                                class="block rounded-sm px-3 py-2 text-xs font-semibold {{ $jenisAktif === 'usaha' ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                                Surat Keterangan Usaha
                            </a>
                            <a href="{{ route('layanan.pengajuan.create', ['jenis' => 'belum_menikah']) }}"
                                data-sidebar-navigation
                                title="Surat Keterangan Belum Menikah"
                                class="block rounded-sm px-3 py-2 text-xs font-semibold {{ $jenisAktif === 'belum_menikah' ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                                Surat Keterangan Belum Menikah
                            </a>
                            <a href="{{ route('layanan.pengajuan.create', ['jenis' => 'kehilangan']) }}"
                                data-sidebar-navigation
                                title="Surat Keterangan Kehilangan"
                                class="block rounded-sm px-3 py-2 text-xs font-semibold {{ $jenisAktif === 'kehilangan' ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                                Surat Keterangan Kehilangan
                            </a>
                            <a href="{{ route('layanan.pengajuan.create', ['jenis' => 'penghasilan_ortu']) }}"
                                data-sidebar-navigation
                                title="Surat Keterangan Penghasilan Orang Tua"
                                class="block rounded-sm px-3 py-2 text-xs font-semibold {{ $jenisAktif === 'penghasilan_ortu' ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                                Surat Keterangan Penghasilan Orang Tua
                            </a>
                        </div>
                    </details>

                    <a href="{{ route('dashboard.status') }}"
                        title="Status Surat"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('dashboard.status') ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-signal sidebar-menu-icon"></i>
                        <span class="sidebar-label">Status Surat</span>
                    </a>

                    <a href="{{ route('dashboard.dokumen') }}"
                        title="Dokumen Saya"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('dashboard.dokumen', 'dashboard.riwayat') ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-clock-rotate-left sidebar-menu-icon"></i>
                        <span class="sidebar-label">Dokumen Saya</span>
                    </a>

                    <a href="{{ route('dashboard.chatbot') }}"
                        title="Chatbot"
                        class="sidebar-menu-item flex items-center gap-3 rounded-sm px-4 py-2 text-xs font-semibold {{ request()->routeIs('dashboard.chatbot') ? 'bg-orange-500 tracking-[.5px] text-white' : 'tracking-[.5px] text-white hover:bg-orange-500 hover:text-white transition' }}">
                        <i class="fa-solid fa-comments sidebar-menu-icon"></i>
                        <span class="sidebar-label">Chatbot</span>
                    </a>
                </nav>
            </aside>

            <button id="sidebarCollapseBtn" type="button"
                class="sidebar-edge-toggle hidden h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-md transition hover:border-orange-500 hover:bg-orange-500 hover:text-white md:flex"
                aria-label="Collapse sidebar" title="Collapse sidebar">
                <i id="sidebarCollapseIcon" class="fa-solid fa-angles-left text-sm"></i>
            </button>

            <div id="dashboardContentWrap" class="dashboard-content-wrap flex min-w-0 flex-1 flex-col md:ml-64">
                <main class="flex-1 overflow-x-hidden bg-gray-100 p-2 md:py-4 md:pr-4 md:pl-14">
                    @include('partials.admin-flash-toast')
                    @include('partials.admin-delete-confirm')
                    @yield('dashboard_content')
                </main>
                @include('partials.footer-dashboard')
            </div>
        </div>
    </div>

    <script>
    (() => {
        const openBtn = document.getElementById('sidebarOpenBtn');
        const closeBtn = document.getElementById('sidebarCloseBtn');
        const drawer = document.getElementById('sidebarDrawer');
        const backdrop = document.getElementById('sidebarBackdrop');

        if (!openBtn || !drawer || !backdrop) return;

        const isDesktop = () => window.innerWidth >= 768;

        const open = () => {
            drawer.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            openBtn.setAttribute('aria-expanded', 'true');
            document.body.classList.add('overflow-hidden');
        };

        const close = () => {
            drawer.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            openBtn.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('overflow-hidden');
        };

        const syncDrawer = () => {
            if (isDesktop()) {
                drawer.classList.remove('-translate-x-full');
                backdrop.classList.add('hidden');
                openBtn.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('overflow-hidden');
                return;
            }

            close();
        };

        openBtn.addEventListener('click', open);
        closeBtn?.addEventListener('click', close);
        backdrop.addEventListener('click', close);

        drawer.querySelectorAll('nav a[href]').forEach((link) => {
            link.addEventListener('click', (event) => {
                if (!isDesktop()) {
                    window.setTimeout(close, 120);
                }

                if (
                    link.matches('[data-sidebar-navigation]') &&
                    event.button === 0 &&
                    !event.metaKey &&
                    !event.ctrlKey &&
                    !event.shiftKey &&
                    !event.altKey
                ) {
                    const targetUrl = new URL(link.href, window.location.href);

                    if (targetUrl.href !== window.location.href) {
                        event.preventDefault();
                        window.location.assign(targetUrl.href);
                    }
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !isDesktop()) {
                close();
            }
        });

        window.addEventListener('resize', syncDrawer);
        syncDrawer();
    })();
    </script>

    @stack('scripts')
</body>

</html>
