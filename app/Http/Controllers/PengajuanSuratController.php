<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PengajuanSuratController extends Controller
{
    public function create(Request $request): View
    {
        $jenisOptions = SuratPengajuan::jenisOptions();
        $selectedJenis = (string) $request->query('jenis', SuratPengajuan::JENIS_DOMISILI);
        if (!array_key_exists($selectedJenis, $jenisOptions)) {
            $selectedJenis = SuratPengajuan::JENIS_DOMISILI;
        }

        return view('layanan.surat.create', [
            'user' => $request->user(),
            'jenisOptions' => $jenisOptions,
            'selectedJenis' => $selectedJenis,
            'alamatRingkas' => $this->alamatRingkas($request->user()),
            'ttlFormatted' => $this->ttlFormatted($request->user()),
            'domisiliAlamat' => $this->domisiliAlamat($request->user()),
            'alamatDomisiliLines' => $this->alamatDomisiliLines($request->user()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $baseRules = [
            'jenis_surat' => ['required', 'in:' . implode(',', array_keys(SuratPengajuan::jenisOptions()))],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ];

        $jenis = $request->string('jenis_surat')->toString();

        if ($jenis === SuratPengajuan::JENIS_DOMISILI) {
            $request->validate($baseRules);
        } elseif ($jenis === SuratPengajuan::JENIS_TIDAK_MAMPU) {
            $request->validate($baseRules + [
                'status_perkawinan' => ['required', 'in:belum_kawin,kawin,cerai_hidup,cerai_mati'],
                'pekerjaan' => ['required', 'string', 'max:100'],
                'keperluan' => ['required', Rule::in($this->sktmKeperluanOptions())],
                'keperluan_lainnya' => ['nullable', 'required_if:keperluan,lainnya', 'string', 'max:255'],
                'digunakan_di' => ['nullable', 'string', 'max:255'],
            ]);
        } elseif ($jenis === SuratPengajuan::JENIS_KEMATIAN) {
            $request->validate($baseRules + [
                'nama_meninggal' => ['required', 'string', 'max:255'],
                'jenis_kelamin_meninggal' => ['required', 'in:laki-laki,perempuan'],
                'usia_meninggal' => ['required', 'integer', 'min:0', 'max:130'],
                'tempat_lahir_meninggal' => ['required', 'string', 'max:255'],
                'tanggal_lahir_meninggal' => ['required', 'date'],
                'nama_ortu_meninggal' => ['required', 'string', 'max:255'],
                'tanggal_meninggal' => ['required', 'date'],
                'lokasi_meninggal' => ['required', 'string', 'max:255'],
                'sebab_meninggal' => ['required', 'string', 'max:255'],
            ]);
        } elseif ($jenis === SuratPengajuan::JENIS_KELAHIRAN) {
            $request->validate($baseRules + [
                'nama_bayi' => ['required', 'string', 'max:255'],
                'jenis_kelamin_bayi' => ['required', 'in:laki-laki,perempuan'],
                'tempat_lahir_bayi' => ['required', 'string', 'max:255'],
                'tanggal_lahir_bayi' => ['required', 'date'],
                'anak_ke' => ['required', 'integer', 'min:1', 'max:20'],
                'nama_ayah' => ['required', 'string', 'max:255'],
                'agama_ayah' => ['required', 'string', 'max:80'],
                'tempat_lahir_ayah' => ['required', 'string', 'max:255'],
                'tanggal_lahir_ayah' => ['required', 'date'],
                'nama_ibu' => ['required', 'string', 'max:255'],
                'agama_ibu' => ['required', 'string', 'max:80'],
                'tempat_lahir_ibu' => ['required', 'string', 'max:255'],
                'tanggal_lahir_ibu' => ['required', 'date'],
            ]);
        } elseif ($jenis === SuratPengajuan::JENIS_USAHA) {
            $request->validate($baseRules + [
                'pekerjaan' => ['required', 'string', 'max:100'],
                'status_perkawinan' => ['required', 'in:belum_kawin,kawin,cerai_hidup,cerai_mati'],
                'nama_usaha' => ['required', 'string', 'max:255'],
            ]);
        } elseif ($jenis === SuratPengajuan::JENIS_BELUM_MENIKAH) {
            $request->merge([
                'status_perkawinan' => 'belum_kawin',
            ]);
            $request->validate($baseRules + [
                'pekerjaan' => ['required', 'string', 'max:100'],
                'status_perkawinan' => ['required', 'in:belum_kawin'],
            ]);
        } elseif ($jenis === SuratPengajuan::JENIS_KEHILANGAN) {
            $request->validate($baseRules + [
                'jenis_dokumen' => ['required', 'in:kk,ktp,akta_kelahiran,lainnya'],
                'jenis_dokumen_lainnya' => ['nullable', 'required_if:jenis_dokumen,lainnya', 'string', 'max:255'],
                'nama_dokumen' => ['required', 'string', 'max:255'],
                'status_perkawinan' => ['required', 'in:belum_kawin,kawin,cerai_hidup,cerai_mati'],
                'pekerjaan' => ['required', 'string', 'max:100'],
                'tanggal_kehilangan' => ['required', 'date'],
                'lokasi_kehilangan' => ['required', 'in:rumah,jalan'],
                'lokasi_rumah_detail' => ['nullable', 'string', 'max:255'],
                'lokasi_jalan_detail' => ['nullable', 'string', 'max:255'],
            ]);
        } elseif ($jenis === SuratPengajuan::JENIS_PENGHASILAN_ORTU) {
            $penghasilanOptions = [500000, 1000000, 1500000, 2000000, 2500000, 3000000, 3500000, 4000000, 4500000, 5000000];
            $request->validate($baseRules + [
                'nama_ayah' => ['required', 'string', 'max:255'],
                'tempat_lahir_ayah' => ['required', 'string', 'max:255'],
                'tanggal_lahir_ayah' => ['required', 'date'],
                'nik_ayah' => ['required', 'digits:16'],
                'pekerjaan_ayah' => ['required', 'string', 'max:100'],
                'penghasilan_ayah' => ['required', Rule::in([...$penghasilanOptions, 'lainnya'])],
                'penghasilan_ayah_lainnya' => ['nullable', 'required_if:penghasilan_ayah,lainnya', 'string', 'max:50'],
                'nama_ibu' => ['required', 'string', 'max:255'],
                'tempat_lahir_ibu' => ['required', 'string', 'max:255'],
                'tanggal_lahir_ibu' => ['required', 'date'],
                'nik_ibu' => ['required', 'digits:16'],
                'pekerjaan_ibu' => ['required', 'string', 'max:100'],
                'penghasilan_ibu' => ['required', Rule::in([...$penghasilanOptions, 'lainnya'])],
                'penghasilan_ibu_lainnya' => ['nullable', 'required_if:penghasilan_ibu,lainnya', 'string', 'max:50'],
                'universitas_anak' => ['required', 'string', 'max:255'],
            ]);
        } else {
            $request->validate($baseRules + [
                'perihal' => ['required', 'string', 'max:255'],
                'keperluan' => ['required', 'string', 'max:255'],
            ]);
        }

        $surat = DB::transaction(function () use ($request, $jenis): SuratPengajuan {
            $tahun = (int) now()->format('Y');

            $perihal = match ($jenis) {
                SuratPengajuan::JENIS_DOMISILI => 'Keterangan Domisili',
                SuratPengajuan::JENIS_TIDAK_MAMPU => 'Keterangan Tidak Mampu',
                SuratPengajuan::JENIS_KEMATIAN => 'Keterangan Kematian',
                SuratPengajuan::JENIS_KELAHIRAN => 'Keterangan Kelahiran',
                SuratPengajuan::JENIS_USAHA => 'Keterangan Usaha',
                SuratPengajuan::JENIS_BELUM_MENIKAH => 'Keterangan Belum Menikah',
                SuratPengajuan::JENIS_KEHILANGAN => 'Keterangan Kehilangan',
                SuratPengajuan::JENIS_PENGHASILAN_ORTU => 'Keterangan Penghasilan Orang Tua',
                default => $request->string('perihal')->toString(),
            };
            $keperluan = match ($jenis) {
                SuratPengajuan::JENIS_DOMISILI => 'Menerangkan status domisili warga Desa Wonorejo',
                SuratPengajuan::JENIS_TIDAK_MAMPU => ($request->string('keperluan')->toString() === 'lainnya'
                    ? $request->string('keperluan_lainnya')->toString()
                    : $request->string('keperluan')->toString()),
                SuratPengajuan::JENIS_KEMATIAN => 'Surat Keterangan Kematian dibuat atas dasar yang sebenarnya.',
                SuratPengajuan::JENIS_KELAHIRAN => 'Keterangan ini dibuat agar diperlukan sebagaimana mestinya.',
                SuratPengajuan::JENIS_USAHA => 'Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.',
                SuratPengajuan::JENIS_BELUM_MENIKAH => 'Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.',
                SuratPengajuan::JENIS_KEHILANGAN => 'Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.',
                SuratPengajuan::JENIS_PENGHASILAN_ORTU => 'Demikian surat keterangan ini kami buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.',
                default => $request->string('keperluan')->toString(),
            };
            $catatan = match ($jenis) {
                SuratPengajuan::JENIS_DOMISILI => null,
                SuratPengajuan::JENIS_TIDAK_MAMPU => ($request->string('digunakan_di')->toString() ?: null),
                SuratPengajuan::JENIS_KEMATIAN => json_encode([
                    'nama_meninggal' => $request->string('nama_meninggal')->toString(),
                    'jenis_kelamin_meninggal' => $request->string('jenis_kelamin_meninggal')->toString(),
                    'usia_meninggal' => $request->integer('usia_meninggal'),
                    'tempat_lahir_meninggal' => $request->string('tempat_lahir_meninggal')->toString(),
                    'tanggal_lahir_meninggal' => $request->string('tanggal_lahir_meninggal')->toString(),
                    'nama_ortu_meninggal' => $request->string('nama_ortu_meninggal')->toString(),
                    'tanggal_meninggal' => $request->string('tanggal_meninggal')->toString(),
                    'lokasi_meninggal' => $request->string('lokasi_meninggal')->toString(),
                    'sebab_meninggal' => $request->string('sebab_meninggal')->toString(),
                ], JSON_UNESCAPED_UNICODE),
                SuratPengajuan::JENIS_KELAHIRAN => json_encode([
                    'nama_bayi' => $request->string('nama_bayi')->toString(),
                    'jenis_kelamin_bayi' => $request->string('jenis_kelamin_bayi')->toString(),
                    'tempat_lahir_bayi' => $request->string('tempat_lahir_bayi')->toString(),
                    'tanggal_lahir_bayi' => $request->string('tanggal_lahir_bayi')->toString(),
                    'anak_ke' => $request->integer('anak_ke'),
                    'nama_ayah' => $request->string('nama_ayah')->toString(),
                    'agama_ayah' => $request->string('agama_ayah')->toString(),
                    'tempat_lahir_ayah' => $request->string('tempat_lahir_ayah')->toString(),
                    'tanggal_lahir_ayah' => $request->string('tanggal_lahir_ayah')->toString(),
                    'nama_ibu' => $request->string('nama_ibu')->toString(),
                    'agama_ibu' => $request->string('agama_ibu')->toString(),
                    'tempat_lahir_ibu' => $request->string('tempat_lahir_ibu')->toString(),
                    'tanggal_lahir_ibu' => $request->string('tanggal_lahir_ibu')->toString(),
                ], JSON_UNESCAPED_UNICODE),
                SuratPengajuan::JENIS_USAHA => json_encode([
                    'nama_usaha' => $request->string('nama_usaha')->toString(),
                ], JSON_UNESCAPED_UNICODE),
                SuratPengajuan::JENIS_BELUM_MENIKAH => null,
                SuratPengajuan::JENIS_KEHILANGAN => json_encode([
                    'jenis_dokumen' => $request->string('jenis_dokumen')->toString(),
                    'jenis_dokumen_lainnya' => $request->string('jenis_dokumen_lainnya')->toString(),
                    'nama_dokumen' => $request->string('nama_dokumen')->toString(),
                    'tanggal_kehilangan' => $request->string('tanggal_kehilangan')->toString(),
                    'lokasi_kehilangan' => $request->string('lokasi_kehilangan')->toString(),
                    'lokasi_rumah_detail' => $request->string('lokasi_rumah_detail')->toString(),
                    'lokasi_jalan_detail' => $request->string('lokasi_jalan_detail')->toString(),
                ], JSON_UNESCAPED_UNICODE),
                SuratPengajuan::JENIS_PENGHASILAN_ORTU => json_encode([
                    'nama_ayah' => $request->string('nama_ayah')->toString(),
                    'tempat_lahir_ayah' => $request->string('tempat_lahir_ayah')->toString(),
                    'tanggal_lahir_ayah' => $request->string('tanggal_lahir_ayah')->toString(),
                    'nik_ayah' => $request->string('nik_ayah')->toString(),
                    'pekerjaan_ayah' => $request->string('pekerjaan_ayah')->toString(),
                    'penghasilan_ayah' => $request->string('penghasilan_ayah')->toString(),
                    'penghasilan_ayah_lainnya' => $request->string('penghasilan_ayah_lainnya')->toString(),
                    'nama_ibu' => $request->string('nama_ibu')->toString(),
                    'tempat_lahir_ibu' => $request->string('tempat_lahir_ibu')->toString(),
                    'tanggal_lahir_ibu' => $request->string('tanggal_lahir_ibu')->toString(),
                    'nik_ibu' => $request->string('nik_ibu')->toString(),
                    'pekerjaan_ibu' => $request->string('pekerjaan_ibu')->toString(),
                    'penghasilan_ibu' => $request->string('penghasilan_ibu')->toString(),
                    'penghasilan_ibu_lainnya' => $request->string('penghasilan_ibu_lainnya')->toString(),
                    'universitas_anak' => $request->string('universitas_anak')->toString(),
                ], JSON_UNESCAPED_UNICODE),
                default => ($request->string('catatan')->toString() ?: null),
            };
            $statusPerkawinan = in_array($jenis, [SuratPengajuan::JENIS_TIDAK_MAMPU, SuratPengajuan::JENIS_USAHA, SuratPengajuan::JENIS_BELUM_MENIKAH, SuratPengajuan::JENIS_KEHILANGAN], true)
                ? $request->string('status_perkawinan')->toString()
                : null;
            $pekerjaan = in_array($jenis, [SuratPengajuan::JENIS_TIDAK_MAMPU, SuratPengajuan::JENIS_USAHA, SuratPengajuan::JENIS_BELUM_MENIKAH, SuratPengajuan::JENIS_KEHILANGAN], true)
                ? $request->string('pekerjaan')->toString()
                : null;

            return SuratPengajuan::create([
                'user_id' => $request->user()->id,
                'jenis_surat' => $jenis,
                'nomor_surat' => null,
                'nomor_urut' => null,
                'tahun' => $tahun,
                'perihal' => $perihal,
                'keperluan' => $keperluan,
                'catatan' => $catatan,
                'status_perkawinan' => $statusPerkawinan,
                'pekerjaan' => $pekerjaan,
                'tanggal_surat' => now()->toDateString(),
                'status' => 'menunggu',
            ]);
        });

        return redirect()
            ->route('layanan.pengajuan.show', $surat)
            ->with('status', 'Pengajuan berhasil dikirim. Menunggu persetujuan admin.');
    }

    public function show(Request $request, SuratPengajuan $suratPengajuan): View
    {
        abort_unless($suratPengajuan->user_id === $request->user()->id, 403);

        $jenisLabel = SuratPengajuan::jenisOptions()[$suratPengajuan->jenis_surat] ?? 'Surat Keterangan';

        return view('layanan.surat.show', [
            'surat' => $suratPengajuan,
            'user' => $request->user(),
            'jenisLabel' => $jenisLabel,
            'alamatRingkas' => $this->alamatRingkas($request->user()),
            'ttlFormatted' => $this->ttlFormatted($request->user()),
            'domisiliAlamat' => $this->domisiliAlamat($request->user()),
            'alamatDomisiliLines' => $this->alamatDomisiliLines($request->user()),
            'canViewDocument' => $suratPengajuan->status === 'disetujui',
        ]);
    }

    public function destroy(Request $request, SuratPengajuan $suratPengajuan): RedirectResponse
    {
        abort_unless($suratPengajuan->user_id === $request->user()->id, 403);

        $suratPengajuan->delete();

        return redirect()->back()->with('status', 'Data pengajuan surat berhasil dihapus.');
    }

    public function download(Request $request, SuratPengajuan $suratPengajuan)
    {
        $this->authorizePdfDownloadAccess($request, $suratPengajuan);

        [$pdf, $fileName] = $this->buildPdfDocument($request, $suratPengajuan);

        return $pdf->download($fileName);
    }

    public function preview(Request $request, SuratPengajuan $suratPengajuan)
    {
        $this->authorizePdfPreviewAccess($request, $suratPengajuan);

        [$pdf, $fileName] = $this->buildPdfDocument($request, $suratPengajuan);
        $pdfBinary = $pdf->output();

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Content-Length' => (string) strlen($pdfBinary),
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function authorizePdfDownloadAccess(Request $request, SuratPengajuan $suratPengajuan): void
    {
        $isAdmin = in_array($request->user()->role, ['admin', 'petugas'], true);
        if (! $isAdmin) {
            abort_unless($suratPengajuan->user_id === $request->user()->id, 403);
        }
        abort_unless($suratPengajuan->status === 'disetujui', 403);
    }

    private function authorizePdfPreviewAccess(Request $request, SuratPengajuan $suratPengajuan): void
    {
        $isAdmin = in_array($request->user()->role, ['admin', 'petugas'], true);
        if ($isAdmin) {
            abort_unless(in_array($suratPengajuan->status, ['menunggu', 'diajukan', 'disetujui'], true), 403);
            return;
        }

        abort_unless($suratPengajuan->user_id === $request->user()->id, 403);
        abort_unless($suratPengajuan->status === 'disetujui', 403);
    }

    private function buildPdfDocument(Request $request, SuratPengajuan $suratPengajuan): array
    {
        $jenisLabel = SuratPengajuan::jenisOptions()[$suratPengajuan->jenis_surat] ?? 'Surat Keterangan';
        $suratPengajuan->loadMissing('user');
        $suratUser = $suratPengajuan->user;

        $payload = [
            'surat' => $suratPengajuan,
            'user' => $suratUser,
            'jenisLabel' => $jenisLabel,
            'alamatRingkas' => $this->alamatRingkas($suratUser),
            'ttlFormatted' => $this->ttlFormatted($suratUser),
            'domisiliAlamat' => $this->domisiliAlamat($suratUser),
            'alamatDomisiliLines' => $this->alamatDomisiliLines($suratUser),
            'provinsiNama' => $this->cleanRegionName($this->resolveProvinceName($suratUser->provinsi_id) ?? $suratUser->provinsi_id),
        ];

        $nomorSurat = $suratPengajuan->nomor_surat ?: 'pengajuan-' . $suratPengajuan->id;
        $fileName = 'surat-' . $suratPengajuan->jenis_surat . '-' . str_replace('/', '-', $nomorSurat) . '.pdf';

        $pdf = Pdf::loadView('layanan.surat.download', $payload)
            ->setPaper('a4', 'portrait');

        return [$pdf, $fileName];
    }

    private function alamatRingkas($user): string
    {
        $lines = $this->alamatDomisiliLines($user);
        return $lines ? implode(', ', $lines) : '-';
    }

    private function domisiliAlamat($user): string
    {
        $lines = $this->alamatDomisiliLines($user);
        return $lines ? implode(', ', $lines) : '-';
    }

    private function alamatDomisiliLines($user): array
    {
        $desaNama = $this->resolveVillageName($user->desa_id);
        $kecamatanNama = $this->resolveDistrictName($user->kecamatan_id);
        $kabupatenNama = $this->resolveRegencyName($user->kabupaten_id);
        $provinsiNama = $this->resolveProvinceName($user->provinsi_id) ?? $user->provinsi_id;

        $desaNama = $this->cleanRegionName($desaNama ?? $user->desa_id);
        $kecamatanNama = $this->cleanRegionName($kecamatanNama ?? $user->kecamatan_id);
        $kabupatenNama = $this->cleanRegionName($kabupatenNama ?? $user->kabupaten_id);
        $provinsiNama = $this->cleanRegionName($provinsiNama);

        $dusunRt = trim(implode(' ', array_filter([
            $user->dusun ? 'Dusun ' . $user->dusun : null,
            $this->formattedRtRw($user->{'rt/rw'} ?? null),
        ])));

        $desaKec = trim(implode(' ', array_filter([
            ($desaNama ?? $user->desa_id) ? 'Desa ' . ($desaNama ?? $user->desa_id) : null,
            ($kecamatanNama ?? $user->kecamatan_id) ? 'Kecamatan ' . ($kecamatanNama ?? $user->kecamatan_id) : null,
        ])));

        $kabupaten = ($kabupatenNama ?? $user->kabupaten_id)
            ? 'Kabupaten ' . ($kabupatenNama ?? $user->kabupaten_id)
            : null;

        return array_values(array_filter([
            $dusunRt ?: null,
            $desaKec ?: null,
            $kabupaten,
        ]));
    }

    private function ttlFormatted($user): string
    {
        if (! $user->tanggal_lahir || ! $user->bulan_lahir || ! $user->tahun_lahir) {
            return '-';
        }

        try {
            $tanggal = Carbon::createFromDate(
                (int) $user->tahun_lahir,
                (int) $user->bulan_lahir,
                (int) $user->tanggal_lahir
            )->translatedFormat('d-m-Y');
        } catch (\Throwable) {
            return '-';
        }

        if (! $user->tempat_lahir) {
            return $tanggal;
        }

        return $user->tempat_lahir . ', ' . $tanggal;
    }

    private function resolveProvinceName(?string $provinceCode): ?string
    {
        if (! $provinceCode) {
            return null;
        }

        $data = $this->fetchWilayah('https://wilayah.id/api/provinces.json');
        foreach ($data['data'] ?? [] as $item) {
            if (($item['code'] ?? null) === $provinceCode) {
                return $item['name'] ?? null;
            }
        }

        return null;
    }

    private function resolveRegencyName(?string $regencyCode): ?string
    {
        if (! $regencyCode) {
            return null;
        }

        $provinceCode = $this->parentCode($regencyCode, 1);
        if (! $provinceCode) {
            return null;
        }

        $data = $this->fetchWilayah("https://wilayah.id/api/regencies/{$provinceCode}.json");
        foreach ($data['data'] ?? [] as $item) {
            if (($item['code'] ?? null) === $regencyCode) {
                return $item['name'] ?? null;
            }
        }

        return null;
    }

    private function resolveDistrictName(?string $districtCode): ?string
    {
        if (! $districtCode) {
            return null;
        }

        $regencyCode = $this->parentCode($districtCode, 2);
        if (! $regencyCode) {
            return null;
        }

        $data = $this->fetchWilayah("https://wilayah.id/api/districts/{$regencyCode}.json");
        foreach ($data['data'] ?? [] as $item) {
            if (($item['code'] ?? null) === $districtCode) {
                return $item['name'] ?? null;
            }
        }

        return null;
    }

    private function resolveVillageName(?string $villageCode): ?string
    {
        if (! $villageCode) {
            return null;
        }

        $districtCode = $this->parentCode($villageCode, 3);
        if (! $districtCode) {
            return null;
        }

        $data = $this->fetchWilayah("https://wilayah.id/api/villages/{$districtCode}.json");
        foreach ($data['data'] ?? [] as $item) {
            if (($item['code'] ?? null) === $villageCode) {
                return $item['name'] ?? null;
            }
        }

        return null;
    }

    private function parentCode(string $code, int $parts): ?string
    {
        $segments = explode('.', $code);
        if (count($segments) < $parts) {
            return null;
        }

        return implode('.', array_slice($segments, 0, $parts));
    }

    private function fetchWilayah(string $url): array
    {
        static $cache = [];

        if (array_key_exists($url, $cache)) {
            return $cache[$url];
        }

        $context = stream_context_create([
            'http' => [
                'timeout' => 8,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            $cache[$url] = ['data' => []];
            return $cache[$url];
        }

        $decoded = json_decode($body, true);
        $cache[$url] = is_array($decoded) ? $decoded : ['data' => []];

        return $cache[$url];
    }

    private function formattedRtRw(?string $rtRw): ?string
    {
        if (! $rtRw) {
            return null;
        }

        $parts = explode('/', $rtRw);
        $rt = trim($parts[0] ?? '');
        $rw = trim($parts[1] ?? '');

        if ($rt !== '' && $rw !== '') {
            return 'RT.' . $rt . ' RW.' . $rw;
        }

        return 'RT/RW ' . $rtRw;
    }

    private function cleanRegionName(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return trim((string) preg_replace('/^(Kabupaten|Kota|Provinsi|Kecamatan|Desa)\s+/i', '', $value));
    }

    private function sktmKeperluanOptions(): array
    {
        return [
            'Pengajuan bantuan biaya berobat',
            'Pengajuan bantuan pendidikan/beasiswa',
            'Pengajuan bantuan sosial',
            'Pengajuan keringanan biaya rumah sakit',
            'Persyaratan administrasi sekolah/kuliah',
            'Persyaratan pengajuan BPJS PBI',
            'Persyaratan bantuan rehab rumah',
            'lainnya',
        ];
    }
}
