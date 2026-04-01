<?php

namespace App\Services;

use App\Models\ExerciseSubmission;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepseekCorrectionService
{
    /**
     * Normalize a Gemini model identifier so it can be injected in
     * /models/{model}:generateContent endpoints.
     */
    private function normalizeModelName(string $model): string
    {
        $normalized = trim($model);

        if (str_starts_with($normalized, 'models/')) {
            return substr($normalized, strlen('models/'));
        }

        return $normalized;
    }

    /**
     * Evaluate a submission with Gemini (Google AI Studio).
     */
    public function evaluate(ExerciseSubmission $submission): ?array
    {
        $apiKey = config('services.gemini.key');
        $baseUrl = rtrim((string) config('services.gemini.url'), '/');
        $model = $this->normalizeModelName((string) config('services.gemini.model', 'gemini-2.0-flash'));
        $timeout = (int) config('services.gemini.timeout', 25);

        if (empty($apiKey) || empty($baseUrl)) {
            return [
                'score' => null,
                'feedback' => 'Correction automatique indisponible pour le moment. Une validation manuelle est requise.',
                'requires_human_review' => true,
                'model' => $model,
                'is_fallback' => true,
                'fallback_reason' => 'missing_configuration',
            ];
        }

        $exercise = $submission->exercise;

        $prompt = [
            'Tu es un correcteur de code strict et pédagogue.',
            'Retourne UNIQUEMENT un JSON valide avec la structure suivante :',
            '{"score": entier 0-100, "feedback": "texte court en français", "requires_human_review": booléen}',
            'Critères : exactitude, qualité du code, respect des consignes.',
            'Mets requires_human_review à true si le code est ambigu, partiellement correct, ou si tu n\'es pas certain.',
            '',
            'Données exercice :',
            'Titre: '.($exercise->title ?? 'N/A'),
            'Langage: '.($exercise->programming_language ?? 'N/A'),
            'Instructions: '.($exercise->instructions ?? 'N/A'),
            'Solution de référence: '.($exercise->solution_code ?? 'Non fournie'),
            '',
            'Code soumis:',
            $submission->submitted_code,
        ];

        $candidateModels = array_values(array_unique(array_filter([
            $model,
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-flash-latest',
        ])));

        $response = null;
        $selectedModel = $model;

        try {
            foreach ($candidateModels as $candidateModel) {
                $selectedModel = $candidateModel;
                $url = sprintf('%s/models/%s:generateContent', $baseUrl, $candidateModel);

                $response = Http::timeout($timeout)
                    ->withHeaders([
                        'X-goog-api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => implode("\n", $prompt),
                                    ],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.2,
                            'responseMimeType' => 'application/json',
                        ],
                    ]);

                if ($response->successful()) {
                    $selectedModel = $candidateModel;
                    break;
                }

                $status = $response->status();
                $errorMessage = data_get($response->json(), 'error.message', $response->body());

                if ($status === 404 && is_string($errorMessage) && str_contains(strtolower($errorMessage), 'not found')) {
                    continue;
                }

                if ($status === 429 || $status >= 500) {
                    continue;
                }

                break;
            }
        } catch (ConnectionException|\Throwable $exception) {
            Log::warning('Gemini API unavailable while correcting submission.', [
                'submission_id' => $submission->id,
                'exercise_id' => $submission->exercise_id,
                'error' => $exception->getMessage(),
            ]);

            return [
                'score' => null,
                'feedback' => 'Correction automatique temporairement indisponible. Une validation manuelle est requise.',
                'requires_human_review' => true,
                'model' => $selectedModel,
                'is_fallback' => true,
                'fallback_reason' => 'connection_exception',
            ];
        }

        if ($response === null) {
            return [
                'score' => null,
                'feedback' => 'Correction automatique indisponible pour le moment. Une validation manuelle est requise.',
                'requires_human_review' => true,
                'model' => $selectedModel,
                'is_fallback' => true,
                'fallback_reason' => 'no_response',
            ];
        }

        if (!$response->successful()) {
            $status = $response->status();
            $errorMessage = data_get($response->json(), 'error.message', $response->body());

            Log::warning('Gemini API returned a non-success response.', [
                'submission_id' => $submission->id,
                'exercise_id' => $submission->exercise_id,
                'status' => $status,
                'error' => is_string($errorMessage) ? $errorMessage : null,
            ]);

            if ($status === 402 || str_contains(strtolower((string) $errorMessage), 'insufficient')) {
                return [
                    'score' => null,
                    'feedback' => 'Correction automatique momentanément indisponible. Une validation manuelle est requise.',
                    'requires_human_review' => true,
                    'model' => $selectedModel,
                    'is_fallback' => true,
                    'fallback_reason' => 'billing_or_quota',
                ];
            }

            if ($status === 429) {
                return [
                    'score' => null,
                    'feedback' => 'Correction automatique momentanément indisponible. Merci de réessayer plus tard.',
                    'requires_human_review' => true,
                    'model' => $selectedModel,
                    'is_fallback' => true,
                    'fallback_reason' => 'rate_limited',
                ];
            }

            return [
                'score' => null,
                'feedback' => 'Correction automatique indisponible (code: '.($status ?: 'erreur inconnue').'). Une validation manuelle est requise.',
                'requires_human_review' => true,
                'model' => $selectedModel,
                'is_fallback' => true,
                'fallback_reason' => 'http_'.$status,
            ];
        }

        $content = data_get($response->json(), 'candidates.0.content.parts.0.text');

        if (!is_string($content) || trim($content) === '') {
            return [
                'score' => null,
                'feedback' => 'Correction automatique indisponible (réponse vide). Une validation manuelle est requise.',
                'requires_human_review' => true,
                'model' => data_get($response->json(), 'modelVersion', $selectedModel),
                'is_fallback' => true,
                'fallback_reason' => 'empty_response',
            ];
        }

        $normalizedContent = trim($content);

        if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/is', $normalizedContent, $matches) === 1) {
            $normalizedContent = $matches[1];
        }

        $parsed = json_decode($normalizedContent, true);

        if (!is_array($parsed) && preg_match('/\{.*\}/s', $normalizedContent, $matches) === 1) {
            $parsed = json_decode($matches[0], true);
        }

        if (!is_array($parsed)) {
            return [
                'score' => null,
                'feedback' => 'Correction automatique indisponible (format de réponse non exploitable). Une validation manuelle est requise.',
                'requires_human_review' => true,
                'model' => data_get($response->json(), 'modelVersion', $selectedModel),
                'is_fallback' => true,
                'fallback_reason' => 'invalid_json_payload',
            ];
        }

        $score = max(0, min(100, (int) data_get($parsed, 'score', 0)));
        $feedback = (string) data_get($parsed, 'feedback', 'Correction automatique indisponible.');
        $requiresHumanReview = (bool) data_get($parsed, 'requires_human_review', false);

        return [
            'score' => $score,
            'feedback' => $feedback,
            'requires_human_review' => $requiresHumanReview,
            'model' => data_get($response->json(), 'modelVersion', $selectedModel),
            'is_fallback' => false,
            'fallback_reason' => null,
        ];
    }
}
