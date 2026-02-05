<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\Lesson;
use Illuminate\Http\Request;
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

        // Get related exercises
        $relatedExercises = Exercise::active()
            ->where('id', '!=', $exercise->id)
            ->where('programming_language', $exercise->programming_language)
            ->where('access_level', 'free')
            ->take(3)
            ->get();

        return view('exercises.show', compact('exercise', 'submission', 'relatedExercises'));
    }

    /**
     * Submit exercise solution.
     */
    public function submit(Request $request, Exercise $exercise)
    {
        // Check access
        if (!$exercise->isAccessibleBy(auth()->user())) {
            return response()->json(['error' => 'Subscription required'], 403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50000',
        ]);

        $user = auth()->user();

        // Find or create submission
        $submission = ExerciseSubmission::firstOrNew([
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
        ]);

        $submission->submit($validated['code']);

        return response()->json([
            'success' => true,
            'message' => 'Votre solution a été soumise avec succès. Elle sera corrigée prochainement.',
            'submission' => $submission->fresh(),
        ]);
    }

    /**
     * Save exercise progress (without submitting).
     */
    public function saveProgress(Request $request, Exercise $exercise)
    {
        // Check access
        if (!$exercise->isAccessibleBy(auth()->user())) {
            return response()->json(['error' => 'Subscription required'], 403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50000',
        ]);

        $user = auth()->user();

        $submission = ExerciseSubmission::firstOrNew([
            'user_id' => $user->id,
            'exercise_id' => $exercise->id,
        ]);

        $submission->submitted_code = $validated['code'];
        $submission->status = 'en_cours';
        $submission->save();

        return response()->json([
            'success' => true,
            'message' => 'Progression sauvegardée.',
        ]);
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
            'points' => 'nullable|integer|min:1|max:100',
            'estimated_time' => 'nullable|integer|min:1',
            'lesson_id' => 'nullable|exists:lessons,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

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
            'points' => 'nullable|integer|min:1|max:100',
            'estimated_time' => 'nullable|integer|min:1',
            'lesson_id' => 'nullable|exists:lessons,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

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
