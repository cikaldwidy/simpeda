<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\SuratPengajuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const CHATBOT_WIZARD_SESSION_KEY = 'chatbot_wizard';

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

    public function chatbot(Request $request): View
    {
        return view('dashboard.chatbot', [
            'user' => $request->user(),
        ]);
    }

    public function chatbotMessage(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message', ''));
        if ($message === '') {
            return response()->json([
                'reply' => 'Pesan kosong. Contoh: "status nik 123456789" atau "buat surat domisili".',
            ], 422);
        }

        $lower = mb_strtolower($message);

        if (in_array($lower, ['batal', 'cancel', 'stop'], true)) {
            $request->session()->forget(self::CHATBOT_WIZARD_SESSION_KEY);

            return response()->json([
                'reply' => 'Oke, proses pengajuannya aku batalkan ya ✨',
            ]);
        }

        $activeWizard = $request->session()->get(self::CHATBOT_WIZARD_SESSION_KEY);
        if (is_array($activeWizard) && isset($activeWizard['jenis'], $activeWizard['step'])) {
            return response()->json([
                'reply' => $this->chatbotHandleWizardResponse($request, $message, $activeWizard),
            ]);
        }

        if (str_contains($lower, 'status')) {
            return response()->json([
                'reply' => $this->chatbotHandleStatusLookup($message),
            ]);
        }

        if (str_contains($lower, 'buat surat') || str_contains($lower, 'ajukan surat')) {
            return response()->json([
                'reply' => $this->chatbotHandleCreateSurat($request, $message),
            ]);
        }

        // Cek FAQ dengan similarity matching
        $faqs = Faq::where('is_aktif', true)->get();
        $bestMatch = null;
        $bestSimilarity = 0;

        foreach ($faqs as $faq) {
            $similarity = 0;
            similar_text(strtolower($message), strtolower($faq->pertanyaan), $similarity);
            if ($similarity > $bestSimilarity) {
                $bestSimilarity = $similarity;
                $bestMatch = $faq;
            }
        }

        // Jika similarity >= 50%, gunakan jawaban FAQ
        if ($bestMatch && $bestSimilarity >= 50) {
            return response()->json([
                'reply' => $bestMatch->jawaban,
            ]);
        }

        return response()->json([
            'reply' => implode("\n", [
                'Saya bisa bantu 2 hal utama:',
                '1) Cek status surat: "status nik 123456789" atau "status nama cikal"',
                '2) Buat surat otomatis:',
                '- "buat surat domisili"',
                '- "buat surat tidak mampu" (wizard bertahap)',
                '- "buat surat kematian" (wizard bertahap)',
                'Ketik "batal" kapan saja untuk menghentikan wizard.',
            ]),
        ]);
    }

    private function chatbotHandleStatusLookup(string $message): string
    {
        $nik = null;
        if (preg_match('/\bnik\s*[:=]?\s*([0-9]{8,20})\b/i', $message, $m)) {
            $nik = $m[1];
        }

        $nama = null;
        if (preg_match('/\bnama\s*[:=]?\s*([a-zA-Z\s\.\'-]{3,80})$/i', $message, $m)) {
            $nama = trim($m[1]);
        }

        $query = SuratPengajuan::query()
            ->with('user')
            ->latest()
            ->limit(5);

        if ($nik) {
            $query->whereHas('user', function ($q) use ($nik) {
                $q->where('nik', $nik);
            });
        } elseif ($nama) {
            $query->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        } else {
            return 'Format belum tepat. Gunakan "status nik xxx" atau "status nama abc".';
        }

        $rows = $query->get();
        if ($rows->isEmpty()) {
            return 'Data surat tidak ditemukan untuk pencarian tersebut.';
        }

        $lines = ['Berikut status surat yang ditemukan:'];
        foreach ($rows as $item) {
            $jenis = SuratPengajuan::jenisOptions()[$item->jenis_surat] ?? $item->jenis_surat;
            $lines[] = '- ' . ($item->user->name ?? '-') . ' | ' . ($item->user->nik ?? '-') . ' | ' . $jenis .
                ' | ' . strtoupper((string) $item->status) . ' | ' .
                optional($item->created_at)->format('d-m-Y H:i');
        }

        return implode("\n", $lines);
    }

    private function chatbotHandleCreateSurat(Request $request, string $message): string
    {
        $jenis = $this->extractJenisSurat($message);
        if (! $jenis) {
            return 'Jenis surat belum dikenali. Contoh: "buat surat domisili" atau "buat surat tidak mampu ...".';
        }

        if ($jenis === SuratPengajuan::JENIS_KELAHIRAN) {
            return 'Maaf ya, pengajuan Surat Kelahiran via chatbot belum tersedia 🙏 Coba lewat form pengajuan biasa dulu ya.';
        }

        if ($jenis === SuratPengajuan::JENIS_TIDAK_MAMPU) {
            return $this->startWizard(
                $request,
                $jenis,
                'Baik, kita mulai pengajuan Surat Keterangan Tidak Mampu.'
            );
        }

        if ($jenis === SuratPengajuan::JENIS_KEMATIAN) {
            return $this->startWizard(
                $request,
                $jenis,
                'Baik, kita mulai pengajuan Surat Keterangan Kematian.'
            );
        }

        $surat = $this->createSuratByChat(
            $request,
            SuratPengajuan::JENIS_DOMISILI,
            'Keterangan Domisili',
            'Menerangkan status domisili warga Desa Wonorejo',
            null,
            null,
            null
        );

        return 'Yeay! Pengajuan surat domisili berhasil dibuat 🎉 Nomor: ' . $surat->nomor_surat .
            ' | Status: ' . strtoupper($surat->status);
    }

    private function startWizard(Request $request, string $jenis, string $intro): string
    {
        $request->session()->put(self::CHATBOT_WIZARD_SESSION_KEY, [
            'jenis' => $jenis,
            'step' => 0,
            'data' => [],
        ]);

        $steps = $this->wizardSteps($jenis);
        $firstPrompt = $steps[0]['prompt'] ?? 'Ketik data pertama.';

        return $intro . "\n" . $firstPrompt . "\nKetik \"batal\" kapan saja kalau mau berhenti ya 🙏";
    }

    private function chatbotHandleWizardResponse(Request $request, string $message, array $wizard): string
    {
        $jenis = (string) ($wizard['jenis'] ?? '');
        $stepIndex = (int) ($wizard['step'] ?? 0);
        $data = is_array($wizard['data'] ?? null) ? $wizard['data'] : [];
        $steps = $this->wizardSteps($jenis);

        if (! isset($steps[$stepIndex])) {
            $request->session()->forget(self::CHATBOT_WIZARD_SESSION_KEY);
            return 'Ups, pesannya belum bisa diproses 😅 Coba ulangi ya, misalnya: "buat surat ..."';
        }

        $step = $steps[$stepIndex];
        $key = $step['key'];
        $isOptional = (bool) ($step['optional'] ?? false);
        $rawValue = trim($message);

        if ($isOptional && in_array(mb_strtolower($rawValue), ['-', 'skip', 'kosong'], true)) {
            $value = null;
        } else {
            [$ok, $value, $error] = $this->validateWizardField($key, $rawValue);
            if (! $ok) {
                return trim(($error ?: 'Input belum pas nih 😅') . "\n" . $step['prompt']);
            }
        }

        $data[$key] = $value;
        $stepIndex++;

        if (isset($steps[$stepIndex])) {
            $wizard['step'] = $stepIndex;
            $wizard['data'] = $data;
            $request->session()->put(self::CHATBOT_WIZARD_SESSION_KEY, $wizard);

            return (string) $steps[$stepIndex]['prompt'];
        }

        $request->session()->forget(self::CHATBOT_WIZARD_SESSION_KEY);

        if ($jenis === SuratPengajuan::JENIS_TIDAK_MAMPU) {
            $surat = $this->createSuratByChat(
                $request,
                SuratPengajuan::JENIS_TIDAK_MAMPU,
                'Keterangan Tidak Mampu',
                (string) ($data['keperluan'] ?? ''),
                $data['digunakan_di'] ?? null,
                (string) ($data['status_perkawinan'] ?? ''),
                (string) ($data['pekerjaan'] ?? '')
            );

            return 'Sip! Pengajuan SKTM berhasil dibuat ✅ Nomor: ' . $surat->nomor_surat .
                ' | Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_KEMATIAN) {
            $surat = $this->createKematianByChat($request, $data);

            return 'Baik, pengajuan Surat Kematian berhasil dibuat 🕊️ Nomor: ' . $surat->nomor_surat .
                ' | Status: ' . strtoupper($surat->status);
        }

        return 'Proses selesai, tapi jenis suratnya belum dikenali 🤔';
    }

    private function wizardSteps(string $jenis): array
    {
        if ($jenis === SuratPengajuan::JENIS_TIDAK_MAMPU) {
            return [
                [
                    'key' => 'status_perkawinan',
                    'prompt' => 'Status perkawinan kamu apa? 😊 Pilih: belum_kawin / kawin / cerai_hidup / cerai_mati',
                ],
                [
                    'key' => 'pekerjaan',
                    'prompt' => 'Boleh tulis pekerjaan kamu? ✍️',
                ],
                [
                    'key' => 'keperluan',
                    'prompt' => "Keperluannya untuk apa ya? 🙂\n" . $this->formatKeperluanOptions(),
                ],
                [
                    'key' => 'digunakan_di',
                    'prompt' => 'Tempat tujuan (opsional). Kalau kosong, ketik "-" ya 🙏',
                    'optional' => true,
                ],
            ];
        }

        if ($jenis === SuratPengajuan::JENIS_KEMATIAN) {
            return [
                ['key' => 'nama_meninggal', 'prompt' => 'Mohon tulis nama yang meninggal ya 🙏'],
                ['key' => 'jenis_kelamin_meninggal', 'prompt' => 'Jenis kelamin? (laki-laki/perempuan) 🙂'],
                ['key' => 'usia_meninggal', 'prompt' => 'Usia berapa? (angka saja)'],
                ['key' => 'tanggal_meninggal', 'prompt' => 'Tanggal meninggal (format YYYY-MM-DD) 📅'],
                ['key' => 'lokasi_meninggal', 'prompt' => 'Lokasi meninggal di mana?'],
                ['key' => 'sebab_meninggal', 'prompt' => 'Penyebab meninggal?'],
                ['key' => 'provinsi_meninggal', 'prompt' => 'Provinsinya apa?'],
                ['key' => 'kabupaten_meninggal', 'prompt' => 'Kabupaten/kota?'],
                ['key' => 'kecamatan_meninggal', 'prompt' => 'Kecamatan?'],
                ['key' => 'desa_meninggal', 'prompt' => 'Desa/kelurahan?'],
                ['key' => 'rt_rw_meninggal', 'prompt' => 'RT/RW (opsional, contoh 002/001). Kalau kosong, ketik "-" ya 🙂', 'optional' => true],
                ['key' => 'dusun_meninggal', 'prompt' => 'Dusun (opsional). Kalau kosong, ketik "-" ya 🙂', 'optional' => true],
            ];
        }

        return [];
    }

    private function validateWizardField(string $key, string $value): array
    {
        if ($value === '') {
            return [false, null, 'Ups, inputnya kosong 😅 Coba isi dulu ya.'];
        }

        if ($key === 'status_perkawinan') {
            $normalized = str_replace(' ', '_', mb_strtolower($value));
            if (! in_array($normalized, ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'], true)) {
                return [false, null, 'Statusnya belum sesuai 😅 Pilih: belum_kawin, kawin, cerai_hidup, cerai_mati.'];
            }
            return [true, $normalized, null];
        }

        if ($key === 'jenis_kelamin_meninggal') {
            $normalized = mb_strtolower($value);
            if (in_array($normalized, ['laki', 'laki-laki', 'pria'], true)) {
                return [true, 'laki-laki', null];
            }
            if (in_array($normalized, ['perempuan', 'wanita'], true)) {
                return [true, 'perempuan', null];
            }
            return [false, null, 'Jenis kelaminnya belum sesuai 😅 Tulis: laki-laki atau perempuan.'];
        }

        if ($key === 'usia_meninggal') {
            if (! ctype_digit($value)) {
                return [false, null, 'Usia harus angka ya 🙏'];
            }
            $usia = (int) $value;
            if ($usia < 0 || $usia > 130) {
                return [false, null, 'Usia harus di rentang 0-130 ya 🙂'];
            }
            return [true, $usia, null];
        }

        if ($key === 'tanggal_meninggal') {
            try {
                $tanggal = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
            } catch (\Throwable) {
                return [false, null, 'Format tanggalnya belum pas 😅 Gunakan YYYY-MM-DD ya.'];
            }

            return [true, $tanggal, null];
        }

        return [true, $value, null];
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
            'Lainnya',
        ];
    }

    private function formatKeperluanOptions(): string
    {
        $lines = [];
        foreach ($this->sktmKeperluanOptions() as $i => $opt) {
            $lines[] = ($i + 1) . '. ' . $opt;
        }
        return implode("\n", $lines) . "\nKetik nomor atau tulis keperluanmu.";
    }

    private function extractJenisSurat(string $message): ?string
    {
        $lower = mb_strtolower($message);

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

        return null;
    }

    private function extractField(string $message, string $key): ?string
    {
        $pattern = '/\b' . preg_quote($key, '/') . '\s*[:=]\s*([^:]+?)(?=\s+\w+\s*[:=]|$)/i';
        if (! preg_match($pattern, $message, $m)) {
            return null;
        }

        return trim($m[1]);
    }

    private function createSuratByChat(
        Request $request,
        string $jenis,
        string $perihal,
        string $keperluan,
        ?string $catatan,
        ?string $statusPerkawinan,
        ?string $pekerjaan
    ): SuratPengajuan {
        return DB::transaction(function () use (
            $request,
            $jenis,
            $perihal,
            $keperluan,
            $catatan,
            $statusPerkawinan,
            $pekerjaan
        ): SuratPengajuan {
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
                'tanggal_surat' => Carbon::now()->toDateString(),
                'status' => 'menunggu',
            ]);
        });
    }

    private function createKematianByChat(Request $request, array $data): SuratPengajuan
    {
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

        return $this->createSuratByChat(
            $request,
            SuratPengajuan::JENIS_KEMATIAN,
            'Keterangan Kematian',
            'Surat Keterangan Kematian dibuat atas dasar yang sebenarnya.',
            $catatan,
            null,
            null
        );
    }
}
