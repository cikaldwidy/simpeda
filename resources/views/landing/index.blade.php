@extends('layouts.app')

@section('title', config('app.name') . ' | Beranda')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/index-style.css') }}">
<style>
/* Chatbot Popup (Landing) */
.animated-grid {
    background-image:
        linear-gradient(to right, rgba(249, 115, 22, .05) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(249, 115, 22, .05) 1px, transparent 1px);
    background-size: 40px 40px;
    animation: gridMove 30s linear infinite;
}

@keyframes gridMove {
    0% {
        background-position: 0 0;
    }

    100% {
        background-position: 40px 40px;
    }
}

.typing-dot {
    animation: typingBounce 2s infinite ease-in-out;
}

.typing-dot:nth-child(2) {
    animation-delay: .3s;
}

.typing-dot:nth-child(3) {
    animation-delay: .6s;
}

@keyframes typingBounce {

    0%,
    70%,
    100% {
        transform: translateY(0);
        opacity: .3;
    }

    35% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

.slide-in-left {
    animation: slideInLeft .30s ease-out;
}

.slide-in-right {
    animation: slideInRight .30s ease-out;
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.btn-glow:hover {
    box-shadow: 0 0 20px rgba(249, 115, 22, .4), 0 4px 12px rgba(0, 0, 0, .3);
}

.quick-btn {
    transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
}

.quick-btn:hover {
    transform: translateY(-2px);
}

#chat-messages-public::-webkit-scrollbar,
#quick-actions-panel::-webkit-scrollbar {
    width: 6px;
}

#chat-messages-public::-webkit-scrollbar-track,
#quick-actions-panel::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, .1);
    border-radius: 10px;
}

#chat-messages-public::-webkit-scrollbar-thumb,
#quick-actions-panel::-webkit-scrollbar-thumb {
    background: rgba(249, 115, 22, .4);
    border-radius: 10px;
}

#chat-messages-public::-webkit-scrollbar-thumb:hover,
#quick-actions-panel::-webkit-scrollbar-thumb:hover {
    background: rgba(249, 115, 22, .6);
}

.shimmer {
    position: relative;
    overflow: hidden;
}

.shimmer::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(249, 115, 22, .1), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }

    100% {
        left: 100%;
    }
}

/* ===== SECTION REVEAL ANIMATIONS ===== */

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

<!-- konten 1 -->
<section id="hero" class="relative w-full overflow-hidden min-h-[520px] h-screen">
    <div id="heroSlider" class="absolute inset-0 w-full h-full">
        @forelse ($heroSlides as $i => $s)
        <div class="hero-slide absolute inset-0 w-full h-full {{ $i === 0 ? 'is-active' : '' }}"
            aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">

            <img src="{{ Storage::url($s->image_path) }}" alt="Slide {{ $i + 1 }}"
                loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                class="hero-img absolute inset-0 w-full h-full object-cover object-center {{ $i === 0 ? 'kenburns' : '' }}">

            <!-- overlay -->
            <div class="absolute inset-0 bg-black/30 z-10"></div>
            <!-- gradient bawah -->
            <div class="hero-bottom-bar"></div>
            <div class="absolute bottom-10 left-0 right-0">
                <div class="relative z-30 mx-auto w-full max-w-6xl px-4">
                    <h2 class="text-2xl sm:text-2xl font-extrabold uppercase tracking-wide text-white ">
                        {{ $s->title }}
                    </h2>
                    <p class="mt-3 text-sm sm:text-base font-medium text-white">
                        {{ $s->description }}
                    </p>
                </div>
            </div>
        </div>
        @empty
        <div class="hero-slide absolute inset-0 w-full h-full is-active">
            <img src="/" alt="Default Slide" class="absolute inset-0 w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-black/25"></div>

            <div class="absolute bottom-10 inset-x-0 z-20">
                <div class="mx-auto w-full max-w-6xl px-4 ">
                    <h2 class="text-3xl sm:text-5xl font-extrabold uppercase tracking-wide text-white drop-shadow">
                        SLIDE BELUM DIISI
                    </h2>
                    <p class="mt-3 text-sm sm:text-base font-medium text-white/85 drop-shadow">
                        Tambahkan data slide lewat menu admin.
                    </p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
    <div class="absolute inset-0 z-30 pointer-events-none">
        <div class="mx-auto w-full max-w-6xl px-4 h-full relative">

            <!-- tombol prev -->
            <button id="heroPrev" type="button" aria-label="Slide sebelumnya"
                class="hero-nav pointer-events-auto absolute left-0 top-1/2 -translate-y-1/2">
                <svg class="h-16 w-16 text-white/70 hover:text-white transition" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                </svg>
            </button>

            <!-- tombol next -->
            <button id="heroNext" type="button" aria-label="Slide berikutnya"
                class="hero-nav pointer-events-auto absolute right-0 top-1/2 -translate-y-1/2">
                <svg class="h-16 w-16 text-white/70 hover:text-white transition" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6" />
                </svg>
            </button>

            <!-- dots (angka) -->
            <div id="heroDots" class="pointer-events-auto absolute bottom-10 right-4 hero-dots 
                        hidden md:flex transition-opacity duration-300">
            </div>
        </div>
    </div>
</section>

<!-- konten 2 -->
<div class="max-w-7xl mx-auto reveal">
    <div class="grid grid-cols-2 md:grid-cols-4 bg-orange-100 shadow" data-reveal-group>

        <!-- PROFILE -->
        <a href="#" class="reveal-item group flex flex-col items-center justify-center py-10
                  border-b border-r md:border-b-0 md:border-r border-orange-500
                  hover:bg-white transition">
            <div class="text-orange-500 mb-4 text-5xl
                        transition duration-300 group-hover:scale-125 group-hover:text-orange-500">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <span
                class="text-orange-500 font-semibold tracking-widest uppercase text-center group-hover:text-orange-500 transition">
                Profile
            </span>
        </a>

        <!-- BERITA -->
        <a href="#" class="reveal-item group flex flex-col items-center justify-center py-10
                  border-b border-r-0 md:border-b-0 md:border-r border-orange-500
                  hover:bg-white transition">
            <div class="text-orange-500 mb-4 text-5xl
                        transition duration-300 group-hover:scale-125 group-hover:text-orange-500">
                <i class="fa-solid fa-newspaper"></i>
            </div>
            <span
                class="text-orange-500 font-semibold tracking-widest uppercase text-center group-hover:text-orange-500 transition">
                Berita
            </span>
        </a>

        <!-- ASPIRASI -->
        <a href="#" class="reveal-item group flex flex-col items-center justify-center py-10
                  border-r md:border-r border-orange-500
                  hover:bg-white transition text-center px-4">
            <div class="text-orange-500 mb-4 text-5xl
                        transition duration-300 group-hover:scale-125 group-hover:text-orange-500">
                <i class="fa-solid fa-users"></i>
            </div>
            <span
                class="text-orange-500 font-semibold tracking-widest uppercase leading-tight group-hover:text-orange-500 transition">
                Aspirasi Warga
            </span>
        </a>

        <!-- HALLO -->
        <a href="#" class="reveal-item group flex flex-col items-center justify-center py-10
                  border-r-0 md:border-r border-orange-500
                  hover:bg-white transition text-center px-4">
            <div class="text-orange-500 mb-4 text-5xl
                        transition duration-300 group-hover:scale-125 group-hover:text-orange-500">
                <i class="fa-solid fa-phone"></i>
            </div>
            <span
                class="text-orange-500 font-semibold tracking-widest uppercase text-center group-hover:text-orange-500 transition">
                Hallo Desa
            </span>
        </a>

    </div>
</div>

<!-- konten 3 -->
<section id="sambutan" class="py-10 px-5 relative overflow-hidden reveal">

    <div class="mx-auto w-full max-w-6xl px-2 md:px-4 relative z-10">

        {{-- HEADER --}}
        <div class="mb-12 text-center ">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-wide">
                SAMBUTAN KEPALA DESA
            </h2>
            <div class="mx-auto mt-2 h-[4px] w-10 bg-orange-500"></div>
        </div>

        <div class="grid grid-cols-1 items-center gap-12 md:grid-cols-2">

            {{-- FOTO --}}
            <div class="relative reveal-left">

                <div class="relative z-10 overflow-hidden">
                    <img src="{{ asset('img/kepala_desaaa.png') }}" alt="Kepala Desa"
                        class="w-full max-w-sm object-cover aspect-[4/5]" />
                </div>

                {{-- Badge jabatan --}}
                <div class="absolute -bottom-5 left-6 z-20
                    bg-orange-500 text-white px-5 py-2 shadow-lg">
                    <p class="text-xs tracking-[3px] font-semibold uppercase">Kepala Desa</p>
                </div>

            </div>

            {{-- TEKS SAMBUTAN --}}
            <div class="relative pt-6 reveal-right">

                {{-- Tanda kutip dekoratif --}}
                <div class="text-[70px] font-extrabold text-slate-400 leading-none select-none">
                    <i class="fa-solid fa-quote-left"></i>
                </div>

                <p class="relative z-10 text-base leading-8 text-gray-700 text-justify">
                    Selamat datang di Website resmi Pemerintah Desa Wonorejo Kecamatan Sumbergempol
                    Kabupaten Tulungagung Provinsi Jawa Timur. Ini adalah ruang media informasi
                    desa sebagai sarana komunikasi dan keterbukaan informasi publik.
                    Jangan lupa selalu ikuti website dan media sosial kami untuk update
                    informasi dalam penyelenggaraan pemerintahan di Desa Wonorejo.
                    Terima kasih sudah mengunjungi website kami. Semoga bermanfaat,
                    kritik dan saran selalu kami harapkan untuk desa yang lebih baik.
                </p>

                {{-- Garis pemisah --}}
                <div class="my-8 flex items-center gap-3">
                    <div class="h-[2px] w-8 bg-orange-500 rounded-full"></div>
                    <div class="h-[2px] flex-1 bg-gray-100 rounded-full"></div>
                </div>

                {{-- Nama & jabatan --}}
                <div class="flex items-center gap-4">
                    <div class="w-1 h-14 bg-orange-500 rounded-full shrink-0"></div>
                    <div>
                        <p class="font-signature text-4xl text-gray-900 leading-tight">
                            Anis Wijayanti
                        </p>
                        <p class="mt-1 text-xs tracking-[3px] text-gray-500 uppercase">
                            Kepala Desa Wonorejo
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- konten 4 -->
<section class="relative py-12 px-5 text-center overflow-hidden bg-white reveal">

    <div class="relative z-10 max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col items-center mb-10">

            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-wide">
                SOTK
            </h2>

            <div class="mt-2 inline-flex items-center gap-2">
                <span class="text-gray-600 text-xs font-semibold tracking-[0.25em] uppercase">Struktur Organisasi
                    dan Tata Kerja Pemerintah Desa</span>
            </div>
            <div class="mx-auto mt-3 h-[3px] w-12 bg-orange-400"></div>
        </div>

        @php
        $perangkatHome = $perangkat;
        @endphp
        {{-- HORIZONTAL SCROLL: semua perangkat --}}
        <div class="mt-8 reveal">
            <div id="sotk-scroll" class="flex gap-5 overflow-x-auto px-2 pb-4 scrollbar-hide snap-x snap-mandatory"
                data-reveal-group>

                @forelse($perangkatHome as $item)
                <div class="reveal-item relative min-w-[220px] sm:min-w-[240px] h-[300px] overflow-hidden bg-white/95
                    shadow-2xl transition-all duration-500
                     hover:-translate-y-2 group
                    rounded-md flex-shrink-0 snap-start">

                    <img src="{{ asset('storage/'.$item->foto) }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 reveal-up">

                    {{-- Hover overlay — nama & jabatan hanya muncul saat hover --}}
                    <div class="absolute inset-0 bg-black
                        flex flex-col justify-center items-center
                        opacity-0 group-hover:opacity-90
                        transition duration-300 text-white p-4">

                        <div class="w-8 h-[2px] bg-orange-300 mb-3"></div>
                        <h3 class="text-lg font-semibold uppercase">
                            {{ $item->nama }}
                        </h3>
                        <span class="text-sm opacity-80 capitalize mt-1">
                            {{ $item->jabatan }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-slate-500">Data belum tersedia</p>
                @endforelse

            </div>
        </div>

        {{-- Link selengkapnya --}}
        <div class="mt-10 flex justify-center">
            <a href="#" class="group inline-flex items-center gap-2 text-gray-800 font-semibold
              text-sm sm:text-base border-b-2 border-orange-500 pb-1
              hover:text-orange-500 transition-all duration-300">
                <span>Lihat Selengkapnya</span>
                <span class="inline-block transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>

    </div>
</section>

<!-- konten 5 -->
<section class="py-5 reveal">
    <div class="max-w-6xl mx-auto px-4">

        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-wide text-center">
            BERITA TERKINI
        </h2>
        <div class="mx-auto mt-2 h-[4px] w-10 bg-orange-500"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mt-5 md:mt-10" data-reveal-group>

            @foreach($berita->take(6) as $item)
            <div class="reveal-item bg-white shadow-lg overflow-hidden group
                        transition duration-300 hover:shadow-2xl">

                <div class="relative overflow-hidden">

                    <!-- IMAGE -->
                    <img src="{{ asset('storage/'.$item->gambar) }}" class="w-full h-72 object-cover 
                                transition duration-500 group-hover:scale-110 reveal-up">

                    <!-- DATE (ORANGE) -->
                    <div class="absolute top-4 left-4 bg-orange-500 text-white 
                                px-3 py-2 text-sm font-bold rounded-md shadow z-30">
                        <div class="text-lg leading-none">
                            {{ $item->created_at->format('d') }}
                        </div>
                        <div class="text-xs uppercase">
                            {{ $item->created_at->format('M') }}
                        </div>
                    </div>

                    <!-- OVERLAY -->
                    <div class="absolute inset-0 bg-black/70 
                                flex items-center justify-center
                                opacity-0 group-hover:opacity-100 
                                transition duration-300">

                        <a href="{{ route('berita.show', $item->slug) }}" class="text-white text-md font-medium 
                                  tracking-wide hover:underline transition">
                            Selengkapnya &rarr;
                        </a>

                    </div>

                </div>

                <!-- CONTENT -->
                <div class=" py-4 px-8">

                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 
                               group-hover:text-orange-500 transition uppercase hover:underline tracking-[1px]">
                        {{ $item->judul }}
                    </h3>

                    <p class="text-gray-700 text-sm md:text-md font-semibold leading-relaxed tracking-[0.5px]">
                        {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 200) }}
                    </p>

                </div>

            </div>
            @endforeach

        </div>
    </div>
    <div class="mt-10 flex justify-center">
        <a href="{{route ('berita')}}" class="group inline-flex items-center gap-2 text-gray-800 font-semibold
              text-sm sm:text-base border-b-2 border-orange-500 pb-1
              hover:text-orange-500 transition-all duration-300">
            <span>Lihat Selengkapnya</span>
            <span class="inline-block transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
        </a>
    </div>
</section>


<!-- konten 6 -->
<section class="relative py-10 px-5 overflow-hidden bg-white reveal">
    <div class="absolute inset-0 opacity-[0.04]"
        style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.12) 1px, transparent 0); background-size: 28px 28px;">
    </div>

    <div class="relative z-10 max-w-5xl mx-auto text-center">
        <p class="text-xs sm:text-sm font-semibold tracking-[0.3em] text-gray-500 uppercase">
            Support By
        </p>
        <h3 class="mt-3 text-2xl sm:text-3xl font-bold text-gray-900 tracking-wide">
            Universitas Bhinneka PGRI
        </h3>
        <div class="mx-auto mt-4 h-[3px] w-16 bg-orange-400"></div>
    </div>
</section>
@include('partials.footer')

<div class="fixed bottom-4 right-5 z-50">
    <button id="chatToggle" type="button"
        class="group relative flex items-center gap-3 rounded-full pl-4 pr-5 py-1 text-sm font-semibold text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl"
        style="background: linear-gradient(135deg, #f97316, #ea580c); box-shadow: 0 5px 15px rgba(249,115,22,0.45);"
        aria-expanded="false" aria-controls="chatPopup">

        {{-- Bot icon --}}
        <img src="{{ asset('img/cs2.png') }}" alt="Chatbot Simpeda" class="h-auto flex w-7 rounded-full object-contain">
        <span class="font-medium tracking-[1px] text-sm">Ada yang bisa dibantu?</span>
    </button>
</div>

{{-- ===================== CHATBOT POPUP (STATIC) ===================== --}}
<div id="chatPopup"
    class="fixed z-50 w-[94vw] max-w-md rounded-xl bottom-4 left-0 right-0 mx-auto md:bottom-24 md:right-6 md:left-auto md:mx-0"
    style="display:none; border: 1px solid rgba(249,115,22,0.15); box-shadow: 0 20px 60px rgba(0,0,0,0.25);">
    <div
        class="relative flex h-[520px] max-h-[70vh] w-full flex-col overflow-hidden rounded-xl bg-gradient-to-br from-neutral-900 via-neutral-800 to-neutral-900 shadow-2xl">
        <div class="pointer-events-none absolute inset-0 animated-grid opacity-30"></div>
        <div class="pointer-events-none absolute -left-16 -top-16 h-48 w-48 rounded-full bg-orange-500/5 blur-3xl">
        </div>
        <div class="pointer-events-none absolute -bottom-16 -right-16 h-48 w-48 rounded-full bg-orange-600/5 blur-3xl">
        </div>

        <div class="relative flex min-h-0 flex-1 flex-col">
            <div
                class="shimmer overflow-hidden border-b border-neutral-800 bg-gradient-to-r from-orange-600 via-orange-500 to-orange-600 px-4 py-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/cs.png') }}" alt="Chatbot Simpeda"
                            class="h-10 w-10 rounded-full object-contain">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-orange-100">Chatbot</p>
                            <h5 class="text-sm font-bold text-white uppercase">Layanan Chatbot Desa Wonorejo</h5>
                        </div>
                    </div>
                    <button id="chatClose" type="button"
                        class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition-colors duration-200"
                        aria-label="Tutup chat">
                        <svg class="h-3.5 w-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex min-h-0 flex-1 flex-col p-3">
                <div id="chat-messages-public" class="mb-4 flex-1 space-y-4 overflow-y-auto pr-2">
                    <div class="slide-in-left flex items-start gap-3">
                        <img src="{{ asset('img/cs.png') }}" alt="Chatbot Simpeda"
                            class="h-8 w-8 rounded-full object-contain">
                        <div
                            class="max-w-[85%] rounded-3xl rounded-tr-sm bg-black px-4 py-3 text-sm font-medium text-white">
                            <p class="mb-2 text-xs font-bold text-gray-500">Nara</p>
                            <p class="text-xs leading-relaxed text-neutral-200">
                                Hai, saya <span class="font-bold text-white">Nara</span>. Ada yang bisa saya bantu?
                            </p>
                        </div>
                    </div>

                </div>

                <div class="rounded-md">
                    <form id="chat-form-public" class="flex items-center gap-2">
                        <input id="chat-input-public" type="text" placeholder="Ketik pertanyaan..."
                            class="w-full rounded-md border border-neutral-700 bg-neutral-800 px-3 py-2 text-xs text-neutral-100 placeholder:text-neutral-500 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition-all" />
                        <button id="chat-send-public" type="submit"
                            class="btn-glow flex items-center justify-center gap-1 rounded-md bg-gradient-to-r from-orange-600 to-orange-500 px-4 py-2 text-xs font-semibold text-white shadow-lg transition-all hover:from-orange-500 hover:to-orange-400 active:scale-95 whitespace-nowrap">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span class="hidden sm:inline">Kirim</span>
                        </button>
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.__heroSliderInited) return;
    window.__heroSliderInited = true;

    const slider = document.getElementById('heroSlider');
    const slides = Array.from(slider.querySelectorAll('.hero-slide'));
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    const dotsWrap = document.getElementById('heroDots');

    const INTERVAL = 5000;
    let idx = 0;
    let timer = null;

    /* ===== BUILD DOTS ANGKA ===== */
    function buildDots() {
        if (!dotsWrap || slides.length === 0) return;

        dotsWrap.innerHTML = `
            <span class="current">1</span>
            <span class="separator"></span>
            <span class="total">${slides.length}</span>
        `;
    }

    function updateDots(index) {
        const current = dotsWrap?.querySelector('.current');
        if (current) current.textContent = index + 1;
    }

    /* ===== SET ACTIVE SLIDE ===== */
    function setActive(i) {
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

        updateDots(idx);
    }

    function next() {
        setActive(idx + 1);
    }

    function prev() {
        setActive(idx - 1);
    }

    function stop() {
        clearInterval(timer);
        timer = null;
    }

    function start() {
        stop();
        if (slides.length > 1) {
            timer = setInterval(next, INTERVAL);
        }
    }

    /* ===== EVENTS ===== */
    nextBtn?.addEventListener('click', () => {
        next();
        start();
    });

    prevBtn?.addEventListener('click', () => {
        prev();
        start();
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') next();
        if (e.key === 'ArrowLeft') prev();
        start();
    });

    document.addEventListener('visibilitychange', () => {
        document.hidden ? stop() : start();
    });

    /* ===== INIT ===== */
    buildDots();
    setActive(0);
    setTimeout(start, 200);
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('chat-form-public');
    const input = document.getElementById('chat-input-public');
    const sendBtn = document.getElementById('chat-send-public');
    const list = document.getElementById('chat-messages-public');
    if (!form || !input || !sendBtn || !list) return;

    const csrf = document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content') || '';

    function addUserMessage(text) {
        const wrap = document.createElement('div');
        wrap.className = 'slide-in-right flex justify-end';
        wrap.innerHTML =
            `<div class=\"max-w-[85%] whitespace-pre-line rounded-2xl rounded-tr-sm border border-orange-600/30 bg-gradient-to-br from-orange-600 to-orange-700 px-4 py-3 text-xs font-medium text-white shadow-lg shadow-orange-500/20\">${escapeHtml(text)}</div>`;
        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    function addBotMessage(text) {
        const wrap = document.createElement('div');
        wrap.className = 'slide-in-left flex items-start gap-3';
        wrap.innerHTML =
            `<img src=\"{{ asset('img/cs.png') }}\" alt=\"Chatbot Simpeda\" class=\"h-8 w-8 rounded-full object-contain\">
             <div class=\"max-w-[85%] rounded-3xl rounded-tr-sm bg-black px-4 py-3 text-sm font-medium text-white\">
                 <p class=\"mb-2 text-xs font-bold text-gray-500\">Nara</p>
                 <p class=\"text-xs leading-relaxed text-neutral-200\">${escapeHtml(text)}</p>
             </div>`;
        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    function escapeHtml(text) {
        return text
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    async function sendMessage(message) {
        addUserMessage(message);
        input.value = '';
        sendBtn.disabled = true;
        sendBtn.classList.add('opacity-50', 'cursor-not-allowed');

        try {
            const response = await fetch("{{ route('chatbot.public.message') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    message
                }),
            });

            const data = await response.json();
            addBotMessage(data.reply || 'Maaf, aku belum paham. Coba tanya dengan kalimat lain ya 🙏');
        } catch (error) {
            addBotMessage('Maaf, chatbot lagi bermasalah. Coba lagi ya 🙏');
        } finally {
            sendBtn.disabled = false;
            sendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            input.focus();
        }
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) return;
        sendMessage(message);
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-item, .reveal-up');
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

<script>
// ===== SOTK SCROLL ARAH =====
document.addEventListener('DOMContentLoaded', () => {
    const scroller = document.getElementById('sotk-scroll');
    if (!scroller) return;

    let lastScrollY = window.scrollY;
    let ticking = false;
    let paused = false;
    const STEP = 300;

    function handleScroll() {
        if (paused) {
            lastScrollY = window.scrollY;
            return;
        }

        const currentY = window.scrollY;
        const delta = currentY - lastScrollY;
        lastScrollY = currentY;

        const max = scroller.scrollWidth - scroller.clientWidth;
        if (max <= 0) return;

        if (delta > 0) {
            // scroll ke bawah → geser kanan
            scroller.scrollTo({
                left: Math.min(scroller.scrollLeft + STEP, max),
                behavior: 'smooth'
            });
        } else if (delta < 0) {
            // scroll ke atas → geser kiri
            scroller.scrollTo({
                left: Math.max(scroller.scrollLeft - STEP, 0),
                behavior: 'smooth'
            });
        }
    }

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                handleScroll();
                ticking = false;
            });
            ticking = true;
        }
    }, {
        passive: true
    });

    // Pause saat hover / touch pada scroller
    scroller.addEventListener('mouseenter', () => paused = true);
    scroller.addEventListener('mouseleave', () => {
        paused = false;
        lastScrollY = window.scrollY;
    });
    scroller.addEventListener('touchstart', () => paused = true, {
        passive: true
    });
    scroller.addEventListener('touchend', () => {
        paused = false;
        lastScrollY = window.scrollY;
    }, {
        passive: true
    });
});
</script>


<script>
(function() {
    const toggle = document.getElementById('chatToggle');
    const popup = document.getElementById('chatPopup');
    const closeBtn = document.getElementById('chatClose');
    if (!toggle || !popup || !closeBtn) return;

    function openChat() {
        popup.style.display = 'block';
        popup.style.opacity = '0';
        popup.style.transform = 'translateY(12px) scale(0.97)';
        popup.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
        requestAnimationFrame(() => {
            popup.style.opacity = '1';
            popup.style.transform = 'translateY(0) scale(1)';
        });
        toggle.setAttribute('aria-expanded', 'true');
    }

    function closeChat() {
        popup.style.opacity = '0';
        popup.style.transform = 'translateY(12px) scale(0.97)';
        setTimeout(() => {
            popup.style.display = 'none';
        }, 220);
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function() {
        popup.style.display === 'none' || popup.style.display === '' ?
            openChat() :
            closeChat();
    });

    closeBtn.addEventListener('click', closeChat);
})();
</script>
@endpush
