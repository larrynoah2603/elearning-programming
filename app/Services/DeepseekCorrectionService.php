<?php

namespace App\Services;

use App\Models\ExerciseSubmission;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepseekCorrectionService
{
    /**
     * Evaluate a submission with DeepSeek.
     */
    public function evaluate(ExerciseSubmission $submission): ?array
    {
        $apiKey = config('services.deepseek.key');
        $baseUrl = rtrim((string) config('services.deepseek.url'), '/');
        $model = (string) config('services.deepseek.model', 'deepseek-chat');

        if (empty($apiKey) || empty($baseUrl)) {
            return null;
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

        try {
            $response = Http::timeout((int) config('services.deepseek.timeout', 25))
                ->retry(2, 300)
                ->withToken($apiKey)
                ->post($baseUrl.'/chat/completions', [
                    'model' => $model,
                    'temperature' => 0.2,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => 'Tu es un assistant de correction automatique de code.'],
                        ['role' => 'user', 'content' => implode("\n", $prompt)],
                    ],
                ]);
        } catch (ConnectionException $e) {
            Log::warning('DeepSeek API connection failed during correction.', [
                'submission_id' => $submission->id,
                'exercise_id' => $submission->exercise_id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        $content = data_get($response->json(), 'choices.0.message.content');

        if (!is_string($content) || trim($content) === '') {
            return null;
        }

        $parsed = json_decode($content, true);

        if (!is_array($parsed)) {
            return null;
        }

        $score = max(0, min(100, (int) data_get($parsed, 'score', 0)));
        $feedback = (string) data_get($parsed, 'feedback', 'Correction IA indisponible.');
        $requiresHumanReview = (bool) data_get($parsed, 'requires_human_review', false);

        return [
            'score' => $score,
            'feedback' => $feedback,
            'requires_human_review' => $requiresHumanReview,
            'model' => data_get($response->json(), 'model', $model),
        ];
    }
}
