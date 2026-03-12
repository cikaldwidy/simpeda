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
                'tanggal_meninggal' => ['required', 'date'],
                'lokasi_meninggal' => ['required', 'string', 'max:255'],
                'sebab_meninggal' => ['required', 'string', 'max:255'],

                'provinsi_meninggal' => ['required', 'string', 'max:120'],
                'kabupaten_meninggal' => ['required', 'string', 'max:120'],
                'kecamatan_meninggal' => ['required', 'string', 'max:120'],
                'desa_meninggal' => ['required', 'string', 'max:120'],
                'rt_rw_meninggal' => ['nullable', 'string', 'max:20'],
                'dusun_meninggal' => ['nullable', 'string', 'max:120'],
            ]);
        } else {
            $request->validate($baseRules + [
                'perihal' => ['required', 'string', 'max:255'],
                'keperluan' => ['required', 'string', 'max:255'],
            ]);
        }

        $surat = DB::transaction(function () use ($request, $jenis): SuratPengajuan {
            $tahun = (int) now()->format('Y');

            $lastNomor = SuratPengajuan::query()
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->max('nomor_urut');

            $nomorUrut = ((int) $lastNomor) + 1;
            $kodeJenis = SuratPengajuan::kodeJenis($jenis);
            $nomorSurat = sprintf(
                '%03d/%s/DSW/%s/%d',
                $nomorUrut,
                $kodeJenis,
                now()->format('m'),
                $tahun
            );

            $perihal = match ($jenis) {
                SuratPengajuan::JENIS_DOMISILI => 'Keterangan Domisili',
                SuratPengajuan::JENIS_TIDAK_MAMPU => 'Keterangan Tidak Mampu',
                SuratPengajuan::JENIS_KEMATIAN => 'Keterangan Kematian',
                default => $request->string('perihal')->toString(),
            };
            $keperluan = $jenis === SuratPengajuan::JENIS_DOMISILI
                ? 'Menerangkan status domisili warga Desa Wonorejo'
                : ($jenis === SuratPengajuan::JENIS_TIDAK_MAMPU
                    ? ($request->string('keperluan')->toString() === 'lainnya'
                        ? $request->string('keperluan_lainnya')->toString()
                        : $request->string('keperluan')->toString())
                    : ($jenis === SuratPengajuan::JENIS_KEMATIAN
                        ? 'Surat Keterangan Kematian dibuat atas dasar yang sebenarnya.'
                        : $request->string('keperluan')->toString()));
            $catatan = match ($jenis) {
                SuratPengajuan::JENIS_DOMISILI => null,
                SuratPengajuan::JENIS_TIDAK_MAMPU => ($request->string('digunakan_di')->toString() ?: null),
                SuratPengajuan::JENIS_KEMATIAN => json_encode([
                    'nama_meninggal' => $request->string('nama_meninggal')->toString(),
                    'jenis_kelamin_meninggal' => $request->string('jenis_kelamin_meninggal')->toString(),
                    'usia_meninggal' => $request->integer('usia_meninggal'),
                    'tanggal_meninggal' => $request->string('tanggal_meninggal')->toString(),
                    'lokasi_meninggal' => $request->string('lokasi_meninggal')->toString(),
                    'sebab_meninggal' => $request->string('sebab_meninggal')->toString(),
                    'provinsi_meninggal' => $request->string('provinsi_meninggal')->toString(),
                    'kabupaten_meninggal' => $request->string('kabupaten_meninggal')->toString(),
                    'kecamatan_meninggal' => $request->string('kecamatan_meninggal')->toString(),
                    'desa_meninggal' => $request->string('desa_meninggal')->toString(),
                    'rt_rw_meninggal' => $request->string('rt_rw_meninggal')->toString(),
                    'dusun_meninggal' => $request->string('dusun_meninggal')->toString(),
                ], JSON_UNESCAPED_UNICODE),
                default => ($request->string('catatan')->toString() ?: null),
            };
            $statusPerkawinan = $jenis === SuratPengajuan::JENIS_TIDAK_MAMPU
                ? $request->string('status_perkawinan')->toString()
                : null;
            $pekerjaan = $jenis === SuratPengajuan::JENIS_TIDAK_MAMPU
                ? $request->string('pekerjaan')->toString()
                : null;

            return SuratPengajuan::create([
                'user_id' => $request->user()->id,
                'jenis_surat' => $jenis,
                'nomor_surat' => $nomorSurat,
                'nomor_urut' => $nomorUrut,
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
        $this->authorizePdfAccess($request, $suratPengajuan);

        [$pdf, $fileName] = $this->buildPdfDocument($request, $suratPengajuan);

        return $pdf->download($fileName);
    }

    public function preview(Request $request, SuratPengajuan $suratPengajuan)
    {
        $this->authorizePdfAccess($request, $suratPengajuan);

        [$pdf, $fileName] = $this->buildPdfDocument($request, $suratPengajuan);
        $pdfBinary = $pdf->output();

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Content-Length' => (string) strlen($pdfBinary),
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function authorizePdfAccess(Request $request, SuratPengajuan $suratPengajuan): void
    {
        abort_unless($suratPengajuan->user_id === $request->user()->id, 403);
        abort_unless($suratPengajuan->status === 'disetujui', 403);
    }

    private function buildPdfDocument(Request $request, SuratPengajuan $suratPengajuan): array
    {
        $jenisLabel = SuratPengajuan::jenisOptions()[$suratPengajuan->jenis_surat] ?? 'Surat Keterangan';

        $payload = [
            'surat' => $suratPengajuan,
            'user' => $request->user(),
            'jenisLabel' => $jenisLabel,
            'alamatRingkas' => $this->alamatRingkas($request->user()),
            'ttlFormatted' => $this->ttlFormatted($request->user()),
            'domisiliAlamat' => $this->domisiliAlamat($request->user()),
            'alamatDomisiliLines' => $this->alamatDomisiliLines($request->user()),
        ];

        $fileName = 'surat-' . $suratPengajuan->jenis_surat . '-' . str_replace('/', '-', $suratPengajuan->nomor_surat) . '.pdf';

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
        $provinsi = $provinsiNama ? 'Provinsi ' . $provinsiNama : null;

        return array_values(array_filter([
            $dusunRt ?: null,
            $desaKec ?: null,
            $kabupaten,
            $provinsi,
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
