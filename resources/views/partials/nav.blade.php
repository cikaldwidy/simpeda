<link rel="stylesheet" href="{{ asset('css/nav-style.css') }}">

<nav id="siteNav" class="nav-base fixed inset-x-0 top-0 z-50 font-sans antialiased">
    <div class="mx-auto w-full max-w-6x px-2 md:px-8">
        <div class="flex items-center justify-between py-4">
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
            $isKontak = request()->routeIs('contact');
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

                    <div
                        class="dropdown-panel absolute left-0 mt-2 w-80 overflow-hidden bg-black p-5 text-white shadow-xl backdrop-blur">
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
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('profil') }}#inovasi">INOVASI</a>
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

                    <div
                        class="dropdown-panel absolute left-0 mt-2 w-56 overflow-hidden bg-black p-4 text-white shadow-xl backdrop-blur">
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('berita') }}">BERITA</a>
                        <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/50 hover:text-white/90 tracking-[2px]"
                            href="{{ route('artikel') }}">ARTIKEL</a>
                    </div>
                </div>
                <a class="nav-link text-xs font-semibold tracking-[2px] {{ $isLayanan ? 'is-active' : 'text-white/90' }}"
                    href="{{ route('layanan') }}">LAYANAN DIGITAL</a>
                <a class="nav-link text-xs font-semibold tracking-[2px] {{ $isKontak ? 'is-active' : 'text-white/90' }}"
                    href="{{ route('contact') }}">KONTAK</a>
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

                @if(auth()->check() && auth()->user()->approval_status === 'approved')
                <div id="userAccountDropdown" class="relative dropdown">
                    <button id="userAccountBtn" type="button"
                        class="ml-1 inline-flex items-center justify-center text-white/90 transition"
                        aria-haspopup="true" aria-expanded="false" title="Menu Pengguna">
                        <i class="fa-solid fa-circle-user text-2xl"></i>
                    </button>

                    <div
                        class="dropdown-panel absolute right-0 mt-2 w-56 overflow-hidden rounded-xl bg-black p-3 text-white shadow-xl backdrop-blur">
                        <a class="block rounded-xl px-3 py-2 text-xs font-semibold text-white/50 hover:text-white/90 tracking-[1px]"
                            href="{{ route('dashboard') }}">
                            DASHBOARD
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="mt-1 block rounded-xl px-3 py-2 text-xs font-semibold text-red-300 hover:text-red-400 tracking-[1px]">
                                KELUAR
                            </button>
                        </form>
                    </div>
                </div>
                @elseif(auth()->check())
                <a class="ml-1 rounded-xl bg-orange-500 px-4 py-2 text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route('account.pending') }}">STATUS AKUN</a>
                @else
                <a class="ml-1 rounded-xl bg-orange-500 px-4 py-2 text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route('register') }}">DAFTAR DIRI</a>
                @endif
            </div>

            <!-- Mobile button -->
            <button id="mobileMenuBtn" type="button"
                class="md:hidden rounded-xl border border-white/15 px-3 py-2 text-white/90 hover:bg-white/10"
                aria-expanded="false" aria-controls="mobilePanel">
                ☰
            </button>
        </div>

        <!-- Mobile Panel -->
        <div id="mobilePanel" class="mobile-panel pb-4 md:hidden">
            <div class="rounded-2xl border border-white/10 bg-black/75 p-3 text-white backdrop-blur">
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


                    <div id="mobileFiturPanel" class="hidden mt-1 space-y-1 rounded-xl bg-white/5 p-2">
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
                        <a class="block rounded-lg px-3 py-2 text-xs text-white/50 hover:text-white/90 tracking-[2px]  font-semibold"
                            href="{{ route('profil') }}#inovasi">INOVASI</a>
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


                    <div id="mobileBeritaPanel" class="hidden mt-1 space-y-1 rounded-xl bg-white/5 p-2">
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
                    href="{{ route('contact') }}">
                    KONTAK
                </a>

                <button id="mobileSearchOpen"
                    class="text-white/50 hover:text-white/90 mobile-nav-item flex items-center mb-5 gap-2 text-xs font-semibold tracking-[2px] text-white/90"
                    aria-label="Search">

                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"></path>
                    </svg>

                    <span>CARI</span>
                </button>

                @if(auth()->check() && auth()->user()->approval_status === 'approved')
                <div class="mt-2 rounded-xl border border-white/10 bg-white/5 p-2">
                    <a class="mb-2 block rounded-lg px-3 py-2 text-xs font-semibold tracking-[1px] text-white/90 hover:text-white"
                        href="{{ route('dashboard') }}">
                        DASHBOARD
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full rounded-lg px-3 py-2 text-left text-xs font-semibold tracking-[1px] text-red-300 hover:text-red-400">
                            KELUAR
                        </button>
                    </form>
                </div>
                @elseif(auth()->check())
                <a class="mt-2 block rounded-xl bg-orange-500 p-2 text-center text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route('account.pending') }}">STATUS AKUN</a>
                @else
                <a class="mt-2 block rounded-xl bg-orange-500 p-2 text-center text-sm font-semibold text-white/90 hover:bg-orange-400 tracking-[1px]"
                    href="{{ route('register') }}">DAFTAR DIRI</a>
                @endif
            </div>
        </div>
    </div>
</nav>
<!-- Search Popup -->
<div id="searchPopup" class="fixed top-20 right-6 z-[100] hidden w-[420px] max-w-[90vw]
           rounded-md bg-white shadow-2xl">

    <div class="flex items-center gap-3 px-4 py-2">
        <!-- icon -->
        <svg class="h-10 w-10 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="8"></circle>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"></path>
        </svg>

        <input type="text" placeholder="Cari..." class="w-full border border-gray-500 bg-transparent text-sm text-gray-800
                placeholder-gray-400 tracking-[.5px]
                focus:outline-none focus:border-1 focus:border-orange-500 focus:ring-0 rounded-md" autofocus />


        <!-- close -->
        <button id="searchClose" class="text-gray-400 hover:text-gray-700" aria-label="Close search">
            ✕
        </button>
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

    if (!searchOpen || !searchPopup) return;

    function openSearch() {
        searchPopup.classList.remove('hidden');
    }

    function closeSearch() {
        searchPopup.classList.add('hidden');
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


