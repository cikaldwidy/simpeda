<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Simpeda - Layanan Desa Modern</title>
    <style>
        #hero {
            height: 100vh;
            min-height: 520px;
        }

        @media (max-width: 640px) {
            #hero {
                height: 86vh;
                min-height: 420px;
            }
        }

        .hero-slide {
            opacity: 0;
            transition: opacity .8s ease;
            will-change: opacity;
        }

        .hero-slide.is-active {
            opacity: 1;
        }

        .hero-img {
            transform: scale(1);
            will-change: transform;
        }

        .hero-img.kenburns {
            animation: kenburns 7s ease-in-out forwards;
        }

        @keyframes kenburns {
            from {
                transform: scale(1);
            }

            to {
                transform: scale(1.12);
            }
        }

        .cap-words {
            text-transform: capitalize;
        }

        @media (prefers-reduced-motion: reduce) {
            .hero-slide {
                transition: none;
            }

            .hero-img.kenburns {
                animation: none;
            }
        }

        .hero-bottom-bar {
            pointer-events: none;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 110px;
            z-index: 15;
            background: linear-gradient(to top,
                    rgba(0, 0, 0, .75) 0%,
                    rgba(0, 0, 0, .35) 55%,
                    rgba(0, 0, 0, 0) 100%);
        }

        @media (max-width: 640px) {
            .hero-bottom-bar {
                height: 95px;
            }
        }

        .hero-dots {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .hero-dot {
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, .28);
            border: 1px solid rgba(255, 255, 255, .18);
        }

        .hero-dot.is-active {
            background: rgba(255, 255, 255, .65);
            transform: scale(1.15);
        }
    </style>


</head>

<body class="bg-emerald-50 text-emerald-950">
    @include('partials.nav')

    <main>
        <section id="hero" class="relative w-full overflow-hidden" style="height:100vh; min-height:520px;">
            <div id="heroSlider" class="absolute inset-0 h-full w-full">
                @forelse($heroSlides as $i => $s)
                    <div class="hero-slide absolute inset-0 h-full w-full {{ $i === 0 ? 'is-active' : '' }}">
                        <img src="{{ Storage::url($s->image_path) }}"
                            class="hero-img absolute inset-0 z-0 h-full w-full object-cover object-center {{ $i === 0 ? 'kenburns' : '' }}"
                            alt="Slide {{ $i + 1 }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                        <div class="absolute inset-0 z-10 bg-black/25"></div>
                        <!-- caption kiri bawah -->
                        <div class="absolute bottom-10 left-6 right-6 z-20 max-w-4xl">
                            <h2
                                class="text-3xl font-extrabold uppercase tracking-wide text-white drop-shadow sm:text-5xl">
                                {{ $s->title }}
                            </h2>
                            <p class="cap-words mt-3 text-sm font-medium text-white/85 drop-shadow sm:text-base">
                                {{ $s->description }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="hero-slide absolute inset-0 h-full w-full is-active">
                        <img src="/img/hero/slide-1.jpg"
                            class="absolute inset-0 h-full w-full object-cover object-center" alt="Default">
                        <div class="absolute inset-0 bg-black/25"></div>
                        <div class="absolute bottom-10 left-6 right-6 z-20 max-w-4xl">
                            <h2
                                class="text-3xl font-extrabold uppercase tracking-wide text-white drop-shadow sm:text-5xl">
                                SLIDE BELUM DIISI
                            </h2>
                            <p class="cap-words mt-3 text-sm font-medium text-white/85 drop-shadow sm:text-base">
                                Tambahkan data slide lewat menu admin.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- strip gelap hanya bawah -->
            <div class="hero-bottom-bar"></div>

            <!-- panah -->
            <button id="heroPrev" type="button" aria-label="Sebelumnya"
                class="absolute left-5 top-1/2 z-30 -translate-y-1/2 hero-nav cursor-pointer">
                <svg class="h-15 w-auto text-white/70 hover:text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                </svg>
            </button>

            <button id="heroNext" type="button" aria-label="Berikutnya"
                class="absolute right-5 top-1/2 z-30 -translate-y-1/2 hero-nav cursor-pointer">
                <svg class="h-15 w-auto text-white/70 hover:text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6" />
                </svg>
            </button>

            <!-- dots kanan bawah -->
            <div id="heroDots" class="absolute bottom-7 right-6 z-30 hero-dots"></div>
        </section>


        <section id="fitur" class="bg-white">
            <div class="mx-auto w-full max-w-6xl px-4 py-14">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-emerald-950 md:text-3xl">Fitur utama yang relevan</h2>
                        <p class="mt-2 text-sm text-emerald-800">Dirancang agar petugas desa dan warga bisa bekerja
                            selaras, tanpa proses yang membingungkan.</p>
                    </div>
                </div>
                <div class="mt-8 grid gap-5 md:grid-cols-3">
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                        <span class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Pendataan</span>
                        <h3 class="mt-3 text-lg font-semibold text-emerald-900">Profil warga terpusat</h3>
                        <p class="mt-2 text-sm text-emerald-800">Satu data warga untuk semua layanan. Tidak perlu input
                            berulang dan mudah diperbarui.</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                        <span class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Pengajuan</span>
                        <h3 class="mt-3 text-lg font-semibold text-emerald-900">Permohonan cepat</h3>
                        <p class="mt-2 text-sm text-emerald-800">Alur pengajuan jelas, notifikasi status, dan lampiran
                            dokumen yang tertata.</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                        <span class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Monitoring</span>
                        <h3 class="mt-3 text-lg font-semibold text-emerald-900">Progres real time</h3>
                        <p class="mt-2 text-sm text-emerald-800">Pantau status pengajuan dan tindak lanjut, cocok untuk
                            perangkat desktop maupun mobile.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="layanan" class="bg-emerald-50">
            <div class="mx-auto w-full max-w-6xl px-4 py-14">
                <div class="grid gap-8 md:grid-cols-2 md:items-center">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Layanan</span>
                        <h2 class="mt-3 text-2xl font-semibold text-emerald-950 md:text-3xl">Layanan desa yang disusun
                            ulang agar lebih simpel.</h2>
                        <p class="mt-3 text-sm text-emerald-800">Mulai dari surat keterangan, bantuan sosial, hingga
                            administrasi kependudukan. Semua disajikan dalam struktur yang konsisten dan mudah diikuti.
                        </p>
                        <div class="mt-6 grid gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl bg-white p-4 text-center">
                                <p class="text-lg font-semibold text-emerald-900">30%</p>
                                <p class="text-xs text-emerald-700">lebih cepat diproses</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 text-center">
                                <p class="text-lg font-semibold text-emerald-900">24/7</p>
                                <p class="text-xs text-emerald-700">akses online warga</p>
                            </div>
                            <div class="rounded-2xl bg-white p-4 text-center">
                                <p class="text-lg font-semibold text-emerald-900">100%</p>
                                <p class="text-xs text-emerald-700">arsip digital rapi</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-emerald-100 bg-white p-6">
                        <h3 class="text-lg font-semibold text-emerald-900">Pengalaman pelayanan yang tenang</h3>
                        <p class="mt-2 text-sm text-emerald-700">Petugas memiliki dashboard yang fokus, warga mendapat
                            panduan langkah demi langkah, dan semua data tersimpan aman.</p>
                        <div class="mt-4 space-y-3 text-sm text-emerald-800">
                            <div class="flex items-center gap-3"><span
                                    class="h-2 w-2 rounded-full bg-emerald-600"></span>Template surat siap pakai</div>
                            <div class="flex items-center gap-3"><span
                                    class="h-2 w-2 rounded-full bg-emerald-600"></span>Log aktivitas yang jelas</div>
                            <div class="flex items-center gap-3"><span
                                    class="h-2 w-2 rounded-full bg-emerald-600"></span>Role akses terkontrol</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="proses" class="bg-white">
            <div class="mx-auto w-full max-w-6xl px-4 py-14">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-emerald-950 md:text-3xl">Proses layanan yang jelas</h2>
                        <p class="mt-2 text-sm text-emerald-800">Warga memahami langkah yang harus dilakukan, petugas
                            memiliki struktur kerja yang konsisten.</p>
                    </div>
                </div>
                <div class="mt-8 grid gap-4 md:grid-cols-4">
                    <div class="rounded-2xl border border-emerald-100 p-5">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-700 text-sm font-semibold text-white">
                            1</div>
                        <h3 class="mt-4 text-sm font-semibold text-emerald-900">Daftar akun</h3>
                        <p class="mt-2 text-xs text-emerald-700">Buat akun dengan data penduduk yang valid.</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 p-5">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-700 text-sm font-semibold text-white">
                            2</div>
                        <h3 class="mt-4 text-sm font-semibold text-emerald-900">Pilih layanan</h3>
                        <p class="mt-2 text-xs text-emerald-700">Tentukan jenis layanan yang dibutuhkan.</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 p-5">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-700 text-sm font-semibold text-white">
                            3</div>
                        <h3 class="mt-4 text-sm font-semibold text-emerald-900">Unggah berkas</h3>
                        <p class="mt-2 text-xs text-emerald-700">Lampirkan dokumen sesuai kebutuhan.</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 p-5">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-700 text-sm font-semibold text-white">
                            4</div>
                        <h3 class="mt-4 text-sm font-semibold text-emerald-900">Terima hasil</h3>
                        <p class="mt-2 text-xs text-emerald-700">Notifikasi status dikirim otomatis.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-emerald-50">
            <div class="mx-auto w-full max-w-6xl px-4 py-12">
                <div
                    class="flex flex-col items-center justify-between gap-6 rounded-3xl bg-emerald-700 px-6 py-10 text-center text-white md:flex-row md:text-left">
                    <div>
                        <h2 class="text-2xl font-semibold">Siap membuat layanan desa lebih rapi?</h2>
                        <p class="mt-2 text-sm text-emerald-100">Mulai dari pendaftaran hingga proses persetujuan,
                            semua bisa ditata lebih efisien bersama Simpeda.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}"
                            class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-emerald-800">Daftar
                            Warga</a>
                        <a href="{{ route('login') }}"
                            class="rounded-full border border-white/40 px-5 py-2 text-sm font-semibold text-white">Masuk
                            Petugas</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <div class="fixed bottom-6 right-6 z-50">
        <button id="chatToggle" type="button"
            class="hover:cursor-pointer flex items-center rounded-full  bg-transparent px-4 py-3 text-sm font-semibold text-emerald-900  transition hover:scale-[1.02] "
            aria-expanded="false" aria-controls="chatPopup">
            <span class="flex h-15 w-15 items-center justify-center">
                <svg class="h-13 w-13" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                    <rect x="6" y="12" width="36" height="24" rx="12" fill="#34D399"></rect>
                    <rect x="12" y="18" width="24" height="12" rx="6" fill="#ECFDF5"></rect>
                    <circle cx="20" cy="24" r="2.8" fill="#10B981"></circle>
                    <circle cx="28" cy="24" r="2.8" fill="#10B981"></circle>
                </svg>
            </span>
            <span class="leading-tight">
                <span class="block text-base font-bold tracking-wide">EJABOT</span>
            </span>
        </button>
    </div>

    <div id="chatPopup"
        class="fixed bottom-24 right-6 z-50 hidden w-[92vw] max-w-sm rounded-2xl border border-emerald-100 bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-emerald-100 px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.6" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 9V7a5 5 0 0 1 10 0v2"></path>
                        <rect x="4" y="9" width="16" height="10" rx="3"></rect>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14h.01M15 14h.01"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-emerald-900">Ejabot</p>
                    <p class="text-xs text-emerald-600">Virtual AI untuk layanan desa</p>
                </div>
            </div>
            <button id="chatClose" type="button"
                class="rounded-full border border-emerald-100 px-2 py-1 text-xs text-emerald-700 hover:border-emerald-200">x</button>
        </div>
        <div class="max-h-72 space-y-3 overflow-y-auto px-4 py-3 text-sm">
            <div class="rounded-xl bg-emerald-50 px-3 py-2 text-emerald-800">Halo! Ada yang bisa dibantu?</div>
            <div class="ml-auto w-fit rounded-xl bg-emerald-700 px-3 py-2 text-white">Saya ingin tahu layanan surat.
            </div>
            <div class="rounded-xl bg-emerald-50 px-3 py-2 text-emerald-800">Silakan pilih menu layanan atau ajukan
                pertanyaan spesifik.</div>
        </div>
        <div class="flex items-center gap-2 border-t border-emerald-100 px-4 py-3">
            <input type="text" placeholder="Tulis pesan..."
                class="w-full rounded-full border border-emerald-200 px-3 py-2 text-sm focus:border-emerald-400 focus:outline-none">
            <button type="button"
                class="rounded-full bg-emerald-700 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-800">Kirim</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // cegah init ganda
            if (window.__heroSliderInited) return;
            window.__heroSliderInited = true;

            const slider = document.getElementById('heroSlider');
            const hero = document.getElementById('hero');
            if (!slider || !hero) return;

            const slides = Array.from(slider.querySelectorAll('.hero-slide'));
            const prevBtn = document.getElementById('heroPrev');
            const nextBtn = document.getElementById('heroNext');
            const dotsWrap = document.getElementById('heroDots');

            const INTERVAL = 5000;
            let idx = 0;
            let timer = null;
            let dots = [];

            function buildDots() {
                if (!dotsWrap) return;
                dotsWrap.innerHTML = '';
                dots = slides.map((_, i) => {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'hero-dot';
                    b.setAttribute('aria-label', `Slide ${i+1}`);
                    b.addEventListener('click', () => {
                        setActive(i);
                        start();
                    });
                    dotsWrap.appendChild(b);
                    return b;
                });
            }

            function setActive(i) {
                if (!slides.length) return;
                idx = (i + slides.length) % slides.length;

                slides.forEach((s, j) => {
                    const img = s.querySelector('img');
                    const active = j === idx;

                    s.classList.toggle('is-active', active);

                    if (img) {
                        img.classList.remove('kenburns');
                        void img.offsetWidth;
                        if (active) img.classList.add('kenburns');
                    }
                });

                dots.forEach((d, j) => d.classList.toggle('is-active', j === idx));
            }

            function next() {
                setActive(idx + 1);
            }

            function prev() {
                setActive(idx - 1);
            }

            function stop() {
                if (timer) clearInterval(timer);
                timer = null;
            }

            function start() {
                stop();
                if (slides.length <= 1) return;
                timer = setInterval(next, INTERVAL);
            }

            // tombol
            if (nextBtn) nextBtn.addEventListener('click', () => {
                next();
                start();
            });
            if (prevBtn) prevBtn.addEventListener('click', () => {
                prev();
                start();
            });

            // keyboard
            window.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') {
                    next();
                    start();
                }
                if (e.key === 'ArrowLeft') {
                    prev();
                    start();
                }
            });

            // tab visibility
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) stop();
                else start();
            });

            buildDots();
            setActive(0);

            // start setelah render stabil
            setTimeout(start, 200);
        });
    </script>


    <script>
        (function() {
            const toggle = document.getElementById('chatToggle');
            const popup = document.getElementById('chatPopup');
            const closeBtn = document.getElementById('chatClose');
            if (!toggle || !popup || !closeBtn) return;

            function openChat() {
                popup.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
            }

            function closeChat() {
                popup.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            }

            toggle.addEventListener('click', function() {
                if (popup.classList.contains('hidden')) {
                    openChat();
                } else {
                    closeChat();
                }
            });

            closeBtn.addEventListener('click', closeChat);
        })();
    </script>





</body>

</html>
