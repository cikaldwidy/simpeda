@extends('layouts.app')

@section('title', config('app.name') . ' | Profil Desa')

@push('styles')
<style>
/* Animasi Scroll Reveal */
.reveal,
.reveal-item {
    opacity: 0;
    transform: translateY(50px);
    transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.reveal.is-visible,
.reveal-item.is-visible {
    opacity: 1;
    transform: translateY(0);
}

.reveal-left {
    opacity: 0;
    transform: translateX(-60px);
    transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1);
}

.reveal-right {
    opacity: 0;
    transform: translateX(60px);
    transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1);
}

.reveal-left.is-visible,
.reveal-right.is-visible {
    opacity: 1;
    transform: translateX(0);
}

@media (prefers-reduced-motion: reduce) {

    .reveal,
    .reveal-left,
    .reveal-right,
    .reveal-item {
        opacity: 1;
        transform: none;
        transition: none;
    }
}

/* Custom Shape Divider - Ombak Bawah */
.custom-shape-divider-bottom {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    transform: rotate(180deg);
}

.custom-shape-divider-bottom svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 120px;
}

/* PERBAIKAN WARNA OMBAK BAWAH */
/* Menyesuaikan dengan warna bg-slate-50 (#f8fafc) di section Statistik */
.custom-shape-divider-bottom .shape-fill-1 {
    fill: #f8fafc;
    opacity: 0.3;
}

.custom-shape-divider-bottom .shape-fill-2 {
    fill: #f8fafc;
    opacity: 0.5;
}

.custom-shape-divider-bottom .shape-fill-3 {
    fill: #f8fafc;
}
</style>
@endpush

@section('content')
@include('partials.nav')

<div class="bg-gray-100">
    <main class="relative z-10">

        <section class="relative bg-[#2a3a52] pb-24">

            <div class="absolute top-0 left-0 w-full h-[400px] lg:h-[500px] bg-cover bg-center bg-fixed"
                style="background-image: url('{{ asset('img/balai-desa.jpeg') }}');">
                <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                <div class="absolute inset-0 backdrop-blur-[.5px]"></div>
            </div>

            <div id="sejarah" class="relative z-20 mx-auto w-full max-w-5xl px-4 pt-[250px] lg:pt-[350px] reveal">
                <div
                    class="bg-white p-8 md:p-14 lg:p-16 rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.15)] border border-slate-100">
                    <div class="mb-8 flex items-center gap-5">
                        <span class="h-16 w-2 rounded-full bg-orange-500"></span>
                        <div>
                            <p class="text-xs font-bold tracking-[3px] text-orange-500 uppercase mb-2">Awal Mula &
                                Perjalanan Kami</p>
                            <h2 class="text-3xl sm:text-5xl font-bold text-slate-800">Menelusuri Jejak Masa Lalu</h2>
                        </div>
                    </div>

                    <div class="prose max-w-none text-slate-700 text-justify leading-relaxed text-lg sm:text-xl">
                        <p class="mb-6">
                            Pada sekitar tahun 1830, setelah penangkapan Pangeran Diponegoro, salah satu pengikutnya
                            bernama <strong>Driyoleksono</strong> melarikan diri ke sebuah hutan belantara. Seiring
                            waktu, banyak rekannya yang ikut bergabung, membuat kawasan hutan tersebut menjadi
                            ramai.
                        </p>
                        <p class="mb-6">
                            Dari situlah nama <strong>Wonorejo</strong> lahir, yang dalam bahasa Jawa memiliki arti
                            <span class="text-orange-600 font-semibold">"Hutan yang Ramai"</span>.
                        </p>
                        <p>
                            Driyoleksono kemudian diangkat menjadi Demang pertama dan memimpin hingga tahun 1872.
                            Kepemimpinan dilanjutkan oleh putranya, Driyaontani, di tengah kondisi yang kala itu
                            penuh dinamika dan tantangan perampok di wilayah tersebut.
                        </p>
                    </div>
                </div>
            </div>

            <div id="visimisi" class="mx-auto w-full max-w-6xl px-4 relative z-10 reveal pt-10 lg:pt-16">
                <div class="mb-10 text-center">
                    <h2 class="text-4xl sm:text-5xl font-serif text-white tracking-wide">Visi & Misi</h2>
                    <div class="mx-auto mt-4 h-1 w-20 bg-orange-500 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 mb-10" data-reveal-group>
                    <div
                        class="reveal-item rounded-3xl bg-white/5 p-8 backdrop-blur-md border border-white/10 text-white shadow-2xl hover:border-orange-500/50 transition duration-300">
                        <div class="flex items-center gap-4 mb-10">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-500 text-white shadow-lg shadow-orange-500/30">
                                <i class="fa-solid fa-eye text-2xl"></i>
                            </div>
                            <h3 class="text-3xl font-serif text-orange-400">Visi</h3>
                        </div>
                        <p class="text-xl font-light leading-relaxed italic text-slate-200">
                            "Membangun Masyarakat Wonorejo Cerdas, Berkualitas dan Sejahtera Menuju Kemakmuran
                            Masyarakat yang Adil dan Merata"
                        </p>
                    </div>

                    <div
                        class="reveal-item rounded-3xl bg-white p-8 shadow-2xl text-slate-800 border-b-4 border-orange-500">
                        <div class="flex items-center gap-4 mb-10">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-800 text-orange-500 shadow-lg">
                                <i class="fa-solid fa-bullseye text-2xl"></i>
                            </div>
                            <h3 class="text-3xl font-serif text-slate-800">Misi</h3>
                        </div>
                        <ul class="space-y-4 text-slate-600">
                            <li class="flex items-start gap-4">
                                <div
                                    class="mt-1 flex-shrink-0 bg-orange-100 text-orange-500 p-2 rounded-full flex items-center justify-center h-8 w-8">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </div>
                                <span class="text-lg">Mewujudkan masyarakat desa dapat mengenyam pendidikan formal
                                    maupun informal.</span>
                            </li>
                            <li class="flex items-start gap-4">
                                <div
                                    class="mt-1 flex-shrink-0 bg-orange-100 text-orange-500 p-2 rounded-full flex items-center justify-center h-8 w-8">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </div>
                                <span class="text-lg">Mewujudkan kehidupan masyarakat yang semakin baik dan berdaya
                                    saing.</span>
                            </li>
                            <li class="flex items-start gap-4">
                                <div
                                    class="mt-1 flex-shrink-0 bg-orange-100 text-orange-500 p-2 rounded-full flex items-center justify-center h-8 w-8">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </div>
                                <span class="text-lg">Mewujudkan pemerataan pembangunan yang dapat dirasakan oleh
                                    seluruh masyarakat.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="custom-shape-divider-bottom ">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                    preserveAspectRatio="none">
                    <path class="shape-fill-1"
                        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z">
                    </path>
                    <path class="shape-fill-2"
                        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z">
                    </path>
                    <path class="shape-fill-3"
                        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z">
                    </path>
                </svg>
            </div>
        </section>

        <section id="statistik" class="bg-slate-50 py-24 reveal">
            <div class="mx-auto w-full max-w-6xl px-4">
                <div class="mb-14 text-center">
                    <p class="text-sm font-bold tracking-[3px] text-orange-500 uppercase mb-2">Demografi Wilayah</p>
                    <h2 class="text-4xl font-bold text-slate-800">Statistik Desa</h2>
                    <div class="mx-auto mt-4 h-1 w-16 bg-orange-500 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-reveal-group>
                    <div
                        class="reveal-item bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-b-4 border-orange-500 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(249,115,22,0.15)]">
                        <div
                            class="w-20 h-20 mx-auto bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-3xl mb-6">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <h3 class="text-5xl font-black text-slate-800 mb-2">4.250</h3>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Total Penduduk</p>
                    </div>

                    <div
                        class="reveal-item bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-b-4 border-orange-500 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(249,115,22,0.15)]">
                        <div
                            class="w-20 h-20 mx-auto bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-3xl mb-6">
                            <i class="fa-solid fa-map-marked-alt"></i>
                        </div>
                        <h3 class="text-5xl font-black text-slate-800 mb-2">245 <span class="text-2xl">Ha</span></h3>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Luas Wilayah</p>
                    </div>

                    <div
                        class="reveal-item bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-b-4 border-orange-500 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(249,115,22,0.15)]">
                        <div
                            class="w-20 h-20 mx-auto bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-3xl mb-6">
                            <i class="fa-solid fa-male"></i>
                        </div>
                        <h3 class="text-5xl font-black text-slate-800 mb-2">2.110</h3>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Laki-laki</p>
                    </div>

                    <div
                        class="reveal-item bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-b-4 border-orange-500 text-center transform transition duration-300 hover:-translate-y-2 hover:shadow-[0_8px_30px_rgb(249,115,22,0.15)]">
                        <div
                            class="w-20 h-20 mx-auto bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-3xl mb-6">
                            <i class="fa-solid fa-female"></i>
                        </div>
                        <h3 class="text-5xl font-black text-slate-800 mb-2">2.140</h3>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Perempuan</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="struktur" class="bg-white py-24 reveal">
            <div class="mx-auto w-full max-w-6xl px-4">
                <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
                    <div class="reveal-right">
                        <div class="mb-6 flex items-center gap-4">
                            <span class="h-12 w-1.5 rounded-full bg-orange-500"></span>
                            <div>
                                <p class="text-xs font-bold tracking-[3px] text-orange-500">STRUKTUR ORGANISASI</p>
                                <h2 class="text-3xl sm:text-4xl font-bold text-slate-800">Tata Kelola Pemerintahan Desa
                                </h2>
                            </div>
                        </div>
                        <p class="text-slate-600 text-lg leading-relaxed mb-8">
                            Pemerintah Desa Wonorejo dijalankan oleh Kepala Desa yang dibantu oleh Sekretaris Desa,
                            Kepala Urusan (Kaur), Kepala Seksi (Kasi), dan Kepala Dusun (Kasun). Kami berkomitmen
                            memberikan pelayanan publik yang efisien, transparan, dan akuntabel.
                        </p>

                        <button type="button" id="openSotkModal"
                            class="inline-flex items-center gap-3 rounded-full bg-orange-500 px-8 py-4 font-bold text-white shadow-lg shadow-orange-500/40 transition-all hover:bg-orange-600 hover:-translate-y-1">
                            Lihat Bagan Lengkap SOTK
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>

                    <div class="relative w-full">
                        <img src="{{ asset('img/Bagan.png') }}" alt="Bagan Struktur Organisasi Desa"
                            class="w-full rounded-2xl object-cover shadow-md border border-slate-200">
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

<div id="sotkModal" class="fixed inset-0 z-[999] hidden bg-slate-950/85 p-4 sm:p-6">
    <div class="flex h-full w-full items-center justify-center">
        <button type="button" id="closeSotkModal"
            class="absolute right-4 top-4 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/20"
            aria-label="Tutup gambar SOTK">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <img src="{{ asset('img/Bagan.png') }}" alt="Bagan Struktur Organisasi Desa ukuran penuh"
            class="max-h-full max-w-full rounded-2xl border border-white/10 bg-white object-contain shadow-2xl">
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-item');
    if (!items.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -5% 0px',
    });

    const groups = document.querySelectorAll('[data-reveal-group]');
    groups.forEach((group) => {
        const groupItems = Array.from(group.querySelectorAll('.reveal-item'));
        groupItems.forEach((el, idx) => {
            el.style.transitionDelay = `${idx * 0.15}s`;
        });
    });

    items.forEach((el) => {
        observer.observe(el);
    });

    const openSotkModal = document.getElementById('openSotkModal');
    const closeSotkModal = document.getElementById('closeSotkModal');
    const sotkModal = document.getElementById('sotkModal');

    if (!openSotkModal || !closeSotkModal || !sotkModal) return;

    const openModal = () => {
        sotkModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    const closeModal = () => {
        sotkModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    openSotkModal.addEventListener('click', openModal);
    closeSotkModal.addEventListener('click', closeModal);
    sotkModal.addEventListener('click', (event) => {
        if (event.target === sotkModal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !sotkModal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>
@endpush
@endsection
