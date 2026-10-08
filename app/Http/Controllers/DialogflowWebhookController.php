<?php

namespace App\Http\Controllers;

use App\Models\SuratPengajuan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DialogflowWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $intent = (string) data_get($payload, 'queryResult.intent.displayName', '');
        $queryText = (string) data_get($payload, 'queryResult.queryText', '');
        $parameters = (array) data_get($payload, 'queryResult.parameters', []);
        $lowerQuery = mb_strtolower($queryText);
        $normalizedQuery = str_replace('_', ' ', $lowerQuery);

        $userId = data_get($payload, 'originalDetectIntentRequest.payload.user_id');
        $user = $userId ? User::find($userId) : null;

        if ($this->looksLikeStatusPayload($queryText)) {
            return response()->json([
                'fulfillmentText' => $this->handleStatus($parameters, $queryText),
            ]);
        }

        if (str_contains($normalizedQuery, 'cek status') || str_contains($normalizedQuery, 'status surat')) {
            return response()->json([
                'fulfillmentText' => $this->handleStatus($parameters, $queryText),
            ]);
        }

        if ($this->isIntent($intent, ['cek_status_surat', 'status_surat', 'cek_status'])) {
            return response()->json([
                'fulfillmentText' => $this->handleStatus($parameters, $queryText),
            ]);
        }

        if (
            str_contains($normalizedQuery, 'buat surat') ||
            str_contains($normalizedQuery, 'ajukan surat') ||
            str_contains($normalizedQuery, 'pengajuan surat')
        ) {
            if (! $user) {
                return response()->json([
                    'fulfillmentText' => 'Untuk membuat surat, kamu perlu login dulu ya :)',
                ]);
            }

            $jenis = $this->resolveJenisSurat($intent, $parameters, $queryText);
            if (! $jenis) {
                return response()->json([
                    'fulfillmentText' => 'Jenis suratnya belum kebaca nih. Coba sebutkan jenis suratnya ya :)',
                ]);
            }

            return response()->json([
                'fulfillmentText' => $this->handleCreateSurat($user, $jenis, $parameters),
            ]);
        }

        if ($this->isIntent($intent, ['buat_surat', 'buat_surat_domisili', 'buat_surat_sktm', 'buat_surat_tidak_mampu', 'buat_surat_kematian', 'buat_surat_kelahiran', 'buat_surat_usaha', 'buat_surat_belum_menikah', 'buat_surat_kehilangan', 'buat_surat_penghasilan_ortu', 'buat_surat_penghasilan_orang_tua'])) {
            if (! $user) {
                return response()->json([
                    'fulfillmentText' => 'Untuk membuat surat, kamu perlu login dulu ya :)',
                ]);
            }

            $jenis = $this->resolveJenisSurat($intent, $parameters, $queryText);
            if (! $jenis) {
                return response()->json([
                    'fulfillmentText' => 'Jenis suratnya belum kebaca nih. Coba sebutkan jenis suratnya ya :)',
                ]);
            }

            $result = $this->handleCreateSurat($user, $jenis, $parameters);

            return response()->json([
                'fulfillmentText' => $result,
            ]);
        }

        $presetReply = (string) data_get($payload, 'queryResult.fulfillmentText', '');
        if ($presetReply === '') {
            $presetReply = (string) data_get($payload, 'queryResult.fulfillmentMessages.0.text.text.0', '');
        }
        if ($presetReply !== '') {
            return response()->json([
                'fulfillmentText' => $presetReply,
            ]);
        }

        return response()->json([
            'fulfillmentText' => 'Maaf, aku belum paham. Coba tulis dengan cara lain ya :)',
        ]);
    }

    private function handleStatus(array $parameters, string $queryText): string
    {
        $nik = $this->paramString($parameters, 'nik');
        $nama = $this->paramString($parameters, 'nama');

        if (! $nik && ! $nama) {
            if (preg_match('/\bnik\s*[:=]?\s*([0-9]{8,20})\b/i', $queryText, $m)) {
                $nik = $m[1];
            } elseif (preg_match('/\bnama\s*[:=]?\s*([a-zA-Z\s\.\'-]{3,80})$/i', $queryText, $m)) {
                $nama = trim($m[1]);
            }
        }

        if (! $nik && ! $nama) {
            return implode("\n", [
                'Biar saya cek status suratnya, kirim salah satu ya :)',
                '- NIK',
                '- Nama',
            ]);
        }

        $query = SuratPengajuan::query()
            ->with('user')
            ->latest()
            ->limit(5);

        if ($nik) {
            $query->whereHas('user', function ($q) use ($nik) {
                $q->where('nik', $nik);
            });
        } else {
            $query->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        $rows = $query->get();
        if ($rows->isEmpty()) {
            return 'Maaf ya, datanya belum ketemu. Coba cek lagi :)';
        }

        $lines = ['Ini status surat yang ditemukan ya :)'];
        foreach ($rows as $item) {
            $jenis = SuratPengajuan::jenisOptions()[$item->jenis_surat] ?? $item->jenis_surat;
            $lines[] = '- ' . ($item->user->name ?? '-') . ' | ' . ($item->user->nik ?? '-') . ' | ' . $jenis .
                ' | ' . strtoupper((string) $item->status) . ' | ' .
                optional($item->created_at)->format('d-m-Y H:i');
        }

        return implode("\n", $lines);
    }

    private function handleCreateSurat(User $user, string $jenis, array $parameters): string
    {
        $required = $this->requiredFieldsByJenis($jenis);
        $missing = [];
        $data = [];

        foreach ($required as $field) {
            $value = $this->paramString($parameters, $field);
            if ($value === null || $value === '') {
                $missing[] = $field;
            } else {
                $data[$field] = $value;
            }
        }

        if ($missing) {
            return 'Aku masih butuh data ini ya: ' . implode(', ', $missing) . '.';
        }

        if ($jenis === SuratPengajuan::JENIS_DOMISILI) {
            $keperluan = $this->paramString($parameters, 'keperluan') ?: $this->keperluanByJenis($jenis);
            $surat = $this->createSuratByChat(
                $user,
                $jenis,
                $this->perihalByJenis($jenis),
                $keperluan,
                null,
                null,
                null
            );

            return 'Pengajuan surat domisili berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_TIDAK_MAMPU) {
            $surat = $this->createSuratByChat(
                $user,
                $jenis,
                $this->perihalByJenis($jenis),
                (string) ($data['keperluan'] ?? ''),
                $data['digunakan_di'] ?? null,
                (string) ($data['status_perkawinan'] ?? ''),
                (string) ($data['pekerjaan'] ?? '')
            );

            return 'Pengajuan SKTM berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_KEMATIAN) {
            $catatan = json_encode([
                'nama_meninggal' => $data['nama_meninggal'] ?? '',
                'jenis_kelamin_meninggal' => $data['jenis_kelamin_meninggal'] ?? '',
                'usia_meninggal' => (int) ($data['usia_meninggal'] ?? 0),
                'tanggal_meninggal' => $data['tanggal_meninggal'] ?? '',
                'lokasi_meninggal' => $data['lokasi_meninggal'] ?? '',
                'sebab_meninggal' => $data['sebab_meninggal'] ?? '',
                'provinsi_meninggal' => $data['provinsi_meninggal'] ?? '',
                'kabupaten_meninggal' => $data['kabupaten_meninggal'] ?? '',
                'kecamatan_meninggal' => $data['kecamatan_meninggal'] ?? '',
                'desa_meninggal' => $data['desa_meninggal'] ?? '',
                'rt_rw_meninggal' => $data['rt_rw_meninggal'] ?? '',
                'dusun_meninggal' => $data['dusun_meninggal'] ?? '',
            ], JSON_UNESCAPED_UNICODE);

            $surat = $this->createSuratByChat(
                $user,
                $jenis,
                'Keterangan Kematian',
                'Surat Keterangan Kematian dibuat atas dasar yang sebenarnya.',
                $catatan,
                null,
                null
            );

            return 'Pengajuan Surat Kematian berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_KELAHIRAN) {
            $catatan = json_encode([
                'nama_bayi' => $data['nama_bayi'] ?? '',
                'jenis_kelamin_bayi' => $data['jenis_kelamin_bayi'] ?? '',
                'tempat_lahir_bayi' => $data['tempat_lahir_bayi'] ?? '',
                'tanggal_lahir_bayi' => $data['tanggal_lahir_bayi'] ?? '',
                'anak_ke' => (int) ($data['anak_ke'] ?? 0),
                'nama_ayah' => $data['nama_ayah'] ?? '',
                'agama_ayah' => $data['agama_ayah'] ?? '',
                'tempat_lahir_ayah' => $data['tempat_lahir_ayah'] ?? '',
                'tanggal_lahir_ayah' => $data['tanggal_lahir_ayah'] ?? '',
                'nama_ibu' => $data['nama_ibu'] ?? '',
                'agama_ibu' => $data['agama_ibu'] ?? '',
                'tempat_lahir_ibu' => $data['tempat_lahir_ibu'] ?? '',
                'tanggal_lahir_ibu' => $data['tanggal_lahir_ibu'] ?? '',
            ], JSON_UNESCAPED_UNICODE);

            $surat = $this->createSuratByChat(
                $user,
                $jenis,
                $this->perihalByJenis($jenis),
                $this->keperluanByJenis($jenis),
                $catatan,
                null,
                null
            );

            return 'Pengajuan Surat Kelahiran berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_USAHA) {
            $catatan = json_encode([
                'nama_usaha' => $data['nama_usaha'] ?? '',
            ], JSON_UNESCAPED_UNICODE);

            $surat = $this->createSuratByChat(
                $user,
                $jenis,
                $this->perihalByJenis($jenis),
                $this->keperluanByJenis($jenis),
                $catatan,
                (string) ($data['status_perkawinan'] ?? ''),
                (string) ($data['pekerjaan'] ?? '')
            );

            return 'Pengajuan Surat Usaha berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_BELUM_MENIKAH) {
            $surat = $this->createSuratByChat(
                $user,
                $jenis,
                $this->perihalByJenis($jenis),
                $this->keperluanByJenis($jenis),
                null,
                'belum_kawin',
                (string) ($data['pekerjaan'] ?? '')
            );

            return 'Pengajuan Surat Belum Menikah berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_KEHILANGAN) {
            $catatan = json_encode([
                'jenis_dokumen' => $data['jenis_dokumen'] ?? '',
                'jenis_dokumen_lainnya' => $data['jenis_dokumen_lainnya'] ?? '',
                'nama_dokumen' => $data['nama_dokumen'] ?? '',
                'tanggal_kehilangan' => $data['tanggal_kehilangan'] ?? '',
                'lokasi_kehilangan' => $data['lokasi_kehilangan'] ?? '',
                'lokasi_rumah_detail' => $data['lokasi_rumah_detail'] ?? '',
                'lokasi_jalan_detail' => $data['lokasi_jalan_detail'] ?? '',
            ], JSON_UNESCAPED_UNICODE);

            $surat = $this->createSuratByChat(
                $user,
                $jenis,
                $this->perihalByJenis($jenis),
                $this->keperluanByJenis($jenis),
                $catatan,
                (string) ($data['status_perkawinan'] ?? ''),
                (string) ($data['pekerjaan'] ?? '')
            );

            return 'Pengajuan Surat Kehilangan berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_PENGHASILAN_ORTU) {
            $catatan = json_encode([
                'nama_ayah' => $data['nama_ayah'] ?? '',
                'tempat_lahir_ayah' => $data['tempat_lahir_ayah'] ?? '',
                'tanggal_lahir_ayah' => $data['tanggal_lahir_ayah'] ?? '',
                'nik_ayah' => $data['nik_ayah'] ?? '',
                'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? '',
                'penghasilan_ayah' => $data['penghasilan_ayah'] ?? '',
                'penghasilan_ayah_lainnya' => $data['penghasilan_ayah_lainnya'] ?? '',
                'nama_ibu' => $data['nama_ibu'] ?? '',
                'tempat_lahir_ibu' => $data['tempat_lahir_ibu'] ?? '',
                'tanggal_lahir_ibu' => $data['tanggal_lahir_ibu'] ?? '',
                'nik_ibu' => $data['nik_ibu'] ?? '',
                'pekerjaan_ibu' => $data['pekerjaan_ibu'] ?? '',
                'penghasilan_ibu' => $data['penghasilan_ibu'] ?? '',
                'penghasilan_ibu_lainnya' => $data['penghasilan_ibu_lainnya'] ?? '',
                'universitas_anak' => $data['universitas_anak'] ?? '',
            ], JSON_UNESCAPED_UNICODE);

            $surat = $this->createSuratByChat(
                $user,
                $jenis,
                $this->perihalByJenis($jenis),
                $this->keperluanByJenis($jenis),
                $catatan,
                null,
                null
            );

            return 'Pengajuan Surat Penghasilan Orang Tua berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        return 'Prosesnya selesai, tapi jenis suratnya belum kebaca ya :)';
    }

    private function requiredFieldsByJenis(string $jenis): array
    {
        return match ($jenis) {
            SuratPengajuan::JENIS_DOMISILI => ['keperluan'],
            SuratPengajuan::JENIS_TIDAK_MAMPU => ['status_perkawinan', 'pekerjaan', 'keperluan'],
            SuratPengajuan::JENIS_KEMATIAN => [
                'nama_meninggal',
                'jenis_kelamin_meninggal',
                'usia_meninggal',
                'tanggal_meninggal',
                'lokasi_meninggal',
                'sebab_meninggal',
                'provinsi_meninggal',
                'kabupaten_meninggal',
                'kecamatan_meninggal',
                'desa_meninggal',
            ],
            SuratPengajuan::JENIS_KELAHIRAN => [
                'nama_bayi',
                'jenis_kelamin_bayi',
                'tempat_lahir_bayi',
                'tanggal_lahir_bayi',
                'anak_ke',
                'nama_ayah',
                'agama_ayah',
                'tempat_lahir_ayah',
                'tanggal_lahir_ayah',
                'nama_ibu',
                'agama_ibu',
                'tempat_lahir_ibu',
                'tanggal_lahir_ibu',
            ],
            SuratPengajuan::JENIS_USAHA => ['pekerjaan', 'status_perkawinan', 'nama_usaha'],
            SuratPengajuan::JENIS_BELUM_MENIKAH => ['pekerjaan'],
            SuratPengajuan::JENIS_KEHILANGAN => [
                'jenis_dokumen',
                'nama_dokumen',
                'status_perkawinan',
                'pekerjaan',
                'tanggal_kehilangan',
                'lokasi_kehilangan',
            ],
            SuratPengajuan::JENIS_PENGHASILAN_ORTU => [
                'universitas_anak',
                'nama_ayah',
                'tempat_lahir_ayah',
                'tanggal_lahir_ayah',
                'nik_ayah',
                'pekerjaan_ayah',
                'penghasilan_ayah',
                'nama_ibu',
                'tempat_lahir_ibu',
                'tanggal_lahir_ibu',
                'nik_ibu',
                'pekerjaan_ibu',
                'penghasilan_ibu',
            ],
            default => [],
        };
    }

    private function resolveJenisSurat(string $intent, array $parameters, string $queryText): ?string
    {
        $jenisParam = $this->paramString($parameters, 'jenis_surat');
        $source = $jenisParam ?: $intent . ' ' . $queryText;
        $lower = mb_strtolower($source);

        if (str_contains($lower, 'domisili')) {
            return SuratPengajuan::JENIS_DOMISILI;
        }
        if (str_contains($lower, 'tidak mampu') || str_contains($lower, 'sktm')) {
            return SuratPengajuan::JENIS_TIDAK_MAMPU;
        }
        if (str_contains($lower, 'kematian')) {
            return SuratPengajuan::JENIS_KEMATIAN;
        }
        if (str_contains($lower, 'kelahiran')) {
            return SuratPengajuan::JENIS_KELAHIRAN;
        }
        if (str_contains($lower, 'usaha')) {
            return SuratPengajuan::JENIS_USAHA;
        }
        if (str_contains($lower, 'belum menikah') || str_contains($lower, 'belum nikah')) {
            return SuratPengajuan::JENIS_BELUM_MENIKAH;
        }
        if (str_contains($lower, 'kehilangan')) {
            return SuratPengajuan::JENIS_KEHILANGAN;
        }
        if (str_contains($lower, 'penghasilan orang tua') || str_contains($lower, 'penghasilan ortu')) {
            return SuratPengajuan::JENIS_PENGHASILAN_ORTU;
        }

        return null;
    }

    private function isIntent(string $intent, array $candidates): bool
    {
        $intent = mb_strtolower($intent);
        foreach ($candidates as $candidate) {
            if ($intent === mb_strtolower($candidate)) {
                return true;
            }
        }
        return false;
    }

    private function paramString(array $parameters, string $key): ?string
    {
        $value = $parameters[$key] ?? null;
        if (is_array($value)) {
            $value = $value[0] ?? null;
        }
        if ($value === null) {
            return null;
        }
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function looksLikeStatusPayload(string $queryText): bool
    {
        $text = trim(mb_strtolower($queryText));
        if ($text === '') {
            return false;
        }

        if (str_contains($text, 'nik') || str_contains($text, 'nama')) {
            return true;
        }

        if (preg_match('/\b\d{8,20}\b/', $text)) {
            return true;
        }

        return false;
    }

    private function perihalByJenis(string $jenis): string
    {
        return SuratPengajuan::jenisOptions()[$jenis] ?? 'Pengajuan Surat';
    }

    private function keperluanByJenis(string $jenis): string
    {
        return match ($jenis) {
            SuratPengajuan::JENIS_DOMISILI => 'Menerangkan status domisili warga Desa Wonorejo',
            SuratPengajuan::JENIS_TIDAK_MAMPU => 'Keperluan akan ditentukan dari jawaban Anda.',
            SuratPengajuan::JENIS_KEMATIAN => 'Surat Keterangan Kematian dibuat atas dasar yang sebenarnya.',
            SuratPengajuan::JENIS_KELAHIRAN => 'Keterangan ini dibuat agar diperlukan sebagaimana mestinya.',
            SuratPengajuan::JENIS_USAHA => 'Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.',
            SuratPengajuan::JENIS_BELUM_MENIKAH => 'Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.',
            SuratPengajuan::JENIS_KEHILANGAN => 'Demikian Surat Keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.',
            SuratPengajuan::JENIS_PENGHASILAN_ORTU => 'Demikian surat keterangan ini kami buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.',
            default => 'Pengajuan surat dibuat untuk keperluan yang benar.',
        };
    }

    private function createSuratByChat(
        User $user,
        string $jenis,
        string $perihal,
        string $keperluan,
        ?string $catatan,
        ?string $statusPerkawinan,
        ?string $pekerjaan
    ): SuratPengajuan {
        return DB::transaction(function () use (
            $user,
            $jenis,
            $perihal,
            $keperluan,
            $catatan,
            $statusPerkawinan,
            $pekerjaan
        ): SuratPengajuan {
            $tahun = (int) now()->format('Y');

            return SuratPengajuan::create([
                'user_id' => $user->id,
                'jenis_surat' => $jenis,
                'nomor_surat' => null,
                'nomor_urut' => null,
                'tahun' => $tahun,
                'perihal' => $perihal,
                'keperluan' => $keperluan,
                'catatan' => $catatan,
                'status_perkawinan' => $statusPerkawinan,
                'pekerjaan' => $pekerjaan,
                'tanggal_surat' => Carbon::now()->toDateString(),
                'status' => 'menunggu',
            ]);
        });
    }
}
