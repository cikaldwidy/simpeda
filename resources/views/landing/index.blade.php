@extends('layouts.app')

@section('title', config('app.name') . ' | Beranda')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/index-style.css') }}">
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
<div class="mx-auto reveal">
    <div class="grid grid-cols-2 md:grid-cols-4 bg-orange-50 shadow" data-reveal-group>

        <!-- PROFILE -->
        <a href="{{ route('profil') }}" class="reveal-item group flex flex-col items-center justify-center py-10
                 border-r-4 md:border-r-4 border-orange-200
                  hover:bg-white transition">
            <div class="text-indigo-900 mb-4 text-5xl
                        transition duration-300 group-hover:scale-125 group-hover:text-orange-500">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <span
                class="text-indigo-900 font-semibold tracking-widest uppercase text-center group-hover:text-orange-500 transition">
                Profile
            </span>
        </a>

        <!-- BERITA -->
        <a href="{{ route('berita') }}" class="reveal-item group flex flex-col items-center justify-center py-10
                 border-r-4 md:border-r-4 border-orange-200
                  hover:bg-white transition">
            <div class="text-indigo-900 mb-4 text-5xl
                        transition duration-300 group-hover:scale-125 group-hover:text-orange-500">
                <i class="fa-solid fa-newspaper"></i>
            </div>
            <span
                class="text-indigo-900 font-semibold tracking-widest uppercase text-center group-hover:text-orange-500 transition">
                Berita
            </span>
        </a>

        <!-- ASPIRASI -->
        <a href="{{ route('aspirasi') }}" class="reveal-item group flex flex-col items-center justify-center py-10
                 border-r-4 md:border-r-4 border-orange-200
                  hover:bg-white transition">
            <div class="text-indigo-900 mb-4 text-5xl
                        transition duration-300 group-hover:scale-125 group-hover:text-orange-500">
                <i class="fa-solid fa-users"></i>
            </div>
            <span
                class="text-indigo-900 font-semibold tracking-widest uppercase leading-tight group-hover:text-orange-500 transition">
                Aspirasi Warga
            </span>
        </a>

        <!-- HALLO -->
        <a href="#" class="reveal-item group flex flex-col items-center justify-center py-10
                  border-r-4 md:border-r-4 border-orange-200
                  hover:bg-white transition">
            <div class="text-indigo-900 mb-4 text-5xl
                        transition duration-300 group-hover:scale-125 group-hover:text-orange-500">
                <i class="fa-solid fa-phone"></i>
            </div>
            <span
                class="text-indigo-900 font-semibold tracking-widest uppercase text-center group-hover:text-orange-500 transition">
                Hallo Desa
            </span>
        </a>

    </div>
</div>

<!-- konten 3 -->
<section id="sambutan" class="py-10 px-5 relative overflow-hidden">

    <div class="mx-auto w-full px-2 md:px-6 relative z-10">

        {{-- HEADER --}}
        <div class="mb-12 text-center ">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-wide reveal">
                SAMBUTAN KEPALA DESA
            </h2>
            <div class="mx-auto mt-2 h-[4px] w-10 bg-orange-500 reveal"></div>
        </div>

        <div class="grid grid-cols-1 items-center gap-12 md:grid-cols-2">

            {{-- FOTO --}}
            <div class="relative reveal-left">

                <div class="relative z-10 overflow-hidden">
                    <img src="{{ asset('img/kepala_desaa.png') }}" alt="Kepala Desa"
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
<section class="relative py-12 px-5 text-center overflow-hidden bg-white">

    <div class="relative z-10 mx-auto px-2 md:px-6">

        {{-- Header --}}
        <div class="flex flex-col items-center mb-10 reveal">

            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-wide">
                SOTK
            </h2>

            <div class="mt-2 inline-flex items-center gap-2">
                <span class="text-gray-600 text-xs font-semibold tracking-[0.25em] uppercase">Struktur
                    Organisasi
                    dan Tata Kerja Pemerintah Desa</span>
            </div>
            <div class="mx-auto mt-3 h-[3px] w-12 bg-orange-400 reveal"></div>
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
        <div class="mt-10 flex justify-center reveal-left">
            <a href="{{ route('profil') }}" class="group inline-flex items-center gap-2 text-gray-800 font-semibold
              text-sm sm:text-base border-b-2 border-orange-500 pb-1
              hover:text-orange-500 transition-all duration-300">
                <span>Lihat Selengkapnya</span>
                <span class="inline-block transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>

    </div>
</section>

<!-- konten 5 -->
<section class="py-5 px-5">
    <div class="mx-auto px-2 md:px-6 ">

        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-wide text-center reveal">
            BERITA TERKINI
        </h2>
        <div class="mx-auto mt-2 h-[4px] w-10 bg-orange-500 reveal"></div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mt-5 md:mt-10" data-reveal-group>

            @foreach($berita->take(6) as $item)
            <div class="reveal-up bg-white shadow-lg overflow-hidden group
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
                                  tracking-wide hover:underline transition reveal-left">
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
    <div class="mt-10 flex justify-center reval-left">
        <a href="{{route ('berita')}}" class="group inline-flex items-center gap-2 text-gray-800 font-semibold
              text-sm sm:text-base border-b-2 border-orange-500 pb-1
              hover:text-orange-500 transition-all duration-300">
            <span>Lihat Selengkapnya</span>
            <span class="inline-block transition-transform duration-300 group-hover:translate-x-1">&rarr;</span>
        </a>
    </div>
</section>



<section id="anggaran" class="relative overflow-hidden bg-gradient-to-b from-slate-50 to-white px-4 py-14 sm:px-6 sm:py-20">
    <div class="pointer-events-none absolute -right-24 -top-32 h-96 w-96 rounded-full bg-sky-100 blur-3xl"></div>
    <div class="relative mx-auto max-w-4xl">
        <div class="mx-auto mb-7 flex max-w-3xl flex-col items-center gap-3 text-center">
            <img src="{{ asset('img/logo_TAhead.png') }}" alt="Lambang Desa Wonorejo"
                class="h-14 w-14 rounded-full border border-slate-200 bg-white object-contain p-1 shadow-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Infografik APBDesa Wonorejo</p>
                <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">Anggaran Dana Desa</h2>
            </div>
        </div>

        @if($laporanAnggaran->isNotEmpty())
        <div class="mx-auto mb-3 flex max-w-4xl flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <label for="landingBudgetReport" class="text-sm font-bold text-slate-700">Pilih laporan APBDes</label>
            <select id="landingBudgetReport"
                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100 sm:w-auto sm:min-w-64">
                @foreach($laporanAnggaran as $laporan)
                <option value="{{ $laporan->id }}" @selected($loop->first)>{{ $laporan->jenis_laporan }} {{ $laporan->tahun }}</option>
                @endforeach
            </select>
        </div>

        <div class="mx-auto max-w-4xl overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_8px_24px_-18px_rgba(15,23,42,0.3)]">
            @foreach($laporanAnggaran as $laporan)
            @php
            $incomeItems = $laporan->items->where('jenis', 'pendapatan');
            $financingItems = $laporan->items->where('jenis', 'pembiayaan');
            $expensesByCategory = $laporan->items->where('jenis', 'belanja')->groupBy('kategori')
                ->map(fn ($items) => $items->sum('jumlah'));
            $totalIncome = $laporan->getTotalByJenis('pendapatan');
            $totalExpense = $laporan->getTotalByJenis('belanja');
            $totalFinancing = $laporan->getTotalByJenis('pembiayaan');
            $budgetDisplayTitle = stripos(trim($laporan->judul), 'infografik') !== false
                ? 'Anggaran Desa'
                : $laporan->judul;
            $budgetTheme = [
                'infografis' => [
                    'font' => "'Hind', sans-serif",
                    'header' => 'linear-gradient(115deg, #083344, #0369a1 55%, #f97316)',
                    'accent' => '#0369a1',
                    'surface' => 'linear-gradient(135deg, rgba(240,249,255,.98), rgba(255,247,237,.96)), radial-gradient(circle at 18% 22%, rgba(14,165,233,.18) 0 56px, transparent 57px), repeating-linear-gradient(90deg, rgba(3,105,161,.05) 0 2px, transparent 2px 18px)',
                    'ornament' => 'radial-gradient(circle at 10% 18%, rgba(14,165,233,.18) 0 2px, transparent 3px), radial-gradient(circle at 86% 80%, rgba(249,115,22,.18) 0 2px, transparent 3px), linear-gradient(120deg, transparent 0 65%, rgba(14,165,233,.08) 65% 66%, transparent 66%)',
                    'pie' => ['#0ea5e9', '#f97316', '#14b8a6', '#2563eb', '#84cc16'],
                ],
                'merah-putih' => [
                    'font' => "'Barlow Condensed', sans-serif",
                    'header' => 'linear-gradient(120deg, #7f1d1d, #dc2626 44%, #ffffff 44% 50%, #991b1b 50%)',
                    'accent' => '#b91c1c',
                    'surface' => 'linear-gradient(135deg, rgba(255,241,242,.98), rgba(255,255,255,.97)), repeating-linear-gradient(135deg, rgba(220,38,38,.08) 0 10px, transparent 10px 24px)',
                    'ornament' => 'linear-gradient(135deg, rgba(220,38,38,.14) 0 14%, transparent 14% 28%, rgba(255,255,255,.7) 28% 36%, transparent 36%), radial-gradient(circle at 92% 10%, rgba(220,38,38,.16), transparent 24%)',
                    'pie' => ['#dc2626', '#ef4444', '#1d4ed8', '#f59e0b', '#0f766e'],
                ],
                'nusantara' => [
                    'font' => "'Lora', serif",
                    'header' => 'linear-gradient(115deg, #064e3b, #047857 58%, #a16207)',
                    'accent' => '#047857',
                    'surface' => 'linear-gradient(135deg, rgba(236,253,245,.98), rgba(254,243,199,.82)), repeating-linear-gradient(45deg, rgba(4,120,87,.08) 0 7px, transparent 7px 18px), repeating-linear-gradient(-45deg, rgba(202,138,4,.07) 0 5px, transparent 5px 22px)',
                    'ornament' => 'radial-gradient(circle at 8% 12%, rgba(202,138,4,.22) 0 3px, transparent 4px), radial-gradient(circle at 16% 20%, rgba(4,120,87,.18) 0 8px, transparent 9px), repeating-linear-gradient(45deg, rgba(4,120,87,.05) 0 8px, transparent 8px 22px)',
                    'pie' => ['#047857', '#ca8a04', '#0e7490', '#b45309', '#166534'],
                ],
                'poster-batik' => [
                    'font' => "'Righteous', sans-serif",
                    'header' => 'linear-gradient(115deg, #881337, #be123c 58%, #9a3412)',
                    'accent' => '#be123c',
                    'surface' => 'linear-gradient(135deg, rgba(255,241,242,.97), rgba(255,251,235,.95)), radial-gradient(circle at 18% 18%, rgba(251,191,36,.2) 0 8px, transparent 9px), radial-gradient(circle at 82% 24%, rgba(190,18,60,.14) 0 14px, transparent 15px), repeating-linear-gradient(45deg, rgba(190,18,60,.07) 0 6px, transparent 6px 18px)',
                    'ornament' => 'radial-gradient(circle at 90% 10%, rgba(251,191,36,.24) 0 3px, transparent 4px), radial-gradient(circle at 78% 26%, rgba(251,191,36,.18) 0 8px, transparent 9px), repeating-linear-gradient(45deg, rgba(190,18,60,.06) 0 7px, transparent 7px 20px)',
                    'pie' => ['#be123c', '#d97706', '#1d4ed8', '#9f1239', '#047857'],
                ],
                'dashboard' => [
                    'font' => "'Space Grotesk', sans-serif",
                    'header' => 'linear-gradient(115deg, #020617, #1e3a8a 58%, #0f766e)',
                    'accent' => '#2563eb',
                    'surface' => 'linear-gradient(135deg, rgba(15,23,42,.98), rgba(30,41,59,.96)), linear-gradient(rgba(148,163,184,.09) 1px, transparent 1px), linear-gradient(90deg, rgba(148,163,184,.09) 1px, transparent 1px)',
                    'ornament' => 'radial-gradient(circle at 88% 12%, rgba(37,99,235,.22), transparent 28%), linear-gradient(rgba(255,255,255,.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.06) 1px, transparent 1px)',
                    'pie' => ['#38bdf8', '#22c55e', '#f97316', '#a78bfa', '#06b6d4'],
                ],
                'rincian' => [
                    'font' => "'Roboto Slab', serif",
                    'header' => 'linear-gradient(115deg, #292524, #57534e 55%, #854d0e)',
                    'accent' => '#57534e',
                    'surface' => 'linear-gradient(135deg, rgba(250,250,249,.98), rgba(245,245,244,.96)), repeating-linear-gradient(0deg, rgba(87,83,78,.08) 0 1px, transparent 1px 20px), linear-gradient(90deg, rgba(180,83,9,.18) 0 3px, transparent 3px)',
                    'ornament' => 'repeating-linear-gradient(0deg, rgba(87,83,78,.055) 0 1px, transparent 1px 18px), linear-gradient(90deg, rgba(180,83,9,.16) 0 2px, transparent 2px 100%)',
                    'pie' => ['#57534e', '#0f766e', '#b45309', '#1d4ed8', '#be123c'],
                ],
            ][$laporan->template] ?? [
                'font' => "'Hind', sans-serif",
                'header' => 'linear-gradient(115deg, #083344, #0369a1 55%, #f97316)',
                'accent' => '#0369a1',
                'surface' => 'linear-gradient(135deg, rgba(240,249,255,.98), rgba(255,247,237,.96))',
                'ornament' => 'radial-gradient(circle at 10% 18%, rgba(14,165,233,.18) 0 2px, transparent 3px)',
                'pie' => ['#0ea5e9', '#f97316', '#14b8a6', '#2563eb', '#84cc16'],
            ];
            $pieColors = $budgetTheme['pie'];
            $pieSegments = [];
            $pieOffset = 0;
            foreach ($expensesByCategory as $category => $amount) {
                $share = $totalExpense > 0 ? ((float) $amount / $totalExpense) * 100 : 0;
                $pieSegments[] = $pieColors[count($pieSegments) % count($pieColors)] . ' ' . number_format($pieOffset, 2, '.', '') . '% ' . number_format($pieOffset + $share, 2, '.', '') . '%';
                $pieOffset += $share;
            }
            $pieBackground = $pieSegments ? 'conic-gradient(' . implode(', ', $pieSegments) . ')' : '#e2e8f0';
            $maxExpense = max((float) $expensesByCategory->max(), 1);
            @endphp
            <article class="relative overflow-hidden" data-landing-budget-panel="{{ $laporan->id }}" style="font-family: {{ $budgetTheme['font'] }}; background: {{ $budgetTheme['surface'] }}; background-size: auto, 28px 28px, 28px 28px;" @if(!$loop->first) hidden @endif>
                <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-80" style="background: {{ $budgetTheme['ornament'] }}"></div>
                <header class="relative flex flex-wrap items-center justify-between gap-3 px-4 py-3 text-white sm:px-5 sm:py-3.5"
                    style="background: {{ $budgetTheme['header'] }}">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/logo_TAhead.png') }}" alt="" class="h-9 w-9 rounded-full bg-white object-contain p-1">
                        <div>
                            <p class="text-base font-extrabold tracking-wide">Desa Wonorejo</p>
                            <p class="text-[11px] text-white/85 sm:text-xs">Kec. Sumbergempol &middot; Kab. Tulungagung</p>
                        </div>
                    </div>
                    <div class="border-l border-white/30 pl-3 text-right">
                        <p class="text-[11px] uppercase tracking-[0.16em] text-white/85">{{ $laporan->jenis_laporan }}</p>
                        <p class="text-xl font-black tracking-[0.18em]">{{ $laporan->tahun }}</p>
                    </div>
                </header>

                <div class="relative p-3 sm:p-4 md:p-5">
                    @if($laporan->template === 'infografis')
                    <section class="relative mb-4 overflow-hidden rounded-2xl border border-sky-100 bg-white/85 p-4 shadow-sm sm:p-5">
                        <div aria-hidden="true" class="absolute -left-10 top-4 h-28 w-28 rounded-full bg-sky-100"></div>
                        <div aria-hidden="true" class="absolute -right-8 -bottom-8 h-32 w-32 rounded-full bg-orange-100"></div>
                        <div class="relative grid gap-4 sm:grid-cols-[1fr_auto] sm:items-center">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color: {{ $budgetTheme['accent'] }}">Infografik APBDesa - {{ $laporan->tahun }}</p>
                                <h3 class="mt-1 text-xl font-black leading-tight text-slate-950 sm:text-2xl">{{ $budgetDisplayTitle }}</h3>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-bold text-sky-800">{{ $incomeItems->count() }} sumber pendapatan</span>
                                    <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-800">{{ $expensesByCategory->count() }} bidang belanja</span>
                                </div>
                            </div>
                            <div class="rounded-2xl bg-slate-950 px-4 py-3 text-white shadow-sm">
                                <p class="text-[11px] font-bold uppercase tracking-widest text-sky-200">Total Pendapatan</p>
                                <p class="mt-1 text-2xl font-black">{{ 'Rp ' . number_format($totalIncome, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>
                    @elseif($laporan->template === 'merah-putih')
                    <section class="relative mb-4 overflow-hidden rounded-xl px-4 py-5 text-white sm:px-6 sm:py-6"
                        style="background: {{ $budgetTheme['header'] }}">
                        <div aria-hidden="true" class="absolute -right-8 -top-10 h-36 w-36 rotate-12 border-[18px] border-white/10"></div>
                        <div class="relative grid items-center gap-4 sm:grid-cols-[1fr_auto]">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.22em] text-white/75">Infografik APBDesa - {{ $laporan->tahun }}</p>
                                <h3 class="mt-1 text-xl font-black uppercase leading-tight sm:text-2xl">{{ $budgetDisplayTitle }}</h3>
                                <p class="mt-2 text-sm text-white/80">Ringkasan pendapatan, pembiayaan, dan alokasi belanja desa.</p>
                            </div>
                            <div class="border-t border-white/30 pt-3 sm:border-l sm:border-t-0 sm:pl-6 sm:pt-0 sm:text-right">
                                <p class="text-xs font-bold uppercase tracking-widest text-white/75">Total Pendapatan</p>
                                <p class="mt-1 text-2xl font-black sm:text-3xl">{{ 'Rp ' . number_format($totalIncome, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>
                    @elseif($laporan->template === 'nusantara')
                    <section class="relative mb-4 overflow-hidden rounded-t-[2rem] rounded-b-lg border border-emerald-100 bg-white/90 px-4 py-5 text-center sm:px-6">
                        <div aria-hidden="true" class="absolute -left-7 -top-9 h-24 w-24 rounded-full border-[12px] border-amber-100"></div>
                        <div aria-hidden="true" class="absolute -bottom-10 -right-5 h-28 w-28 rounded-full border-[14px] border-emerald-50"></div>
                        <div class="relative">
                            <p class="text-xs font-bold uppercase tracking-[0.22em]" style="color: {{ $budgetTheme['accent'] }}">Infografik APBDesa - {{ $laporan->tahun }}</p>
                            <h3 class="mt-1 text-xl font-black uppercase leading-tight text-slate-900 sm:text-2xl">{{ $budgetDisplayTitle }}</h3>
                            <p class="mx-auto mt-3 inline-flex max-w-full flex-wrap items-center justify-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-4 py-2">
                                <span class="text-xs font-bold uppercase tracking-wide text-emerald-800">Pendapatan</span>
                                <span class="text-base font-black text-slate-900 sm:text-lg">{{ 'Rp ' . number_format($totalIncome, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    </section>
                    @elseif($laporan->template === 'poster-batik')
                    <section class="relative mb-4 overflow-hidden rounded-lg border border-rose-100 bg-rose-50/80 p-4 sm:p-5">
                        <div aria-hidden="true" class="absolute inset-y-0 right-0 w-1/3 opacity-70" style="background: {{ $budgetTheme['ornament'] }}"></div>
                        <div class="relative grid items-center gap-3 sm:grid-cols-[auto_1fr_auto]">
                            <span class="hidden h-14 w-2 rounded-full bg-rose-700 sm:block"></span>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-rose-800">Infografik APBDesa - {{ $laporan->tahun }}</p>
                                <h3 class="mt-1 text-xl font-black uppercase leading-tight text-slate-900 sm:text-2xl">{{ $budgetDisplayTitle }}</h3>
                            </div>
                            <div class="relative rounded-xl border border-rose-200 bg-white/90 px-4 py-3 shadow-sm">
                                <p class="text-[11px] font-bold uppercase tracking-widest text-rose-700">Pendapatan</p>
                                <p class="text-lg font-black text-slate-900 sm:text-xl">{{ 'Rp ' . number_format($totalIncome, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>
                    @elseif($laporan->template === 'dashboard')
                    <section class="mb-4 rounded-xl border border-white/10 bg-white/10 p-3 text-white shadow-sm backdrop-blur sm:p-4">
                        <div class="grid gap-3 sm:grid-cols-[1fr_auto] sm:items-end">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-200">Infografik APBDesa - {{ $laporan->tahun }}</p>
                                <h3 class="mt-1 text-xl font-black text-white sm:text-2xl">{{ $budgetDisplayTitle }}</h3>
                            </div>
                            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wide">{{ $laporan->jenis_laporan }}</span>
                        </div>
                        <div class="mt-4 grid gap-2 sm:grid-cols-3">
                            <div class="rounded-lg border border-sky-300/20 bg-sky-400/10 p-3">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-sky-200">Pendapatan</p>
                                <p class="mt-1 text-base font-black">{{ 'Rp ' . number_format($totalIncome, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-lg border border-emerald-300/20 bg-emerald-400/10 p-3">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-200">Pembiayaan</p>
                                <p class="mt-1 text-base font-black">{{ 'Rp ' . number_format($totalFinancing, 0, ',', '.') }}</p>
                            </div>
                            <div class="rounded-lg border border-orange-300/20 bg-orange-400/10 p-3">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-orange-200">Belanja</p>
                                <p class="mt-1 text-base font-black">{{ 'Rp ' . number_format($totalExpense, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>
                    @else
                    <section class="relative mb-4 overflow-hidden rounded-md border border-stone-300 bg-white/95 px-4 py-4 shadow-sm sm:px-5">
                        <div aria-hidden="true" class="absolute inset-y-0 left-8 w-px bg-amber-700/20"></div>
                        <div aria-hidden="true" class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-stone-700 via-amber-700 to-stone-500"></div>
                        <div class="relative pl-5">
                            <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color: {{ $budgetTheme['accent'] }}">Infografik APBDesa - {{ $laporan->tahun }}</p>
                            <h3 class="mt-1 text-xl font-black leading-tight text-stone-950 sm:text-2xl">{{ $budgetDisplayTitle }}</h3>
                            <p class="mt-2 inline-flex rounded border border-stone-200 bg-stone-50 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-stone-600">{{ $laporan->jenis_laporan }}</p>
                        </div>
                        <div class="relative mt-3 rounded-md border border-stone-200 bg-stone-50 px-3 py-2 text-left sm:absolute sm:right-5 sm:top-5 sm:mt-0 sm:text-right">
                            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-500">Total Pendapatan</p>
                            <p class="text-lg font-black" style="color: {{ $budgetTheme['accent'] }}">{{ 'Rp ' . number_format($totalIncome, 0, ',', '.') }}</p>
                        </div>
                    </section>
                    @endif

                    <div class="grid gap-3 @if($laporan->template === 'merah-putih') lg:grid-cols-[1.15fr_.85fr] @elseif($laporan->template === 'poster-batik') lg:grid-cols-[1.35fr_.65fr] @elseif($laporan->template === 'dashboard') lg:grid-cols-[.9fr_1.1fr] @else lg:grid-cols-2 @endif">
                        <section class="overflow-hidden @if($laporan->template === 'dashboard') rounded-xl border border-white/10 bg-white/95 @elseif($laporan->template === 'rincian') rounded-sm border border-stone-300 bg-white/95 @else rounded-lg border border-slate-200 bg-white/90 @endif @if($laporan->template === 'nusantara') rounded-t-2xl border-t-4 border-t-emerald-700 @elseif($laporan->template === 'poster-batik') rounded-br-3xl @endif">
                            <div class="flex flex-wrap items-end justify-between gap-2 @if($laporan->template === 'rincian') border-b-2 border-stone-300 bg-stone-100/70 @else border-b border-slate-200 @endif px-3 py-2">
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-[0.14em]" style="color: {{ $budgetTheme['accent'] }}">Laporan Anggaran</p>
                                    <h3 class="mt-1 text-base font-extrabold text-slate-900 sm:text-lg">Rincian Pendapatan</h3>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-bold text-white" style="background-color: {{ $budgetTheme['accent'] }}">{{ $incomeItems->count() }} sumber</span>
                            </div>
                            <div class="@if($laporan->template === 'rincian') divide-y divide-stone-200 @else divide-y divide-slate-100 @endif">
                                @foreach($incomeItems as $item)
                                <div class="flex items-center justify-between gap-3 px-3 py-2.5 text-xs sm:text-sm @if($laporan->template === 'rincian') odd:bg-white even:bg-stone-50/80 @endif">
                                    <span class="text-slate-700">{{ $item->kategori }}</span>
                                    <strong class="shrink-0 text-right text-slate-900">{{ 'Rp ' . number_format($item->jumlah, 0, ',', '.') }}</strong>
                                </div>
                                @endforeach
                                <div class="flex items-center justify-between gap-3 @if($laporan->template === 'rincian') bg-stone-800 text-white @else bg-emerald-50 text-emerald-900 @endif px-3 py-2.5 text-xs font-extrabold sm:text-sm">
                                    <span>Total Pendapatan</span>
                                    <span>{{ 'Rp ' . number_format($totalIncome, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </section>

                        <section class="overflow-hidden @if($laporan->template === 'dashboard') rounded-xl border border-cyan-300/20 bg-slate-950 text-white @elseif($laporan->template === 'rincian') rounded-sm border border-stone-300 bg-white/95 @else rounded-lg border border-cyan-200 bg-cyan-50/70 @endif @if($laporan->template === 'nusantara') lg:mt-8 lg:rounded-bl-3xl @elseif($laporan->template === 'poster-batik') rounded-t-3xl @endif">
                            <div class="px-3 py-2 text-sm font-bold uppercase tracking-wide text-white" style="background-color: {{ $budgetTheme['accent'] }}">Pembiayaan</div>
                            <div class="@if($laporan->template === 'rincian') divide-y divide-stone-200 @else divide-y divide-cyan-100 @endif px-3">
                                @foreach($financingItems as $item)
                                <div class="flex items-center justify-between gap-3 py-2.5 text-xs sm:text-sm @if($laporan->template === 'rincian') odd:bg-white even:bg-stone-50/80 @endif">
                                    <span class="@if($laporan->template === 'dashboard') text-slate-100 @else text-slate-700 @endif">{{ $item->kategori }}</span>
                                    <strong class="shrink-0 text-right @if($laporan->template === 'dashboard') text-white @else text-slate-900 @endif">{{ 'Rp ' . number_format($item->jumlah, 0, ',', '.') }}</strong>
                                </div>
                                @endforeach
                                <div class="flex items-center justify-between gap-3 py-2.5 text-xs font-extrabold @if($laporan->template === 'dashboard') text-white @else text-slate-950 @endif sm:text-sm">
                                    <span>Total Pembiayaan</span>
                                    <span>{{ 'Rp ' . number_format($totalFinancing, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @if($laporan->keterangan)
                            <p class="m-2 rounded-md bg-white/80 p-2 text-[10px] leading-4 text-slate-600">{{ $laporan->keterangan }}</p>
                            @endif
                        </section>
                    </div>

                    <section class="mt-4 @if($laporan->template === 'merah-putih') rounded-xl border border-rose-100 bg-rose-50/50 p-3 sm:p-4 @elseif($laporan->template === 'nusantara') rounded-t-[2rem] border border-emerald-100 bg-white/85 p-3 sm:p-4 @elseif($laporan->template === 'poster-batik') rounded-2xl border border-amber-100 bg-amber-50/50 p-3 sm:p-4 @elseif($laporan->template === 'dashboard') rounded-xl border border-white/10 bg-white/95 p-3 sm:p-4 @elseif($laporan->template === 'rincian') rounded-sm border border-stone-300 bg-white/95 p-3 sm:p-4 @else rounded-2xl border border-sky-100 bg-white/85 p-3 sm:p-4 @endif">
                        <div class="flex flex-wrap items-end justify-between gap-3 border-b-2 pb-1.5" style="border-color: {{ $budgetTheme['accent'] }}">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-[0.14em]" style="color: {{ $budgetTheme['accent'] }}">Ringkasan Alokasi</p>
                                <h3 class="text-2xl font-black uppercase text-slate-950">Belanja</h3>
                            </div>
                            <p class="text-xl font-black" style="color: {{ $budgetTheme['accent'] }}">{{ 'Rp ' . number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                        <div class="mt-4 grid items-center gap-5 @if($laporan->template === 'nusantara') md:grid-cols-[1fr_190px] @elseif($laporan->template === 'poster-batik') md:grid-cols-1 @elseif($laporan->template === 'dashboard') md:grid-cols-[1fr_190px] @else md:grid-cols-[190px_1fr] @endif">
                            <div class="flex flex-col items-center gap-1.5 @if($laporan->template === 'nusantara') md:order-2 @elseif($laporan->template === 'poster-batik') md:mb-2 @elseif($laporan->template === 'dashboard') md:order-2 @endif">
                                <div class="relative h-36 w-36 overflow-hidden border border-slate-200 shadow-inner sm:h-44 sm:w-44 @if($laporan->template === 'poster-batik') sm:h-48 sm:w-48 @endif"
                                    style="background: {{ $pieBackground }}; border-radius: 50% !important; clip-path: circle(50% at 50% 50%);">
                                    <div class="absolute inset-[34%] border-4 border-white bg-white shadow-sm"
                                        style="border-radius: 50% !important; clip-path: circle(50% at 50% 50%);"></div>
                                </div>
                                <p class="text-center text-[10px] font-semibold text-slate-500">Proporsi alokasi belanja</p>
                            </div>
                            <div class="grid gap-1.5 @if($laporan->template === 'poster-batik') sm:grid-cols-2 xl:grid-cols-3 @elseif($laporan->template === 'nusantara') grid-cols-1 @else sm:grid-cols-2 @endif">
                                @forelse($expensesByCategory as $category => $amount)
                                @php
                                $share = $totalExpense > 0 ? ((float) $amount / $totalExpense) * 100 : 0;
                                $color = $pieColors[$loop->index % count($pieColors)];
                                @endphp
                                <div class="@if($laporan->template === 'dashboard') rounded-lg border border-slate-200 bg-slate-50 p-2.5 @elseif($laporan->template === 'rincian') rounded-sm border-b border-stone-200 bg-white/80 p-2 @else rounded-md border border-slate-200 bg-white/95 p-2 @endif @if($laporan->template === 'poster-batik') rounded-br-xl p-3 @elseif($laporan->template === 'nusantara') rounded-r-2xl border-l-4 @endif" @if($laporan->template === 'nusantara') style="border-left-color: {{ $color }}" @endif>
                                    <div class="flex items-start justify-between gap-2">
                                        <span class="text-xs font-bold leading-4 text-slate-700 sm:text-sm">{{ $category }}</span>
                                        <span class="shrink-0 rounded px-1.5 py-0.5 text-xs font-extrabold text-white" style="background-color: {{ $color }}">{{ number_format($share, 1, ',', '.') }}%</span>
                                    </div>
                                    <p class="mt-1 text-xs font-extrabold text-slate-900 sm:text-sm">{{ 'Rp ' . number_format($amount, 0, ',', '.') }}</p>
                                    <div class="mt-1.5 h-1 overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full" style="width: {{ min(100, ($amount / $maxExpense) * 100) }}%; background-color: {{ $color }}"></div>
                                    </div>
                                </div>
                                @empty
                                <p class="rounded-md bg-slate-50 p-4 text-sm text-slate-500 md:col-span-2">Belum ada data belanja.</p>
                                @endforelse
                            </div>
                        </div>
                    </section>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
            <i class="fa-solid fa-chart-pie text-3xl text-slate-300"></i>
            <h3 class="mt-4 text-lg font-bold text-slate-800">Infografis anggaran segera hadir</h3>
            <p class="mt-1 text-sm text-slate-500">Laporan anggaran yang dipublikasikan akan ditampilkan di sini.</p>
        </div>
        @endif
    </div>
</section>


<!-- konten 6 -->
<section class="relative py-10 px-5 overflow-hidden bg-white">
    <div class="absolute inset-0 opacity-[0.04]"
        style="background-image: radial-gradient(circle at 1px 1px, rgba(0,0,0,0.12) 1px, transparent 0); background-size: 28px 28px;">
    </div>

    <div class="relative z-10 max-w-5xl mx-auto text-center reveal">
        <p class="text-xs sm:text-sm font-semibold tracking-[0.3em] text-gray-500 uppercase">
            Support By
        </p>
        <img src="{{ asset('img/ubhi-logo.jpg') }}" alt="logo"
            class="w-32 h-auto object-cover mx-auto reveal-up">
        <div class="mx-auto mt-4 h-[3px] w-16 bg-orange-400 reveal-up"></div>
    </div>
</section>

@if($laporanAnggaran->isNotEmpty())
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const selector = document.getElementById('landingBudgetReport');
    if (!selector) return;

    const panels = document.querySelectorAll('[data-landing-budget-panel]');
    const showSelectedReport = () => {
        panels.forEach((panel) => {
            panel.hidden = panel.dataset.landingBudgetPanel !== selector.value;
        });
    };

    selector.addEventListener('change', showSelectedReport);
    showSelectedReport();
});
</script>
@endpush
@endif

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
        const normalized = normalizeNewlines(text);
        const wrap = document.createElement('div');
        wrap.className = 'slide-in-left flex items-start gap-3';
        wrap.innerHTML =
            `<img src=\"{{ asset('img/cs.png') }}\" alt=\"Chatbot Simpeda\" class=\"h-8 w-8 rounded-full object-contain\">
             <div class=\"max-w-[85%] rounded-3xl rounded-tr-sm bg-black px-4 py-3 text-sm font-medium text-white\">
                 <p class=\"mb-2 text-xs font-bold text-gray-500\">Nara</p>
                 <p class=\"whitespace-pre-line text-xs leading-relaxed text-neutral-200\">${escapeHtml(normalized)}</p>
             </div>`;
        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    function addTypingIndicator() {
        const existing = document.getElementById('chat-typing-indicator-public');
        if (existing) return;
        const wrap = document.createElement('div');
        wrap.id = 'chat-typing-indicator-public';
        wrap.className = 'slide-in-left flex items-start gap-3';
        wrap.innerHTML =
            `<img src=\"{{ asset('img/cs.png') }}\" alt=\"Chatbot Simpeda\" class=\"h-8 w-8 rounded-full object-contain\">
             <div class=\"rounded-2xl rounded-tr-sm bg-black px-4 py-3 text-sm font-medium text-white\">
                 <div class=\"mb-2 text-xs font-bold text-gray-500\">Nara sedang mengetik...</div>
                 <div class=\"flex items-center gap-2\">
                     <span class=\"typing-dot h-2 w-2 rounded-full bg-orange-500\"></span>
                     <span class=\"typing-dot h-2 w-2 rounded-full bg-orange-500\"></span>
                     <span class=\"typing-dot h-2 w-2 rounded-full bg-orange-500\"></span>
                 </div>
             </div>`;
        list.appendChild(wrap);
        list.scrollTop = list.scrollHeight;
    }

    function removeTypingIndicator() {
        const typing = document.getElementById('chat-typing-indicator-public');
        if (typing) typing.remove();
    }

    function escapeHtml(text) {
        return text
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function normalizeNewlines(text) {
        return String(text)
            .replace(/\\r\\n/g, '\n')
            .replace(/\\n/g, '\n')
            .replace(/\\r/g, '\n');
    }

    async function sendMessage(message) {
        addUserMessage(message);
        input.value = '';
        sendBtn.disabled = true;
        sendBtn.classList.add('opacity-50', 'cursor-not-allowed');
        removeTypingIndicator();
        addTypingIndicator();

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
            removeTypingIndicator();
            addBotMessage(data.reply || 'Maaf, aku belum paham. Coba tanya dengan kalimat lain ya 🙏');
        } catch (error) {
            removeTypingIndicator();
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
