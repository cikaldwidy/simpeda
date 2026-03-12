@extends('layouts.app')

@section('title', config('app.name') . ' | Kontak')

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
        <img src="{{ asset('img/kontak-img.jpg') }}" alt="Kontak Desa" class="h-full w-full object-cover object-center">
        <div class="absolute inset-0 bg-black/70"></div>
    </div>
    <div
        class="absolute left-1/2 -translate-x-1/2 top-[200px] sm:top-[230px] w-[140%] h-32 bg-gray-100 rounded-t-[100%]">
    </div>

    <div class="relative z-10 mx-auto w-full max-w-6xl px-2 md:px-4">
        <div class="mb-6 sm:mb-10 text-center mt-0 md:mt-6 reveal reveal-delay-1">
            <h1 class="text-2xl md:text-6xl font-extrabold tracking-[2px] text-white uppercase">Kontak Desa</h1>
            <p class="text-sm md:text-base text-white/80">
                Silakan hubungi kami untuk informasi layanan desa.
            </p>
        </div>

        <div
            class="mt-16 md:mt-0 rounded-xl border border-gray-200/80 bg-white p-6 shadow-sm md:p-10 reveal reveal-delay-2">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4" data-reveal-group>
                <div class="reveal-item rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold tracking-[2px] text-orange-500">ALAMAT</p>
                    <p class="mt-3 text-md font-medium text-slate-700">
                        Balai Desa Wonorejo, Kec. Sumbergempol, Kab. Tulungagung, Jawa Timur
                    </p>
                </div>

                <div class="reveal-item rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold tracking-[2px] text-orange-500">TELEPON</p>
                    <p class="mt-3 text-md font-medium text-slate-700">
                        (0355) 000000
                    </p>
                    <p class="text-sm text-slate-500">Senin - Jumat</p>
                </div>

                <div class="reveal-item rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold tracking-[2px] text-orange-500">EMAIL</p>
                    <p class="mt-3 text-sm font-medium text-slate-700 break-words">
                        pemdeswonorejo@gmail.com
                    </p>
                </div>

                <div class="reveal-item rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-bold tracking-[2px] text-orange-500">JAM PELAYANAN</p>
                    <p class="mt-3 text-md font-medium text-slate-700">08.00 - 15.00 WIB</p>
                    <p class="text-sm text-slate-500">Sabtu, Minggu, dan hari libur nasional tutup</p>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="reveal-left rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold uppercase tracking-wide text-slate-800">Kirim Pesan</h2>
                    <p class="mt-2 text-sm text-slate-600">Isi formulir berikut, lalu tim kami akan menindaklanjuti
                        secepatnya.</p>

                    <form class="mt-6 space-y-4">
                        <div>
                            <label for="nama"
                                class="mb-1 block text-xs font-semibold tracking-[1px] text-slate-600">Nama
                                Lengkap</label>
                            <input id="nama" type="text" placeholder="Masukkan nama Anda"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-0">
                        </div>

                        <div>
                            <label for="email"
                                class="mb-1 block text-xs font-semibold tracking-[1px] text-slate-600">Email</label>
                            <input id="email" type="email" placeholder="nama@email.com"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-0">
                        </div>

                        <div>
                            <label for="telepon"
                                class="mb-1 block text-xs font-semibold tracking-[1px] text-slate-600">No.
                                Telepon</label>
                            <input id="telepon" type="text" placeholder="08xxxxxxxxxx"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-0">
                        </div>

                        <div>
                            <label for="pesan"
                                class="mb-1 block text-xs font-semibold tracking-[1px] text-slate-600">Pesan</label>
                            <textarea id="pesan" rows="5" placeholder="Tulis pertanyaan atau pesan Anda"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-orange-400 focus:outline-none focus:ring-0"></textarea>
                        </div>

                        <button type="button"
                            class="inline-flex items-center rounded-md bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-400">
                            Kirim Pesan
                        </button>
                    </form>
                </div>

                <div class="reveal-right space-y-6">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <iframe
                            src="https://maps.google.com/maps?q=Wonorejo%20Sumbergempol%20Tulungagung&t=&z=13&ie=UTF8&iwloc=&output=embed"
                            class="h-[330px] w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi Desa Wonorejo"></iframe>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-bold uppercase tracking-wide text-slate-800">Kanal Cepat</h3>
                        <div class="mt-4 space-y-3 text-sm font-semibold text-slate-700">
                            <a href="https://wa.me/620000000000" target="_blank" rel="noopener"
                                class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 transition hover:border-orange-300 hover:bg-orange-50">
                                <span>WhatsApp Layanan</span>
                                <span>-></span>
                            </a>
                            <a href="{{ route('layanan') }}"
                                class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 transition hover:border-orange-300 hover:bg-orange-50">
                                <span>Layanan Digital</span>
                                <span>-></span>
                            </a>
                            <a href="mailto:pemdeswonorejo@example.id"
                                class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 transition hover:border-orange-300 hover:bg-orange-50">
                                <span>Email Resmi Desa</span>
                                <span>-></span>
                            </a>
                        </div>
                    </div>
                </div>
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