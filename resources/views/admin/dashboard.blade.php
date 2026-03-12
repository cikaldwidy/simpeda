@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | ' . (((auth()->user()->role ?? null) === 'petugas') ? 'Dashboard Petugas' :
'Dashboard Admin'))

@section('content')
@php
$isPetugas = (auth()->user()->role ?? null) === 'petugas';
$routePrefix = request()->routeIs('petugas.*') ? 'petugas' : 'admin';
$monthlyRows = $monthlyRows ?? [];
$suratStats = $suratStats ?? ['total' => 0, 'week' => 0, 'month' => 0];
$chatbotStats = $chatbotStats ?? ['total' => 0, 'week' => 0, 'month' => 0, 'sessionsMonth' => 0];
$akunStats = $akunStats ?? ['totalAll' => 0, 'totalWarga' => 0, 'pending' => 0, 'approved' => 0];
$contentStats = $contentStats ?? ['beritaPublished' => 0, 'artikelPublished' => 0, 'beritaViews' => 0, 'artikelViews' =>
0];
$suratByStatus = $suratByStatus ?? [];
$suratByJenis = $suratByJenis ?? [];
$roleSummary = $roleSummary ?? [];
$suratGrowth = $suratStats['month'] > 0 ? round(($suratStats['week'] / max($suratStats['month'] / 4, 1)) * 100, 1) : 0;
$chatbotGrowth = $chatbotStats['month'] > 0 ? round(($chatbotStats['week'] / max($chatbotStats['month'] / 4, 1)) * 100,
1) : 0;
$approvalRate = $akunStats['totalWarga'] > 0 ? round(($akunStats['approved'] / $akunStats['totalWarga']) * 100) : 0;
$maxSurat = max(collect($monthlyRows)->max('surat') ?? 1, 1);
$maxRegister = max(collect($monthlyRows)->max('registrasi') ?? 1, 1);
$maxKontenPublikasi = 1;
foreach ($monthlyRows as $row) {
$totalKontenBulanan = (int) ($row['berita_publikasi'] ?? 0) + (int) ($row['artikel_publikasi'] ?? 0);
$maxKontenPublikasi = max($maxKontenPublikasi, $totalKontenBulanan);
}
$maxKontenViews = max($contentStats['beritaViews'] ?? 0, $contentStats['artikelViews'] ?? 0, 1);
@endphp

<div class="space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-[1px] md:text-left text-center">
                    {{ $isPetugas ? 'Dashboard Petugas' : 'Dashboard Admin' }}
                </h1>
                <p class="mt-2 text-base text-slate-600 md:text-left text-center">
                    {{ $isPetugas ? 'Ringkasan layanan desa dari panel petugas.' : 'Ringkasan layanan desa dari panel Admin.' }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
        <div class="xl:col-span-5 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-lg font-semibold text-slate-800">Selamat datang, {{ auth()->user()->name }}</p>
            <p class="mt-1 text-sm text-slate-600">
                {{ $isPetugas ? 'Pantau metrik utama layanan dari panel petugas.' : 'Pantau metrik utama layanan dari panel admin.' }}
            </p>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                    <p class="text-sm text-slate-600">Total Surat</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($suratStats['total']) }}</p>
                    <p class="mt-1 text-sm font-semibold text-emerald-600">{{ $suratGrowth }}% ritme mingguan</p>
                </div>
                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm text-slate-600">Akses Chatbot</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($chatbotStats['total']) }}</p>
                    <p class="mt-1 text-sm font-semibold text-emerald-600">{{ $chatbotGrowth }}% ritme mingguan</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="mb-1 text-sm font-semibold text-slate-700">Aktivitas Surat Bulanan (12 bulan)</p>
                <p class="mb-2 text-xs text-slate-500">Jumlah pengajuan surat baru pada tiap bulan. Hover/klik batang
                    untuk detail.</p>
                <div data-chart data-info-target="suratChartInfo"
                    class="relative flex h-28 items-end gap-1 rounded-xl border border-slate-200 bg-slate-50 px-2 py-2">
                    <div data-tooltip
                        class="pointer-events-none absolute hidden rounded-md bg-slate-900 px-2 py-1 text-xs text-white shadow">
                    </div>
                    @foreach($monthlyRows as $row)
                    @php $h = max(10, (int) round(($row['surat'] / $maxSurat) * 92)); @endphp
                    <button type="button" data-bar data-label="{{ $row['label'] }}" data-value="{{ $row['surat'] }}"
                        class="w-full self-end rounded-sm bg-gradient-to-t from-orange-500 to-orange-300 transition hover:from-orange-600 hover:to-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400"
                        style="height: {{ $h }}px" aria-label="{{ $row['label'] }}: {{ $row['surat'] }} surat"></button>
                    @endforeach
                </div>
                <p id="suratChartInfo" class="mt-2 text-xs text-emerald-600">klik batang chart
                    untuk melihat rincian periode dan jumlah surat.</p>
            </div>
        </div>

        <div class="xl:col-span-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm uppercase tracking-[1px] text-slate-500">Total Pengguna</p>
            <div class="mt-2 flex items-end gap-2">
                <p class="text-4xl font-bold text-slate-900">{{ number_format($akunStats['totalAll']) }}</p>
                <span class="text-sm text-slate-500">semua peran</span>
            </div>
            <p class="mt-1 text-sm text-slate-600">Warga {{ number_format($akunStats['totalWarga']) }} | Menunggu
                {{ number_format($akunStats['pending']) }}</p>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm text-slate-600">7 Hari</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($suratStats['week']) }}</p>
                    <p class="text-sm font-medium text-emerald-600">Surat baru</p>
                </div>
                <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm text-slate-600">30 Hari</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($chatbotStats['month']) }}</p>
                    <p class="text-sm font-medium text-emerald-600">Interaksi bot</p>
                </div>
            </div>

            <div class="mt-6">
                <p class="mb-1 text-sm font-semibold text-slate-700">Registrasi Bulanan (12 bulan)</p>
                <p class="mb-2 text-xs text-slate-500">Jumlah akun warga yang mendaftar per bulan. Hover/klik batang
                    untuk detail.</p>
                <div data-chart data-info-target="registerChartInfo"
                    class="relative flex h-24 items-end gap-1 rounded-md border border-slate-200 bg-slate-50 px-2 py-2">
                    <div data-tooltip
                        class="pointer-events-none absolute hidden rounded-md bg-slate-900 px-2 py-1 text-xs text-white shadow">
                    </div>
                    @foreach($monthlyRows as $row)
                    @php $h = max(8, (int) round(($row['registrasi'] / $maxRegister) * 78)); @endphp
                    <button type="button" data-bar data-label="{{ $row['label'] }}"
                        data-value="{{ $row['registrasi'] }}"
                        class="w-full self-end rounded-sm bg-gradient-to-t from-slate-700 to-slate-500 transition hover:from-slate-800 hover:to-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-400"
                        style="height: {{ $h }}px"
                        aria-label="{{ $row['label'] }}: {{ $row['registrasi'] }} registrasi"></button>
                    @endforeach
                </div>
                <p id="registerChartInfo" class="mt-2 text-xs text-emerald-600">Klik batang chart
                    untuk melihat rincian periode dan jumlah registrasi.</p>
            </div>
        </div>

        <div class="xl:col-span-3 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm uppercase tracking-[1px] text-slate-500">Pengguna Aktif</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ number_format($chatbotStats['sessionsMonth']) }}</p>
            <p class="mt-1 text-sm text-slate-600">Sesi chatbot unik 30 hari terakhir</p>

            <div class="mt-6 flex items-center justify-center">
                <div class="relative h-40 w-40 rounded-full"
                    style="background: conic-gradient(#f97316 {{ $approvalRate }}%, #e2e8f0 0);">
                    <div
                        class="absolute inset-[14px] flex items-center justify-center rounded-full bg-white border border-slate-200">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-slate-900">{{ $approvalRate }}%</p>
                            <p class="text-xs uppercase tracking-[1px] text-slate-500">Disetujui</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3">
                    <p class="text-sm text-emerald-700">Disetujui</p>
                    <p class="text-xl font-bold text-emerald-700">{{ number_format($akunStats['approved']) }}</p>
                </div>
                <div class="rounded-md border border-amber-200 bg-amber-50 p-3">
                    <p class="text-sm text-amber-700">Menunggu</p>
                    <p class="text-xl font-bold text-amber-700">{{ number_format($akunStats['pending']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Performa Berita & Artikel</h2>
        <p class="mt-1 text-sm text-slate-600">Statistik konten yang sudah dipublikasikan dan total jumlah dilihat.</p>

        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Berita Dipublikasikan</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">
                    {{ number_format($contentStats['beritaPublished'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-500">Konten berita aktif di publik.</p>
            </div>
            <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Artikel Dipublikasikan</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">
                    {{ number_format($contentStats['artikelPublished'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-500">Konten artikel aktif di publik.</p>
            </div>
            <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Berita Dilihat</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">{{ number_format($contentStats['beritaViews'] ?? 0) }}
                </p>
                <p class="mt-1 text-xs text-slate-500">Akumulasi kunjungan halaman detail berita.</p>
            </div>
            <div class="rounded-md border border-orange-200 bg-orange-50 p-4">
                <p class="text-sm text-slate-600">Artikel Dilihat</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">
                    {{ number_format($contentStats['artikelViews'] ?? 0) }}</p>
                <p class="mt-1 text-xs text-slate-500">Akumulasi kunjungan halaman detail artikel.</p>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-4 xl:grid-cols-2">
            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <p class="mb-1 text-sm font-semibold text-slate-700">Publikasi Konten Bulanan (12 bulan)</p>
                <p class="mb-2 text-xs text-slate-500">Jumlah berita + artikel yang dipublikasikan per bulan. Hover/klik
                    batang untuk detail.</p>
                <div data-chart data-info-target="kontenPublikasiInfo"
                    class="relative flex h-28 items-end gap-1 rounded-md border border-slate-200 bg-white px-2 py-2">
                    <div data-tooltip
                        class="pointer-events-none absolute hidden rounded-md bg-slate-900 px-2 py-1 text-xs text-white shadow">
                    </div>
                    @foreach($monthlyRows as $row)
                    @php
                    $totalKonten = ($row['berita_publikasi'] ?? 0) + ($row['artikel_publikasi'] ?? 0);
                    $h = max(8, (int) round(($totalKonten / $maxKontenPublikasi) * 92));
                    @endphp
                    <button type="button" data-bar data-label="{{ $row['label'] }}" data-value="{{ $totalKonten }}"
                        class="w-full self-end rounded-sm bg-gradient-to-t from-orange-500 to-orange-300 transition hover:from-orange-600 hover:to-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-400"
                        style="height: {{ $h }}px" aria-label="{{ $row['label'] }}: {{ $totalKonten }} konten"></button>
                    @endforeach
                </div>
                <p id="kontenPublikasiInfo" class="mt-2 text-xs text-emerald-600">klik batang chart
                    untuk melihat jumlah publikasi konten per bulan.</p>
            </div>

            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <p class="mb-1 text-sm font-semibold text-slate-700">Perbandingan Total Dilihat</p>
                <p class="mb-2 text-xs text-slate-500">Akumulasi jumlah dilihat dari konten yang dipublikasikan.</p>
                <div data-chart data-info-target="kontenViewsInfo"
                    class="relative flex h-28 items-end gap-3 rounded-md border border-slate-200 bg-white px-4 py-2">
                    <div data-tooltip
                        class="pointer-events-none absolute hidden rounded-md bg-slate-900 px-2 py-1 text-xs text-white shadow">
                    </div>
                    @php
                    $hBerita = max(10, (int) round((($contentStats['beritaViews'] ?? 0) / $maxKontenViews) * 92));
                    $hArtikel = max(10, (int) round((($contentStats['artikelViews'] ?? 0) / $maxKontenViews) * 92));
                    @endphp
                    <button type="button" data-bar data-label="Dilihat Berita"
                        data-value="{{ $contentStats['beritaViews'] ?? 0 }}"
                        class="w-full self-end rounded-sm bg-gradient-to-t from-blue-600 to-blue-400 transition hover:from-blue-700 hover:to-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        style="height: {{ $hBerita }}px"
                        aria-label="Dilihat Berita: {{ $contentStats['beritaViews'] ?? 0 }}"></button>
                    <button type="button" data-bar data-label="Dilihat Artikel"
                        data-value="{{ $contentStats['artikelViews'] ?? 0 }}"
                        class="w-full self-end rounded-sm bg-gradient-to-t from-emerald-600 to-emerald-400 transition hover:from-emerald-700 hover:to-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-300"
                        style="height: {{ $hArtikel }}px"
                        aria-label="Dilihat Artikel: {{ $contentStats['artikelViews'] ?? 0 }}"></button>
                </div>
                <p id="kontenViewsInfo" class="mt-2 text-xs text-emerald-600">klik batang chart
                    untuk melihat total view berita dan artikel.</p>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Rekap Lainnya</h2>

        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-700">Status Surat</p>
                @forelse($suratByStatus as $status => $total)
                <div class="mt-2 flex justify-between text-sm text-slate-700"><span
                        class="uppercase">{{ $status }}</span><strong>{{ number_format($total) }}</strong></div>
                @empty
                <p class="mt-2 text-sm text-slate-500">-</p>
                @endforelse
            </div>

            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-700">Jenis Surat</p>
                @forelse($suratByJenis as $jenis => $total)
                <div class="mt-2 flex justify-between text-sm text-slate-700"><span
                        class="uppercase">{{ str_replace('_', ' ', $jenis) }}</span><strong>{{ number_format($total) }}</strong>
                </div>
                @empty
                <p class="mt-2 text-sm text-slate-500">-</p>
                @endforelse
            </div>

            <div class="rounded-md border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-700">Komposisi Peran</p>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    @foreach($roleSummary as $role => $total)
                    <div class="rounded-md bg-white border border-slate-200 p-2 text-center">
                        <p class="text-[11px] uppercase text-slate-500">{{ $role }}</p>
                        <p class="text-base font-bold text-slate-900">{{ number_format($total) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-4 flex gap-3">
            <a href="{{ route($routePrefix . '.users.index') }}"
                class="text-sm text-blue-700 underline hover:text-blue-500">Kelola Pengguna</a>
            <a href="{{ route($routePrefix . '.surat-pengajuan.index') }}"
                class="text-sm text-blue-700 underline hover:text-blue-500">Lihat
                Surat</a>
            <a href="{{ route($routePrefix . '.berita.index') }}"
                class="text-sm text-blue-700 underline hover:text-blue-500">Lihat
                Berita</a>
            <a href="{{ route($routePrefix . '.artikel.index') }}"
                class="text-sm text-blue-700 underline hover:text-blue-500">Lihat
                Artikel</a>
            @unless($isPetugas)
            <a href="{{ route('admin.faq.index') }}" class="text-sm text-blue-700 underline hover:text-blue-500">Kelola
                FAQ</a>
            @endunless
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-chart]').forEach((chartEl) => {
    const tooltip = chartEl.querySelector('[data-tooltip]');
    const infoTargetId = chartEl.dataset.infoTarget;
    const infoEl = infoTargetId ? document.getElementById(infoTargetId) : null;
    const bars = chartEl.querySelectorAll('[data-bar]');

    const showTooltip = (bar, event) => {
        if (!tooltip) return;
        const label = bar.dataset.label || '-';
        const value = bar.dataset.value || '0';
        tooltip.textContent = `${label}: ${value}`;
        tooltip.classList.remove('hidden');

        const chartRect = chartEl.getBoundingClientRect();
        const barRect = bar.getBoundingClientRect();

        const x = (barRect.left - chartRect.left) + (barRect.width / 2);
        tooltip.style.left = `${x}px`;
        tooltip.style.top = `6px`;
        tooltip.style.transform = 'translateX(-50%)';

        if (infoEl) {
            infoEl.textContent = `${label}: ${value}`;
        }
    };

    const hideTooltip = () => {
        if (!tooltip) return;
        tooltip.classList.add('hidden');
    };

    bars.forEach((bar) => {
        bar.addEventListener('mouseenter', (e) => showTooltip(bar, e));
        bar.addEventListener('mousemove', (e) => showTooltip(bar, e));
        bar.addEventListener('click', (e) => showTooltip(bar, e));
        bar.addEventListener('focus', (e) => showTooltip(bar, e));
        bar.addEventListener('mouseleave', hideTooltip);
        bar.addEventListener('blur', hideTooltip);
    });
});
</script>
@endpush