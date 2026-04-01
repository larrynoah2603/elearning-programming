<?php

namespace App\Jobs;

use App\Models\ExerciseSubmission;
use App\Services\DeepseekCorrectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EvaluateSubmissionWithAiJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $submissionId)
    {
    }

    public function handle(DeepseekCorrectionService $correctionService): void
    {
        $submission = ExerciseSubmission::query()
            ->with('exercise')
            ->find($this->submissionId);

        if (!$submission || $submission->status !== 'soumis') {
            return;
        }

        $aiCorrection = $correctionService->evaluate($submission);

        if ($aiCorrection === null) {
            return;
        }

        $unitTestScore = $submission->unit_test_score;
        $isFallback = (bool) data_get($aiCorrection, 'is_fallback', false);
        $aiScore = data_get($aiCorrection, 'score');
        $finalScore = $aiScore;

        if (!$isFallback && $unitTestScore !== null && $aiScore !== null) {
            $finalScore = (int) round(($aiScore * 0.7) + ($unitTestScore * 0.3));
        }

        $status = $aiCorrection['requires_human_review'] || $isFallback
            ? 'corrige'
            : (($finalScore ?? 0) >= 50 ? 'reussi' : 'echoue');

        $feedback = $aiCorrection['feedback'];
        if ($submission->unit_tests_total > 0) {
            $feedback .= "

Tests unitaires: {$submission->unit_tests_passed}/{$submission->unit_tests_total} réussis.";
        }

        $submission->update([
            'score' => $finalScore,
            'feedback' => $feedback,
            'feedback_structured' => [
                'strengths' => ($finalScore !== null && $finalScore >= 50) ? 'Bonne logique globale.' : 'Tentative complète soumise.',
                'blocking_points' => $aiCorrection['feedback'],
                'next_action' => $submission->unit_tests_total > 0
                    ? "Corriger les tests unitaires en échec ({$submission->unit_tests_passed}/{$submission->unit_tests_total})."
                    : 'Améliorer la gestion des cas limites selon le feedback.',
                'ai_diagnostic' => $isFallback ? [
                    'mode' => 'fallback_manual_review',
                    'reason' => data_get($aiCorrection, 'fallback_reason', 'unknown'),
                ] : null,
            ],
            'status' => $status,
            'corrected_at' => now(),
            'corrected_by' => null,
            'ai_score' => $aiScore,
            'ai_feedback' => $aiCorrection['feedback'],
            'ai_requires_human_review' => $aiCorrection['requires_human_review'],
            'ai_corrected_at' => now(),
            'ai_model' => $aiCorrection['model'],
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('AI correction job failed.', [
            'submission_id' => $this->submissionId,
            'error' => $exception->getMessage(),
        ]);
    }
}
