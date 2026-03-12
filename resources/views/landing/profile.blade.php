@extends('layouts.app')

@section('title', config('app.name') . ' | Profil Desa')

@push('styles')
<style>
.reveal,
.reveal-item {
    opacity: 0;
    transform: translateY(50px) rotateX(15deg) scale(0.95);
    transform-origin: top center;
    transition:
        opacity .9s cubic-bezier(0.16, 1, 0.3, 1),
        transform .9s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.reveal.is-visible,
.reveal-item.is-visible {
    opacity: 1;
    transform: translateY(0) rotateX(0deg) scale(1);
}

.reveal-left {
    opacity: 0;
    transform: translateX(-60px) rotateY(20deg) scale(0.94);
    transform-origin: left center;
    transition:
        opacity .9s cubic-bezier(0.16, 1, 0.3, 1),
        transform .9s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.reveal-right {
    opacity: 0;
    transform: translateX(60px) rotateY(-20deg) scale(0.94);
    transform-origin: right center;
    transition:
        opacity .9s cubic-bezier(0.16, 1, 0.3, 1),
        transform .9s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.reveal-left.is-visible,
.reveal-right.is-visible {
    opacity: 1;
    transform: translateX(0) rotateY(0deg) scale(1);
}

.reveal-up {
    opacity: 0;
    transform: translateY(55px) rotateX(18deg) scale(0.95);
    transform-origin: bottom center;
    transition:
        opacity .85s cubic-bezier(0.16, 1, 0.3, 1),
        transform .85s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
}

.reveal-up.is-visible {
    opacity: 1;
    transform: translateY(0) rotateX(0deg) scale(1);
}

/* ===== STAGGER DELAYS ===== */
.reveal-delay-1 {
    transition-delay: .05s;
}

.reveal-delay-2 {
    transition-delay: .40s;
}

.reveal-delay-3 {
    transition-delay: .60s;
}

.reveal-delay-4 {
    transition-delay: .20s;
}

.reveal-delay-5 {
    transition-delay: .70s;
}

.reveal-delay-6 {
    transition-delay: .40s;
}

/* ===== REDUCED MOTION ===== */
@media (prefers-reduced-motion: reduce) {

    .reveal,
    .reveal-left,
    .reveal-right,
    .reveal-item,
    .reveal-up {
        opacity: 1;
        transform: none;
        transition: none;
        animation: none;
    }
}
</style>
@endpush

@section('content')
@include('partials.nav')

<section class="relative bg-gray-100 pt-28 pb-6 overflow-hidden reveal">
    <div class="absolute inset-x-0 top-0 z-0 h-[230px] sm:h-[260px] bg-gradient-to-b from-black via-black to-gray-900">
    </div>
    <div
        class="absolute left-1/2 -translate-x-1/2 top-[200px] sm:top-[230px] w-[140%] h-32 bg-gray-100 rounded-t-[100%]">
    </div>

    <div class="relative z-10 mx-auto w-full max-w-6xl px-2 md:px-4">
        <div class="mb-10 text-center mt-0 md:mt-6">
            <h1 class="text-2xl md:text-6xl font-extrabold tracking-[2px] text-white uppercase">Profil Desa</h1>
            <p class="text-sm md:text-base text-white/80">
                Informasi lengkap tentang sejarah, visi-misi, struktur, statistik, potensi, dan inovasi desa.
            </p>
        </div>
    </div>
</section>
<section id="sejarah" class="relative overflow-hidden bg-gray-50 reveal">
    <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-orange-200/40 blur-3xl"></div>
    <div class="mx-auto w-full max-w-6xl px-2 md:px-4">
        <div class="mb-8 flex items-center gap-3">
            <span class="h-10 w-1 rounded-full bg-orange-500"></span>
            <div>
                <p class="text-xs font-bold tracking-[3px] text-orange-500">SEJARAH DESA</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Perjalanan Desa Wonorejo</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3" data-reveal-group>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold tracking-[2px] text-gray-500">ERA PERINTIS</p>
                <h3 class="mt-2 text-lg font-semibold text-gray-900">Awal Pembentukan</h3>
                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Desa Wonorejo bermula dari kawasan pertanian yang dihuni kelompok perintis. Tradisi gotong royong
                    menjadi pondasi utama pembentukan desa.
                </p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold tracking-[2px] text-gray-500">ERA BERKEMBANG</p>
                <h3 class="mt-2 text-lg font-semibold text-gray-900">Pertumbuhan Infrastruktur</h3>
                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Pembangunan jalan desa, fasilitas umum, dan layanan administrasi menjadi fokus utama untuk
                    meningkatkan kualitas hidup warga.
                </p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold tracking-[2px] text-gray-500">ERA DIGITAL</p>
                <h3 class="mt-2 text-lg font-semibold text-gray-900">Transformasi Layanan</h3>
                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Inisiatif pelayanan digital diperkenalkan agar akses informasi, pengajuan surat, dan komunikasi
                    publik semakin cepat.
                </p>
            </div>
        </div>
    </div>
</section>

<section id="visimisi" class="bg-white py-12 reveal">
    <div class="mx-auto w-full max-w-6xl px-2 md:px-4">
        <div class="mb-8 text-center">
            <p class="text-xs font-bold tracking-[3px] text-orange-500">VISI & MISI</p>
            <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Arah Pembangunan Desa</h2>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2" data-reveal-group>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-gray-50 p-6">
                <p class="text-xs font-bold tracking-[2px] text-gray-500">VISI</p>
                <p class="mt-3 text-sm text-gray-700 leading-relaxed">
                    Terwujudnya Desa Wonorejo yang mandiri, inovatif, dan berdaya saing dengan pelayanan publik yang
                    cepat, transparan, dan partisipatif.
                </p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-gray-50 p-6">
                <p class="text-xs font-bold tracking-[2px] text-gray-500">MISI</p>
                <div class="mt-3 space-y-3 text-sm text-gray-700">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-2 w-2 rounded-full bg-orange-500"></span>
                        <span>Meningkatkan kualitas pelayanan administrasi dan keterbukaan informasi publik.</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-2 w-2 rounded-full bg-orange-500"></span>
                        <span>Memperkuat ekonomi lokal melalui pengembangan UMKM dan potensi desa.</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-2 w-2 rounded-full bg-orange-500"></span>
                        <span>Memperluas pemanfaatan teknologi untuk percepatan layanan dan kolaborasi warga.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="struktur" class="relative overflow-hidden bg-gray-50 py-12 reveal">
    <div class="absolute -left-24 top-10 h-64 w-64 rounded-full bg-orange-200/40 blur-3xl"></div>
    <div class="mx-auto w-full max-w-6xl px-2 md:px-4">
        <div class="mb-8 flex items-center gap-3">
            <span class="h-10 w-1 rounded-full bg-orange-500"></span>
            <div>
                <p class="text-xs font-bold tracking-[3px] text-orange-500">STRUKTUR ORGANISASI</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Susunan Perangkat Desa</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3" data-reveal-group>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <p class="text-sm text-gray-600 leading-relaxed">
                    Struktur organisasi desa memastikan setiap layanan berjalan efektif. Perangkat desa dibagi ke
                    beberapa unit kerja untuk pelayanan administrasi, pembangunan, dan pemberdayaan masyarakat.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <span class="rounded-full bg-orange-50 px-4 py-2 text-xs font-semibold text-orange-600">Kepala
                        Desa</span>
                    <span class="rounded-full bg-orange-50 px-4 py-2 text-xs font-semibold text-orange-600">Sekretaris
                        Desa</span>
                    <span class="rounded-full bg-orange-50 px-4 py-2 text-xs font-semibold text-orange-600">Kaur
                        Keuangan</span>
                    <span class="rounded-full bg-orange-50 px-4 py-2 text-xs font-semibold text-orange-600">Kaur
                        Umum</span>
                    <span class="rounded-full bg-orange-50 px-4 py-2 text-xs font-semibold text-orange-600">Kasi
                        Pelayanan</span>
                </div>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-bold tracking-[2px] text-gray-500">LIHAT DETAIL</p>
                <h3 class="mt-2 text-lg font-semibold text-gray-900">Bagan Organisasi</h3>
                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Informasi lengkap perangkat desa dapat dilihat pada halaman SOTK.
                </p>
                <a href="{{ route('sotk') }}"
                    class="mt-5 inline-flex items-center rounded-xl bg-orange-500 px-4 py-2 text-xs font-semibold text-white hover:bg-orange-600 transition">
                    Lihat SOTK
                </a>
            </div>
        </div>
    </div>
</section>

<section id="statistik" class="bg-white py-12 reveal">
    <div class="mx-auto w-full max-w-6xl px-2 md:px-4">
        <div class="mb-8 text-center">
            <p class="text-xs font-bold tracking-[3px] text-orange-500">STATISTIK DESA</p>
            <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Gambaran Data Singkat</h2>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4" data-reveal-group>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-gray-50 p-5 text-center">
                <p class="text-2xl font-extrabold text-gray-900">4.250</p>
                <p class="mt-1 text-xs font-semibold tracking-[2px] text-gray-500 uppercase">Penduduk</p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-gray-50 p-5 text-center">
                <p class="text-2xl font-extrabold text-gray-900">1.180</p>
                <p class="mt-1 text-xs font-semibold tracking-[2px] text-gray-500 uppercase">KK</p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-gray-50 p-5 text-center">
                <p class="text-2xl font-extrabold text-gray-900">8</p>
                <p class="mt-1 text-xs font-semibold tracking-[2px] text-gray-500 uppercase">Dusun</p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-gray-50 p-5 text-center">
                <p class="text-2xl font-extrabold text-gray-900">245 Ha</p>
                <p class="mt-1 text-xs font-semibold tracking-[2px] text-gray-500 uppercase">Luas Wilayah</p>
            </div>
        </div>
    </div>
</section>

<section id="potensi" class="relative overflow-hidden bg-gray-50 py-12 reveal">
    <div class="absolute right-0 top-0 h-64 w-64 rounded-full bg-orange-100/60 blur-3xl"></div>
    <div class="mx-auto w-full max-w-6xl px-2 md:px-4">
        <div class="mb-8 flex items-center gap-3">
            <span class="h-10 w-1 rounded-full bg-orange-500"></span>
            <div>
                <p class="text-xs font-bold tracking-[3px] text-orange-500">POTENSI DESA</p>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Sumber Daya Unggulan</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Pertanian</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Komoditas padi, sayuran, dan hortikultura menjadi tulang punggung ekonomi desa.
                </p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">UMKM</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Produk olahan pangan dan kerajinan lokal berkembang melalui pendampingan dan pemasaran digital.
                </p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Wisata Desa</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Potensi wisata edukasi dan alam menawarkan pengalaman baru bagi pengunjung dan warga sekitar.
                </p>
            </div>
        </div>
    </div>
</section>

<section id="inovasi" class="bg-white py-12 reveal">
    <div class="mx-auto w-full max-w-6xl px-2 md:px-4">
        <div class="mb-8 text-center">
            <p class="text-xs font-bold tracking-[3px] text-orange-500">INOVASI DESA</p>
            <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Program dan Layanan Baru</h2>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2" data-reveal-group>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-gray-50 p-6">
                <h3 class="text-lg font-semibold text-gray-900">Sistem Layanan Terpadu</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Integrasi pengajuan surat dan informasi pelayanan untuk memangkas waktu proses administrasi.
                </p>
            </div>
            <div class="reveal-item rounded-2xl border border-slate-200 bg-gray-50 p-6">
                <h3 class="text-lg font-semibold text-gray-900">Komunikasi Digital Warga</h3>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    Kanal informasi resmi desa yang terhubung dengan media sosial untuk menyebarkan pengumuman lebih
                    cepat.
                </p>
            </div>
        </div>
    </div>
</section>

@include('partials.footer')
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
        threshold: 0.12,
        rootMargin: '0px 0px -10% 0px',
    });

    const groups = document.querySelectorAll('[data-reveal-group]');
    groups.forEach((group) => {
        const groupItems = Array.from(group.querySelectorAll('.reveal-item'));
        groupItems.forEach((el, idx) => {
            el.style.transitionDelay = `${Math.min(6, idx) * 0.05}s`;
        });
    });

    items.forEach((el, i) => {
        if (!el.style.transitionDelay) {
            const delayClass = `reveal-delay-${Math.min(4, (i % 4) + 1)}`;
            el.classList.add(delayClass);
        }
        observer.observe(el);
    });
});
</script>
@endpush
@endsection