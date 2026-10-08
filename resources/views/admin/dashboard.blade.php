@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | ' . (((auth()->user()->role ?? null) === 'petugas') ? 'Dashboard Petugas' : 'Dashboard Admin'))

@section('content')
@php
$isPetugas = (auth()->user()->role ?? null) === 'petugas';
$routePrefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';
$trendRows = collect($trendRows ?? []);
$trendPeriod = $trendPeriod ?? 'bulan';
$suratStats = $suratStats ?? ['total' => 0, 'week' => 0, 'month' => 0, 'year' => 0];
$chatbotStats = $chatbotStats ?? ['total' => 0, 'week' => 0, 'month' => 0, 'year' => 0, 'sessionsMonth' => 0];
$akunStats = $akunStats ?? ['totalAll' => 0, 'totalWarga' => 0, 'pending' => 0, 'approved' => 0, 'week' => 0, 'month' => 0];
$aspirasiStats = $aspirasiStats ?? ['total' => 0, 'week' => 0, 'month' => 0, 'today' => 0, 'withImage' => 0];
$contentStats = $contentStats ?? ['beritaPublished' => 0, 'artikelPublished' => 0, 'beritaViews' => 0, 'artikelViews' => 0, 'totalPublished' => 0, 'totalViews' => 0];
$suratByStatus = $suratByStatus ?? [];
$suratByJenis = $suratByJenis ?? [];
$roleSummary = $roleSummary ?? [];

$approvalRate = $akunStats['totalWarga'] > 0 ? round(($akunStats['approved'] / $akunStats['totalWarga']) * 100) : 0;
$pendingRate = max(0, 100 - $approvalRate);
$maxTrend = max(
    $trendRows->max('surat') ?? 0,
    $trendRows->max('chatbot') ?? 0,
    $trendRows->max('aspirasi') ?? 0,
    $trendRows->max('registrasi') ?? 0,
    1
);
$pointX = fn (int $index) => 54 + ($index * (506 / max($trendRows->count() - 1, 1)));
$pointY = fn (int $value) => 210 - (($value / $maxTrend) * 164);
$seriesData = function (string $key) use ($trendRows, $pointX, $pointY) {
    return $trendRows->values()->map(function ($row, $index) use ($key, $pointX, $pointY) {
        $value = (int) ($row[$key] ?? 0);

        return [
            'x' => round($pointX($index), 1),
            'y' => round($pointY($value), 1),
            'value' => $value,
            'label' => (string) ($row['label'] ?? ''),
        ];
    })->values();
};
$smoothPath = function ($points): string {
    $points = collect($points)->values();

    if ($points->isEmpty()) {
        return '';
    }

    $first = $points->first();
    $path = 'M ' . $first['x'] . ' ' . $first['y'];

    for ($i = 1; $i < $points->count(); $i++) {
        $previous = $points[$i - 1];
        $current = $points[$i];
        $distance = $current['x'] - $previous['x'];
        $path .= ' C ' . round($previous['x'] + ($distance * 0.42), 1) . ' ' . $previous['y'];
        $path .= ', ' . round($current['x'] - ($distance * 0.42), 1) . ' ' . $current['y'];
        $path .= ', ' . $current['x'] . ' ' . $current['y'];
    }

    return $path;
};
$areaPath = function ($points) use ($smoothPath): string {
    $points = collect($points)->values();

    if ($points->isEmpty()) {
        return '';
    }

    $first = $points->first();
    $last = $points->last();

    return 'M ' . $first['x'] . ' 210 L ' . $first['x'] . ' ' . $first['y'] . ' ' . substr($smoothPath($points), 2) . ' L ' . $last['x'] . ' 210 Z';
};
$suratData = $seriesData('surat');
$chatbotData = $seriesData('chatbot');
$aspirasiData = $seriesData('aspirasi');
$suratPath = $smoothPath($suratData);
$chatbotPath = $smoothPath($chatbotData);
$aspirasiPath = $smoothPath($aspirasiData);
$suratAreaPath = $areaPath($suratData);
$chatbotAreaPath = $areaPath($chatbotData);
$aspirasiAreaPath = $areaPath($aspirasiData);
$peakRow = $trendRows->sortByDesc(fn ($row) => max((int) ($row['surat'] ?? 0), (int) ($row['chatbot'] ?? 0), (int) ($row['aspirasi'] ?? 0)))->first();
$trendTitle = [
    'hari' => 'Tren Aktivitas 24 Jam',
    'minggu' => 'Tren Aktivitas 4 Minggu',
    'bulan' => 'Tren Aktivitas 12 Bulan',
    'tahun' => 'Tren Aktivitas 5 Tahun',
][$trendPeriod] ?? 'Tren Aktivitas';
if ($trendPeriod === 'hari') {
    $trendTitle .= ' - ' . \Illuminate\Support\Carbon::parse($trendDate)->translatedFormat('d M Y');
}
$trendLabelInterval = match ($trendPeriod) {
    'hari' => 4,
    'bulan' => 2,
    default => 1,
};
$trendPeriodLabels = ['hari' => 'Hari', 'minggu' => 'Minggu', 'bulan' => 'Bulan', 'tahun' => 'Tahun'];
$maxStatus = max(count($suratByStatus) ? max($suratByStatus) : 0, 1);
$maxJenis = max(count($suratByJenis) ? max($suratByJenis) : 0, 1);
$maxContent = max($contentStats['beritaViews'] ?? 0, $contentStats['artikelViews'] ?? 0, $contentStats['beritaPublished'] ?? 0, $contentStats['artikelPublished'] ?? 0, 1);
$kpis = [
    ['label' => 'Akun Warga', 'value' => $akunStats['totalWarga'], 'sub' => number_format($akunStats['pending']) . ' menunggu approval', 'icon' => 'fa-users', 'tone' => 'rose'],
    ['label' => 'Pengajuan Surat', 'value' => $suratStats['total'], 'sub' => number_format($suratStats['week']) . ' baru 7 hari', 'icon' => 'fa-file-signature', 'tone' => 'orange'],
    ['label' => 'Interaksi Chatbot', 'value' => $chatbotStats['total'], 'sub' => number_format($chatbotStats['sessionsMonth']) . ' sesi unik 30 hari', 'icon' => 'fa-robot', 'tone' => 'blue'],
    ['label' => 'Aspirasi Masuk', 'value' => $aspirasiStats['total'], 'sub' => number_format($aspirasiStats['today']) . ' masuk hari ini', 'icon' => 'fa-comments', 'tone' => 'emerald'],
];
$toneClass = [
    'orange' => ['box' => 'bg-orange-50 border-orange-200 text-orange-700', 'icon' => 'bg-orange-500 text-white', 'bar' => 'bg-orange-500'],
    'blue' => ['box' => 'bg-blue-50 border-blue-200 text-blue-700', 'icon' => 'bg-blue-600 text-white', 'bar' => 'bg-blue-600'],
    'emerald' => ['box' => 'bg-emerald-50 border-emerald-200 text-emerald-700', 'icon' => 'bg-emerald-600 text-white', 'bar' => 'bg-emerald-600'],
    'rose' => ['box' => 'bg-red-50 border-red-200 text-red-700', 'icon' => 'bg-red-600 text-white', 'bar' => 'bg-red-600'],
];
@endphp

<div class="space-y-5">
    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="grid gap-4 p-5 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <h1 class="mt-2 text-3xl font-bold tracking-[1px] text-gray-800">
                    {{ $isPetugas ? 'Dashboard Petugas' : 'Dashboard Admin' }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-600">
                    Selamat datang di Dashboard Admin Desa Wonorejo, Kecamatan Sumbergempol, Tulungagung.
                </p>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($kpis as $kpi)
        @php $tone = $toneClass[$kpi['tone']]; @endphp
        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[1px] text-slate-500">{{ $kpi['label'] }}</p>
                    <p class="mt-2 text-4xl font-bold text-slate-900">{{ number_format($kpi['value']) }}</p>
                    <p class="mt-1 text-sm text-slate-600">{{ $kpi['sub'] }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-md {{ $tone['icon'] }}">
                    <i class="fa-solid {{ $kpi['icon'] }}"></i>
                </div>
            </div>
        </div>
        @endforeach
    </section>

    <section id="activityChartPanel" class="grid grid-cols-1 gap-4 xl:grid-cols-12">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm xl:col-span-8">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $trendTitle }}</h2>
                    <p class="mt-1 text-sm text-slate-600">Perbandingan surat, chatbot, dan aspirasi sesuai periode.</p>
                </div>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <details class="relative">
                        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-gray-800 transition hover:bg-slate-100">
                            <i class="fa-solid fa-calendar-days text-slate-500"></i>
                            {{ $trendPeriodLabels[$trendPeriod] ?? 'Periode' }}
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </summary>
                        <div class="absolute right-0 z-20 mt-2 w-56 rounded-lg border border-slate-200 bg-white p-2 shadow-lg">
                            <p class="px-2 py-1 text-[11px] font-semibold uppercase tracking-[1px] text-slate-500">Pilih periode</p>
                            @foreach($trendPeriodLabels as $period => $label)
                            @if($period === 'hari')
                            <form method="GET" action="{{ request()->url() }}" data-trend-form class="mt-1 rounded-md {{ $trendPeriod === 'hari' ? 'bg-slate-50' : '' }}">
                                <input type="hidden" name="trend_period" value="hari">
                                <label class="flex cursor-pointer items-center justify-between gap-2 px-2 py-2 text-sm font-semibold text-gray-800">
                                    <span>Hari</span>
                                    <input type="date" name="trend_date" value="{{ $trendDate }}" max="{{ now()->toDateString() }}" class="rounded border border-slate-300 px-2 py-1 text-xs text-slate-700 focus:border-orange-500 focus:ring-orange-500" onchange="this.form.submit()">
                                </label>
                            </form>
                            @else
                            <a href="{{ request()->fullUrlWithQuery(['trend_period' => $period]) }}" data-trend-period class="mt-1 flex items-center justify-between rounded-md px-2 py-2 text-sm font-semibold transition {{ $trendPeriod === $period ? 'bg-gray-800 text-white' : 'text-gray-800 hover:bg-slate-100' }}">
                                <span>{{ $label }}</span>
                                @if($trendPeriod === $period)<i class="fa-solid fa-check text-xs"></i>@endif
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </details>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold" aria-label="Pilih seri chart">
                    <button type="button" data-chart-toggle="surat" aria-pressed="true" class="inline-flex items-center gap-1 rounded-md border border-orange-600 bg-orange-600 px-2 py-1 text-white transition hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <i class="fa-solid fa-file-signature text-[11px]"></i>Surat
                    </button>
                    <button type="button" data-chart-toggle="chatbot" aria-pressed="true" class="inline-flex items-center gap-1 rounded-md border border-blue-600 bg-blue-600 px-2 py-1 text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <i class="fa-solid fa-robot text-[11px]"></i>Chatbot
                    </button>
                    <button type="button" data-chart-toggle="aspirasi" aria-pressed="true" class="inline-flex items-center gap-1 rounded-md border border-emerald-600 bg-emerald-600 px-2 py-1 text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <i class="fa-solid fa-comments text-[11px]"></i>Aspirasi
                    </button>
                </div>

            <div class="dashboard-chart-shell mt-5 overflow-hidden rounded-lg border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-2 sm:p-3">
                <svg viewBox="0 0 600 270" class="block h-auto w-full" role="img" aria-label="{{ $trendTitle }}" preserveAspectRatio="xMidYMid meet">
                    <defs>
                        <linearGradient id="suratArea" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#fb923c" stop-opacity="0.22" />
                            <stop offset="100%" stop-color="#fb923c" stop-opacity="0" />
                        </linearGradient>
                        <linearGradient id="chatbotArea" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#2563eb" stop-opacity="0.22" />
                            <stop offset="100%" stop-color="#2563eb" stop-opacity="0" />
                        </linearGradient>
                        <linearGradient id="aspirasiArea" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#059669" stop-opacity="0.22" />
                            <stop offset="100%" stop-color="#059669" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    @for($i = 0; $i <= 4; $i++)
                    @php
                    $y = 46 + ($i * 41);
                    $tickValue = max(0, round($maxTrend - (($maxTrend / 4) * $i)));
                    @endphp
                    <line x1="54" y1="{{ $y }}" x2="560" y2="{{ $y }}" stroke="#cbd5e1" stroke-width="1" stroke-dasharray="4 7" />
                    <text x="42" y="{{ $y + 4 }}" text-anchor="end" font-size="11" fill="#64748b">{{ $tickValue }}</text>
                    @endfor
                    <line x1="54" y1="210" x2="560" y2="210" stroke="#94a3b8" stroke-width="1.5" />
                    <path data-chart-series="surat" d="{{ $suratAreaPath }}" fill="url(#suratArea)" />
                    <path data-chart-series="chatbot" d="{{ $chatbotAreaPath }}" fill="url(#chatbotArea)" />
                    <path data-chart-series="aspirasi" d="{{ $aspirasiAreaPath }}" fill="url(#aspirasiArea)" />
                    <path data-chart-series="surat" d="{{ $suratPath }}" fill="none" stroke="#f97316" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path data-chart-series="chatbot" d="{{ $chatbotPath }}" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path data-chart-series="aspirasi" d="{{ $aspirasiPath }}" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    @foreach($trendRows->values() as $index => $row)
                    @php
                    $x = round($pointX($index), 1);
                    $suratY = round($pointY((int) ($row['surat'] ?? 0)), 1);
                    $chatbotY = round($pointY((int) ($row['chatbot'] ?? 0)), 1);
                    $aspirasiY = round($pointY((int) ($row['aspirasi'] ?? 0)), 1);
                    $displayLabel = $trendPeriod === 'bulan' ? str($row['label'])->before(' ') : $row['label'];
                    @endphp
                    <line x1="{{ $x }}" y1="210" x2="{{ $x }}" y2="216" stroke="#94a3b8" stroke-width="1" />
                    <circle data-chart-series="surat" data-chart-point data-chart-category="Surat" data-chart-value="{{ $row['surat'] ?? 0 }}" data-chart-month="{{ $row['label'] }}" cx="{{ $x }}" cy="{{ $suratY }}" r="8" fill="transparent" stroke="transparent" opacity="0" class="cursor-pointer" />
                    <circle data-chart-series="chatbot" data-chart-point data-chart-category="Chatbot" data-chart-value="{{ $row['chatbot'] ?? 0 }}" data-chart-month="{{ $row['label'] }}" cx="{{ $x }}" cy="{{ $chatbotY }}" r="8" fill="transparent" stroke="transparent" opacity="0" class="cursor-pointer" />
                    <circle data-chart-series="aspirasi" data-chart-point data-chart-category="Aspirasi" data-chart-value="{{ $row['aspirasi'] ?? 0 }}" data-chart-month="{{ $row['label'] }}" cx="{{ $x }}" cy="{{ $aspirasiY }}" r="8" fill="transparent" stroke="transparent" opacity="0" class="cursor-pointer" />
                    @if($trendLabelInterval === 1 || $index % $trendLabelInterval === 0 || $index === $trendRows->count() - 1)
                    <text x="{{ $x }}" y="230" text-anchor="middle" font-size="11" fill="#475569">{{ $displayLabel }}</text>
                    @if($trendPeriod === 'minggu' && !empty($row['subLabel']))
                    <text x="{{ $x }}" y="247" text-anchor="middle" font-size="10" fill="#64748b">{{ $row['subLabel'] }}</text>
                    @endif
                    @endif
                    @endforeach
                    <g id="chartTooltip" class="hidden" pointer-events="none">
                        <rect x="0" y="0" width="112" height="36" rx="7" fill="#ffffff" stroke="#cbd5e1" stroke-width="1.5" />
                        <text id="chartTooltipLabel" x="56" y="14" text-anchor="middle" font-size="9" font-weight="700" fill="#374151"></text>
                        <text id="chartTooltipValue" x="56" y="28" text-anchor="middle" font-size="11" font-weight="700" fill="#111827"></text>
                    </g>
                </svg>
            </div>

            @if($peakRow)
            <div class="mt-3 rounded-md border border-orange-200 bg-orange-50 px-3 py-2 text-sm text-slate-700">
                <span class="font-semibold text-orange-700">Periode paling aktif:</span>
                {{ $peakRow['label'] }} dengan {{ number_format(max((int) ($peakRow['surat'] ?? 0), (int) ($peakRow['chatbot'] ?? 0), (int) ($peakRow['aspirasi'] ?? 0))) }} aktivitas tertinggi.
            </div>
            @endif

            <div class="mt-4 grid grid-cols-2 gap-3 md:grid-cols-4">
                <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">Puncak Surat</p>
                    <p class="text-xl font-bold text-slate-900">{{ number_format($trendRows->max('surat') ?? 0) }}</p>
                </div>
                <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">Puncak Chatbot</p>
                    <p class="text-xl font-bold text-slate-900">{{ number_format($trendRows->max('chatbot') ?? 0) }}</p>
                </div>
                <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">Puncak Aspirasi</p>
                    <p class="text-xl font-bold text-slate-900">{{ number_format($trendRows->max('aspirasi') ?? 0) }}</p>
                </div>
                <div class="rounded-md border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs text-slate-500">Total View Konten</p>
                    <p class="text-xl font-bold text-slate-900">{{ number_format($contentStats['totalViews'] ?? 0) }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm xl:col-span-4">
            <h2 class="text-lg font-bold text-gray-800">Status Akun Warga</h2>
            <p class="mt-1 text-sm text-slate-600">{{ number_format($akunStats['totalAll']) }} total akun semua peran.</p>

            <div class="mt-6 flex items-center justify-center">
                <div class="relative h-48 w-48 rounded-full"
                    style="background: conic-gradient(#059669 0 {{ $approvalRate }}%, #f59e0b {{ $approvalRate }}% 100%);">
                    <div class="absolute inset-[18px] flex items-center justify-center rounded-full border border-slate-200 bg-white">
                        <div class="text-center">
                            <p class="text-4xl font-bold text-slate-900">{{ $approvalRate }}%</p>
                            <p class="text-xs font-semibold uppercase tracking-[1px] text-slate-500">Disetujui</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3">
                    <p class="text-sm font-semibold text-emerald-700">Disetujui</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-700">{{ number_format($akunStats['approved']) }}</p>
                </div>
                <div class="rounded-md border border-amber-200 bg-amber-100 p-3">
                    <p class="text-sm font-semibold text-amber-700">Menunggu</p>
                    <p class="mt-1 text-2xl font-bold text-amber-700">{{ number_format($akunStats['pending']) }}</p>
                </div>
            </div>

            <div class="mt-5 space-y-3">
                @foreach($roleSummary as $role => $total)
                @php $roleWidth = $akunStats['totalAll'] > 0 ? max(4, ($total / $akunStats['totalAll']) * 100) : 0; @endphp
                <div>
                    <div class="mb-1 flex justify-between text-xs font-semibold uppercase tracking-[1px] text-slate-600">
                        <span>{{ $role }}</span>
                        <span>{{ number_format($total) }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-200">
                        <div class="h-2 rounded-full bg-orange-500" style="width: {{ $roleWidth }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 xl:grid-cols-12">
        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm xl:col-span-7">
            <h2 class="text-lg font-bold text-gray-800">Distribusi Surat</h2>
            <p class="mt-1 text-sm text-slate-600">Status dan jenis surat yang paling sering diajukan.</p>

            <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-slate-700">Status Surat</p>
                    @forelse($suratByStatus as $status => $total)
                    @php $width = max(4, ($total / $maxStatus) * 100); @endphp
                    <div>
                        <div class="mb-1 flex justify-between text-xs font-semibold uppercase tracking-[1px] text-slate-600">
                            <span>{{ $status }}</span>
                            <span>{{ number_format($total) }}</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-200">
                            <div class="h-3 rounded-full bg-orange-500" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-500">Belum ada data surat.</p>
                    @endforelse
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-slate-700">Jenis Surat</p>
                    @forelse($suratByJenis as $jenis => $total)
                    @php $width = max(4, ($total / $maxJenis) * 100); @endphp
                    <div>
                        <div class="mb-1 flex justify-between gap-3 text-xs font-semibold uppercase tracking-[1px] text-slate-600">
                            <span class="truncate">{{ str_replace('_', ' ', $jenis) }}</span>
                            <span>{{ number_format($total) }}</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-200">
                            <div class="h-3 rounded-full bg-blue-600" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-slate-500">Belum ada jenis surat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm xl:col-span-5">
            <h2 class="text-lg font-bold text-gray-800">Performa Konten</h2>
            <p class="mt-1 text-sm text-slate-600">Publikasi dan akumulasi kunjungan berita serta artikel.</p>

            <div class="mt-5 space-y-4">
                @foreach([
                    ['label' => 'Berita Dipublikasikan', 'value' => $contentStats['beritaPublished'] ?? 0, 'tone' => 'bg-orange-500'],
                    ['label' => 'Artikel Dipublikasikan', 'value' => $contentStats['artikelPublished'] ?? 0, 'tone' => 'bg-emerald-600'],
                    ['label' => 'Berita Dilihat', 'value' => $contentStats['beritaViews'] ?? 0, 'tone' => 'bg-blue-600'],
                    ['label' => 'Artikel Dilihat', 'value' => $contentStats['artikelViews'] ?? 0, 'tone' => 'bg-slate-700'],
                ] as $item)
                @php $width = max(4, ($item['value'] / $maxContent) * 100); @endphp
                <div>
                    <div class="mb-1 flex justify-between text-sm text-slate-700">
                        <span>{{ $item['label'] }}</span>
                        <strong>{{ number_format($item['value']) }}</strong>
                    </div>
                    <div class="h-4 rounded-md bg-slate-200">
                        <div class="h-4 rounded-md {{ $item['tone'] }}" style="width: {{ $width }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Akses Cepat</h2>
                <p class="mt-1 text-sm text-slate-600">Menu yang paling sering dipakai untuk operasional harian.</p>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2">
            @unless($isPetugas)
            <a href="{{ route($routePrefix . '.users.index') }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Kelola Pengguna</a>
            @endunless
            <a href="{{ route($routePrefix . '.surat-pengajuan.index') }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Pengajuan Surat</a>
            <a href="{{ route($routePrefix . '.aspirasi.index') }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Aspirasi / Keluhan</a>
            <a href="{{ route($routePrefix . '.comments.index') }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Moderasi Komentar</a>
            <a href="{{ route($routePrefix . '.berita.index') }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Berita</a>
            <a href="{{ route($routePrefix . '.artikel.index') }}" class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Artikel</a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const numberFormat = new Intl.NumberFormat('id-ID');

    const bindChartInteractions = () => {
        const tooltip = document.getElementById('chartTooltip');
        const tooltipLabel = document.getElementById('chartTooltipLabel');
        const tooltipValue = document.getElementById('chartTooltipValue');

        const showChartTooltip = (point) => {
            if (!tooltip || !tooltipLabel || !tooltipValue) return;

            const pointX = Number(point.getAttribute('cx'));
            const pointY = Number(point.getAttribute('cy'));
            const tooltipX = pointX > 440 ? pointX - 122 : pointX + 10;
            const tooltipY = Math.min(Math.max(pointY - 18, 8), 174);

            tooltip.setAttribute('transform', `translate(${tooltipX} ${tooltipY})`);
            tooltipLabel.textContent = `${point.dataset.chartCategory} | ${point.dataset.chartMonth}`;
            tooltipValue.textContent = numberFormat.format(Number(point.dataset.chartValue || 0));
            tooltip.classList.remove('hidden');
        };

        const hideChartTooltip = () => tooltip?.classList.add('hidden');

        document.querySelectorAll('[data-chart-point]').forEach((point) => {
            point.addEventListener('click', () => showChartTooltip(point));
            point.addEventListener('mouseenter', () => showChartTooltip(point));
            point.addEventListener('mouseleave', hideChartTooltip);
            point.addEventListener('focus', () => showChartTooltip(point));
            point.addEventListener('blur', hideChartTooltip);
            point.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    showChartTooltip(point);
                }
            });
        });

        document.querySelectorAll('[data-chart-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const series = button.dataset.chartToggle;
                const isVisible = button.getAttribute('aria-pressed') === 'true';

                document.querySelectorAll(`[data-chart-series="${series}"]`).forEach((element) => {
                    element.classList.toggle('hidden', isVisible);
                });
                button.setAttribute('aria-pressed', String(!isVisible));
                button.classList.toggle('opacity-50', isVisible);
            });
        });
    };

    const updateChartPanel = async (url) => {
        const currentPanel = document.getElementById('activityChartPanel');
        if (!currentPanel) return;

        currentPanel.classList.add('opacity-60', 'transition-opacity');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!response.ok) throw new Error('Gagal memuat data chart.');

            const html = await response.text();
            const documentParser = new DOMParser();
            const nextDocument = documentParser.parseFromString(html, 'text/html');
            const nextPanel = nextDocument.getElementById('activityChartPanel');
            if (!nextPanel) throw new Error('Panel chart tidak ditemukan.');

            currentPanel.replaceWith(nextPanel);
            window.history.pushState({}, '', url);
            bindChartInteractions();
        } catch (error) {
            currentPanel.classList.remove('opacity-60');
            console.error(error);
        }
    };

    document.addEventListener('click', (event) => {
        const periodLink = event.target.closest('[data-trend-period]');
        if (!periodLink) return;

        event.preventDefault();
        updateChartPanel(periodLink.href);
        periodLink.closest('details')?.removeAttribute('open');
    });

    document.addEventListener('submit', (event) => {
        const trendForm = event.target.closest('[data-trend-form]');
        if (!trendForm) return;

        event.preventDefault();
        updateChartPanel(`${trendForm.action}?${new URLSearchParams(new FormData(trendForm))}`);
        trendForm.closest('details')?.removeAttribute('open');
    });

    bindChartInteractions();
})();
</script>
@endpush
