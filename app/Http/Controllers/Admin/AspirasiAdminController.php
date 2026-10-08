<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AspirasiAdminController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $aspirasis = Aspirasi::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', '%' . $search . '%')
                        ->orWhere('jenis_kelamin', 'like', '%' . $search . '%')
                        ->orWhere('isi_aspirasi', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Aspirasi::count(),
            'withImage' => Aspirasi::whereNotNull('gambar')->count(),
            'today' => Aspirasi::whereDate('created_at', today())->count(),
        ];

        return view('admin.aspirasi.index', [
            'aspirasis' => $aspirasis,
            'stats' => $stats,
            'search' => $search,
        ]);
    }

    public function destroy(Aspirasi $aspirasi): RedirectResponse
    {
        if ($aspirasi->gambar) {
            Storage::disk('public')->delete($aspirasi->gambar);
        }

        $aspirasi->delete();

        return back()->with('status', 'Aspirasi warga berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct', 'exists:aspirasis,id'],
        ]);

        $aspirasis = Aspirasi::whereKey($data['ids'])->get();

        foreach ($aspirasis as $aspirasi) {
            if ($aspirasi->gambar) {
                Storage::disk('public')->delete($aspirasi->gambar);
            }

            $aspirasi->delete();
        }

        return back()->with('status', $aspirasis->count() . ' aspirasi warga berhasil dihapus.');
    }
}
