<link rel="stylesheet" href="{{ asset('css/nav-style.css') }}">

<nav id="siteNav" class="nav-base fixed inset-x-0 top-0 z-50 font-sans antialiased">
    <div class="topbar">
        <div class="topbar-inner mx-auto flex w-full max-w-6x items-center justify-between px-2 md:px-8">
            <div class="flex items-center gap-3 text-xs text-white">
                <svg class="h-5 w-5 text-white mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>{{ now()->translatedFormat('l | d M, Y') }}</span>
            </div>
            <div class="flex items-center gap-5 text-white">
                <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook"
                    class="hover:text-white">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube"
                    class="hover:text-white">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="nav-main mx-auto w-full max-w-6x px-2 md:px-8">
        <div class="flex items-center justify-between py-2">
            <a href="/" class="flex items-center ">
                <img src="{{ asset('img/logo_TA.png') }}" alt="Logo Desa Wonorejo"
                    class="h-12 w-auto rounded-full object-cover">
                <div class="text-white leading-tight leading-relaxed">
                    <p class="text-[10px] font-extrabold uppercase tracking-[2px]">
                        Desa Wonorejo
                    </p>
                    <p class="text-[7px] font-semibold uppercase tracking-[2px]">
                        Kecamatan Sumbergempol
                    </p>
                    <p class="text-[7px] font-semibold uppercase tracking-[2px]">
                        Kabupaten Tulungagung
                    </p>
                </div>
            </a>


            @php
            $isProfil = request()->routeIs('profil');
            $isBerita = request()->routeIs('berita*') || request()->routeIs('artikel*');
            $isLayanan = request()->routeIs('layanan*') || request()->routeIs('layanan');
            $isKontak = request()->routeIs('kontak');
            $role = auth()->user()->role ?? null;
            $dashboardRoute = $role === 'petugas'
            ? 'petugas.dashboard'
            : ($role === 'admin' ? 'admin.dashboard' : 'dashboard');
            @endphp

            <!-- Desktop -->
            <div class="hidden items-center gap-5 md:flex">
                <!-- Dropdown Fitur -->
                <div id="profileDropdown" class="relative dropdown">
                    <button id="profileBtn" type="button"
                        class="nav-link text-xs font-semibold flex leading-none items-center tracking-[2px] gap-2 {{ $isProfil ? 'is-active' : 'text-white/90' }}"
                        aria-haspopup="true" aria-expanded="false">
                        PROFILE
                        <svg class="h-3 w-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                        </svg>
                    </button>

                    <div class="dropdown-panel absolute left-0 mt-2 w-80 overflow-hidden p-5 text-white shadow-xl backdrop-blur"
                        style="background: #111827;">
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('profil') }}#sejarah">SEJARAH DESA</a>
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('profil') }}#visimisi">VISI MISI</a>
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('profil') }}#struktur">STRUKTUR ORGANISASI</a>
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('profil') }}#statistik">STATISTIK</a>
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('profil') }}#potensi">POTENSI</a>

                    </div>
                </div>

                <div id="beritaDropdown" class="relative dropdown ">
                    <button id="beritaBtn" type="button"
                        class="nav-link text-xs font-semibold flex leading-none items-center tracking-[2px] gap-2 {{ $isBerita ? 'is-active' : 'text-white/90' }}"
                        aria-haspopup="true" aria-expanded="false">
                        BERITA
                        <svg class="h-3 w-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                        </svg>
                    </button>

                    <div class="dropdown-panel absolute left-0 mt-2 w-56 overflow-hidden p-4 text-white shadow-xl backdrop-blur"
                        style="background: #111827;">
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('berita') }}">BERITA</a>
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('artikel') }}">ARTIKEL</a>
                    </div>
                </div>
                <a class="nav-link text-xs font-semibold tracking-[2px] {{ $isLayanan ? 'is-active' : 'text-white/90' }}"
                    href="{{ route('layanan') }}">LAYANAN DIGITAL</a>
                <a class="nav-link text-xs font-semibold tracking-[2px] {{ $isKontak ? 'is-active' : 'text-white/90' }}"
                    href="{{ route('kontak') }}">KONTAK</a>
                <span class="mx-2 h-7 w-px bg-white/40"></span>
                <!-- Search button -->
                <button id="searchOpen"
                    class="flex h-12 w-auto items-center justify-center text-white/70 hover:text-white"
                    aria-label="Search">
                    <!-- icon search -->
                    <svg class="h-5 w-auto" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"></path>
                    </svg>
                </button>

                @if(auth()->check() && in_array($role, ['admin', 'petugas'], true))
                <a class="ml-1 rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route($dashboardRoute) }}">DASHBOARD</a>
                @elseif(auth()->check() && auth()->user()->approval_status === 'approved')
                <a class="ml-1 rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route($dashboardRoute) }}">DASHBOARD</a>
                @elseif(auth()->check())
                <a class="ml-1 rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route('account.pending') }}">STATUS AKUN</a>
                @else
                <a class="ml-1 rounded-md bg-orange-500 px-4 py-1.5 text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route('login') }}">LOGIN</a>
                @endif
            </div>

            <!-- Mobile button -->
            <button id="mobileMenuBtn" type="button"
                class="md:hidden rounded-md border border-white/15 px-3 py-2 text-white/90 hover:bg-white/10"
                aria-expanded="false" aria-controls="mobilePanel">
                ☰
            </button>
        </div>

        <!-- Mobile Panel -->
        <div id="mobilePanel" class="mobile-panel pb-4 md:hidden">
            <div class="rounded-lg border border-white/10 p-3 text-white backdrop-blur" style="background: #111827;">
                <!-- Mobile Dropdown Fitur -->
                <div class="mb-5">
                    <button id="mobileFiturBtn" type="button"
                        class="flex items-center gap-2 leading-none text-xs font-semibold tracking-[2px] {{ $isProfil ? 'text-white' : 'text-white/50 hover:text-white/90' }}"
                        aria-expanded="false">
                        PROFILE
                        <svg class="h-3 w-auto transition-transform duration-200" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                        </svg>
                    </button>


                    <div id="mobileFiturPanel" class="hidden mt-2 space-y-1 rounded-md bg-white/5 p-2">
                        <a class="block rounded-lg px-3 py-2 text-xs text-white/50 hover:text-white/90 tracking-[2px] font-semibold"
                            href="{{ route('profil') }}#sejarah">SEJARAH DESA</a>
                        <a class="block rounded-lg px-3 py-2 text-xs text-white/50 hover:text-white/90 tracking-[2px]  font-semibold"
                            href="{{ route('profil') }}#visimisi">VISI MISI</a>
                        <a class="block rounded-lg px-3 py-2 text-xs text-white/50 hover:text-white/90 tracking-[2px]  font-semibold"
                            href="{{ route('profil') }}#struktur">STRUKTUR
                            ORGANISASI</a>
                        <a class="block rounded-lg px-3 py-2 text-xs text-white/50 hover:text-white/90 tracking-[2px]  font-semibold"
                            href="{{ route('profil') }}#statistik">STATISTIK</a>
                        <a class="block rounded-lg px-3 py-2 text-xs text-white/50 hover:text-white/90 tracking-[2px]  font-semibold"
                            href="{{ route('profil') }}#potensi">POTENSI</a>

                    </div>
                </div>

                <div class="mb-5">
                    <button id="mobileBeritaBtn" type="button"
                        class="flex items-center gap-2 leading-none text-xs font-semibold tracking-[2px] {{ $isBerita ? 'text-white' : 'text-white/50 hover:text-white/90' }}"
                        aria-expanded="false">
                        BERITA
                        <svg class="h-3 w-auto transition-transform duration-200" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                        </svg>
                    </button>


                    <div id="mobileBeritaPanel" class="hidden mt-2 space-y-1 rounded-md bg-white/5 p-2">
                        <a class=" block rounded-lg px-3 py-2 text-xs text-white/50 hover:text-white/90 tracking-[2px]  font-semibold"
                            href="{{ route('berita') }}">
                            BERITA
                        </a>
                        <a class=" block rounded-lg px-3 py-2 text-xs text-white/50 hover:text-white/90 tracking-[2px]  font-semibold"
                            href="{{ route('artikel') }}">
                            ARTIKEL
                        </a>
                    </div>
                </div>
                <a class="flex items-center mb-5 leading-none text-xs font-semibold tracking-[2px] {{ $isLayanan ? 'text-white' : 'text-white/50 hover:text-white/90' }}"
                    href="{{ route('layanan') }}">
                    LAYANAN DIGITAL
                </a>
                <a class="flex items-center mb-5 leading-none text-xs font-semibold tracking-[2px] {{ $isKontak ? 'text-white' : 'text-white/50 hover:text-white/90' }}"
                    href="{{ route('kontak') }}">
                    KONTAK
                </a>

                <button id="mobileSearchOpen"
                    class="text-white/50 hover:text-white/90 mobile-nav-item flex items-center mb-5 gap-2 text-xs font-semibold tracking-[2px]"
                    aria-label="Search">

                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"></path>
                    </svg>

                    <span>CARI</span>
                </button>

                @if(auth()->check() && in_array($role, ['admin', 'petugas'], true))
                <a class="mt-2 block rounded-md bg-orange-500  px-4 py-1.5 text-center text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route($dashboardRoute) }}">DASHBOARD</a>
                @elseif(auth()->check() && auth()->user()->approval_status === 'approved')
                <a class="mt-2 block rounded-md bg-orange-500  px-4 py-1.5 text-center text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route($dashboardRoute) }}">DASHBOARD</a>
                @elseif(auth()->check())
                <a class="mt-2 block rounded-md bg-orange-500  px-4 py-1.5 text-center text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route('account.pending') }}">STATUS AKUN</a>
                @else
                <a class="mt-2 block rounded-md bg-orange-500  px-4 py-1.5 text-center text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route('login') }}">LOGIN</a>
                @endif
            </div>
        </div>
    </div>
</nav>
<!-- Search Popup -->
@php
$quickLinks = [
['label' => 'Beranda', 'href' => url('/'), 'keywords' => 'home landing halaman utama'],
['label' => 'Profil Desa', 'href' => route('profil'), 'keywords' => 'profil desa tentang wonorejo'],
['label' => 'Sejarah Desa', 'href' => route('profil') . '#sejarah', 'keywords' => 'sejarah desa profil riwayat'],
['label' => 'Visi Misi', 'href' => route('profil') . '#visimisi', 'keywords' => 'visi misi tujuan profil'],
['label' => 'Struktur Organisasi', 'href' => route('profil') . '#struktur', 'keywords' => 'struktur organisasi sotk
perangkat'],
['label' => 'Statistik Desa', 'href' => route('profil') . '#statistik', 'keywords' => 'statistik demografi penduduk
wilayah'],
['label' => 'Layanan Digital', 'href' => route('layanan'), 'keywords' => 'layanan surat pengajuan digital'],
['label' => 'Kontak', 'href' => route('kontak'), 'keywords' => 'kontak alamat telepon hubungi'],
['label' => 'Berita', 'href' => route('berita'), 'keywords' => 'berita informasi kabar'],
['label' => 'Artikel', 'href' => route('artikel'), 'keywords' => 'artikel tulisan informasi'],
];
@endphp
<div id="searchPopup" class="fixed top-20 right-6 z-[100] hidden w-[420px] max-w-[90vw]
           rounded-md bg-white shadow-2xl">

    <div class="flex items-center gap-3 px-4 py-2">
        <!-- icon -->
        <svg class="h-10 w-10 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="8"></circle>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"></path>
        </svg>

        <input id="searchInput" type="text" placeholder="Cari halaman, menu, atau bagian situs..."
            class="w-full rounded-md border border-gray-500 bg-transparent text-sm text-gray-800 placeholder-gray-400 tracking-[.5px] focus:border-orange-500 focus:outline-none focus:ring-0"
            autofocus />


        <!-- close -->
        <button id="searchClose" class="text-gray-400 hover:text-gray-700" aria-label="Close search">
            ✕
        </button>
    </div>

    <div class="px-4 py-3">
        <p id="searchHint" class="mb-2 text-xs font-semibold uppercase tracking-[1px] text-slate-500">Navigasi cepat</p>
        <div id="searchResults" class="max-h-72 space-y-1 overflow-y-auto">
            @foreach($quickLinks as $quickLink)
            <a href="{{ $quickLink['href'] }}" data-search-item data-keywords="{{ $quickLink['keywords'] }}"
                class="block rounded-md px-3 py-2 text-sm text-slate-700 transition hover:bg-orange-50 hover:text-orange-600">
                {{ $quickLink['label'] }}
            </a>
            @endforeach
        </div>
        <p id="searchEmpty" class="hidden rounded-md bg-slate-50 px-3 py-3 text-sm text-slate-500">
            Tidak ada hasil yang cocok.
        </p>
    </div>
</div>




<script>
/* ===== Navbar scroll effect ===== */
(function() {
    const nav = document.getElementById('siteNav');

    function onScrollNav() {
        if (!nav) return;
        if (window.scrollY > 12) {
            nav.classList.add('is-scrolled');
        } else {
            nav.classList.remove('is-scrolled');
        }
    }

    window.addEventListener('scroll', onScrollNav, {
        passive: true
    });
    onScrollNav();
})();
</script>

<script>
/* ===== Desktop dropdown: auto close other dropdowns ===== */
(function() {
    const dropdowns = [{
            wrapper: document.getElementById('profileDropdown'),
            button: document.getElementById('profileBtn')
        },
        {
            wrapper: document.getElementById('beritaDropdown'),
            button: document.getElementById('beritaBtn')
        },
        {
            wrapper: document.getElementById('userAccountDropdown'),
            button: document.getElementById('userAccountBtn')
        }
    ];

    function closeAll(except = null) {
        dropdowns.forEach(d => {
            if (d.wrapper && d.wrapper !== except) {
                d.wrapper.classList.remove('open');
                d.button?.setAttribute('aria-expanded', 'false');
            }
        });
    }

    dropdowns.forEach(d => {
        if (!d.wrapper || !d.button) return;

        d.button.addEventListener('click', (e) => {
            e.stopPropagation();

            const isOpen = d.wrapper.classList.contains('open');
            closeAll();

            if (!isOpen) {
                d.wrapper.classList.add('open');
                d.button.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // klik di luar dropdown
    document.addEventListener('click', () => closeAll());

    // ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });
})();
</script>
<script>
(() => {
    const searchOpen = document.getElementById('searchOpen');
    const searchPopup = document.getElementById('searchPopup');
    const searchClose = document.getElementById('searchClose');
    const searchInput = document.getElementById('searchInput');
    const searchItems = Array.from(document.querySelectorAll('[data-search-item]'));
    const searchEmpty = document.getElementById('searchEmpty');
    const searchHint = document.getElementById('searchHint');

    if (!searchOpen || !searchPopup) return;

    function filterSearchResults() {
        const keyword = (searchInput?.value || '').trim().toLowerCase();
        let visibleCount = 0;

        searchItems.forEach((item) => {
            const haystack = `${item.textContent} ${item.dataset.keywords || ''}`.toLowerCase();
            const matched = keyword === '' || haystack.includes(keyword);
            item.classList.toggle('hidden', !matched);

            if (matched) {
                visibleCount += 1;
            }
        });

        searchEmpty?.classList.toggle('hidden', visibleCount !== 0);

        if (searchHint) {
            searchHint.textContent = keyword === '' ? 'Navigasi cepat' : `Hasil untuk "${keyword}"`;
        }
    }

    function openSearch() {
        searchPopup.classList.remove('hidden');
        filterSearchResults();
        setTimeout(() => searchInput?.focus(), 0);
    }

    function closeSearch() {
        searchPopup.classList.add('hidden');
        if (searchInput) {
            searchInput.value = '';
        }
        filterSearchResults();
    }

    // buka popup
    searchOpen.addEventListener('click', (e) => {
        e.stopPropagation(); // 🔑 PENTING
        openSearch();
    });

    // close button
    searchClose?.addEventListener('click', (e) => {
        e.stopPropagation();
        closeSearch();
    });

    // klik luar popup
    document.addEventListener('click', (e) => {
        const inside =
            searchPopup.contains(e.target) ||
            searchOpen.contains(e.target);

        if (!inside) closeSearch();
    });

    // ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSearch();
    });

    searchInput?.addEventListener('input', filterSearchResults);
    searchInput?.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter') return;

        const firstVisible = searchItems.find((item) => !item.classList.contains('hidden'));
        if (!firstVisible) return;

        window.location.href = firstVisible.href;
    });

    searchItems.forEach((item) => {
        item.addEventListener('click', () => {
            closeSearch();
        });
    });

    filterSearchResults();
})();
</script>

<script>
(function() {
    /* ===== Mobile menu ===== */
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobilePanel = document.getElementById('mobilePanel');

    function openMobile() {
        mobilePanel?.classList.add('open');
        mobileBtn?.setAttribute('aria-expanded', 'true');
    }

    function closeMobile() {
        mobilePanel?.classList.remove('open');
        mobileBtn?.setAttribute('aria-expanded', 'false');

        // tutup semua dropdown mobile
        closeAllMobile();
    }

    function toggleMobile() {
        if (!mobilePanel) return;
        mobilePanel.classList.contains('open') ? closeMobile() : openMobile();
    }

    mobileBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMobile();
    });

    /* ===== Mobile dropdowns ===== */
    const mobileDropdowns = [{
            btn: document.getElementById('mobileFiturBtn'),
            panel: document.getElementById('mobileFiturPanel')
        },
        {
            btn: document.getElementById('mobileBeritaBtn'),
            panel: document.getElementById('mobileBeritaPanel')
        }
    ];

    function closeAllMobile(except = null) {
        mobileDropdowns.forEach(d => {
            if (d.panel && d.panel !== except) {
                d.panel.classList.add('hidden');
                d.btn?.setAttribute('aria-expanded', 'false');
            }
        });
    }

    mobileDropdowns.forEach(d => {
        if (!d.btn || !d.panel) return;

        d.btn.addEventListener('click', (e) => {
            e.stopPropagation();

            const willOpen = d.panel.classList.contains('hidden');

            closeAllMobile();

            if (willOpen) {
                d.panel.classList.remove('hidden');
                d.btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* ===== Mobile search ===== */
    const mobileSearchOpen = document.getElementById('mobileSearchOpen');
    const searchPopup = document.getElementById('searchPopup');

    mobileSearchOpen?.addEventListener('click', (e) => {
        e.stopPropagation();
        searchPopup?.classList.remove('hidden');
        closeMobile(); // optional: tutup menu mobile
    });

    /* ===== Klik luar => tutup menu mobile ===== */
    document.addEventListener('click', (e) => {
        if (!mobilePanel || !mobileBtn) return;
        const inside = mobilePanel.contains(e.target) || mobileBtn.contains(e.target);
        if (!inside) closeMobile();
    });

})();
</script>