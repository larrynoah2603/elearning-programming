<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\User;
use App\Models\VideoProgress;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class WeeklyChallengeController extends Controller
{
    public function index(): View
    {
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        $challenges = Exercise::query()
            ->active()
            ->withCount(['submissions as weekly_attempts' => function ($q) use ($start) {
                $q->where('updated_at', '>=', $start);
            }])
            ->orderByDesc('weekly_attempts')
            ->orderByDesc('difficulty')
            ->take(8)
            ->get();

        $leaderboard = User::query()
            ->where('role', '!=', 'admin')
            ->get()
            ->map(function (User $user) use ($start) {
                $exerciseScore = (int) ExerciseSubmission::query()
                    ->where('user_id', $user->id)
                    ->where('status', 'reussi')
                    ->where('updated_at', '>=', $start)
                    ->sum('score');

                $videoScore = (int) VideoProgress::query()
                    ->where('user_id', $user->id)
                    ->where('is_completed', true)
                    ->where('updated_at', '>=', $start)
                    ->count() * 10;

                return [
                    'name' => $user->name,
                    'score' => $exerciseScore + $videoScore,
                    'exercise_score' => $exerciseScore,
                    'video_score' => $videoScore,
                ];
            })
            ->sortByDesc('score')
            ->take(20)
            ->values();

        return view('challenges.index', compact('challenges', 'leaderboard', 'start', 'end'));
    }
}
