<?php
// Nanti Laravel bisa mengirim enam feature ke Python API. 
// Laravel menyediakan HTTP Client untuk komunikasi dengan service eksternal.

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class AiPredictionService
{
    public function predict(array $features): ?array
    {
        $url = config('services.ai.url');

        if (!$url) {
            return null;
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->post($url, [
                    'features' => $features,
                ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        return null;
    }
}
