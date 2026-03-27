<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\LearningPlan;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoProgress;
use App\Services\DashboardCoachService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(DashboardCoachService $coachService)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $studyMetrics = $this->buildStudyMetrics($user);
        $streak = $this->buildStreakData($user);

        $stats = [
            'completed_exercises' => $user->completed_exercises_count,
            'total_points' => $user->total_points,
            'watched_videos' => $user->watched_videos_count,
            'current_streak' => $streak['current'],
            'study_minutes_week' => $studyMetrics['minutes_this_week'],
            // formations purchased (pour carte du dashboard)
            'purchased_formations' => $user->formationEnrollments()->where('status', 'paid')->count(),
        ];

        $recentSubmissions = ExerciseSubmission::with('exercise')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $recentVideoProgress = VideoProgress::with('video')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        if ($user->isSubscribed()) {
            $availableLessons = Lesson::active()->orderBy('order')->take(6)->get();
            $availableExercises = Exercise::active()->orderBy('order')->take(6)->get();
            $availableVideos = Video::active()->orderBy('order')->take(6)->get();
        } else {
            $availableLessons = Lesson::active()->where('access_level', 'free')->orderBy('order')->take(6)->get();
            $availableExercises = Exercise::active()->where('access_level', 'free')->orderBy('order')->take(6)->get();
            $availableVideos = collect();
        }

        $exerciseProgress = [
            'simple' => Exercise::simple()->whereHas('submissions', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'reussi');
            })->count(),
            'complexe' => Exercise::complex()->whereHas('submissions', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'reussi');
            })->count(),
        ];

        $badges = $this->buildBadges($stats, $streak);
        $recommendations = $this->buildRecommendations($user);
        $quickWins = $this->buildQuickWins($user, $studyMetrics);
        $leaderboard = $this->buildLeaderboard($user, $streak['current']);
        $activePlan = LearningPlan::query()
            ->with('items')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();
        $needsOnboarding = $user->learningProfile?->onboarding_completed_at === null;
        $coach = $coachService->buildCoachPayload($user, $recommendations, $quickWins, $streak);

        return view('dashboard', compact(
            'stats',
            'streak',
            'badges',
            'quickWins',
            'recommendations',
            'leaderboard',
            'recentSubmissions',
            'recentVideoProgress',
            'availableLessons',
            'availableExercises',
            'availableVideos',
            'exerciseProgress',
            'activePlan',
            'needsOnboarding',
            'coach'
        ));
    }

    private function buildStudyMetrics(User $user): array
    {
        $startOfWeek = Carbon::now()->startOfWeek();

        $exerciseMinutes = (int) Exercise::query()
            ->whereHas('submissions', function ($query) use ($user, $startOfWeek) {
                $query->where('user_id', $user->id)
                    ->where('status', 'reussi')
                    ->where('updated_at', '>=', $startOfWeek);
            })
            ->sum('estimated_time');

        $videoSeconds = (int) VideoProgress::query()
            ->where('user_id', $user->id)
            ->where('updated_at', '>=', $startOfWeek)
            ->sum('watched_duration');

        $videoMinutes = (int) round($videoSeconds / 60);

        return [
            'minutes_this_week' => $exerciseMinutes + $videoMinutes,
            'exercise_minutes_this_week' => $exerciseMinutes,
            'video_minutes_this_week' => $videoMinutes,
        ];
    }

    private function buildStreakData(User $user): array
    {
        $activityDates = $this->collectActivityDates($user);

        if ($activityDates->isEmpty()) {
            return [
                'current' => 0,
                'longest' => 0,
                'last_activity_date' => null,
            ];
        }

        $currentStreak = 0;
        $cursor = Carbon::today();

        if (!$activityDates->contains($cursor->toDateString())) {
            $cursor->subDay();
        }

        while ($activityDates->contains($cursor->toDateString())) {
            $currentStreak++;
            $cursor->subDay();
        }

        $longestStreak = 0;
        $running = 0;
        $previous = null;

        foreach ($activityDates as $date) {
            $current = Carbon::parse($date);

            if ($previous && $previous->diffInDays($current) === 1) {
                $running++;
            } else {
                $running = 1;
            }

            $longestStreak = max($longestStreak, $running);
            $previous = $current;
        }

        return [
            'current' => $currentStreak,
            'longest' => $longestStreak,
            'last_activity_date' => $activityDates->last(),
        ];
    }

    private function collectActivityDates(User $user): Collection
    {
        $submissionDates = ExerciseSubmission::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['soumis', 'corrige', 'reussi'])
            ->pluck('updated_at')
            ->map(fn ($dt) => Carbon::parse($dt)->toDateString());

        $videoDates = VideoProgress::query()
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('watched_duration', '>', 0)
                    ->orWhere('is_completed', true);
            })
            ->pluck('updated_at')
            ->map(fn ($dt) => Carbon::parse($dt)->toDateString());

        return $submissionDates
            ->merge($videoDates)
            ->unique()
            ->sort()
            ->values();
    }

    private function buildBadges(array $stats, array $streak): array
    {
        $definitions = [
            ['icon' => 'fa-fire', 'name' => 'Régulier', 'description' => '3 jours de streak', 'unlocked' => $streak['current'] >= 3],
            ['icon' => 'fa-fire-flame-curved', 'name' => 'Inarrêtable', 'description' => '7 jours de streak', 'unlocked' => $streak['current'] >= 7],
            ['icon' => 'fa-code', 'name' => 'Code Starter', 'description' => '5 exercices réussis', 'unlocked' => $stats['completed_exercises'] >= 5],
            ['icon' => 'fa-gem', 'name' => 'Expert', 'description' => '500 points cumulés', 'unlocked' => $stats['total_points'] >= 500],
            ['icon' => 'fa-video', 'name' => 'Cinéphile Tech', 'description' => '5 vidéos terminées', 'unlocked' => $stats['watched_videos'] >= 5],
        ];

        return collect($definitions)->map(function ($badge) {
            $badge['status'] = $badge['unlocked'] ? 'Débloqué' : 'À débloquer';
            return $badge;
        })->all();
    }

    private function buildRecommendations(User $user): array
    {
        $completedExerciseIds = ExerciseSubmission::query()
            ->where('user_id', $user->id)
            ->where('status', 'reussi')
            ->pluck('exercise_id');


        $favoriteLanguage = ExerciseSubmission::query()
            ->where('exercise_submissions.user_id', $user->id)
            ->where('exercise_submissions.status', 'reussi')
            ->join('exercises', 'exercise_submissions.exercise_id', '=', 'exercises.id')
            ->selectRaw('exercises.programming_language, COUNT(*) as total')
            ->groupBy('exercises.programming_language')
            ->orderByDesc('total')
            ->value('exercises.programming_language');

        $nextExercise = Exercise::active()
            ->when(!$user->isSubscribed(), fn ($query) => $query->where('access_level', 'free'))
            ->when($favoriteLanguage, fn ($query) => $query->where('programming_language', $favoriteLanguage))
            ->whereNotIn('id', $completedExerciseIds)
            ->orderBy('order')
            ->first();

        if (!$nextExercise) {
            $nextExercise = Exercise::active()
                ->when(!$user->isSubscribed(), fn ($query) => $query->where('access_level', 'free'))
                ->whereNotIn('id', $completedExerciseIds)
                ->orderBy('order')
                ->first();
        }

        $learnedLessonIds = ExerciseSubmission::query()
            ->where('exercise_submissions.user_id', $user->id)
            ->where('exercise_submissions.status', 'reussi')
            ->join('exercises', 'exercise_submissions.exercise_id', '=', 'exercises.id')
            ->whereNotNull('exercises.lesson_id')
            ->pluck('exercises.lesson_id')
            ->unique();

        $nextLesson = Lesson::active()
            ->when(!$user->isSubscribed(), fn ($query) => $query->where('access_level', 'free'))
            ->when($learnedLessonIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $learnedLessonIds))
            ->orderBy('order')
            ->first();

        return [
            'exercise' => $nextExercise,
            'lesson' => $nextLesson,
            'favorite_language' => $favoriteLanguage,
        ];
    }

    private function buildQuickWins(User $user, array $studyMetrics): array
    {
        $startOfWeek = Carbon::now()->startOfWeek();

        $weeklyExercisesGoal = 3;
        $weeklyMinutesGoal = 120;

        $completedThisWeek = ExerciseSubmission::query()
            ->where('user_id', $user->id)
            ->where('status', 'reussi')
            ->where('updated_at', '>=', $startOfWeek)
            ->count();

        $minutesThisWeek = $studyMetrics['minutes_this_week'];

        return [
            'exercise_goal' => $weeklyExercisesGoal,
            'exercise_done' => $completedThisWeek,
            'exercise_progress' => min(100, (int) round(($completedThisWeek / $weeklyExercisesGoal) * 100)),
            'minutes_goal' => $weeklyMinutesGoal,
            'minutes_done' => $minutesThisWeek,
            'minutes_progress' => min(100, (int) round(($minutesThisWeek / $weeklyMinutesGoal) * 100)),
        ];
    }

    private function buildLeaderboard(User $user, int $currentStreak): array
    {
        $baseUsers = User::query()->where('role', '!=', 'admin');

        $contextLabel = 'Classement global';

        if (!empty($user->school_name) && !empty($user->class_name)) {
            $baseUsers->where('school_name', $user->school_name)
                ->where('class_name', $user->class_name);
            $contextLabel = 'Classement privé ' . $user->class_name . ' - ' . $user->school_name;
        }

        $rows = $baseUsers->get()->map(function (User $candidate) use ($user, $currentStreak) {
            $candidateStreak = $candidate->id === $user->id
                ? $currentStreak
                : $this->buildStreakData($candidate)['current'];

            $score = ($candidate->total_points)
                + ($candidate->completed_exercises_count * 100)
                + ($candidate->watched_videos_count * 20)
                + ($candidateStreak * 15);

            return [
                'id' => $candidate->id,
                'name' => $candidate->name,
                'score' => $score,
                'points' => $candidate->total_points,
                'streak' => $candidateStreak,
            ];
        })->sortByDesc('score')->values();

        $top = $rows->take(5)->values();
        $position = $rows->search(fn ($row) => $row['id'] === $user->id);

        return [
            'label' => $contextLabel,
            'top' => $top,
            'my_rank' => $position === false ? null : $position + 1,
        ];
    }

    /**
     * Display user profile.
     */
    public function profile()
    {
        $user = auth()->user();

        $stats = [
            'completed_exercises' => $user->completed_exercises_count,
            'total_points' => $user->total_points,
            'watched_videos' => $user->watched_videos_count,
        ];

        $allSubmissions = ExerciseSubmission::with('exercise')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'submissions_page');

        $allVideoProgress = VideoProgress::with('video')
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->latest('completed_at')
            ->paginate(10, ['*'], 'videos_page');

        return view('profile', compact('user', 'stats', 'allSubmissions', 'allVideoProgress'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'school_name' => 'nullable|string|max:255',
            'class_name' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('success', 'Votre mot de passe a été changé avec succès.');
    }
}
