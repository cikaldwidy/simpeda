<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $suratQuery = SuratPengajuan::query()
            ->where('user_id', $user->id);

        $riwayat = (clone $suratQuery)
            ->latest()
            ->take(10)
            ->get();

        $totalPengajuan = (clone $suratQuery)->count();
        $totalMenunggu = (clone $suratQuery)->where('status', 'menunggu')->count();
        $totalDisetujui = (clone $suratQuery)->where('status', 'disetujui')->count();
        $totalDitolak = (clone $suratQuery)->where('status', 'ditolak')->count();

        return view('dashboard.index', [
            'user' => $user,
            'riwayat' => $riwayat,
            'totalPengajuan' => $totalPengajuan,
            'totalMenunggu' => $totalMenunggu,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
        ]);
    }

    public function status(Request $request): View
    {
        $user = $request->user();
        $query = SuratPengajuan::query()->where('user_id', $user->id);

        return view('dashboard.status', [
            'user' => $user,
            'totalPengajuan' => (clone $query)->count(),
            'totalMenunggu' => (clone $query)->where('status', 'menunggu')->count(),
            'totalDisetujui' => (clone $query)->where('status', 'disetujui')->count(),
            'totalDitolak' => (clone $query)->where('status', 'ditolak')->count(),
            'statusRows' => (clone $query)->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function dokumen(Request $request): View
    {
        $user = $request->user();
        $query = SuratPengajuan::query()->where('user_id', $user->id);

        $dokumen = (clone $query)
            ->where('status', 'disetujui')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('dashboard.dokumen', [
            'user' => $user,
            'dokumen' => $dokumen,
        ]);
    }

    public function riwayat(): RedirectResponse
    {
        return redirect()->route('dashboard.dokumen');
    }
}
