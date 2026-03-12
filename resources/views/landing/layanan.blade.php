@extends('layouts.app')

@section('title', config('app.name') . ' | Layanan')

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

<section class="relative min-h-screen bg-gray-100 pt-28 pb-16 overflow-hidden">
    <div class="absolute inset-x-0 top-0 z-0 h-[230px] sm:h-[260px] overflow-hidden">
        <img src="{{ asset('img/layanan-imgg.jpg') }}" alt="Layanan Digital"
            class="h-full w-full object-cover object-center">
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <div
        class="absolute left-1/2 -translate-x-1/2 top-[200px] sm:top-[230px] w-[140%] h-32 bg-gray-100 rounded-t-[100%]">
    </div>
    <div class="relative z-10 mx-auto w-full max-w-6xl px-2 md:px-4 reveal">
        <div class="mb-10 text-center mt-0 md:mt-6">
            <h1 class="text-2xl md:text-6xl font-extrabold tracking-[2px] text-white uppercase">Layanan Digital
            </h1>
            <p class="text-sm md:text-base text-white/80">
                Pelayanan online dengan cepat untuk kebutuhan administrasi desa.
            </p>
        </div>

        <div class="rounded-xl border border-gray-200/80 bg-white p-6 shadow-sm md:p-10 reveal">
            <div class="mb-7 border-b border-gray-100 pb-5">
                <p class="text-xs font-semibold uppercase tracking-[1.8px] text-orange-600">Keterangan Layanan Surat
                    Digital</p>
                <p class="mt-2 max-w-3xl text-sm leading-relaxed text-gray-600 md:text-base">
                    Layanan Surat Digital adalah fasilitas pengurusan surat secara online tanpa harus datang ke kantor
                    desa. Warga dapat memilih jenis surat, melengkapi data yang diperlukan, lalu memantau prosesnya
                    secara mudah.
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2" data-reveal-group>
                <div
                    class="reveal-item group rounded-lg border border-gray-200 bg-gradient-to-br from-white to-orange-50/50 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                        <i class="fa-solid fa-house-user text-lg"></i>
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Domisili</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan domisili untuk kebutuhan
                        administrasi.</p>
                    <a href="{{ route('layanan.mulai') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-orange-400">
                        Buat Pengajuan
                        @auth
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        @else
                        <i class="fa-solid fa-lock text-[11px]"></i>
                        @endauth
                    </a>
                </div>

                <div
                    class="reveal-item group rounded-lg border border-gray-200 bg-gradient-to-br from-white to-blue-50/60 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                        <i class="fa-solid fa-hand-holding-heart text-lg"></i>
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Tidak Mampu</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan untuk pengajuan bantuan
                        sosial atau pendidikan.</p>
                    <a href="{{ route('layanan.mulai') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-orange-400">
                        Buat Pengajuan
                        @auth
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        @else
                        <i class="fa-solid fa-lock text-[11px]"></i>
                        @endauth
                    </a>
                </div>

                <div
                    class="reveal-item group rounded-lg border border-gray-200 bg-gradient-to-br from-white to-slate-100/70 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gray-200 text-gray-700">
                        <i class="fa-solid fa-ribbon text-lg"></i>
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Kematian</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan kematian untuk keperluan
                        administrasi keluarga.</p>
                    <a href="{{ route('layanan.mulai') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-orange-400">
                        Buat Pengajuan
                        @auth
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        @else
                        <i class="fa-solid fa-lock text-[11px]"></i>
                        @endauth
                    </a>
                </div>

                <div
                    class="reveal-item group rounded-lg border border-gray-200 bg-gradient-to-br from-white to-green-50/70 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-600">
                        <i class="fa-solid fa-baby text-lg"></i>
                    </div>
                    <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Kelahiran</h2>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan kelahiran untuk pengurusan
                        data kependudukan.</p>
                    <a href="{{ route('layanan.mulai') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-md bg-orange-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-orange-400">
                        Buat Pengajuan
                        @auth
                        <i class="fa-solid fa-arrow-right text-[11px]"></i>
                        @else
                        <i class="fa-solid fa-lock text-[11px]"></i>
                        @endauth
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-xl border border-gray-200/80 bg-white p-6 shadow-sm md:p-10 reveal">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-2xl reveal-left">
                    <p class="text-xs font-semibold uppercase tracking-[1.8px] text-orange-600">Keunggulan Chatbot</p>
                    <h2 class="mt-2 text-xl md:text-2xl font-bold text-gray-800">Chatbot siap bantu 24 / 7</h2>
                    <p class="mt-3 text-sm md:text-base text-gray-600 leading-relaxed">
                        Chatbot desa membantu warga mencari informasi layanan, persyaratan surat, dan panduan langkah
                        demi langkah. Warga juga bisa mengajukan surat keterangan melalui chatbot untuk diarahkan ke
                        formulir yang tepat. Cepat, responsif, dan bisa diakses kapan saja.
                    </p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                <div
                    class="reveal-item rounded-lg border border-gray-200 bg-gradient-to-br from-white to-orange-50/60 p-5">
                    <div
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-gray-900">Jawaban Instan</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        Dapatkan jawaban cepat atas pertanyaan umum tanpa menunggu jam layanan kantor.
                    </p>
                </div>
                <div
                    class="reveal-item rounded-lg border border-gray-200 bg-gradient-to-br from-white to-orange-50/60 p-5">
                    <div
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-gray-900">Panduan Lengkap</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        Menjelaskan syarat, alur, dan dokumen yang diperlukan secara ringkas dan jelas.
                    </p>
                </div>
                <div
                    class="reveal-item rounded-lg border border-gray-200 bg-gradient-to-br from-white to-orange-50/60 p-5">
                    <div
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-gray-900">Aman & Terarah</h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        Mengarahkan warga ke layanan resmi dan informasi yang sesuai kebijakan desa.
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('dashboard.chatbot') }}"
                    class="inline-flex items-center gap-2 rounded-md bg-orange-500 px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-orange-400">
                    Coba Chatbot
                    @auth
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                    @else
                    <i class="fa-solid fa-lock text-[11px]"></i>
                    @endauth
                </a>
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