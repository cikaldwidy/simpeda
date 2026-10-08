<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Artikel;
use App\Models\Berita;
use App\Models\LogChatbot;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $now = Carbon::now();
        $weekStart = $now->copy()->subDays(7);
        $monthStart = $now->copy()->subDays(30);
        $yearStart = $now->copy()->subDays(365);

        $suratStats = [
            'week' => SuratPengajuan::where('created_at', '>=', $weekStart)->count(),
            'month' => SuratPengajuan::where('created_at', '>=', $monthStart)->count(),
            'year' => SuratPengajuan::where('created_at', '>=', $yearStart)->count(),
            'total' => SuratPengajuan::count(),
        ];

        $chatbotStats = [
            'week' => LogChatbot::where('waktu_interaksi', '>=', $weekStart)->count(),
            'month' => LogChatbot::where('waktu_interaksi', '>=', $monthStart)->count(),
            'year' => LogChatbot::where('waktu_interaksi', '>=', $yearStart)->count(),
            'total' => LogChatbot::count(),
            'sessionsMonth' => LogChatbot::where('waktu_interaksi', '>=', $monthStart)
                ->distinct('sesi_id')
                ->count('sesi_id'),
        ];

        $akunStats = [
            'week' => User::where('role', 'warga')->where('created_at', '>=', $weekStart)->count(),
            'month' => User::where('role', 'warga')->where('created_at', '>=', $monthStart)->count(),
            'year' => User::where('role', 'warga')->where('created_at', '>=', $yearStart)->count(),
            'totalWarga' => User::where('role', 'warga')->count(),
            'totalAll' => User::count(),
            'pending' => User::where('role', 'warga')->where('approval_status', 'pending')->count(),
            'approved' => User::where('role', 'warga')->where('approval_status', 'approved')->count(),
        ];

        $aspirasiStats = [
            'week' => Aspirasi::where('created_at', '>=', $weekStart)->count(),
            'month' => Aspirasi::where('created_at', '>=', $monthStart)->count(),
            'year' => Aspirasi::where('created_at', '>=', $yearStart)->count(),
            'total' => Aspirasi::count(),
            'today' => Aspirasi::whereDate('created_at', $now->toDateString())->count(),
            'withImage' => Aspirasi::whereNotNull('gambar')->count(),
        ];

        $suratByStatus = SuratPengajuan::query()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->pluck('total', 'status')
            ->toArray();

        $suratByJenis = SuratPengajuan::query()
            ->select('jenis_surat', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_surat')
            ->orderByDesc('total')
            ->pluck('total', 'jenis_surat')
            ->toArray();

        $roleSummary = User::query()
            ->select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->orderByDesc('total')
            ->pluck('total', 'role')
            ->toArray();

        $hasBeritaViews = Schema::hasColumn('beritas', 'view_count');
        $hasArtikelViews = Schema::hasColumn('artikels', 'view_count');

        $contentStats = [
            'beritaPublished' => Berita::where('is_published', true)->count(),
            'artikelPublished' => Artikel::where('is_published', true)->count(),
            'beritaViews' => $hasBeritaViews ? (int) Berita::sum('view_count') : 0,
            'artikelViews' => $hasArtikelViews ? (int) Artikel::sum('view_count') : 0,
        ];
        $contentStats['totalPublished'] = $contentStats['beritaPublished'] + $contentStats['artikelPublished'];
        $contentStats['totalViews'] = $contentStats['beritaViews'] + $contentStats['artikelViews'];

        $trendPeriod = $request->string('trend_period', 'bulan')->toString();
        $trendPeriod = in_array($trendPeriod, ['hari', 'minggu', 'bulan', 'tahun'], true) ? $trendPeriod : 'bulan';
        $trendDate = $now->toDateString();

        if ($trendPeriod === 'hari' && $request->filled('trend_date')) {
            try {
                $trendDate = Carbon::createFromFormat('Y-m-d', $request->string('trend_date')->toString())->toDateString();
            } catch (\Throwable) {
                $trendDate = $now->toDateString();
            }
        }

        $trendBuckets = match ($trendPeriod) {
            'hari' => collect(range(0, 23))->map(fn (int $hour) => [
                'start' => Carbon::parse($trendDate)->startOfDay()->addHours($hour),
                'end' => Carbon::parse($trendDate)->startOfDay()->addHours($hour)->endOfHour(),
                'label' => str_pad((string) $hour, 2, '0', STR_PAD_LEFT) . ':00',
            ]),
            'minggu' => collect(range(3, 0))->map(function (int $offset) use ($now) {
                $start = $now->copy()->subWeeks($offset)->startOfWeek();

                return [
                    'start' => $start,
                    'end' => $start->copy()->endOfWeek(),
                    'label' => 'Minggu ' . (4 - $offset),
                    'subLabel' => $start->translatedFormat('M Y'),
                ];
            }),
            'tahun' => collect(range(4, 0))->map(fn (int $offset) => [
                'start' => $now->copy()->subYears($offset)->startOfYear(),
                'end' => $now->copy()->subYears($offset)->endOfYear(),
                'label' => $now->copy()->subYears($offset)->format('Y'),
            ]),
            default => collect(range(11, 0))->map(fn (int $offset) => [
                'start' => $now->copy()->subMonths($offset)->startOfMonth(),
                'end' => $now->copy()->subMonths($offset)->endOfMonth(),
                'label' => $now->copy()->subMonths($offset)->translatedFormat('M Y'),
            ]),
        };

        $trendRows = $trendBuckets->map(function (array $bucket) {
            $start = $bucket['start'];
            $end = $bucket['end'];

            return [
                'label' => $bucket['label'],
                'subLabel' => $bucket['subLabel'] ?? null,
                'surat' => SuratPengajuan::whereBetween('created_at', [$start, $end])->count(),
                'chatbot' => LogChatbot::whereBetween('waktu_interaksi', [$start, $end])->count(),
                'aspirasi' => Aspirasi::whereBetween('created_at', [$start, $end])->count(),
                'registrasi' => User::where('role', 'warga')->whereBetween('created_at', [$start, $end])->count(),
                'berita_publikasi' => Berita::where('is_published', true)->whereBetween('created_at', [$start, $end])->count(),
                'artikel_publikasi' => Artikel::where('is_published', true)->whereBetween('created_at', [$start, $end])->count(),
            ];
        })->values();

        return view('admin.dashboard', [
            'suratStats' => $suratStats,
            'chatbotStats' => $chatbotStats,
            'akunStats' => $akunStats,
            'aspirasiStats' => $aspirasiStats,
            'suratByStatus' => $suratByStatus,
            'suratByJenis' => $suratByJenis,
            'roleSummary' => $roleSummary,
            'contentStats' => $contentStats,
            'trendRows' => $trendRows,
            'trendPeriod' => $trendPeriod,
            'trendDate' => $trendDate,
        ]);
    }
}
