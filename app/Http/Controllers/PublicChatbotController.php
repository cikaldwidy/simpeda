<?php

namespace App\Http\Controllers;

use Google\Cloud\Dialogflow\V2\DetectIntentRequest;
use Google\Cloud\Dialogflow\V2\QueryInput;
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

        if (! class_exists(SessionsClient::class)) {
            $autoload = base_path('vendor/autoload.php');
            if (is_file($autoload)) {
                require_once $autoload;
            }
        }

        if (! class_exists(SessionsClient::class)) {
            return response()->json([
                'reply' => 'Maaf, layanan chatbot belum siap. Coba lagi sebentar ya 🙏',
            ], 503);
        }

        $projectId = config('services.dialogflow.project_id');
        $languageCode = config('services.dialogflow.language', 'id');
        $credentialsPath = config('services.dialogflow.credentials');

        if (! $projectId) {
            return response()->json([
                'reply' => 'Maaf, chatbot belum dikonfigurasi.',
            ], 503);
        }

        $message = trim((string) $request->input('message'));
        $sessionId = $request->session()->getId() ?: (string) Str::uuid();

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
                    return response()->json([
                        'reply' => 'Maaf, file kredensial chatbot tidak ditemukan.',
                    ], 503);
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

            if ($reply === '') {
                $reply = 'Maaf, aku belum paham. Coba tanya dengan kalimat lain ya 🙏';
            }

            return response()->json([
                'reply' => $reply,
            ]);
        } catch (\Throwable $e) {
            Log::error('Dialogflow error', ['error' => $e->getMessage()]);

            return response()->json([
                'reply' => 'Maaf, chatbot sedang mengalami kendala. Coba lagi sebentar ya 🙏',
            ], 500);
        }
    }
}
