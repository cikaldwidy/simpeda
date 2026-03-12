<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Berita;
use App\Models\LogChatbot;
use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
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

        $monthlyRows = collect(range(11, 0))->map(function (int $offset) use ($now) {
            $start = $now->copy()->subMonths($offset)->startOfMonth();
            $end = $now->copy()->subMonths($offset)->endOfMonth();

            return [
                'label' => $start->translatedFormat('M Y'),
                'surat' => SuratPengajuan::whereBetween('created_at', [$start, $end])->count(),
                'chatbot' => LogChatbot::whereBetween('waktu_interaksi', [$start, $end])->count(),
                'registrasi' => User::where('role', 'warga')
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
                'berita_publikasi' => Berita::where('is_published', true)
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
                'artikel_publikasi' => Artikel::where('is_published', true)
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
            ];
        })->values();

        return view('admin.dashboard', [
            'suratStats' => $suratStats,
            'chatbotStats' => $chatbotStats,
            'akunStats' => $akunStats,
            'suratByStatus' => $suratByStatus,
            'suratByJenis' => $suratByJenis,
            'roleSummary' => $roleSummary,
            'contentStats' => $contentStats,
            'monthlyRows' => $monthlyRows,
        ]);
    }
}
