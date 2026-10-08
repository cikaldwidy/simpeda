<?php

namespace App\Http\Controllers;

use App\Models\LogChatbot;
use Google\Cloud\Dialogflow\V2\DetectIntentRequest;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\QueryParameters;
use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PublicChatbotController extends Controller
{
    public function message(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = trim((string) $request->input('message'));

        if (! class_exists(SessionsClient::class)) {
            $autoload = base_path('vendor/autoload.php');
            if (is_file($autoload)) {
                require_once $autoload;
            }
        }

        if (! class_exists(SessionsClient::class)) {
            $reply = $this->fallbackReply();
            $this->logConversation($request, $message, $reply);

            return response()->json([
                'reply' => $reply,
            ]);
        }

        $projectId = config('services.dialogflow.project_id');
        $languageCode = config('services.dialogflow.language', 'id');
        $credentialsPath = config('services.dialogflow.credentials');

        if (! $projectId) {
            $reply = $this->fallbackReply();
            $this->logConversation($request, $message, $reply);

            return response()->json([
                'reply' => $reply,
            ]);
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
                    $reply = $this->fallbackReply();
                    $this->logConversation($request, $message, $reply);

                    return response()->json([
                        'reply' => $reply,
                    ]);
                }
            }

            $sessionsClient = new SessionsClient($clientOptions);
            $session = $sessionsClient->sessionName($projectId, $sessionId);

            $textInput = (new TextInput())
                ->setText($message)
                ->setLanguageCode($languageCode);

            $queryInput = (new QueryInput())->setText($textInput);
            $queryParams = new QueryParameters();
            $detectRequest = (new DetectIntentRequest())
                ->setSession($session)
                ->setQueryInput($queryInput)
                ->setQueryParams($queryParams);

            $response = $sessionsClient->detectIntent($detectRequest);

            $queryResult = $response->getQueryResult();
            $reply = $queryResult ? (string) $queryResult->getFulfillmentText() : '';

            $sessionsClient->close();

            if ($reply === '') {
                $reply = $this->fallbackReply();
            }

            $this->logConversation($request, $message, $reply);

            return response()->json([
                'reply' => $reply,
            ]);
        } catch (\Throwable $e) {
            Log::error('Dialogflow error', ['error' => $e->getMessage()]);

            $reply = $this->fallbackReply();
            $this->logConversation($request, $message, $reply);

            return response()->json([
                'reply' => $reply,
            ]);
        }
    }

    private function fallbackReply(): string
    {
        return implode("\n", [
            'Maaf ya, chatbot lagi sibuk sedikit :) Coba ini dulu:',
            '1) Cek status surat: "cek status surat"',
            '2) Buat surat otomatis: "buat surat domisili"',
            'Kalau masih bingung, coba tulis dengan kata lain ya :)',
        ]);
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
}
