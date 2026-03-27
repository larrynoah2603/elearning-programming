<?php

namespace App\Services;

use App\Models\Exercise;
use App\Models\LearningPlan;
use App\Models\Lesson;
use App\Models\User;

class LearningPlanGeneratorService
{
    public function generateForUser(User $user): LearningPlan
    {
        $profile = $user->learningProfile;
        $minutesPerDay = max(15, min(180, (int) ($profile?->minutes_per_day ?? 30)));
        $targetLanguage = collect($profile?->preferred_languages)->filter()->first();

        $start = now()->toDateString();
        $end = now()->addDays(6)->toDateString();

        $user->learningPlans()->where('status', 'active')->update(['status' => 'paused']);

        $plan = LearningPlan::create([
            'user_id' => $user->id,
            'starts_at' => $start,
            'ends_at' => $end,
            'status' => 'active',
        ]);

        $lessons = Lesson::query()
            ->active()
            ->when(!$user->isSubscribed(), fn ($q) => $q->where('access_level', 'free'))
            ->orderBy('order')
            ->take(3)
            ->get();

        $exercises = Exercise::query()
            ->active()
            ->when(!$user->isSubscribed(), fn ($q) => $q->where('access_level', 'free'))
            ->when($targetLanguage, fn ($q) => $q->where('programming_language', $targetLanguage))
            ->orderBy('order')
            ->take(4)
            ->get();

        $position = 1;

        foreach ($lessons as $lesson) {
            $plan->items()->create([
                'type' => 'lesson',
                'target_id' => $lesson->id,
                'title' => $lesson->title,
                'url' => route('lessons.show', $lesson->slug),
                'estimated_minutes' => (int) round($minutesPerDay * 0.6),
                'position' => $position++,
            ]);
        }

        foreach ($exercises as $exercise) {
            $plan->items()->create([
                'type' => 'exercise',
                'target_id' => $exercise->id,
                'title' => $exercise->title,
                'url' => route('exercises.show', $exercise->slug),
                'estimated_minutes' => (int) round($minutesPerDay * 0.8),
                'position' => $position++,
            ]);
        }

        return $plan->load('items');
    }
}
