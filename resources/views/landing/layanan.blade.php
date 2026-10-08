@extends('layouts.app')

@section('title', config('app.name') . ' | Layanan')

@push('styles')
<style>
:root {
    --layanan-ink: #0f172a;
    --layanan-accent: #f97316;
    --layanan-sun: #f59e0b;
    --layanan-ocean: #06b6d4;
    --layanan-mint: #10b981;
}

.layanan-bg {
    background:
        radial-gradient(1200px 420px at 5% -5%, rgba(245, 158, 11, 0.22), transparent 60%),
        radial-gradient(900px 400px at 90% 10%, rgba(6, 182, 212, 0.22), transparent 60%),
        radial-gradient(800px 520px at 50% 100%, rgba(16, 185, 129, 0.18), transparent 60%),
        linear-gradient(180deg, #fff7ed 0%, #f8fafc 45%, #f1f5f9 100%);
}

.layanan-orb {
    position: absolute;
    border-radius: 9999px;
    opacity: 0.22;
    pointer-events: none;
    mix-blend-mode: multiply;
}

.layanan-orb.orange {
    background: radial-gradient(circle at 30% 30%, #fdba74, #f97316 60%, transparent 72%);
}

.layanan-orb.cyan {
    background: radial-gradient(circle at 30% 30%, #67e8f9, #06b6d4 60%, transparent 72%);
}

.layanan-orb.mint {
    background: radial-gradient(circle at 30% 30%, #6ee7b7, #10b981 60%, transparent 72%);
}

.layanan-grain {
    background-image: radial-gradient(rgba(15, 23, 42, 0.08) 1px, transparent 1px);
    background-size: 18px 18px;
    opacity: 0.25;
    mix-blend-mode: multiply;
    pointer-events: none;
}

.layanan-card {
    position: relative;
    border: 1px solid rgba(15, 23, 42, 0.08);
    background: #ffffff;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
}

.layanan-card::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 0.5rem;
    opacity: 0.65;
    pointer-events: none;
}

.layanan-card.card-orange::after {
    background: radial-gradient(120% 120% at 0% 0%, rgba(249, 115, 22, 0.28), transparent 60%);
}

.layanan-card.card-blue::after {
    background: radial-gradient(120% 120% at 0% 0%, rgba(59, 130, 246, 0.26), transparent 60%);
}

.layanan-card.card-slate::after {
    background: radial-gradient(120% 120% at 0% 0%, rgba(100, 116, 139, 0.24), transparent 60%);
}

.layanan-card.card-green::after {
    background: radial-gradient(120% 120% at 0% 0%, rgba(34, 197, 94, 0.24), transparent 60%);
}

.layanan-card.card-amber::after {
    background: radial-gradient(120% 120% at 0% 0%, rgba(245, 158, 11, 0.26), transparent 60%);
}

.layanan-card.card-cyan::after {
    background: radial-gradient(120% 120% at 0% 0%, rgba(6, 182, 212, 0.26), transparent 60%);
}

.layanan-card.card-rose::after {
    background: radial-gradient(120% 120% at 0% 0%, rgba(244, 63, 94, 0.24), transparent 60%);
}

.layanan-card.card-teal::after {
    background: radial-gradient(120% 120% at 0% 0%, rgba(20, 184, 166, 0.24), transparent 60%);
}

.chatbot-wrap {
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(15, 23, 42, 0.12);
    background:
        radial-gradient(900px 400px at 0% 0%, rgba(253, 186, 116, 0.55), transparent 60%),
        radial-gradient(700px 360px at 100% 20%, rgba(251, 113, 133, 0.35), transparent 60%),
        linear-gradient(135deg, #9a3412 0%, #c2410c 45%, #f97316 100%);
    box-shadow: 0 18px 50px rgba(249, 115, 22, 0.35);
}

.chatbot-glow {
    position: absolute;
    inset: -20%;
    background: radial-gradient(circle at 30% 30%, rgba(253, 186, 116, 0.35), transparent 55%);
    opacity: 0.85;
    pointer-events: none;
}

.chatbot-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(148, 163, 184, 0.2);
    color: #e2e8f0;
    font-size: 11px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.chatbot-card {
    border: 1px solid rgba(148, 163, 184, 0.2);
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(8px);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
}

.chatbot-icon {
    background: linear-gradient(135deg, rgba(56, 189, 248, 0.2), rgba(16, 185, 129, 0.2));
    color: #e2e8f0;
}

.chatbot-cta {
    background: linear-gradient(135deg, #f97316, #fb7185);
    box-shadow: 0 12px 25px rgba(249, 115, 22, 0.35);
}

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

<div class="bg-gray-100">
    <header class="sticky top-0 z-0 h-[260px] sm:h-[290px]">
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ asset('img/layanan-img.jpg') }}" alt="Layanan Digital"
                class="h-full w-full object-cover object-center">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        <div
            class="relative z-10 mx-auto flex h-full max-w-6xl flex-col items-center justify-center px-2 text-center md:px-4">
            <div class="mt-16 sm:mt-20 reveal">
                <h1 class="text-2xl md:text-6xl font-extrabold tracking-[2px] text-white uppercase">Layanan Digital
                </h1>
                <p class="text-sm md:text-base text-white/80">
                    Pelayanan online untuk kebutuhan administrasi desa.
                </p>
            </div>
        </div>
    </header>

    <main class="relative z-10 -mt-[60px] sm:-mt-[50px]">
        <div class="relative h-16 w-full overflow-hidden">
            <div class="absolute -top-px left-1/2 h-[65px] w-[150%] -translate-x-1/2 rounded-t-[50%] bg-white">
            </div>
        </div>

        <div class="bg-white pb-16">
            <div class="relative z-10 mx-auto w-full md:px-12 px-2">

                <div class="reveal bg-white p-5">
                    <div class="mb-7 border-b border-gray-100 pb-5">
                        <p class="text-xs font-semibold uppercase tracking-[1.8px] text-orange-600">Keterangan Layanan
                            Surat
                            Digital</p>
                        <p class="mt-2 max-w-3xl text-sm leading-relaxed text-gray-600 md:text-base">
                            Layanan Surat Digital adalah fasilitas pengurusan surat secara online tanpa harus datang ke
                            kantor
                            desa. Warga dapat memilih jenis surat, melengkapi data yang diperlukan, lalu memantau
                            prosesnya
                            secara mudah.
                        </p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2" data-reveal-group>
                        <div
                            class="reveal-item group layanan-card card-orange rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-100 text-orange-600">
                                <i class="fa-solid fa-house-user text-lg"></i>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Domisili</h2>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan domisili untuk
                                kebutuhan
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
                            class="reveal-item group layanan-card card-blue rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                                <i class="fa-solid fa-hand-holding-heart text-lg"></i>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Tidak Mampu</h2>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan untuk pengajuan
                                bantuan
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
                            class="reveal-item group layanan-card card-slate rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gray-200 text-gray-700">
                                <i class="fa-solid fa-ribbon text-lg"></i>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Kematian</h2>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan kematian untuk
                                keperluan
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
                            class="reveal-item group layanan-card card-green rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-600">
                                <i class="fa-solid fa-baby text-lg"></i>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Kelahiran</h2>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan kelahiran untuk
                                pengurusan
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

                        <div
                            class="reveal-item group layanan-card card-amber rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                                <i class="fa-solid fa-briefcase text-lg"></i>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Usaha</h2>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan untuk keperluan
                                usaha dan
                                administrasi perizinan.</p>
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
                            class="reveal-item group layanan-card card-cyan rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-100 text-cyan-600">
                                <i class="fa-solid fa-user-check text-lg"></i>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Belum Menikah</h2>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan status belum menikah
                                untuk
                                kebutuhan administrasi.</p>
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
                            class="reveal-item group layanan-card card-rose rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                                <i class="fa-solid fa-magnifying-glass text-lg"></i>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Kehilangan</h2>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan kehilangan dokumen
                                atau
                                barang penting.</p>
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
                            class="reveal-item group layanan-card card-teal rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <div
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-teal-100 text-teal-600">
                                <i class="fa-solid fa-wallet text-lg"></i>
                            </div>
                            <h2 class="mt-4 text-lg font-bold text-gray-900">Surat Keterangan Penghasilan Orang Tua/Wali
                            </h2>
                            <p class="mt-2 text-sm leading-relaxed text-gray-600">Surat keterangan penghasilan untuk
                                kebutuhan
                                pendidikan atau administrasi.</p>
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

                <div class="reveal chatbot-wrap mt-8 rounded-xl p-7">
                    <div class="chatbot-glow"></div>
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                        <div class="max-w-2xl reveal-left">
                            <p class="chatbot-badge">
                                <i class="fa-solid fa-wand-magic-sparkles text-[12px]"></i>
                                Keunggulan Chatbot
                            </p>
                            <h2 class="mt-3 text-2xl md:text-3xl font-bold text-white">Chatbot siap bantu 24 / 7</h2>
                            <p class="mt-3 text-sm md:text-base text-slate-200 leading-relaxed">
                                Chatbot desa membantu warga mencari informasi layanan, persyaratan surat, dan panduan
                                langkah
                                demi langkah. Warga juga bisa mengajukan surat keterangan melalui chatbot.

                            </p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                        <div class="reveal-item chatbot-card rounded-lg p-5">
                            <div class="chatbot-icon inline-flex h-10 w-10 items-center justify-center rounded-xl">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <h3 class="mt-4 text-base font-semibold text-white">Jawaban Instan</h3>
                            <p class="mt-2 text-sm text-slate-200 leading-relaxed">
                                Dapatkan jawaban cepat atas pertanyaan umum tanpa menunggu jam layanan kantor.
                            </p>
                        </div>
                        <div class="reveal-item chatbot-card rounded-lg p-5">
                            <div class="chatbot-icon inline-flex h-10 w-10 items-center justify-center rounded-xl">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <h3 class="mt-4 text-base font-semibold text-white">Panduan Lengkap</h3>
                            <p class="mt-2 text-sm text-slate-200 leading-relaxed">
                                Menjelaskan syarat, alur, dan dokumen yang diperlukan secara ringkas dan jelas.
                            </p>
                        </div>
                        <div class="reveal-item chatbot-card rounded-lg p-5">
                            <div class="chatbot-icon inline-flex h-10 w-10 items-center justify-center rounded-xl">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <h3 class="mt-4 text-base font-semibold text-white">Aman & Terarah</h3>
                            <p class="mt-2 text-sm text-slate-200 leading-relaxed">
                                Mengarahkan warga ke layanan resmi dan informasi yang sesuai kebijakan desa.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('dashboard.chatbot') }}"
                            class="chatbot-cta inline-flex items-center gap-2 rounded-md px-5 py-2.5 text-xs font-semibold text-white transition hover:-translate-y-0.5">
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
        </div>
    </main>
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