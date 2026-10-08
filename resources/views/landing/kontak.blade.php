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

<div class="bg-gray-100">
    <header class="sticky top-0 z-0 h-[260px] sm:h-[290px]">
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ asset('img/kontak-img2.jpg') }}" alt="Kontak Desa"
                class="h-full w-full object-cover object-center">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        <div
            class="relative z-10 mx-auto flex h-full max-w-6xl flex-col items-center justify-center px-2 text-center md:px-4">
            <div class="mt-16 sm:mt-20 reveal">
                <h1 class="text-2xl md:text-6xl font-extrabold tracking-[2px] text-white uppercase">Kontak Desa</h1>
                <p class="text-sm md:text-base text-white/80">
                    Silakan hubungi kami untuk informasi layanan desa.
                </p>
            </div>
        </div>
    </header>

    <main class="relative z-10 -mt-[60px] sm:-mt-[50px]">
        <div class="relative h-16 w-full overflow-hidden">
            <div class="absolute -top-px left-1/2 h-[65px] w-[150%] -translate-x-1/2 rounded-t-[50%] bg-gray-100">
            </div>
        </div>

        <div class="bg-gray-100 pb-16">
            <div class="relative z-10 mx-auto w-full md:px-12">
                <div class="reveal reveal-delay-2 bg-gray-100 p-5">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4" data-reveal-group>
                        <div class="reveal-item rounded-md border border-orange-200/70 bg-white/90 p-5 shadow-lg">
                            <p class="text-xs font-bold tracking-[2px] text-orange-600">ALAMAT</p>
                            <p class="mt-3 text-md font-medium text-gray-800 tracking-[.5px]">
                                Balai Desa Wonorejo, Kec. Sumbergempol, Kab. Tulungagung, Jawa Timur
                            </p>
                        </div>

                        <div class="reveal-item rounded-md border border-orange-200/70 bg-white/90 p-5 shadow-lg">
                            <p class="text-xs font-bold tracking-[2px] text-orange-600">TELEPON</p>
                            <p class="mt-3 text-md font-medium text-gray-800 tracking-[.5px]">
                                (0355) 000000
                            </p>
                            <p class="text-sm text-gray-500 tracking-[.5px]">Senin - Jumat</p>
                        </div>

                        <div class="reveal-item rounded-md border border-orange-200/70 bg-white/90 p-5 shadow-lg">
                            <p class="text-xs font-bold tracking-[2px] text-orange-600">EMAIL</p>
                            <p class="mt-3 text-md font-medium text-gray-800 tracking-[.5px]">
                                pemdeswonorejo@gmail.com
                            </p>
                        </div>

                        <div class="reveal-item rounded-md border border-orange-200/70 bg-white/90 p-5 shadow-lg ">
                            <p class="text-xs font-bold tracking-[2px] text-orange-600">JAM PELAYANAN</p>
                            <p class="mt-3 text-md font-medium text-gray-800 tracking-[.5px]">08.00 - 15.00 WIB</p>
                            <p class="text-sm text-gray-500 tracking-[.5px]">Sabtu, Minggu, dan hari libur nasional
                                tutup</p>
                        </div>
                    </div>

                    <div class="reveal-right mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div class="overflow-hidden rounded-xl bg-white/90 shadow-lg">
                                <iframe
                                    src="https://maps.google.com/maps?q=Wonorejo%20Sumbergempol%20Tulungagung&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                    class="h-[330px] w-full border-0" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade" title="Peta Desa Wonorejo"></iframe>
                            </div>

                            <div class="overflow-hidden rounded-xl bg-white/90 shadow-lg">
                                <iframe
                                    src="https://maps.google.com/maps?q=Balai%20Desa%20Wonorejo%20Sumbergempol%20Tulungagung&t=&z=16&ie=UTF8&iwloc=&output=embed"
                                    class="h-[330px] w-full border-0" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Peta Balai Desa Wonorejo"></iframe>
                            </div>
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