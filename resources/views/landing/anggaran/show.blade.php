@extends('layouts.app')

@section('title', config('app.name') . ' | ' . $anggaran->judul)

@section('content')
@include('partials.nav')
@php
$incomeItems = $anggaran->items->where('jenis', 'pendapatan');
$financingItems = $anggaran->items->where('jenis', 'pembiayaan');
$expenseItems = $anggaran->items->where('jenis', 'belanja');
$expensesByCategory = $expenseItems->groupBy('kategori')->map(fn ($items) => [
    'total' => $items->sum('jumlah'),
    'items' => $items,
]);
$totalPendapatan = $anggaran->getTotalByJenis('pendapatan');
$totalBelanja = $anggaran->getTotalByJenis('belanja');
$totalPembiayaan = $anggaran->getTotalByJenis('pembiayaan');
$maxBelanja = max((float) $expensesByCategory->max('total'), 1);
$infographicTemplates = ['infografis', 'merah-putih', 'nusantara', 'poster-batik'];
$templateThemes = [
    'infografis' => [
        'header' => 'linear-gradient(115deg, #082f49, #1e40af)',
        'accent' => '#075985',
        'ornament' => 'radial-gradient(circle at 12% 20%, rgba(14,165,233,.07) 0 2px, transparent 3px), radial-gradient(circle at 88% 75%, rgba(249,115,22,.08) 0 2px, transparent 3px)',
        'pie' => ['#148e99', '#2584b5', '#ed6b35', '#0c557c', '#18a566'],
    ],
    'merah-putih' => [
        'header' => 'linear-gradient(115deg, #7f1d1d, #dc2626 58%, #991b1b)',
        'accent' => '#b91c1c',
        'ornament' => 'repeating-linear-gradient(135deg, rgba(220,38,38,.035) 0 10px, transparent 10px 24px), radial-gradient(circle at 90% 8%, rgba(220,38,38,.12), transparent 24%)',
        'pie' => ['#dc2626', '#1d4ed8', '#eab308', '#991b1b', '#0f766e'],
    ],
    'nusantara' => [
        'header' => 'linear-gradient(115deg, #064e3b, #047857 60%, #a16207)',
        'accent' => '#047857',
        'ornament' => 'radial-gradient(circle at 8% 12%, rgba(202,138,4,.16) 0 2px, transparent 3px), repeating-linear-gradient(45deg, rgba(4,120,87,.035) 0 8px, transparent 8px 22px)',
        'pie' => ['#047857', '#ca8a04', '#0e7490', '#b45309', '#166534'],
    ],
    'poster-batik' => [
        'header' => 'linear-gradient(115deg, #881337, #be123c 60%, #9a3412)',
        'accent' => '#be123c',
        'ornament' => 'radial-gradient(circle at 90% 10%, rgba(251,191,36,.16) 0 3px, transparent 4px), repeating-linear-gradient(45deg, rgba(190,18,60,.04) 0 7px, transparent 7px 20px)',
        'pie' => ['#be123c', '#d97706', '#1d4ed8', '#9f1239', '#047857'],
    ],
];
$templateTheme = $templateThemes[$anggaran->template] ?? $templateThemes['infografis'];
$pieColors = $templateTheme['pie'];
$pieSegments = [];
$pieOffset = 0;
foreach ($expensesByCategory as $category => $expense) {
    $percentage = $totalBelanja > 0 ? ((float) $expense['total'] / $totalBelanja) * 100 : 0;
    $pieSegments[] = $pieColors[count($pieSegments) % count($pieColors)] . ' ' . number_format($pieOffset, 2, '.', '') . '% ' . number_format($pieOffset + $percentage, 2, '.', '') . '%';
    $pieOffset += $percentage;
}
$pieBackground = $pieSegments ? 'conic-gradient(' . implode(', ', $pieSegments) . ')' : '#e2e8f0';
$formatRupiah = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
$templateLabels = [
    'infografis' => 'Infografis Desa',
    'merah-putih' => 'Merah Putih',
    'nusantara' => 'Nusantara',
    'dashboard' => 'Ringkasan Modern',
    'poster-batik' => 'Batik Merah Putih',
    'rincian' => 'Tabel Rincian',
];
$templateLabel = $templateLabels[$anggaran->template] ?? $templateLabels['infografis'];
$sections = [
    'pendapatan' => ['label' => 'Pendapatan', 'items' => $incomeItems, 'total' => $totalPendapatan],
    'pembiayaan' => ['label' => 'Pembiayaan', 'items' => $financingItems, 'total' => $totalPembiayaan],
    'belanja' => ['label' => 'Belanja', 'items' => $expenseItems, 'total' => $totalBelanja],
];
@endphp

<main class="min-h-screen bg-slate-100 px-3 pb-16 pt-28 md:px-8">
    <div class="mx-auto max-w-6xl">
        <a href="{{ url('/') }}#anggaran" class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-blue-700 transition hover:text-orange-600">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke beranda
        </a>

        @if(in_array($anggaran->template, $infographicTemplates, true))
        <article class="relative mx-auto max-w-5xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-80" style="background: {{ $templateTheme['ornament'] }}"></div>
            <header class="relative flex min-h-28 flex-wrap items-center justify-between gap-4 px-5 py-5 text-white sm:px-8"
                style="background: {{ $templateTheme['header'] }}">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/logo_TAhead.png') }}" alt="Lambang Desa Wonorejo" class="h-14 w-14 rounded-full bg-white object-contain p-1 shadow-md sm:h-16 sm:w-16">
                    <div>
                        <p class="text-xl font-extrabold tracking-wide sm:text-3xl">Desa Wonorejo</p>
                        <p class="mt-0.5 text-xs tracking-wide text-white/85 sm:text-sm">Kec. Sumbergempol · Kab. Tulungagung</p>
                    </div>
                </div>
                <div class="border-l-2 border-white/60 pl-4 sm:text-right">
                    <p class="text-xs font-bold uppercase tracking-widest text-white/85">{{ $anggaran->jenis_laporan }}</p>
                    <p class="text-3xl font-black tracking-[0.2em]">{{ $anggaran->tahun }}</p>
                    <p class="text-sm font-semibold text-white/90">{{ $templateLabel }}</p>
                </div>
            </header>

            <div class="relative px-5 py-6 sm:px-8">
                <div class="grid gap-5 lg:grid-cols-[1.25fr_.75fr]">
                    <section>
                        <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-[0.2em]" style="color: {{ $templateTheme['accent'] }}">Transparansi Anggaran</p>
                                <h1 class="mt-1 text-3xl font-black uppercase leading-tight text-slate-900 sm:text-4xl">{{ $anggaran->judul }}</h1>
                            </div>
                            <p class="text-2xl font-black text-sky-900 sm:text-3xl">{{ $formatRupiah($totalPendapatan) }}</p>
                        </div>
                        <div class="overflow-hidden rounded-lg border border-slate-200">
                            <div class="px-4 py-2.5 text-base font-extrabold uppercase tracking-wide text-white" style="background-color: {{ $templateTheme['accent'] }}">Pendapatan</div>
                            <div class="divide-y divide-slate-100">
                                @foreach($incomeItems as $item)
                                <div class="flex items-center justify-between gap-3 px-4 py-3 text-sm sm:text-base">
                                    <span class="font-medium text-slate-700">{{ $item->kategori }}</span>
                                    <strong class="shrink-0 text-right text-slate-900">{{ $formatRupiah($item->jumlah) }}</strong>
                                </div>
                                @endforeach
                                <div class="flex items-center justify-between gap-3 bg-emerald-50 px-4 py-3.5 text-sm font-extrabold text-emerald-950 sm:text-base">
                                    <span>Total Pendapatan</span>
                                    <span>{{ $formatRupiah($totalPendapatan) }}</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white/95">
                        <div class="px-4 py-2.5 text-base font-extrabold uppercase tracking-wide text-white" style="background-color: {{ $templateTheme['accent'] }}">Pembiayaan</div>
                        <div class="divide-y divide-cyan-100 px-4">
                            @foreach($financingItems as $item)
                            <div class="flex items-center justify-between gap-3 py-3 text-sm sm:text-base">
                                <span class="font-medium text-slate-700">{{ $item->kategori }}</span>
                                <strong class="shrink-0 text-right text-slate-900">{{ $formatRupiah($item->jumlah) }}</strong>
                            </div>
                            @endforeach
                            <div class="flex items-center justify-between gap-3 py-3 text-sm font-extrabold text-slate-950 sm:text-base">
                                <span>Total Pembiayaan</span>
                                <span class="text-right">{{ $formatRupiah($totalPembiayaan) }}</span>
                            </div>
                        </div>
                        @if($anggaran->keterangan)
                        <p class="m-3 rounded-md bg-white/80 p-3 text-xs leading-5 text-slate-600">{{ $anggaran->keterangan }}</p>
                        @endif
                    </section>
                </div>

                <section class="mt-7">
                    <div class="flex flex-wrap items-end justify-between gap-3 border-b-2 pb-3" style="border-color: {{ $templateTheme['accent'] }}">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-[0.2em]" style="color: {{ $templateTheme['accent'] }}">Ringkasan Alokasi</p>
                            <h2 class="text-4xl font-black uppercase text-slate-950">Belanja</h2>
                        </div>
                        <p class="text-3xl font-black text-slate-950 sm:text-4xl">{{ $formatRupiah($totalBelanja) }}</p>
                    </div>

                    <div class="mt-5 grid items-center gap-7 lg:grid-cols-[.65fr_1.35fr]">
                        <div class="flex flex-col items-center gap-4">
                            <div class="relative h-48 w-48 rounded-full shadow-inner sm:h-56 sm:w-56" style="background: {{ $pieBackground }}">
                                <div class="absolute inset-[34%] rounded-full border-4 border-white bg-white shadow"></div>
                            </div>
                            <p class="max-w-xs text-center text-xs leading-5 text-slate-500">Visual proporsi belanja berdasarkan bidang kegiatan.</p>
                        </div>

                        <div class="space-y-3">
                            @forelse($expensesByCategory as $category => $expense)
                            @php
                            $percentage = $totalBelanja > 0 ? ((float) $expense['total'] / $totalBelanja) * 100 : 0;
                            $color = $pieColors[$loop->index % count($pieColors)];
                            @endphp
                            <div class="overflow-hidden rounded-r-full border border-slate-200 bg-white shadow-sm">
                                <div class="grid min-h-16 grid-cols-[5px_1fr_auto] items-center gap-3 sm:grid-cols-[8px_1fr_auto]">
                                    <span class="h-full" style="background-color: {{ $color }}"></span>
                                    <div class="min-w-0 py-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded bg-slate-800 px-2 py-0.5 text-sm font-black text-white">{{ number_format($percentage, 1, ',', '.') }}%</span>
                                            <h3 class="text-sm font-extrabold uppercase leading-5 text-slate-800 sm:text-base">{{ $category }}</h3>
                                        </div>
                                        <p class="mt-1 text-xl font-black text-slate-900 sm:text-2xl">{{ $formatRupiah($expense['total']) }}</p>
                                    </div>
                                    <span class="mr-3 text-xl font-black" style="color: {{ $color }}"><i class="fa-solid fa-chevron-right"></i></span>
                                </div>
                            </div>
                            @empty
                            <p class="rounded-lg bg-slate-50 p-5 text-sm text-slate-500">Belum ada rincian belanja.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
            <footer class="px-5 py-3 text-center text-sm tracking-[0.2em] text-white sm:px-8" style="background-color: {{ $templateTheme['accent'] }}">
                {{ config('app.url') }}
            </footer>
        </article>
        @elseif($anggaran->template === 'dashboard')
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 shadow-xl">
            <header class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-sky-800 p-6 text-white md:p-10">
                <div class="absolute -right-10 -top-20 h-64 w-64 rounded-full border-[30px] border-white/5"></div>
                <div class="relative flex flex-wrap items-center justify-between gap-5">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('img/logo_TAhead.png') }}" alt="Lambang Desa Wonorejo" class="h-16 w-16 rounded-full bg-white object-contain p-1 shadow-lg">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-200">Pemerintah Desa Wonorejo</p>
                            <h1 class="mt-1 text-2xl font-black sm:text-4xl">{{ $anggaran->judul }}</h1>
                            <p class="mt-2 text-sm text-slate-200">{{ $anggaran->jenis_laporan }} · Tahun Anggaran {{ $anggaran->tahun }}</p>
                        </div>
                    </div>
                    <span class="rounded-full border border-white/25 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-wider">{{ $templateLabel }}</span>
                </div>
                @if($anggaran->keterangan)
                <p class="relative mt-6 max-w-3xl text-sm leading-6 text-slate-200">{{ $anggaran->keterangan }}</p>
                @endif
            </header>

            <div class="p-4 md:p-8">
                <section class="grid gap-4 md:grid-cols-3">
                    @foreach([
                        ['label' => 'Total Pendapatan', 'value' => $totalPendapatan, 'icon' => 'fa-arrow-trend-up', 'class' => 'border-emerald-200 text-emerald-700'],
                        ['label' => 'Total Pembiayaan', 'value' => $totalPembiayaan, 'icon' => 'fa-scale-balanced', 'class' => 'border-blue-200 text-blue-700'],
                        ['label' => 'Total Belanja', 'value' => $totalBelanja, 'icon' => 'fa-money-bill-transfer', 'class' => 'border-orange-200 text-orange-700'],
                    ] as $card)
                    <div class="rounded-xl border bg-white p-5 shadow-sm {{ $card['class'] }}">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold">{{ $card['label'] }}</p>
                            <i class="fa-solid {{ $card['icon'] }}"></i>
                        </div>
                        <p class="mt-3 text-2xl font-black text-slate-900 sm:text-3xl">{{ $formatRupiah($card['value']) }}</p>
                    </div>
                    @endforeach
                </section>

                <section class="mt-6 grid gap-6 lg:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">Asal Pendapatan</h2>
                        <div class="mt-4 space-y-4">
                            @foreach($incomeItems as $item)
                            @php $share = $totalPendapatan > 0 ? ((float) $item->jumlah / $totalPendapatan) * 100 : 0; @endphp
                            <div>
                                <div class="mb-1 flex justify-between gap-3 text-sm">
                                    <span class="font-semibold text-slate-600">{{ $item->kategori }}</span>
                                    <strong class="text-slate-900">{{ $formatRupiah($item->jumlah) }}</strong>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-500" style="width: {{ min(100, $share) }}%"></div></div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">Pembiayaan</h2>
                        <div class="mt-3 divide-y divide-slate-100">
                            @foreach($financingItems as $item)
                            <div class="flex justify-between gap-3 py-3 text-sm"><span class="text-slate-600">{{ $item->kategori }}</span><strong class="text-right text-slate-900">{{ $formatRupiah($item->jumlah) }}</strong></div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section class="mt-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-end justify-between gap-3">
                        <div><p class="text-sm font-bold uppercase tracking-widest text-orange-600">Alokasi anggaran</p><h2 class="mt-1 text-2xl font-extrabold text-slate-900">Belanja per Bidang</h2></div>
                        <p class="text-2xl font-black text-slate-900">{{ $formatRupiah($totalBelanja) }}</p>
                    </div>
                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        @foreach($expensesByCategory as $category => $expense)
                        @php $share = $totalBelanja > 0 ? ((float) $expense['total'] / $totalBelanja) * 100 : 0; @endphp
                        <div class="rounded-lg bg-slate-50 p-4">
                            <div class="flex justify-between gap-3"><h3 class="text-base font-bold text-slate-800">{{ $category }}</h3><span class="shrink-0 text-sm font-bold text-orange-700">{{ number_format($share, 1, ',', '.') }}%</span></div>
                            <p class="mt-2 text-xl font-black text-slate-900">{{ $formatRupiah($expense['total']) }}</p>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-gradient-to-r from-orange-500 to-blue-600" style="width: {{ min(100, $share) }}%"></div></div>
                        </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </article>
        @else
        <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
            <header class="flex flex-wrap items-center justify-between gap-4 bg-gradient-to-r from-slate-950 to-blue-900 px-5 py-6 text-white md:px-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/logo_TAhead.png') }}" alt="Lambang Desa Wonorejo" class="h-14 w-14 rounded-full bg-white object-contain p-1">
                    <div><p class="text-sm font-semibold uppercase tracking-widest text-sky-200">Desa Wonorejo · {{ $anggaran->tahun }}</p><h1 class="mt-1 text-3xl font-extrabold">{{ $anggaran->judul }}</h1></div>
                </div>
                <span class="rounded-full border border-white/25 px-3 py-1 text-sm font-bold">{{ $anggaran->jenis_laporan }} · {{ $templateLabel }}</span>
            </header>
            @if($anggaran->keterangan)
            <p class="border-b border-slate-200 bg-slate-50 px-5 py-4 text-sm leading-6 text-slate-600 md:px-8">{{ $anggaran->keterangan }}</p>
            @endif
            <div class="grid gap-4 p-5 md:grid-cols-3 md:p-8">
                @foreach($sections as $section)
                <div class="rounded-lg border border-slate-200">
                    <div class="flex items-center justify-between gap-2 bg-slate-800 px-4 py-3 text-white">
                        <h2 class="font-bold">{{ $section['label'] }}</h2>
                        <span class="text-sm font-semibold">{{ $formatRupiah($section['total']) }}</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[300px]">
                            <thead><tr class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><th class="px-3 py-2">Kategori</th><th class="px-3 py-2 text-right">Jumlah</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($section['items'] as $item)
                                <tr><td class="px-3 py-3 text-sm font-medium text-slate-700">{{ $item->kategori }}@if($item->jenis === 'belanja' && $item->uraian !== $item->kategori)<span class="mt-1 block text-slate-500">{{ $item->uraian }}</span>@endif</td><td class="whitespace-nowrap px-3 py-3 text-right text-sm font-bold text-slate-900">{{ $formatRupiah($item->jumlah) }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </article>
        @endif
    </div>
</main>
@endsection
