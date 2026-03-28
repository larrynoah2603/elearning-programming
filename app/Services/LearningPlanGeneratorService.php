<?php

namespace App\Services;

use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\LearningPlan;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Collection;

class LearningPlanGeneratorService
{
    /**
     * Génère un plan intelligent sur 7 jours :
     * - adapte la charge au temps disponible,
     * - renforce les difficultés faibles selon les performances récentes,
     * - ajoute des items de révision espacée.
     */
    public function generateForUser(User $user): LearningPlan
    {
        $profile = $user->learningProfile;
        $minutesPerDay = max(15, min(180, (int) ($profile?->minutes_per_day ?? 30)));
        $targetLanguage = collect($profile?->preferred_languages)->filter()->first();

        $performance = $this->buildPerformanceSnapshot($user);

        [$lessonBudget, $exerciseBudget] = $this->resolveDailyBudget(
            minutesPerDay: $minutesPerDay,
            profileLevel: (string) ($profile?->level ?? 'beginner'),
            successRate: $performance['global_success_rate']
        );

        $start = now()->toDateString();
        $end = now()->addDays(6)->toDateString();

        $user->learningPlans()->where('status', 'active')->update(['status' => 'paused']);

        $plan = LearningPlan::create([
            'user_id' => $user->id,
            'starts_at' => $start,
            'ends_at' => $end,
            'status' => 'active',
        ]);

        $dueReviewExercises = $this->buildSpacedRepetitionCandidates($user, $targetLanguage)
            ->take($this->resolveReviewSlots($minutesPerDay));

        $reviewIds = $dueReviewExercises->pluck('id')->all();

        $freshExercises = $this->buildFreshExerciseCandidates(
            user: $user,
            targetLanguage: $targetLanguage,
            weakDifficulty: $performance['weak_difficulty'],
            excludeExerciseIds: $reviewIds
        );

        $lessons = $this->buildLessonCandidates(
            user: $user,
            profileLevel: (string) ($profile?->level ?? 'beginner'),
            weakDifficulty: $performance['weak_difficulty']
        );

        $position = 1;

        $position = $this->appendLessons(
            plan: $plan,
            lessons: $lessons,
            minutesBudget: $lessonBudget,
            startPosition: $position
        );

        $position = $this->appendExercises(
            plan: $plan,
            exercises: $dueReviewExercises,
            minutesBudget: max(10, (int) round($exerciseBudget * 0.35)),
            startPosition: $position,
            titlePrefix: 'Révision'
        );

        $position = $this->appendExercises(
            plan: $plan,
            exercises: $freshExercises,
            minutesBudget: max(10, (int) round($exerciseBudget * 0.65)),
            startPosition: $position
        );

        return $plan->load('items');
    }

    private function buildPerformanceSnapshot(User $user): array
    {
        $recent = ExerciseSubmission::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['reussi', 'echoue'])
            ->where('updated_at', '>=', now()->subDays(21))
            ->with('exercise:id,difficulty')
            ->get();

        if ($recent->isEmpty()) {
            return [
                'global_success_rate' => 0.6,
                'weak_difficulty' => null,
            ];
        }

        $successRate = $recent->where('status', 'reussi')->count() / max(1, $recent->count());

        $byDifficulty = $recent
            ->filter(fn (ExerciseSubmission $submission) => (bool) $submission->exercise)
            ->groupBy(fn (ExerciseSubmission $submission) => $submission->exercise->difficulty)
            ->map(function (Collection $rows) {
                $success = $rows->where('status', 'reussi')->count();
                $total = $rows->count();

                return $success / max(1, $total);
            });

        $weakDifficulty = $byDifficulty->sort()->keys()->first();

        return [
            'global_success_rate' => $successRate,
            'weak_difficulty' => $weakDifficulty,
        ];
    }

    private function resolveDailyBudget(int $minutesPerDay, string $profileLevel, float $successRate): array
    {
        $lessonRatio = match ($profileLevel) {
            'advanced' => 0.35,
            'intermediate' => 0.45,
            default => 0.55,
        };

        // Si l'élève est en difficulté récente, on augmente la part apprentissage.
        if ($successRate < 0.45) {
            $lessonRatio += 0.1;
        }

        $lessonRatio = max(0.3, min(0.7, $lessonRatio));

        $lessonBudget = max(15, (int) round($minutesPerDay * $lessonRatio));
        $exerciseBudget = max(10, $minutesPerDay - $lessonBudget);

        return [$lessonBudget, $exerciseBudget];
    }

    private function resolveReviewSlots(int $minutesPerDay): int
    {
        return match (true) {
            $minutesPerDay >= 120 => 3,
            $minutesPerDay >= 60 => 2,
            default => 1,
        };
    }

    private function buildSpacedRepetitionCandidates(User $user, ?string $targetLanguage): Collection
    {
        $checkpoints = [1, 3, 7, 14, 30];

        return ExerciseSubmission::query()
            ->where('user_id', $user->id)
            ->where('status', 'reussi')
            ->with('exercise')
            ->get()
            ->filter(fn (ExerciseSubmission $submission) => $submission->exercise && $submission->exercise->is_active)
            ->filter(function (ExerciseSubmission $submission) use ($targetLanguage) {
                if (!$targetLanguage) {
                    return true;
                }

                return $submission->exercise->programming_language === $targetLanguage;
            })
            ->map(function (ExerciseSubmission $submission) use ($checkpoints) {
                $referenceDate = $submission->corrected_at
                    ?? $submission->ai_corrected_at
                    ?? $submission->updated_at;

                $days = max(0, $referenceDate->diffInDays(now()));
                $distance = collect($checkpoints)->map(fn (int $point) => abs($point - $days))->min();
                $isDue = $distance <= 1;

                return [
                    'exercise' => $submission->exercise,
                    'is_due' => $isDue,
                    'distance' => $distance,
                    'days' => $days,
                ];
            })
            ->filter(fn (array $row) => $row['is_due'])
            ->sortBy([
                ['distance', 'asc'],
                ['days', 'desc'],
            ])
            ->pluck('exercise')
            ->unique('id')
            ->values();
    }

    private function buildFreshExerciseCandidates(
        User $user,
        ?string $targetLanguage,
        ?string $weakDifficulty,
        array $excludeExerciseIds
    ): Collection {
        $knownStatuses = ExerciseSubmission::query()
            ->where('user_id', $user->id)
            ->pluck('status', 'exercise_id');

        return Exercise::query()
            ->active()
            ->when(!$user->isSubscribed(), fn ($query) => $query->where('access_level', 'free'))
            ->when($targetLanguage, fn ($query) => $query->where('programming_language', $targetLanguage))
            ->whereNotIn('id', $excludeExerciseIds)
            ->get()
            ->sortByDesc(function (Exercise $exercise) use ($knownStatuses, $weakDifficulty) {
                $status = $knownStatuses->get($exercise->id);

                $priority = 0;

                // Exercice échoué récemment : priorité maximale pour consolidation.
                if ($status === 'echoue') {
                    $priority += 120;
                }

                // Exercice jamais tenté : priorité importante pour progression.
                if ($status === null) {
                    $priority += 80;
                }

                // Renforcer la difficulté où l'élève est le plus faible.
                if ($weakDifficulty && $exercise->difficulty === $weakDifficulty) {
                    $priority += 40;
                }

                // Adapte selon difficulté intrinsèque.
                $priority += $exercise->difficulty === 'complexe' ? 20 : 10;

                // Favorise les exercices avec durée réaliste pour la séance.
                $priority += max(0, 40 - (int) ($exercise->estimated_time ?? 20));

                return $priority;
            })
            ->values();
    }

    private function buildLessonCandidates(User $user, string $profileLevel, ?string $weakDifficulty): Collection
    {
        $targetLevel = match ($profileLevel) {
            'advanced' => 'avance',
            'intermediate' => 'intermediaire',
            default => 'debutant',
        };

        $previouslyDoneLessons = $user->learningPlans()
            ->with(['items' => function ($query) {
                $query->where('type', 'lesson')
                    ->where('is_done', true)
                    ->whereNotNull('target_id');
            }])
            ->latest('id')
            ->take(8)
            ->get()
            ->pluck('items')
            ->flatten()
            ->pluck('target_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        return Lesson::query()
            ->active()
            ->when(!$user->isSubscribed(), fn ($query) => $query->where('access_level', 'free'))
            ->whereNotIn('id', $previouslyDoneLessons)
            ->withCount(['exercises' => function ($query) use ($weakDifficulty) {
                $query->active();

                if ($weakDifficulty) {
                    $query->where('difficulty', $weakDifficulty);
                }
            }])
            ->get()
            ->sortByDesc(function (Lesson $lesson) use ($targetLevel) {
                $priority = 0;

                if ($lesson->level === $targetLevel) {
                    $priority += 80;
                }

                // Les leçons liées à des exercices disponibles aident la mise en pratique.
                $priority += ((int) $lesson->exercises_count) * 10;

                // Léger bonus aux contenus plus compacts pour cadence quotidienne.
                $priority += max(0, 25 - (int) ($lesson->page_count ?? 10));

                return $priority;
            })
            ->values();
    }

    private function appendLessons(LearningPlan $plan, Collection $lessons, int $minutesBudget, int $startPosition): int
    {
        $position = $startPosition;
        $remaining = $minutesBudget;

        foreach ($lessons as $lesson) {
            if ($remaining < 10) {
                break;
            }

            $estimated = $this->estimateLessonMinutes($lesson, $remaining);

            $plan->items()->create([
                'type' => 'lesson',
                'target_id' => $lesson->id,
                'title' => $lesson->title,
                'url' => route('lessons.show', $lesson->slug),
                'estimated_minutes' => $estimated,
                'position' => $position++,
            ]);

            $remaining -= $estimated;
        }

        return $position;
    }

    private function appendExercises(
        LearningPlan $plan,
        Collection $exercises,
        int $minutesBudget,
        int $startPosition,
        string $titlePrefix = ''
    ): int {
        $position = $startPosition;
        $remaining = $minutesBudget;

        foreach ($exercises as $exercise) {
            if ($remaining < 8) {
                break;
            }

            $estimated = $this->estimateExerciseMinutes($exercise, $remaining);

            $title = $titlePrefix !== ''
                ? "{$titlePrefix} · {$exercise->title}"
                : $exercise->title;

            $plan->items()->create([
                'type' => 'exercise',
                'target_id' => $exercise->id,
                'title' => $title,
                'url' => route('exercises.show', $exercise->slug),
                'estimated_minutes' => $estimated,
                'position' => $position++,
            ]);

            $remaining -= $estimated;
        }

        return $position;
    }

    private function estimateLessonMinutes(Lesson $lesson, int $remainingMinutes): int
    {
        $base = (int) round(max(12, min(45, ((int) ($lesson->page_count ?? 8)) * 2.2)));

        return max(10, min($base, $remainingMinutes));
    }

    private function estimateExerciseMinutes(Exercise $exercise, int $remainingMinutes): int
    {
        $base = (int) ($exercise->estimated_time ?? ($exercise->difficulty === 'complexe' ? 35 : 20));
        $base = max(10, min(60, $base));

        return max(8, min($base, $remainingMinutes));
    }
}
