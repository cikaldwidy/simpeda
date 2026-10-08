<footer class="bg-black text-white">
    <div class="relative mx-auto w-full max-w-6xl px-4 py-10">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-12">
            <div class="md:col-span-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/logo_TA.png') }}" alt="Logo Desa Wonorejo"
                        class="h-12 w-12 rounded-full object-cover">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[3px]">Desa Wonorejo</p>
                        <p class="text-xs text-white/70">Kecamatan Sumbergempol, Tulungagung</p>
                    </div>
                </div>
                <p class="mt-4 max-w-md text-sm text-white/70">
                    Sistem Pelayanan Desa untuk memudahkan administrasi warga secara cepat, rapi, dan transparan.
                </p>
            </div>

            <div class="md:col-span-5">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[2px] text-white/70">Menu</p>
                        <ul class="mt-3 space-y-2 text-sm text-white/80">
                            <li><a href="{{ route('profil') }}"
                                    class="hover:underline underline-offset-4 tracking-[.5px]">Profil
                                    Desa</a></li>
                            <li><a href="{{ route('berita') }}"
                                    class="hover:underline underline-offset-4  tracking-[.5px]">Berita</a>
                            </li>
                            <li><a href="{{ route('artikel') }}"
                                    class="hover:underline underline-offset-4 tracking-[.5px]">Artikel</a>
                            </li>
                            <li><a href="{{ route('layanan') }}"
                                    class="hover:underline underline-offset-4 tracking-[.5px]">Layanan
                                    Digital</a></li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[2px] text-white/70">Layanan</p>
                        <ul class="mt-3 space-y-2 text-sm text-white/80">
                            <li><a href="{{ route('layanan') }}"
                                    class="hover:underline underline-offset-4 tracking-[.5px]">Pengajuan
                                    Surat</a></li>
                            <li><a href="{{ route('kontak') }}"
                                    class="hover:underline underline-offset-4 tracking-[.5px]">Kontak</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[2px] text-white/70">Bantuan</p>
                        <ul class="mt-3 space-y-2 text-sm text-white/80">
                            <li><a href="{{ route('kontak') }}"
                                    class="hover:underline underline-offset-4 tracking-[.5px]">Pusat
                                    Bantuan</a></li>
                            <li><a href="{{ route('login') }}"
                                    class="hover:underline underline-offset-4 tracking-[.5px]">Masuk</a></li>
                            <li><a href="{{ route('register') }}"
                                    class="hover:underline underline-offset-4 tracking-[.5px]">Daftar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="md:col-span-3">
                <p class="text-xs font-semibold uppercase tracking-[2px] text-white/70">Ikuti Kami</p>
                <div class="mt-3 flex items-center gap-3">
                    <a href="#"
                        class="group inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition hover:border-orange-400/40 hover:bg-orange-500/10 hover:text-orange-300">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="group inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition hover:border-orange-400/40 hover:bg-orange-500/10 hover:text-orange-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                            <circle cx="12" cy="12" r="4" />
                            <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none" />
                        </svg>
                    </a>
                    <a href="#"
                        class="group inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition hover:border-orange-400/40 hover:bg-orange-500/10 hover:text-orange-300">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="group inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white transition hover:border-orange-400/40 hover:bg-orange-500/10 hover:text-orange-300">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                            aria-hidden="true">
                            <rect x="2" y="5.5" width="20" height="13" rx="3.2" ry="3.2" />
                            <polygon points="10,9 16,12 10,15" fill="none" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t border-white/10 pt-5">
            <div class="flex flex-col items-center justify-between gap-2 text-xs text-white/60 md:flex-row">
                <span>Pemerintah Desa Wonorejo &copy; 2026</span>
                <span>Desa Wonorejo, Sumbergempol, Tulungagung, Jawa Timur</span>
            </div>
            <div class="mt-2 text-center text-[11px] text-white/50 md:text-right">
                Design by Dea Preciosa Santyana Putri • Universitas Bhinneka PGRI
            </div>
        </div>
    </div>
</footer>