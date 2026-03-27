<?php

namespace App\Services;

use App\Models\ExerciseSubmission;
use App\Models\User;

class DashboardCoachService
{
    public function buildCoachPayload(User $user, array $recommendations, array $quickWins, array $streak): array
    {
        $nextActions = [];

        if (!empty($recommendations['lesson'])) {
            $nextActions[] = [
                'label' => 'Leçon conseillée',
                'title' => $recommendations['lesson']->title,
                'url' => route('lessons.show', $recommendations['lesson']->slug),
                'duration' => 20,
            ];
        }

        if (!empty($recommendations['exercise'])) {
            $nextActions[] = [
                'label' => 'Exercice conseillé',
                'title' => $recommendations['exercise']->title,
                'url' => route('exercises.show', $recommendations['exercise']->slug),
                'duration' => 25,
            ];
        }

        $recoveryAction = null;
        if (($streak['current'] ?? 0) === 0 && !empty($streak['last_activity_date'])) {
            $recoveryAction = [
                'message' => 'Reprenez avec un exercice court pour relancer votre dynamique.',
                'url' => route('exercises.index', ['difficulty' => 'simple']),
                'label' => 'Relancer en 15 min',
            ];
        }

        $failedSubmissions = ExerciseSubmission::query()
            ->where('user_id', $user->id)
            ->where('status', 'echoue')
            ->count();

        return [
            'next_actions' => array_slice($nextActions, 0, 3),
            'weekly_goal' => [
                'target_exercises' => $quickWins['exercise_goal'] ?? 3,
                'done_exercises' => $quickWins['exercise_done'] ?? 0,
                'progress_percent' => $quickWins['exercise_progress'] ?? 0,
            ],
            'recovery_action' => $recoveryAction,
            'failed_submissions' => $failedSubmissions,
        ];
    }
}
