<?php

namespace App\Http\Controllers;

use App\Models\LogChatbot;
use App\Models\SuratPengajuan;
use Google\Cloud\Dialogflow\V2\DetectIntentRequest;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ChatbotController extends Controller
{
    private const CHATBOT_WIZARD_SESSION_KEY = 'chatbot_wizard';
    private const CHATBOT_CONFIRM_SESSION_KEY = 'chatbot_confirm_create';
    private const CHATBOT_STATUS_SESSION_KEY = 'chatbot_status_mode';

    public function chatbot(Request $request): View
    {
        return view('dashboard.chatbot', [
            'user' => $request->user(),
        ]);
    }

    public function chatbotReset(Request $request): JsonResponse
    {
        $request->session()->forget(self::CHATBOT_WIZARD_SESSION_KEY);
        $request->session()->forget(self::CHATBOT_CONFIRM_SESSION_KEY);
        $request->session()->forget(self::CHATBOT_STATUS_SESSION_KEY);

        return response()->json([
            'ok' => true,
        ]);
    }

    public function chatbotMessage(Request $request): JsonResponse
    {
        $message = trim((string) $request->input('message', ''));
        if ($message === '') {
            return $this->jsonReply(
                'Pesannya masih kosong ya. Contoh: "cek status surat" atau "buat surat".',
                422
            );
        }

        $lower = mb_strtolower($message);
        $normalized = $this->normalizeMessage($message);

        if (in_array($lower, ['batal', 'cancel', 'stop'], true)) {
            $request->session()->forget(self::CHATBOT_WIZARD_SESSION_KEY);
            $request->session()->forget(self::CHATBOT_CONFIRM_SESSION_KEY);
            $request->session()->forget(self::CHATBOT_STATUS_SESSION_KEY);

            return $this->jsonReply('Siap, prosesnya saya batalkan dulu ya.');
        }

        $activeWizard = $request->session()->get(self::CHATBOT_WIZARD_SESSION_KEY);
        if (is_array($activeWizard) && isset($activeWizard['jenis'], $activeWizard['step'])) {
            return $this->jsonReply($this->chatbotHandleWizardResponse($request, $message, $activeWizard));
        }

        $statusMode = $request->session()->get(self::CHATBOT_STATUS_SESSION_KEY);
        if (is_array($statusMode) && isset($statusMode['mode'])) {
            $mode = (string) $statusMode['mode'];
            if ($mode === 'menu') {
                $choice = $this->parseStatusChoice($normalized);
                if ($choice === 'nik') {
                    $request->session()->put(self::CHATBOT_STATUS_SESSION_KEY, ['mode' => 'nik']);
                    return $this->jsonReply('Oke, tulis NIK kamu ya.');
                }
                if ($choice === 'nama') {
                    $request->session()->put(self::CHATBOT_STATUS_SESSION_KEY, ['mode' => 'nama']);
                    return $this->jsonReply('Oke, tulis nama kamu ya.');
                }

                return $this->jsonReply(implode("\n", [
                    'Biar saya cek status suratnya, pilih dulu ya.',
                    '1) Pakai NIK',
                    '2) Pakai nama',
                ]));
            }

            if ($mode === 'nik') {
                $request->session()->forget(self::CHATBOT_STATUS_SESSION_KEY);
                return $this->jsonReply($this->chatbotHandleStatusLookup($message, 'nik'));
            }

            if ($mode === 'nama') {
                $request->session()->forget(self::CHATBOT_STATUS_SESSION_KEY);
                return $this->jsonReply($this->chatbotHandleStatusLookup($message, 'nama'));
            }
        }

        $pendingConfirm = $request->session()->get(self::CHATBOT_CONFIRM_SESSION_KEY);
        if (is_array($pendingConfirm) && isset($pendingConfirm['message'])) {
            $decision = $this->parseYesNo($normalized);
            if ($decision === true) {
                $request->session()->forget(self::CHATBOT_CONFIRM_SESSION_KEY);
                return $this->jsonReply($this->chatbotHandleCreateSurat($request, (string) $pendingConfirm['message']));
            }
            if ($decision === false) {
                $request->session()->forget(self::CHATBOT_CONFIRM_SESSION_KEY);
                return $this->jsonReply('Oke, pengajuan suratnya batal ya. Kalau mau lanjut, ketik jenis surat lagi.');
            }

            return $this->jsonReply('Boleh jawab "ya" atau "tidak" ya. Jadi lanjut buat suratnya?');
        }

        $smallTalk = $this->detectSmallTalk($normalized);
        if ($smallTalk === 'greeting') {
            return $this->jsonReply('Halo! Ada yang bisa saya bantu? Kamu mau cek status atau buat surat?');
        }
        if ($smallTalk === 'thanks') {
            return $this->jsonReply('Sama-sama! Kalau masih butuh bantuan, tinggal bilang ya.');
        }

        $serviceInfo = $this->detectServiceInfo($normalized);
        if ($serviceInfo === 'cara_pengajuan') {
            return $this->jsonReply(implode("\n", [
                'Halo, Bapak/Ibu. Untuk pengurusan Surat Keterangan, silakan pilih salah satu cara berikut:',
                '1) Secara Online:',
                'Buat pengajuan di menu Pengajuan Surat.',
                'Mohon tunggu sampai pengajuan di setujui oleh admin. Setelah itu, Bapak/Ibu bisa langsung datang ke kantor desa untuk mengambil surat dan nomor suratnya.',
                '2) Secara Offline:',
                'Apabila ingin datang langsung ke balai desa, silakan membawa fotokopi KTP dan KK',
            ]));
        }
        if ($serviceInfo === 'syarat_dokumen') {
            return $this->jsonReply(implode("\n", [
                'Berikut syarat dokumen yang perlu disiapkan untuk pengurusan surat:',
                '1) Fotokopi KTP pemohon.',
                '2) Fotokopi Kartu Keluarga (KK).',
                '4) Dokumen pendukung sesuai jenis surat yang diajukan (jika diperlukan).',
                'Kalau kamu sebut jenis suratnya, saya bantu cek dokumen pendukung yang perlu disiapkan.',
            ]));
        }

        $intent = $this->detectIntent($normalized);
        $jenisFromMessage = $this->extractJenisSurat($message);

        if ($intent === 'status') {
            $request->session()->put(self::CHATBOT_STATUS_SESSION_KEY, ['mode' => 'menu']);
            return $this->jsonReply(implode("\n", [
                'Biar saya cek status suratnya, pilih dulu ya.',
                '1) Pakai NIK',
                '2) Pakai nama',
            ]));
        }

        if ($intent === 'create_surat' || $jenisFromMessage) {
            $request->session()->put(self::CHATBOT_CONFIRM_SESSION_KEY, [
                'message' => $message,
            ]);

            $label = $jenisFromMessage ? $this->labelByJenis($jenisFromMessage) : 'surat';
            $labelLower = mb_strtolower($label);
            $prefix = str_contains($labelLower, 'surat') ? 'Mau saya bantu buat ' : 'Mau saya bantu buat surat ';
            return $this->jsonReply($prefix . $label . '? Jawab "ya" atau "tidak".');
        }

        // Dialogflow disabled for this controller.

        if ($this->isHelpRequest($normalized)) {
            return $this->jsonReply(implode("\n", [
                'Saya bisa bantu 2 hal utama nih.',
                '1) Cek status surat',
                '2) Buat surat otomatis:',
                '- "buat surat domisili"',
                '- "buat surat tidak mampu"',
                '- "buat surat kelahiran"',
                '- "buat surat kematian"',
                '- "buat surat usaha"',
                '- "buat surat belum menikah"',
                '- "buat surat kehilangan"',
                '- "buat surat penghasilan orang tua"',
                'Ketik "batal" kapan saja kalau mau berhenti ya.',
                'Selengkapnya, silahkan tekan menu opsi di bawah ya.'
            ]));
        }

        if ($intent === null) {
            return $this->jsonReply(implode("\n", [
                'Maaf, saya hanya bisa untuk:',
                '1) Cek status surat',
                '2) Buat surat otomatis',
                '3) Tanya seputar layanan surat',
                'Contoh: "cek status surat" atau "buat surat".',
                'Selengkapnya, silahkan tekan menu opsi di bawah ya.'
            ]));
        }

            return $this->jsonReply(implode("\n", [
                'Maaf, saya hanya bisa untuk:',
                '1) Cek status surat',
                '2) Buat surat otomatis',
                '3) Tanya seputar layanan surat',
                'Contoh: "cek status surat" atau "buat surat".',
                'Selengkapnya, silahkan tekan menu opsi di bawah ya.'
            ]));
    }

    private function jsonReply(string $text, int $status = 200): JsonResponse
    {
        $request = request();
        if ($request instanceof Request && $request->routeIs('dashboard.chatbot.message')) {
            $this->logConversation($request, (string) $request->input('message', ''), $text);
        }

        return response()->json([
            'reply' => $this->decorateReply($text),
        ], $status);
    }

    private function logConversation(Request $request, string $message, string $reply): void
    {
        $message = trim($message);
        if ($message === '') {
            return;
        }

        LogChatbot::create([
            'sesi_id' => $this->getChatbotSessionId($request),
            'pertanyaan_user' => $message,
            'jawaban_bot' => $reply,
            'waktu_interaksi' => now(),
            'id_permohonan' => null,
        ]);
    }

    private function getChatbotSessionId(Request $request): string
    {
        $key = 'chatbot_log_session_id';
        $existing = $request->session()->get($key);

        if (is_string($existing) && $existing !== '') {
            return $existing;
        }

        $newId = (string) Str::uuid();
        $request->session()->put($key, $newId);

        return $newId;
    }

    private function decorateReply(string $text): string
    {
        $clean = trim($text);
        $clean = str_replace('??', '😊', $clean);
        $lines = [];

        if ($this->needsGreeting($clean)) {
            $lines[] = 'Halo! 👋😊';
        }

        foreach (preg_split('/\R/u', $clean) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $lines[] = $this->ensureEmoji($line);
        }

        if ($this->needsThanks($clean)) {
            $lines[] = 'Terima kasih ya 🙏😊';
        }

        return implode("\n", $lines);
    }

    private function ensureEmoji(string $line): string
    {
        if (preg_match('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $line)) {
            return $line;
        }

        return $line . ' 😊';
    }

    private function needsGreeting(string $text): bool
    {
        $lower = mb_strtolower($text);
        return ! preg_match('/^(halo|hai|hi|hey|assalamualaikum|selamat)\b/u', $lower);
    }

    private function needsThanks(string $text): bool
    {
        $lower = mb_strtolower($text);
        return ! preg_match('/\b(terima kasih|makasih|makasi|thanks|thx)\b/u', $lower);
    }

    private function dialogflowReply(Request $request, string $message): ?string
    {
        if (! class_exists(SessionsClient::class)) {
            $autoload = base_path('vendor/autoload.php');
            if (is_file($autoload)) {
                require_once $autoload;
            }
        }

        if (! class_exists(SessionsClient::class)) {
            return null;
        }

        $projectId = config('services.dialogflow.project_id');
        $languageCode = config('services.dialogflow.language', 'id');
        $credentialsPath = config('services.dialogflow.credentials');

        if (! $projectId) {
            return null;
        }

        $message = trim($message);
        if ($message === '') {
            return null;
        }

        $sessionId = $this->getDialogflowSessionId($request);

        try {
            $clientOptions = [];
            if ($credentialsPath) {
                $resolvedPath = $credentialsPath;
                if (! str_starts_with($resolvedPath, '/') && ! preg_match('/^[A-Za-z]:\\\\/', $resolvedPath)) {
                    $resolvedPath = base_path($resolvedPath);
                }
                if (! is_file($resolvedPath)) {
                    $resolvedPath = storage_path($credentialsPath);
                }
                if (is_file($resolvedPath)) {
                    $clientOptions['credentials'] = $resolvedPath;
                } else {
                    return null;
                }
            }

            $sessionsClient = new SessionsClient($clientOptions);
            $session = $sessionsClient->sessionName($projectId, $sessionId);

            $textInput = (new TextInput())
                ->setText($message)
                ->setLanguageCode($languageCode);

            $queryInput = (new QueryInput())->setText($textInput);
            $detectRequest = (new DetectIntentRequest())
                ->setSession($session)
                ->setQueryInput($queryInput);

            $response = $sessionsClient->detectIntent($detectRequest);

            $queryResult = $response->getQueryResult();
            $reply = $queryResult ? (string) $queryResult->getFulfillmentText() : '';

            $sessionsClient->close();

            return $reply ?: null;
        } catch (\Throwable $e) {
            Log::error('Dialogflow error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function getDialogflowSessionId(Request $request): string
    {
        $key = 'dialogflow_session_id';
        $existing = $request->session()->get($key);
        if (is_string($existing) && $existing !== '') {
            return $existing;
        }

        $newId = (string) Str::uuid();
        $request->session()->put($key, $newId);
        return $newId;
    }

    private function chatbotHandleStatusLookup(string $message, ?string $mode = null): string
    {
        $raw = trim((string) $message);
        $nik = null;
        if (preg_match('/\bnik\s*[:=]?\s*([0-9]{8,20})\b/i', $message, $m)) {
            $nik = $m[1];
        } elseif ($mode === 'nik') {
            $digits = preg_replace('/\D+/', '', $raw);
            if ($digits !== '') {
                $nik = $digits;
            }
        }

        $nama = null;
        if (preg_match('/\bnama\s*[:=]?\s*([a-zA-Z\s\.\'-]{3,80})$/i', $message, $m)) {
            $nama = trim($m[1]);
        } elseif ($mode === 'nama') {
            $nama = $raw;
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
            return implode("\n", [
                'Biar saya cek status suratnya, pilih dulu ya ??',
                '1) Pakai NIK',
                '2) Pakai nama',
            ]);
        }

        $rows = $query->get();
        if ($rows->isEmpty()) {
            return 'Maaf ya, datanya belum ketemu. Coba cek lagi ??';
        }

        $lines = ['Ini status surat yang ditemukan ya ??'];
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
            return 'Kamu mau buat surat apa? Contoh: "buat surat domisili" atau "buat surat tidak mampu ...".';
        }

        if (in_array($jenis, [
            SuratPengajuan::JENIS_TIDAK_MAMPU,
            SuratPengajuan::JENIS_KEMATIAN,
            SuratPengajuan::JENIS_KELAHIRAN,
            SuratPengajuan::JENIS_USAHA,
            SuratPengajuan::JENIS_BELUM_MENIKAH,
            SuratPengajuan::JENIS_KEHILANGAN,
            SuratPengajuan::JENIS_PENGHASILAN_ORTU,
        ], true)) {
            return $this->startWizard(
                $request,
                $jenis,
                'Oke, kita mulai pengajuan Surat Keterangan ' . $this->labelByJenis($jenis) . ' ya ??'
            );
        }

        $keperluan = $this->extractField($message, 'keperluan') ?: $this->keperluanByJenis(SuratPengajuan::JENIS_DOMISILI);
        $surat = $this->createSuratByChat(
            $request,
            SuratPengajuan::JENIS_DOMISILI,
            $this->perihalByJenis(SuratPengajuan::JENIS_DOMISILI),
            $keperluan,
            null,
            null,
            null
        );

        return 'Pengajuan surat domisili berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
    }
    private function startWizard(Request $request, string $jenis, string $intro): string
    {
        $steps = $this->wizardSteps($jenis);
        $stepIndex = 0;
        $data = [];

        while (isset($steps[$stepIndex])) {
            $candidate = $steps[$stepIndex];
            if (! empty($candidate['depends_on'])) {
                $depKey = (string) $candidate['depends_on'];
                $depValue = $candidate['depends_value'] ?? true;
                $actual = $data[$depKey] ?? null;
                $match = is_array($depValue)
                    ? in_array($actual, $depValue, true)
                    : $actual === $depValue;

                if (! $match) {
                    $data[$candidate['key']] = null;
                    $stepIndex++;
                    continue;
                }
            }
            break;
        }

        $request->session()->put(self::CHATBOT_WIZARD_SESSION_KEY, [
            'jenis' => $jenis,
            'step' => $stepIndex,
            'data' => $data,
        ]);

        $firstPrompt = $steps[$stepIndex]['prompt'] ?? 'Ketik data pertama.';

        return $intro . "\n" . $firstPrompt . "\nKetik \"batal\" kapan saja kalau mau berhenti ya ??";
    }
    private function chatbotHandleWizardResponse(Request $request, string $message, array $wizard): string
    {
        $jenis = (string) ($wizard['jenis'] ?? '');
        $stepIndex = (int) ($wizard['step'] ?? 0);
        $data = is_array($wizard['data'] ?? null) ? $wizard['data'] : [];
        $steps = $this->wizardSteps($jenis);

        while (isset($steps[$stepIndex])) {
            $candidate = $steps[$stepIndex];
            if (! empty($candidate['depends_on'])) {
                $depKey = (string) $candidate['depends_on'];
                $depValue = $candidate['depends_value'] ?? true;
                $actual = $data[$depKey] ?? null;
                $match = is_array($depValue)
                    ? in_array($actual, $depValue, true)
                    : $actual === $depValue;

                if (! $match) {
                    $data[$candidate['key']] = null;
                    $stepIndex++;
                    continue;
                }
            }
            break;
        }

        if (! isset($steps[$stepIndex])) {
            $request->session()->forget(self::CHATBOT_WIZARD_SESSION_KEY);
            return 'Ups, pesannya belum bisa diproses nih ?? Coba ulangi ya, misalnya: "buat surat ..."';
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
                return trim(($error ?: 'Input belum pas.') . "\n" . $step['prompt']);
            }
        }

        $data[$key] = $value;
        $stepIndex++;

        while (isset($steps[$stepIndex])) {
            $candidate = $steps[$stepIndex];
            if (! empty($candidate['depends_on'])) {
                $depKey = (string) $candidate['depends_on'];
                $depValue = $candidate['depends_value'] ?? true;
                $actual = $data[$depKey] ?? null;
                $match = is_array($depValue)
                    ? in_array($actual, $depValue, true)
                    : $actual === $depValue;

                if (! $match) {
                    $data[$candidate['key']] = null;
                    $stepIndex++;
                    continue;
                }
            }
            break;
        }

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
                $this->perihalByJenis(SuratPengajuan::JENIS_TIDAK_MAMPU),
                (string) ($data['keperluan'] ?? ''),
                $data['digunakan_di'] ?? null,
                (string) ($data['status_perkawinan'] ?? ''),
                (string) ($data['pekerjaan'] ?? '')
            );

            return 'Pengajuan SKTM berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_KEMATIAN) {
            $surat = $this->createKematianByChat($request, $data);

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
                $request,
                SuratPengajuan::JENIS_KELAHIRAN,
                $this->perihalByJenis(SuratPengajuan::JENIS_KELAHIRAN),
                $this->keperluanByJenis(SuratPengajuan::JENIS_KELAHIRAN),
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
                $request,
                SuratPengajuan::JENIS_USAHA,
                $this->perihalByJenis(SuratPengajuan::JENIS_USAHA),
                $this->keperluanByJenis(SuratPengajuan::JENIS_USAHA),
                $catatan,
                (string) ($data['status_perkawinan'] ?? ''),
                (string) ($data['pekerjaan'] ?? '')
            );

            return 'Pengajuan Surat Usaha berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        if ($jenis === SuratPengajuan::JENIS_BELUM_MENIKAH) {
            $surat = $this->createSuratByChat(
                $request,
                SuratPengajuan::JENIS_BELUM_MENIKAH,
                $this->perihalByJenis(SuratPengajuan::JENIS_BELUM_MENIKAH),
                $this->keperluanByJenis(SuratPengajuan::JENIS_BELUM_MENIKAH),
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
                $request,
                SuratPengajuan::JENIS_KEHILANGAN,
                $this->perihalByJenis(SuratPengajuan::JENIS_KEHILANGAN),
                $this->keperluanByJenis(SuratPengajuan::JENIS_KEHILANGAN),
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
                $request,
                SuratPengajuan::JENIS_PENGHASILAN_ORTU,
                $this->perihalByJenis(SuratPengajuan::JENIS_PENGHASILAN_ORTU),
                $this->keperluanByJenis(SuratPengajuan::JENIS_PENGHASILAN_ORTU),
                $catatan,
                null,
                null
            );

            return 'Pengajuan Surat Penghasilan Orang Tua berhasil dibuat. Nomor surat akan diisi admin/petugas. Status: ' . strtoupper($surat->status);
        }

        return 'Prosesnya selesai, tapi jenis suratnya belum kebaca ya ??';
    }
    private function wizardSteps(string $jenis): array
    {
        if ($jenis === SuratPengajuan::JENIS_TIDAK_MAMPU) {
            return [
                [
                    'key' => 'status_perkawinan',
                    'prompt' => "Apakah kamu sudah menikah?
Pilih salah satu:
- belum kawin
- kawin
- cerai hidup
- cerai mati",
                ],
                [
                    'key' => 'pekerjaan',
                    'prompt' => 'Boleh tulis pekerjaan kamu?',
                ],
                [
                    'key' => 'keperluan',
                    'prompt' => "Keperluannya untuk apa ya?
" . $this->formatKeperluanOptions(),
                ],
                [
                    'key' => 'digunakan_di',
                    'prompt' => 'Tempat tujuan (opsional). Kalau kosong, ketik "-" ya.',
                    'optional' => true,
                ],
            ];
        }

        if ($jenis === SuratPengajuan::JENIS_KEMATIAN) {
            return [
                ['key' => 'nama_meninggal', 'prompt' => 'Mohon tulis nama yang meninggal ya.'],
                ['key' => 'jenis_kelamin_meninggal', 'prompt' => "Jenis kelamin?
Pilih:
- laki-laki
- perempuan"],
                ['key' => 'usia_meninggal', 'prompt' => 'Usia berapa? (angka saja)'],
                ['key' => 'tanggal_meninggal', 'prompt' => 'Tanggal meninggal (format YYYY-MM-DD = tahun-bulan-hari)'],
                ['key' => 'lokasi_meninggal', 'prompt' => 'Lokasi meninggal di mana?'],
                ['key' => 'sebab_meninggal', 'prompt' => 'Penyebab meninggal?'],
             
            ];
        }

        if ($jenis === SuratPengajuan::JENIS_KELAHIRAN) {
            return [
                ['key' => 'nama_bayi', 'prompt' => 'Nama lengkap anak?'],
                ['key' => 'jenis_kelamin_bayi', 'prompt' => "Jenis kelamin anak?
Pilih:
- laki-laki
- perempuan"],
                ['key' => 'tempat_lahir_bayi', 'prompt' => 'Tempat lahir anak?'],
                ['key' => 'tanggal_lahir_bayi', 'prompt' => 'Tanggal lahir anak (format YYYY-MM-DD = tahun-bulan-hari)'],
                ['key' => 'anak_ke', 'prompt' => 'Anak ke berapa? (angka 1-20)'],
                ['key' => 'nama_ayah', 'prompt' => 'Nama lengkap ayah?'],
                ['key' => 'agama_ayah', 'prompt' => 'Agama ayah?'],
                ['key' => 'tempat_lahir_ayah', 'prompt' => 'Tempat lahir ayah?'],
                ['key' => 'tanggal_lahir_ayah', 'prompt' => 'Tanggal lahir ayah (format YYYY-MM-DD = tahun-bulan-hari)'],
                ['key' => 'nama_ibu', 'prompt' => 'Nama lengkap ibu?'],
                ['key' => 'agama_ibu', 'prompt' => 'Agama ibu?'],
                ['key' => 'tempat_lahir_ibu', 'prompt' => 'Tempat lahir ibu?'],
                ['key' => 'tanggal_lahir_ibu', 'prompt' => 'Tanggal lahir ibu (format YYYY-MM-DD = tahun-bulan-hari)'],
            ];
        }

        if ($jenis === SuratPengajuan::JENIS_USAHA) {
            return [
                ['key' => 'pekerjaan', 'prompt' => 'Pekerjaan kamu apa?'],
                ['key' => 'status_perkawinan', 'prompt' => "Apakah kamu sudah menikah?
Pilih salah satu:
- belum kawin
- kawin
- cerai hidup
- cerai mati"],
                ['key' => 'nama_usaha', 'prompt' => 'Jenis atau nama usaha?'],
            ];
        }

        if ($jenis === SuratPengajuan::JENIS_BELUM_MENIKAH) {
            return [
                ['key' => 'pekerjaan', 'prompt' => 'Pekerjaan kamu apa?'],
            ];
        }

        if ($jenis === SuratPengajuan::JENIS_KEHILANGAN) {
            return [
                ['key' => 'jenis_dokumen', 'prompt' => "Jenis dokumen yang hilang?
Pilih:
- kk
- ktp
- akta_kelahiran
- lainnya"],
                [
                    'key' => 'jenis_dokumen_lainnya',
                    'prompt' => 'Jenis dokumen lainnya apa?',
                    'depends_on' => 'jenis_dokumen',
                    'depends_value' => 'lainnya',
                ],
                ['key' => 'nama_dokumen', 'prompt' => 'Nama terkait di dokumen?'],
                ['key' => 'status_perkawinan', 'prompt' => "Apakah kamu sudah menikah?
Pilih salah satu:
- belum kawin
- kawin
- cerai hidup
- cerai mati"],
                ['key' => 'pekerjaan', 'prompt' => 'Pekerjaan kamu apa?'],
                ['key' => 'tanggal_kehilangan', 'prompt' => 'Tanggal kehilangan (format YYYY-MM-DD = tahun-bulan-hari)'],
                ['key' => 'lokasi_kehilangan', 'prompt' => "Lokasi kehilangan di mana?
Pilih:
- rumah
- jalan"],
                [
                    'key' => 'lokasi_rumah_detail',
                    'prompt' => 'Keterangan lokasi (rumah).',
                    'depends_on' => 'lokasi_kehilangan',
                    'depends_value' => 'rumah',
                ],
                [
                    'key' => 'lokasi_jalan_detail',
                    'prompt' => 'Rute kehilangan di jalan (contoh: Wonorejo ke ...).',
                    'depends_on' => 'lokasi_kehilangan',
                    'depends_value' => 'jalan',
                ],
            ];
        }

        if ($jenis === SuratPengajuan::JENIS_PENGHASILAN_ORTU) {
            return [
                ['key' => 'universitas_anak', 'prompt' => 'Nama universitas kamu?'],
                ['key' => 'nama_ayah', 'prompt' => 'Nama ayah?'],
                ['key' => 'tempat_lahir_ayah', 'prompt' => 'Tempat lahir ayah?'],
                ['key' => 'tanggal_lahir_ayah', 'prompt' => 'Tanggal lahir ayah (format YYYY-MM-DD = tahun-bulan-hari)'],
                ['key' => 'nik_ayah', 'prompt' => 'NIK ayah (16 digit)?'],
                ['key' => 'pekerjaan_ayah', 'prompt' => 'Pekerjaan ayah?'],
                ['key' => 'penghasilan_ayah', 'prompt' => 'Penghasilan ayah per bulan? Pilih: 500000-5000000 atau tulis "lainnya"'],
                [
                    'key' => 'penghasilan_ayah_lainnya',
                    'prompt' => 'Penghasilan lainnya ayah (angka).',
                    'depends_on' => 'penghasilan_ayah',
                    'depends_value' => 'lainnya',
                ],
                ['key' => 'nama_ibu', 'prompt' => 'Nama ibu?'],
                ['key' => 'tempat_lahir_ibu', 'prompt' => 'Tempat lahir ibu?'],
                ['key' => 'tanggal_lahir_ibu', 'prompt' => 'Tanggal lahir ibu (format YYYY-MM-DD = tahun-bulan-hari)'],
                ['key' => 'nik_ibu', 'prompt' => 'NIK ibu (16 digit)?'],
                ['key' => 'pekerjaan_ibu', 'prompt' => 'Pekerjaan ibu?'],
                ['key' => 'penghasilan_ibu', 'prompt' => 'Penghasilan ibu per bulan? Pilih: 500000-5000000 atau tulis "lainnya"'],
                [
                    'key' => 'penghasilan_ibu_lainnya',
                    'prompt' => 'Penghasilan lainnya ibu (angka).',
                    'depends_on' => 'penghasilan_ibu',
                    'depends_value' => 'lainnya',
                ],
            ];
        }

        return [];
    }

    private function validateWizardField(string $key, string $value): array
    {
        if ($value === '') {
            return [false, null, 'Ups, inputnya masih kosong ya ?? Coba isi dulu.'];
        }

        if ($key === 'status_perkawinan') {
            $normalized = str_replace(' ', '_', mb_strtolower($value));
            if (! in_array($normalized, ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'], true)) {
                return [false, null, 'Statusnya belum pas. Pilih: belum kawin, kawin, cerai hidup, cerai mati ya ??'];
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
            return [false, null, 'Jenis kelaminnya belum pas. Tulis: laki-laki atau perempuan ya ??'];
        }

        if ($key === 'usia_meninggal') {
            if (! ctype_digit($value)) {
                return [false, null, 'Usianya angka aja ya ??'];
            }
            $usia = (int) $value;
            if ($usia < 0 || $usia > 130) {
                return [false, null, 'Usianya di rentang 0-130 ya ??'];
            }
            return [true, $usia, null];
        }

        if ($key === 'tanggal_meninggal') {
            try {
                $tanggal = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
            } catch (\Throwable) {
                return [false, null, 'Format tanggalnya belum pas. Pakai YYYY-MM-DD (tahun-bulan-hari) ya ??'];
            }

            return [true, $tanggal, null];
        }

        if (in_array($key, ['tanggal_lahir_bayi', 'tanggal_lahir_ayah', 'tanggal_lahir_ibu'], true)) {
            try {
                $tanggal = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
            } catch (\Throwable) {
                return [false, null, 'Format tanggalnya belum pas. Pakai YYYY-MM-DD (tahun-bulan-hari) ya ??'];
            }

            return [true, $tanggal, null];
        }

        if ($key === 'jenis_kelamin_bayi') {
            $normalized = mb_strtolower($value);
            if (in_array($normalized, ['laki', 'laki-laki', 'pria'], true)) {
                return [true, 'laki-laki', null];
            }
            if (in_array($normalized, ['perempuan', 'wanita'], true)) {
                return [true, 'perempuan', null];
            }
            return [false, null, 'Jenis kelaminnya belum pas. Tulis: laki-laki atau perempuan ya ??'];
        }

        if ($key === 'anak_ke') {
            if (! ctype_digit($value)) {
                return [false, null, 'Anak ke pakai angka ya ??'];
            }
            $anakKe = (int) $value;
            if ($anakKe < 1 || $anakKe > 20) {
                return [false, null, 'Anak ke di rentang 1-20 ya ??'];
            }
            return [true, $anakKe, null];
        }

        if ($key === 'jenis_dokumen') {
            $normalized = str_replace(' ', '_', mb_strtolower($value));
            $map = [
                'kk' => 'kk',
                'kartu_keluarga' => 'kk',
                'kartu_keluarga_(kk)' => 'kk',
                'ktp' => 'ktp',
                'kartu_tanda_penduduk' => 'ktp',
                'akta_kelahiran' => 'akta_kelahiran',
                'akta-kelahiran' => 'akta_kelahiran',
                'lainnya' => 'lainnya',
            ];
            if (! isset($map[$normalized])) {
                return [false, null, 'Jenis dokumennya belum pas. Pilih: kk, ktp, akta_kelahiran, atau lainnya ya ??'];
            }
            return [true, $map[$normalized], null];
        }

        if ($key === 'lokasi_kehilangan') {
            $normalized = mb_strtolower($value);
            if (in_array($normalized, ['rumah', 'jalan'], true)) {
                return [true, $normalized, null];
            }
            return [false, null, 'Lokasi kehilangan pilih "rumah" atau "jalan" ya ??'];
        }

        if ($key === 'tanggal_kehilangan') {
            try {
                $tanggal = Carbon::createFromFormat('Y-m-d', $value)->format('Y-m-d');
            } catch (\Throwable) {
                return [false, null, 'Format tanggalnya belum pas. Pakai YYYY-MM-DD (tahun-bulan-hari) ya ??'];
            }

            return [true, $tanggal, null];
        }

        if (in_array($key, ['nik_ayah', 'nik_ibu'], true)) {
            $digits = preg_replace('/\D+/', '', $value ?? '');
            if (strlen($digits) != 16) {
                return [false, null, 'NIK harus 16 digit ya ??'];
            }
            return [true, $digits, null];
        }

        if (in_array($key, ['penghasilan_ayah', 'penghasilan_ibu'], true)) {
            $normalized = mb_strtolower(trim($value));
            if ($normalized === 'lainnya') {
                return [true, 'lainnya', null];
            }
            $digits = preg_replace('/\D+/', '', $value ?? '');
            if ($digits === '') {
                return [false, null, 'Penghasilannya diisi ya ??'];
            }
            $amount = (int) $digits;
            $allowed = [500000, 1000000, 1500000, 2000000, 2500000, 3000000, 3500000, 4000000, 4500000, 5000000];
            if (! in_array($amount, $allowed, true)) {
                return [false, null, 'Penghasilan pilih 500000-5000000 atau tulis "lainnya" ya ??'];
            }
            return [true, (string) $amount, null];
        }

        if (in_array($key, ['penghasilan_ayah_lainnya', 'penghasilan_ibu_lainnya'], true)) {
            $digits = preg_replace('/\D+/', '', $value ?? '');
            if ($digits === '') {
                return [false, null, 'Penghasilan lainnya isi angka ya ??'];
            }
            return [true, $digits, null];
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
        return implode("\n", $lines) . "\nKetik nomor atau tulis keperluanmu.";}

    private function extractJenisSurat(string $message): ?string
    {
        $lower = mb_strtolower($message);
        $normalized = $this->normalizeMessage($message);

        if ($this->containsKeywordFuzzy($normalized, ['domisili'])) {
            return SuratPengajuan::JENIS_DOMISILI;
        }

        if ($this->containsKeywordFuzzy($normalized, ['tidak mampu', 'sktm'])) {
            return SuratPengajuan::JENIS_TIDAK_MAMPU;
        }

        if ($this->containsKeywordFuzzy($normalized, ['kematian'])) {
            return SuratPengajuan::JENIS_KEMATIAN;
        }

        if ($this->containsKeywordFuzzy($normalized, [
            'kelahiran',
            'lahir',
            'surat lahir',
            'akta kelahiran',
            'akte kelahiran',
            'surat kelahiran',
            'surat keterangan kelahiran',
        ])) {
            return SuratPengajuan::JENIS_KELAHIRAN;
        }

        if ($this->containsKeywordFuzzy($normalized, ['usaha', 'wiraswasta'])) {
            return SuratPengajuan::JENIS_USAHA;
        }

        if ($this->containsKeywordFuzzy($normalized, ['belum menikah', 'belum nikah'])) {
            return SuratPengajuan::JENIS_BELUM_MENIKAH;
        }

        if ($this->containsKeywordFuzzy($normalized, [
            'kehilangan',
            'hilang',
            'surat kehilangan',
            'kehilangan dokumen',
            'kehilangan kk',
            'kehilangan ktp',
            'kehilangan akta',
        ])) {
            return SuratPengajuan::JENIS_KEHILANGAN;
        }

        if ($this->containsKeywordFuzzy($normalized, [
            'penghasilan orang tua',
            'penghasilan orangtua',
            'penghasilan ortu',
            'surat penghasilan',
            'surat penghasilan ortu',
            'surat penghasilan orang tua',
            'surat penghasilan orangtua',
        ])) {
            return SuratPengajuan::JENIS_PENGHASILAN_ORTU;
        }

        return null;
    }

    private function normalizeMessage(string $message): string
    {
        $lower = mb_strtolower($message);
        $clean = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $lower);
        $clean = preg_replace('/\s+/', ' ', trim($clean ?? ''));
        return $clean ?: '';
    }

    private function detectSmallTalk(string $normalizedMessage): ?string
    {
        if ($normalizedMessage === '') {
            return null;
        }

        if ($this->containsKeywordFuzzy($normalizedMessage, [
            'surat',
            'buat',
            'bikin',
            'ajukan',
            'pengajuan',
            'cek',
            'status',
        ])) {
            return null;
        }

        $greetings = [
            'halo',
            'hai',
            'hi',
            'hey',
            'assalamualaikum',
            'salam',
            'permisi',
            'selamat pagi',
            'selamat siang',
            'selamat sore',
            'selamat malam',
            'pagi',
            'siang',
            'sore',
            'malam',
        ];

        if ($this->containsKeywordFuzzy($normalizedMessage, $greetings)) {
            return 'greeting';
        }

        $thanks = [
            'terima kasih',
            'terimakasih',
            'makasih',
            'makasi',
            'thanks',
            'thx',
        ];

        if ($this->containsKeywordFuzzy($normalizedMessage, $thanks)) {
            return 'thanks';
        }

        return null;
    }

    private function detectIntent(string $normalizedMessage): ?string
    {
        $intents = [
            'status' => [
                'status',
                'cek status',
                'cek surat',
                'status surat',
                'lihat status',
                'tracking',
            ],
            'create_surat' => [
                'buat surat',
                'ajukan surat',
                'pengajuan surat',
                'bikin surat',
                'buat suart',
                'ajuan surat',
            ],
        ];

        $scores = [];
        foreach ($intents as $intent => $keywords) {
            $scores[$intent] = $this->scoreIntent($normalizedMessage, $keywords);
        }

        arsort($scores);
        $topIntent = array_key_first($scores);
        $topScore = $scores[$topIntent] ?? 0;

        if ($topScore <= 0) {
            return null;
        }

        return $topIntent;
    }

    private function isHelpRequest(string $normalizedMessage): bool
    {
        $keywords = [
            'kamu bisa apa',
            'bisa apa',
            'bantuan',
            'help',
            'menu',
        ];

        foreach ($keywords as $keyword) {
            if (str_contains($normalizedMessage, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function detectServiceInfo(string $normalizedMessage): ?string
    {
        $caraKeywords = [
            'cara pengajuan',
            'cara ajukan',
            'pengajuan surat',
            'ajukan surat',
            'alur pengajuan',
            'cara membuat surat',
            'cara buat surat',
        ];

        foreach ($caraKeywords as $keyword) {
            if (str_contains($normalizedMessage, $keyword)) {
                return 'cara_pengajuan';
            }
        }

        $syaratKeywords = [
            'syarat dokumen',
            'syarat surat',
            'syarat pengajuan',
            'persyaratan surat',
            'dokumen syarat',
        ];

        foreach ($syaratKeywords as $keyword) {
            if (str_contains($normalizedMessage, $keyword)) {
                return 'syarat_dokumen';
            }
        }

        return null;
    }

    private function isDialogflowFallback(string $reply): bool
    {
        $lower = mb_strtolower(trim($reply));
        if ($lower === '') {
            return true;
        }

        $patterns = [
            'saya hanya bisa membantu',
            'maaf',
            'saya tidak mengerti',
            'saya belum mengerti',
            'saya belum paham',
            'coba ulangi',
            'bisa bantu',
            'bisa bantu apa',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($lower, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function parseYesNo(string $normalizedMessage): ?bool
    {
        if ($normalizedMessage === '') {
            return null;
        }

        $yesWords = ['ya', 'iya', 'y', 'yes', 'oke', 'ok', 'boleh', 'lanjut', 'gas', 'siap'];
        $noWords = ['tidak', 'ga', 'gak', 'nggak', 'ngga', 'no', 'batal', 'jangan'];

        if ($this->containsKeywordFuzzy($normalizedMessage, $yesWords)) {
            return true;
        }

        if ($this->containsKeywordFuzzy($normalizedMessage, $noWords)) {
            return false;
        }

        return null;
    }

    private function parseStatusChoice(string $normalizedMessage): ?string
    {
        $message = trim($normalizedMessage);
        if ($message === '') {
            return null;
        }

        if (preg_match('/\b1\b/', $message) || $this->containsKeywordFuzzy($message, ['nik'])) {
            return 'nik';
        }

        if (preg_match('/\b2\b/', $message) || $this->containsKeywordFuzzy($message, ['nama'])) {
            return 'nama';
        }

        return null;
    }

    private function scoreIntent(string $normalizedMessage, array $keywords): int
    {
        $score = 0;
        foreach ($keywords as $keyword) {
            if ($this->containsKeywordFuzzy($normalizedMessage, [$keyword])) {
                $score++;
            }
        }
        return $score;
    }

    private function containsKeywordFuzzy(string $normalizedMessage, array $keywords): bool
    {
        if ($normalizedMessage === '') {
            return false;
        }

        $tokens = array_values(array_filter(explode(' ', $normalizedMessage), static function ($token) {
            return mb_strlen($token) > 2;
        }));

        foreach ($keywords as $keyword) {
            $keyword = trim(mb_strtolower($keyword));
            if ($keyword === '') {
                continue;
            }

            if (str_contains($normalizedMessage, $keyword)) {
                return true;
            }

            $keywordTokens = explode(' ', $keyword);
            $keywordTokenCount = count($keywordTokens);

            if ($keywordTokenCount > 1) {
                $window = $keywordTokenCount;
                for ($i = 0; $i <= count($tokens) - $window; $i++) {
                    $segment = implode(' ', array_slice($tokens, $i, $window));
                    similar_text($segment, $keyword, $similarity);
                    if ($similarity >= 80) {
                        return true;
                    }
                }
                continue;
            }

            $target = $keywordTokens[0];
            foreach ($tokens as $token) {
                if ($this->isFuzzyWordMatch($token, $target)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isFuzzyWordMatch(string $token, string $target): bool
    {
        if ($token === $target) {
            return true;
        }

        if ($token === '' || $target === '') {
            return false;
        }

        if (mb_substr($token, 0, 1) !== mb_substr($target, 0, 1)) {
            return false;
        }

        if (abs(mb_strlen($token) - mb_strlen($target)) >= 3) {
            return false;
        }

        $len = max(mb_strlen($target), 1);
        if ($len <= 4) {
            $maxDistance = 1;
        } elseif ($len <= 8) {
            $maxDistance = 2;
        } else {
            $maxDistance = 3;
        }

        return levenshtein($token, $target) <= $maxDistance;
    }

    private function extractField(string $message, string $key): ?string
    {
        $pattern = '/\b' . preg_quote($key, '/') . '\s*[:=]\s*([^:]+?)(?=\s+\w+\s*[:=]|$)/i';
        if (! preg_match($pattern, $message, $m)) {
            return null;
        }

        return trim($m[1]);
    }

    private function labelByJenis(string $jenis): string
    {
        $label = SuratPengajuan::jenisOptions()[$jenis] ?? 'Surat';
        return str_replace('Surat Keterangan ', '', $label);
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
