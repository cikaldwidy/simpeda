<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratNomorSetting;
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
            ->paginate(10)
            ->withQueryString();

        return view('admin.surat_pengajuan.index', [
            'pengajuans' => $pengajuans,
            'jenisOptions' => SuratPengajuan::jenisOptions(),
            'nomorSettings' => $this->nomorSettings(),
            'search' => $search,
        ]);
    }

    public function show(Request $request, SuratPengajuan $suratPengajuan): View
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas'], true), 403);

        $suratPengajuan->loadMissing('user');
        $nomorSetting = $this->nomorSettings()[$suratPengajuan->jenis_surat] ?? [
            'kode_klasifikasi' => SuratPengajuan::kodeJenis($suratPengajuan->jenis_surat),
            'kode_wilayah' => SuratPengajuan::KODE_DESA,
        ];
        $tanggalSurat = $suratPengajuan->tanggal_surat ?? $suratPengajuan->created_at ?? now();

        return view('admin.surat_pengajuan.show', [
            'suratPengajuan' => $suratPengajuan,
            'nomorSetting' => $nomorSetting,
            'nomorSuratSuffix' => $nomorSetting['kode_wilayah'] . '/' . $tanggalSurat->format('Y'),
            'canPreview' => in_array($suratPengajuan->status, ['menunggu', 'diajukan', 'disetujui'], true),
        ]);
    }

    public function updateNomorSettings(Request $request): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas'], true), 403);

        $jenisSurat = array_keys(SuratPengajuan::jenisOptions());

        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.jenis_surat' => ['required', Rule::in($jenisSurat)],
            'settings.*.kode_klasifikasi' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9.\-]+$/'],
            'settings.*.kode_wilayah' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9.\-]+$/'],
        ], [
            'settings.required' => 'Data pengaturan nomor surat wajib diisi.',
            'settings.array' => 'Format data pengaturan nomor surat tidak valid.',
            'settings.*.jenis_surat.required' => 'Kategori surat wajib diisi.',
            'settings.*.jenis_surat.in' => 'Kategori surat tidak valid.',
            'settings.*.kode_klasifikasi.required' => 'Kode klasifikasi arsip wajib diisi.',
            'settings.*.kode_klasifikasi.max' => 'Kode klasifikasi arsip maksimal 50 karakter.',
            'settings.*.kode_klasifikasi.regex' => 'Kode klasifikasi hanya boleh berisi huruf, angka, titik, dan strip.',
            'settings.*.kode_wilayah.required' => 'Kode wilayah wajib diisi.',
            'settings.*.kode_wilayah.max' => 'Kode wilayah maksimal 50 karakter.',
            'settings.*.kode_wilayah.regex' => 'Kode wilayah hanya boleh berisi huruf, angka, titik, dan strip.',
        ]);

        foreach ($validated['settings'] as $setting) {
            SuratNomorSetting::updateOrCreate(
                ['jenis_surat' => $setting['jenis_surat']],
                [
                    'kode_klasifikasi' => strtoupper($setting['kode_klasifikasi']),
                    'kode_wilayah' => strtoupper($setting['kode_wilayah']),
                ]
            );
        }

        return back()->with('status', 'Kode nomor surat berhasil diperbarui.');
    }

    public function updateStatus(Request $request, SuratPengajuan $suratPengajuan): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas'], true), 403);

        $status = (string) $request->input('status');
        $nomorUrutInput = trim((string) $request->input('nomor_urut', ''));
        if ($nomorUrutInput !== '' && ctype_digit($nomorUrutInput)) {
            $request->merge([
                'nomor_urut' => ltrim($nomorUrutInput, '0') ?: '0',
            ]);
        }

        $tanggalSurat = $suratPengajuan->tanggal_surat ?? now();
        $tahunNomor = (int) $tanggalSurat->format('Y');

        $validated = $request->validate([
            'status' => ['required', Rule::in(['menunggu', 'ditolak', 'disetujui'])],
            'nomor_urut' => [
                Rule::requiredIf($status === 'disetujui'),
                'nullable',
                'digits_between:1,10',
                'not_in:0',
                Rule::unique('surat_pengajuans', 'nomor_urut')
                    ->where(fn ($query) => $query
                        ->where('tahun', $tahunNomor)
                        ->where('status', 'disetujui'))
                    ->ignore($suratPengajuan->id),
            ],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ], [
            'status.required' => 'Status pengajuan wajib dipilih.',
            'status.in' => 'Status pengajuan tidak valid.',
            'nomor_urut.required' => 'Nomor urut wajib diisi saat pengajuan disetujui.',
            'nomor_urut.digits_between' => 'Nomor urut hanya boleh berisi angka, maksimal 10 digit.',
            'nomor_urut.not_in' => 'Nomor urut harus lebih dari 0.',
            'nomor_urut.unique' => 'Nomor urut ini sudah dipakai untuk surat lain pada tahun yang sama.',
            'admin_note.string' => 'Catatan admin harus berupa teks.',
            'admin_note.max' => 'Catatan admin maksimal 2000 karakter.',
        ]);

        $status = $validated['status'];
        $nomorSurat = null;

        if ($status === 'disetujui') {
            $nomorSurat = SuratPengajuan::buatNomorSurat(
                $validated['nomor_urut'],
                $suratPengajuan->jenis_surat,
                $tanggalSurat
            );

            SuratPengajuan::query()
                ->where('tahun', $tahunNomor)
                ->where('nomor_urut', $validated['nomor_urut'])
                ->where('status', '!=', 'disetujui')
                ->whereKeyNot($suratPengajuan->id)
                ->update([
                    'nomor_surat' => null,
                    'nomor_urut' => null,
                ]);
        }

        $payload = [
            'status' => $status,
            'nomor_surat' => $nomorSurat,
            'nomor_urut' => $status === 'disetujui' ? $validated['nomor_urut'] : null,
            'tahun' => $tahunNomor,
            'admin_note' => $validated['admin_note'] ?? null,
        ];
        if ($status === 'disetujui') {
            $payload['tanggal_surat'] = $tanggalSurat->toDateString();
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

    public function bulkDestroy(Request $request): RedirectResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'petugas'], true), 403);

        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:surat_pengajuans,id'],
        ], [
            'ids.required' => 'Pilih minimal satu pengajuan surat untuk dihapus.',
            'ids.min' => 'Pilih minimal satu pengajuan surat untuk dihapus.',
        ]);

        $deletedCount = SuratPengajuan::query()
            ->whereIn('id', $validated['ids'])
            ->delete();

        return back()->with('status', $deletedCount . ' pengajuan surat berhasil dihapus.');
    }

    private function nomorSettings(): array
    {
        $settings = SuratNomorSetting::query()
            ->get()
            ->keyBy('jenis_surat');

        return collect(SuratPengajuan::jenisOptions())
            ->mapWithKeys(function (string $label, string $jenis) use ($settings) {
                $setting = $settings->get($jenis);

                return [$jenis => [
                    'jenis_surat' => $jenis,
                    'label' => $label,
                    'kode_klasifikasi' => $setting?->kode_klasifikasi ?? SuratPengajuan::kodeJenis($jenis),
                    'kode_wilayah' => $setting?->kode_wilayah ?? SuratPengajuan::KODE_DESA,
                ]];
            })
            ->all();
    }
}
