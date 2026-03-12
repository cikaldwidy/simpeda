<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPengajuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuratPengajuanAdminController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas'], true), 403);

        $search = trim((string) $request->query('q', ''));

        $pengajuans = SuratPengajuan::query()
            ->with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_surat', 'like', '%' . $search . '%')
                        ->orWhere('jenis_surat', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', '%' . $search . '%')
                                ->orWhere('nik', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.surat_pengajuan.index', [
            'pengajuans' => $pengajuans,
            'jenisOptions' => SuratPengajuan::jenisOptions(),
            'search' => $search,
        ]);
    }

    public function updateStatus(Request $request, SuratPengajuan $suratPengajuan): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas'], true), 403);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['menunggu', 'ditolak', 'disetujui'])],
        ]);

        $status = $validated['status'];

        $payload = ['status' => $status];
        if ($status === 'disetujui') {
            $payload['tanggal_surat'] = now()->toDateString();
        }

        $suratPengajuan->update($payload);

        return back()->with('status', 'Status pengajuan diperbarui.');
    }

    public function destroy(Request $request, SuratPengajuan $suratPengajuan): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas'], true), 403);

        $suratPengajuan->delete();

        return back()->with('status', 'Pengajuan surat berhasil dihapus.');
    }
}
