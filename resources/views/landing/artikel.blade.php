@extends('layouts.app')

@section('title', config('app.name') . ' | Artikel')

@push('styles')
<style>
/* Section Reveal Animations */
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
    <div class="absolute inset-x-0 top-0 z-0 h-[260px] sm:h-[290px] overflow-hidden">
        <img src="{{ asset('img/artikel-img.jpg') }}" alt="Artikel Desa"
            class="h-full w-full object-cover object-center">
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <div
        class="absolute left-1/2 -translate-x-1/2 top-[200px] sm:top-[230px] w-[140%] h-32 bg-gray-100 rounded-t-[100%]">
    </div>

    <div class="relative z-10 mx-auto w-full max-w-6xl px-2 md:px-4">

        <div class="mb-10 text-center mt-0 md:mt-6 reveal reveal-delay-1">
            <h1 class="text-3xl md:text-6xl font-extrabold tracking-[0.08em] text-white uppercase">Artikel Desa</h1>
            <p class="text-sm md:text-base text-white/80">Informasi artikel dan ulasan kegiatan desa.</p>
        </div>

        <div class="py-5 sm:hidden reveal reveal-delay-3">
            <form method="GET" action="{{ route('artikel') }}"
                class="flex items-center gap-2 rounded-lg bg-white p-2 shadow-sm">
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari artikel..."
                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-orange-500 focus:outline-none">
                <button type="submit" class="rounded-md bg-orange-500 px-3 py-2 text-xs font-semibold text-white">
                    Cari
                </button>
            </form>
        </div>

        @if($articles->count())
        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 reveal reveal-delay-2"
            data-reveal-group>
            @foreach($articles as $item)
            <article
                class="reveal-item group bg-white overflow-hidden border border-1 shadow-sm hover:shadow-sm transition duration-300">
                <a href="{{ route('artikel.show', $item->slug) }}" class="relative block overflow-hidden">
                    <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('img/logo_TA.png') }}"
                        alt="{{ $item->judul }}" class="h-48 sm:h-52 lg:h-64 w-full object-cover">
                    <div class="absolute inset-0 bg-black opacity-0 transition duration-300 group-hover:opacity-70">
                    </div>
                    <span
                        class="absolute inset-0 flex items-center justify-center md:text-4xl text-2xl font-light text-white opacity-0 transition duration-300 group-hover:opacity-100">
                        <i class="fa-solid fa-plus font-medium"></i>
                    </span>
                </a>

                <div class="p-5 sm:p-6 sm:p-7">
                    <p class="text-sm text-gray-500 mb-3 sm:mb-4 tracking-[1px]">
                        {{ $item->created_at->format('d/m/Y') }}</p>

                    <a href="{{ route('artikel.show', $item->slug) }}"
                        class="text-xl sm:text-3xl lg:text-3xl font-extrabold uppercase tracking-[1px] text-gray-800 hover:text-orange-500 hover:underline">
                        {{ \Illuminate\Support\Str::limit($item->judul, 100) }}
                    </a>

                    <p class="mt-4 sm:mt-5 text-base lg:text-lg leading-relaxed text-gray-600 tracking-[0.5px]">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 180) }}
                    </p>

                    <a href="{{ route('artikel.show', $item->slug) }}"
                        class="inline-block mt-6 text-sm sm:text-md font-semibold text-gray-800 hover:text-orange-500 hover:underline">
                        Lihat Detail
                    </a>
                </div>
            </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $articles->links() }}
        </div>
        @else
        <div class="bg-white p-8 shadow text-center text-gray-600">
            Belum ada artikel yang dipublikasikan.
        </div>
        @endif
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