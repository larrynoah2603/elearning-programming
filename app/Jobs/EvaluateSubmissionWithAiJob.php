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

        $status = $aiCorrection['requires_human_review']
            ? 'corrige'
            : ($aiCorrection['score'] >= 50 ? 'reussi' : 'echoue');

        $submission->update([
            'score' => $aiCorrection['score'],
            'feedback' => $aiCorrection['feedback'],
            'status' => $status,
            'corrected_at' => now(),
            'corrected_by' => null,
            'ai_score' => $aiCorrection['score'],
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
