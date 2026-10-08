@extends('layouts.app')

@section('title', config('app.name') . ' | Berita')

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

<div class="bg-gray-100">
    <header class="sticky top-0 z-0 h-[260px] sm:h-[290px]">
        <div class="absolute inset-0 overflow-hidden">
            <img src="{{ asset('img/berita-img.jpg') }}" alt="Berita Desa"
                class="h-full w-full object-cover object-center">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        <div
            class="relative z-10 mx-auto flex h-full max-w-6xl flex-col items-center justify-center px-2 text-center md:px-4">
            <div class="mt-16 sm:mt-20 reveal">
                <h1 class="text-2xl md:text-6xl font-extrabold tracking-[2px] text-white uppercase">Berita Desa</h1>
                <p class="text-sm md:text-base text-white/80">Informasi terbaru kegiatan dan agenda desa.</p>
            </div>
        </div>
    </header>

    <main class="relative z-10 -mt-[60px] sm:-mt-[50px]">
        <div class="relative h-16 w-full overflow-hidden">
            <div class="absolute -top-px left-1/2 h-[65px] w-[150%] -translate-x-1/2 rounded-t-[50%] bg-gray-100"></div>
        </div>

        <div class="bg-gray-100 pb-16">
            <div class="relative z-10 mx-auto w-full px-2 md:px-12">

                <div class=" sm:hidden reveal-delay-3 p-5">
                    <form method="GET" action="{{ route('berita') }}"
                        class="flex items-center gap-2 rounded-md bg-white p-2 shadow-sm">
                        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari berita..."
                            class="w-full rounded-md px-3 py-2 text-sm text-slate-700 focus:border focus:border-orange-500 focus:outline-none focus:ring-0 focus:ring-orange-500">
                        <button type="submit"
                            class="rounded-md bg-orange-500 px-3 py-2 text-xs font-semibold text-white">
                            Cari
                        </button>
                    </form>
                </div>

                @if($news->count())
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 reveal reveal-delay-2"
                    data-reveal-group>
                    @foreach($news as $item)
                    <article
                        class="reveal-item group bg-white overflow-hidden shadow-md hover:shadow-xl transition duration-300">
                        <a href="{{ route('berita.show', $item->slug) }}" class="relative block overflow-hidden">
                            <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('img/logo_TA.png') }}"
                                alt="{{ $item->judul }}" class="h-48 sm:h-52 lg:h-64 w-full object-cover">
                            <div
                                class="absolute inset-0 bg-black opacity-0 transition duration-300 group-hover:opacity-70">
                            </div>
                            <span
                                class="absolute inset-0 flex items-center justify-center md:text-4xl text-2xl font-light text-white opacity-0 transition duration-300 group-hover:opacity-100">
                                <i class="fa-solid fa-plus font-medium"></i>
                            </span>
                        </a>

                        <div class="p-5 sm:p-6 sm:p-7">
                            <p class="text-sm text-gray-500 mb-3 sm:mb-4 tracking-[1px]">
                                {{ $item->created_at->format('d/m/Y') }}</p>

                            <a href="{{ route('berita.show', $item->slug) }}"
                                class="text-xl sm:text-3xl lg:text-3xl font-extrabold uppercase tracking-[1px] text-gray-800 hover:text-orange-500 hover:underline">
                                {{ \Illuminate\Support\Str::limit($item->judul, 100) }}
                            </a>

                            <p class="mt-4 sm:mt-5 text-base lg:text-lg leading-relaxed text-gray-600 tracking-[0.5px]">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 170) }}
                            </p>

                            <a href="{{ route('berita.show', $item->slug) }}"
                                class="inline-block mt-6 text-sm sm:text-md font-semibold text-gray-800 hover:text-orange-500 hover:underline">
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $news->links() }}
                </div>
                @else
                <div class="bg-white p-8 shadow text-center text-gray-600">
                    Belum ada berita yang dipublikasikan.
                </div>
                @endif
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