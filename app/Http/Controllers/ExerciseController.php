<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveExerciseProgressRequest;
use App\Http\Requests\SubmitExerciseRequest;
use App\Jobs\EvaluateSubmissionWithAiJob;
use App\Models\Exercise;
use App\Models\ExerciseHintView;
use App\Models\ExerciseSubmission;
use App\Models\Lesson;
use App\Services\DeepseekCorrectionService;
use App\Services\ExerciseUnitTestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ExerciseController extends Controller
{
    /**
     * Display a listing of exercises.
     */
    public function index(Request $request)
    {
        $query = Exercise::active();

        // Filter by difficulty
        if ($request->has('difficulty') && $request->difficulty !== 'all') {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by programming language
        if ($request->has('language') && $request->language !== 'all') {
            $query->byLanguage($request->language);
        }

        // Filter by access level based on user
        if (!auth()->check() || !auth()->user()->isSubscribed()) {
            $query->where('access_level', 'free');
        } elseif ($request->has('access') && $request->access !== 'all') {
            $query->where('access_level', $request->access);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $exercises = $query->orderBy('order')->paginate(12);
        $languages = $this->getProgrammingLanguages();

        return view('exercises.index', compact('exercises', 'languages'));
    }

    /**
     * Display the specified exercise.
     */
    public function show(string $slug)
    {
        $exercise = Exercise::where('slug', $slug)
            ->with(['user', 'lesson'])
            ->firstOrFail();

        // Check access
        if (!$exercise->isAccessibleBy(auth()->user())) {
            return redirect()->route('subscription.plans')
                ->with('info', 'Cet exercice nécessite un abonnement. Découvrez nos offres !');
        }

        // Get user's submission if exists
        $submission = null;
        if (auth()->check()) {
            $submission = $exercise->getUserSubmission(auth()->user());
        }

        $availableHintLevels = [];
        if (auth()->check()) {
            $viewedLevels = ExerciseHintView::query()
                ->where('user_id', auth()->id())
                ->where('exercise_id', $exercise->id)
                ->pluck('hint_level')
                ->map(fn ($lvl) => (int) $lvl)
                ->all();

            $availableHintLevels = [1];
            if (in_array(1, $viewedLevels, true)) {
                $availableHintLevels[] = 2;
            }
            if (in_array(2, $viewedLevels, true)) {
                $availableHintLevels[] = 3;
            }
        }

        // Get related exercises
        $relatedExercises = Exercise::active()
            ->where('id', '!=', $exercise->id)
            ->where('programming_language', $exercise->programming_language)
            ->where('access_level', 'free')
            ->take(3)
            ->get();

        return view('exercises.show', compact('exercise', 'submission', 'relatedExercises', 'availableHintLevels'));
    }

    /**
     * Submit exercise solution.
     */
    public function submit(SubmitExerciseRequest $request, Exercise $exercise)
    {
        // Check access
        if (!$exercise->isAccessibleBy(auth()->user())) {
            return response()->json(['error' => 'Subscription required'], 403);
        }

        $validated = $request->validated();

        $user = auth()->user();

        // Find or create submission
        $submission = ExerciseSubmission::firstOrNew([
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
        ]);

        $submission->submit($validated['code']);

        $unitTestReport = app(ExerciseUnitTestService::class)->evaluate($exercise, $validated['code']);

        $submission->update([
            'score' => null,
            'feedback' => null,
            'corrected_at' => null,
            'corrected_by' => null,
            'ai_score' => null,
            'ai_feedback' => null,
            'ai_requires_human_review' => false,
            'ai_corrected_at' => null,
            'ai_model' => null,
            'unit_test_results' => $unitTestReport['results'],
            'unit_tests_passed' => $unitTestReport['passed'],
            'unit_tests_total' => $unitTestReport['total'],
            'unit_test_score' => $unitTestReport['score'],
        ]);

        EvaluateSubmissionWithAiJob::dispatch($submission->id);

        $latestSubmission = $submission->fresh();

        return response()->json([
            'success' => true,
            'message' => 'Votre solution a été soumise. La correction est en cours de traitement.',
            'submission' => $latestSubmission,
            'report' => [
                'status' => $latestSubmission->status_display,
                'score' => $latestSubmission->score,
                'feedback' => $latestSubmission->feedback,
                'feedback_structured' => $this->buildStructuredFeedback($latestSubmission),
                'requires_human_review' => (bool) $latestSubmission->ai_requires_human_review,
                'unit_tests' => [
                    'passed' => (int) $latestSubmission->unit_tests_passed,
                    'total' => (int) $latestSubmission->unit_tests_total,
                    'score' => $latestSubmission->unit_test_score,
                    'results' => $latestSubmission->unit_test_results ?? [],
                ],
            ],
        ]);
    }


    /**
     * Get latest submission report for the authenticated user.
     */
    public function submissionStatus(Exercise $exercise)
    {
        if (!$exercise->isAccessibleBy(auth()->user())) {
            return response()->json(['error' => 'Subscription required'], 403);
        }

        $submission = ExerciseSubmission::query()
            ->where('user_id', auth()->id())
            ->where('exercise_id', $exercise->id)
            ->first();

        if (!$submission) {
            return response()->json([
                'success' => true,
                'has_submission' => false,
            ]);
        }

        if ($submission->status === 'soumis' && $submission->ai_corrected_at === null) {
            $this->tryInlineAiCorrection($submission);
            $submission->refresh();
        }

        return response()->json([
            'success' => true,
            'has_submission' => true,
            'submission' => $submission,
            'report' => [
                'status' => $submission->status_display,
                'score' => $submission->score,
                'feedback' => $submission->feedback,
                'feedback_structured' => $this->buildStructuredFeedback($submission),
                'requires_human_review' => (bool) $submission->ai_requires_human_review,
                'unit_tests' => [
                    'passed' => (int) $submission->unit_tests_passed,
                    'total' => (int) $submission->unit_tests_total,
                    'score' => $submission->unit_test_score,
                    'results' => $submission->unit_test_results ?? [],
                ],
            ],
            'is_final' => in_array($submission->status, ['corrige', 'reussi', 'echoue'], true),
        ]);
    }

    /**
     * Save exercise progress (without submitting).
     */
    public function saveProgress(SaveExerciseProgressRequest $request, Exercise $exercise)
    {
        // Check access
        if (!$exercise->isAccessibleBy(auth()->user())) {
            return response()->json(['error' => 'Subscription required'], 403);
        }

        $validated = $request->validated();

        $user = auth()->user();

        $submission = ExerciseSubmission::firstOrNew([
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
        ]);

        $submission->submitted_code = $validated['code'];

        // Ne pas écraser une soumission déjà envoyée/corrigée.
        if (!in_array($submission->status, ['soumis', 'corrige', 'reussi', 'echoue'], true)) {
            $submission->status = 'en_cours';
        }

        $submission->save();

        return response()->json([
            'success' => true,
            'message' => 'Progression sauvegardée.',
        ]);
    }

    /**
     * Attempt AI correction inline as a fallback when queue worker is unavailable.
     */
    private function tryInlineAiCorrection(ExerciseSubmission $submission): void
    {
        $lock = Cache::lock('submission-ai-correction-'.$submission->id, 20);

        if (!$lock->get()) {
            return;
        }

        try {
            $submission->loadMissing('exercise');
            $aiCorrection = app(DeepseekCorrectionService::class)->evaluate($submission);

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
                        ? "Corrigez les tests unitaires en échec ({$submission->unit_tests_passed}/{$submission->unit_tests_total})."
                        : 'Revoyez les cas limites et améliorez votre approche.',
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
        } finally {
            optional($lock)->release();
        }
    }

    private function buildStructuredFeedback(ExerciseSubmission $submission): array
    {
        if (is_array($submission->feedback_structured) && !empty($submission->feedback_structured)) {
            return $submission->feedback_structured;
        }

        $unitTestsTotal = (int) ($submission->unit_tests_total ?? 0);
        $unitTestsPassed = (int) ($submission->unit_tests_passed ?? 0);

        return [
            'strengths' => $submission->score !== null && $submission->score >= 50
                ? 'Bonne base de solution. Continuez sur cette logique.'
                : 'Vous avez soumis une tentative complète, c’est une bonne progression.',
            'blocking_points' => $submission->feedback ?: 'Des ajustements sont nécessaires pour valider tous les cas.',
            'next_action' => $unitTestsTotal > 0
                ? "Corrigez d'abord les tests unitaires en échec ({$unitTestsPassed}/{$unitTestsTotal} réussis)."
                : 'Revoyez les instructions puis améliorez la gestion des cas limites.',
        ];
    }

    /**
     * Show the form for creating a new exercise.
     */
    public function create()
    {
        $lessons = Lesson::active()->orderBy('order')->get();
        $languages = $this->getProgrammingLanguages();

        return view('admin.exercises.create', compact('lessons', 'languages'));
    }

    /**
     * Store a newly created exercise.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:simple,complexe',
            'access_level' => 'required|in:free,subscribed',
            'programming_language' => 'required|in:python,javascript,java,cpp,php,html_css,sql',
            'instructions' => 'required|string',
            'starter_code' => 'nullable|string',
            'solution_code' => 'nullable|string',
            'hints' => 'nullable|string',
            'unit_tests' => 'nullable|string',
            'points' => 'nullable|integer|min:1|max:100',
            'estimated_time' => 'nullable|integer|min:1',
            'lesson_id' => 'nullable|exists:lessons,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['unit_tests'] = $this->parseUnitTests($request->input('unit_tests'));

        // Set default values
        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);

        if (empty($validated['points'])) {
            $validated['points'] = $validated['difficulty'] === 'simple' ? 10 : 20;
        }

        $exercise = Exercise::create($validated);

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Exercice créé avec succès.');
    }

    /**
     * Display exercises list for admin.
     */
    public function adminIndex(Request $request)
    {
        $query = Exercise::with('user');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by difficulty
        if ($request->has('difficulty') && $request->difficulty !== 'all') {
            $query->where('difficulty', $request->difficulty);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $exercises = $query->latest()->paginate(20);

        return view('admin.exercises.index', compact('exercises'));
    }

    /**
     * Show the form for editing the exercise.
     */
    public function edit(Exercise $exercise)
    {
        $lessons = Lesson::active()->orderBy('order')->get();
        $languages = $this->getProgrammingLanguages();

        return view('admin.exercises.edit', compact('exercise', 'lessons', 'languages'));
    }

    /**
     * Update the exercise.
     */
    public function update(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:simple,complexe',
            'access_level' => 'required|in:free,subscribed',
            'programming_language' => 'required|in:python,javascript,java,cpp,php,html_css,sql',
            'instructions' => 'required|string',
            'starter_code' => 'nullable|string',
            'solution_code' => 'nullable|string',
            'hints' => 'nullable|string',
            'unit_tests' => 'nullable|string',
            'points' => 'nullable|integer|min:1|max:100',
            'estimated_time' => 'nullable|integer|min:1',
            'lesson_id' => 'nullable|exists:lessons,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['unit_tests'] = $this->parseUnitTests($request->input('unit_tests'));

        // Update slug if title changed
        if ($exercise->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $exercise->update($validated);

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Exercice mis à jour avec succès.');
    }

    /**
     * Remove the exercise.
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Exercice supprimé avec succès.');
    }

    /**
     * Toggle exercise active status.
     */
    public function toggleActive(Exercise $exercise)
    {
        $exercise->update(['is_active' => !$exercise->is_active]);

        $status = $exercise->is_active ? 'activé' : 'désactivé';

        return back()->with('success', "Exercice {$status} avec succès.");
    }

    /**
     * Get programming languages list.
     */
    private function getProgrammingLanguages(): array
    {
        return [
            'python' => 'Python',
            'javascript' => 'JavaScript',
            'java' => 'Java',
            'cpp' => 'C++',
            'php' => 'PHP',
            'html_css' => 'HTML/CSS',
            'sql' => 'SQL',
        ];
    }
}
