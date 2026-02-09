<style>
  .nav-base{
    background: transparent;
    transition: background .25s ease, box-shadow .25s ease, backdrop-filter .25s ease;
  }

  /* Saat scroll: jadi hitam */
  .nav-base.is-scrolled{
    background: rgba(0,0,0,.95);           
    box-shadow: 0 10px 30px rgba(0,0,0,.25);
  }

  /* Hover hitam saat belum scroll */
  .nav-base:not(.is-scrolled):hover{
    background: rgba(0,0,0,.65);
    box-shadow: 0 10px 30px rgba(0,0,0,.25);
  }

  .dropdown-panel{ display:none; }
  .dropdown.open .dropdown-panel{ display:block; }

  .mobile-panel{ display:none; }
  .mobile-panel.open{ display:block; }
</style>



<nav id="siteNav" class="nav-base fixed inset-x-0 top-0 z-50">
  <div class="mx-auto w-full max-w-6xl px-4">
    <div class="flex items-center justify-between py-4">
      <a href="/" class="flex items-center gap-3">
        <div class="h-10 w-10 overflow-hidden rounded-full bg-white/10 ring-1 ring-white/15"></div>
        <div class="leading-tight">
          <p class="text-sm font-semibold text-white">Simpeda</p>
          <p class="text-[11px] text-white/70">Layanan Desa Modern</p>
        </div>
      </a>

      <!-- Desktop -->
      <div class="hidden items-center gap-5 md:flex">
        <!-- Dropdown Fitur -->
        <div id="fiturDropdown" class="relative dropdown">
          <button id="fiturBtn" type="button"
            class="text-xs font-semibold text-white/90 inline-flex items-center tracking-[2px] "
            aria-haspopup="true" aria-expanded="false">
            PROFILE
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
            </svg>
          </button>

          <div class="dropdown-panel absolute left-0 mt-2 w-80 overflow-hidden bg-black p-5 text-white shadow-xl backdrop-blur">
            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/30 hover:text-white/90 tracking-[2px]" href="#sejarah">SEJARAH DESA</a>
            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/30 hover:text-white/90 tracking-[2px]" href="#visimisi">VISI MISI</a>
            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/30 hover:text-white/90 tracking-[2px]" href="#struktur">STRUKTUR ORGANISASI</a>
            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/30 hover:text-white/90 tracking-[2px]" href="#statistik">STATISTIK</a>
            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/30 hover:text-white/90 tracking-[2px]" href="#inovasi">POTENSI</a>
            <a class="block rounded-xl px-3 py-2 text-sm font-semibold text-white/30 hover:text-white/90 tracking-[2px]" href="#potensi">INOVASI</a>
          </div>
        </div>

        <a class="text-xs font-semibold text-white/90 tracking-[2px]" href="#layanan">LAYANAN</a>
        <a class="text-xs font-semibold text-white/90 tracking-[2px]" href="#proses">PROSES</a>
        <a class="text-xs font-semibold text-white/90 tracking-[2px]" href="{{ route('login') }}">MASUK</a>

        <a class="ml-1 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-emerald-950 hover:bg-emerald-400 tracking-[2px]"
           href="{{ route('register') }}">DAFTAR</a>
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
        <div class="mb-2">
          <button id="mobileFiturBtn" type="button"
            class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-sm font-semibold hover:bg-white/10"
            aria-expanded="false">
            Fitur
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
            </svg>
          </button>

          <div id="mobileFiturPanel" class="hidden mt-1 space-y-1 rounded-xl bg-white/5 p-2">
            <a class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10" href="#sejarah">Sejarah Desa</a>
            <a class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10" href="#visimisi">Visi Misi</a>
            <a class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10" href="#struktur">Struktur Organisasi</a>
            <a class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10" href="#statistik">Statistik</a>
            <a class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10" href="#inovasi">Inovasi</a>
            <a class="block rounded-lg px-3 py-2 text-sm hover:bg-white/10" href="#potensi">Potensi</a>
          </div>
        </div>

        <a class="block rounded-xl px-3 py-2 text-sm font-semibold hover:bg-white/10" href="#layanan">Layanan</a>
        <a class="block rounded-xl px-3 py-2 text-sm font-semibold hover:bg-white/10" href="#proses">Proses</a>
        <a class="block rounded-xl px-3 py-2 text-sm font-semibold hover:bg-white/10" href="{{ route('login') }}">Masuk</a>

        <a class="mt-2 block rounded-xl bg-emerald-500 px-3 py-2 text-center text-sm font-semibold text-emerald-950 hover:bg-emerald-400"
           href="{{ route('register') }}">Daftar</a>
      </div>
    </div>
  </div>
</nav>

<script>
(function () {
  // ===== Navbar: jadi hitam saat scroll =====
  const nav = document.getElementById('siteNav');
  function onScrollNav(){
    if (!nav) return;
    if (window.scrollY > 12) nav.classList.add('is-scrolled');
    else nav.classList.remove('is-scrolled');
  }
  window.addEventListener('scroll', onScrollNav, { passive: true });
  onScrollNav();

  // ===== Desktop dropdown Fitur =====
  const dd = document.getElementById('fiturDropdown');
  const ddBtn = document.getElementById('fiturBtn');

  function closeDropdown(){
    if (!dd) return;
    dd.classList.remove('open');
    ddBtn?.setAttribute('aria-expanded', 'false');
  }
  function toggleDropdown(){
    if (!dd) return;
    const isOpen = dd.classList.toggle('open');
    ddBtn?.setAttribute('aria-expanded', String(isOpen));
  }

  ddBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleDropdown();
  });

  // tutup jika klik di luar
  document.addEventListener('click', closeDropdown);
  // tutup dengan ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDropdown();
  });

  // ===== Mobile menu toggle =====
  const mobileBtn = document.getElementById('mobileMenuBtn');
  const mobilePanel = document.getElementById('mobilePanel');

  function openMobile(){
    mobilePanel?.classList.add('open');
    mobileBtn?.setAttribute('aria-expanded', 'true');
  }
  function closeMobile(){
    mobilePanel?.classList.remove('open');
    mobileBtn?.setAttribute('aria-expanded', 'false');
    // tutup sub menu fitur juga
    document.getElementById('mobileFiturPanel')?.classList.add('hidden');
    document.getElementById('mobileFiturBtn')?.setAttribute('aria-expanded', 'false');
  }
  function toggleMobile(){
    if (!mobilePanel) return;
    mobilePanel.classList.contains('open') ? closeMobile() : openMobile();
  }

  mobileBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    toggleMobile();
  });

  // klik link mobile => tutup panel
  mobilePanel?.addEventListener('click', (e) => {
    const a = e.target.closest('a');
    if (a) closeMobile();
  });

  // klik luar => tutup mobile
  document.addEventListener('click', (e) => {
    if (!mobilePanel || !mobileBtn) return;
    const inside = mobilePanel.contains(e.target) || mobileBtn.contains(e.target);
    if (!inside) closeMobile();
  });

  // ===== Mobile dropdown fitur =====
  const mFiturBtn = document.getElementById('mobileFiturBtn');
  const mFiturPanel = document.getElementById('mobileFiturPanel');

  mFiturBtn?.addEventListener('click', (e) => {
    e.stopPropagation();
    if (!mFiturPanel) return;
    const willOpen = mFiturPanel.classList.toggle('hidden') === false;
    mFiturBtn.setAttribute('aria-expanded', String(willOpen));
  });
})();
</script>
