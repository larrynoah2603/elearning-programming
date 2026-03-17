<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\FormationEnrollment;
use App\Services\FormationProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::query()
            ->withCount('modules')
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();

        $purchasedFormationIds = [];

        if (auth()->check()) {
            $purchasedFormationIds = auth()->user()
                ->formationEnrollments()
                ->where('status', 'paid')
                ->pluck('formation_id')
                ->all();
        }

        return view('formations.index', compact('formations', 'purchasedFormationIds'));
    }

    public function show(string $slug)
    {
        $formation = Formation::query()
            ->with(['modules', 'quizzes.questions'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $enrollment = $this->resolvePaidEnrollment($formation->id);
        $hasAccess = $enrollment !== null;

        return view('formations.show', compact('formation', 'hasAccess', 'enrollment'));
    }

    public function myFormations()
    {
        return $this->mesFormations();
    }


    public function mesFormations()
    {
        $enrollments = auth()->user()
            ->formationEnrollments()
            ->where('status', 'paid')
            ->whereHas('formation')
            ->with(['formation' => function ($query) {
                $query->withCount(['modules', 'quizzes']);
            }])
            ->latest('paid_at')
            ->get();

        return view('formations.my-formations', compact('enrollments'));
    }

    public function checkout(Formation $formation)
    {
        abort_unless($formation->is_active, 404);

        $user = auth()->user();

        if ($user->hasPurchasedFormation($formation->id)) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('info', 'Vous avez déjà acheté cette formation.');
        }

        $accountType = match (true) {
            $user->isAdmin() => 'Administrateur',
            $user->isSubscribed() => 'Premium',
            default => 'Gratuit',
        };

        return view('formations.checkout', compact('formation', 'user', 'accountType'));
    }

    public function purchase(Request $request, Formation $formation)
    {
        abort_unless($formation->is_active, 404);

        $request->validate([
            'payment_method' => ['required', 'in:card,mobile_money,bank_transfer,cryptocurrency'],
            'billing_email' => ['required', 'email'],
            'accept_terms' => ['accepted'],
        ]);

        $user = $request->user();

        if (strtolower($request->input('billing_email')) !== strtolower($user->email)) {
            return back()
                ->withInput()
                ->withErrors(['billing_email' => 'L\'email de facturation doit correspondre à votre compte utilisateur.']);
        }

        FormationEnrollment::updateOrCreate(
            [
                'user_id' => $user->id,
                'formation_id' => $formation->id,
            ],
            [
                'amount_paid' => $formation->price,
                'status' => 'paid',
                'payment_method' => $request->input('payment_method'),
                'payment_reference' => 'FRM-' . strtoupper(Str::random(10)),
                'paid_at' => now(),
                'access_expires_at' => now()->addDays($formation->validity_days),
            ]
        );

        return redirect()
            ->route('formations.subscription', ['formation' => $formation->id])
            ->with('success', 'Paiement validé. Vous pouvez maintenant suivre cette formation modulaire.');
    }


    public function subscription(Formation $formation)
    {
        $enrollment = auth()->user()
            ->formationEnrollments()
            ->where('formation_id', $formation->id)
            ->where('status', 'paid')
            ->latest('paid_at')
            ->first();

        if (!$enrollment) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('error', 'Vous devez acheter cette formation pour y accéder.');
        }

        if (!$enrollment->access_expires_at && $enrollment->paid_at) {
            $enrollment->access_expires_at = $enrollment->paid_at->copy()->addDays($formation->validity_days);
            $enrollment->save();
        }

        $deadline = $enrollment->access_expires_at;
        $remainingDays = $deadline ? max(0, now()->startOfDay()->diffInDays($deadline->copy()->startOfDay(), false)) : null;

        return view('formations.subscription', compact('formation', 'enrollment', 'deadline', 'remainingDays'));
    }

    public function validation(Formation $formation)
    {
        $enrollment = auth()->user()
            ->formationEnrollments()
            ->where('formation_id', $formation->id)
            ->where('status', 'paid')
            ->first();

        if (!$enrollment) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('error', 'Vous n\'avez pas accès à cette page.');
        }

        return view('formations.validation', compact('formation', 'enrollment'));
    }

    /**
     * Page d'accès direct à une formation achetée (indépendante du statut Premium)
     */
    public function access(Formation $formation)
    {
        $enrollment = auth()->user()
            ->formationEnrollments()
            ->where('formation_id', $formation->id)
            ->where('status', 'paid')
            ->first();

        if (!$enrollment) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('error', 'Vous devez acheter cette formation pour y accéder.');
        }

        if ($enrollment->access_expires_at && now()->greaterThan($enrollment->access_expires_at)) {
            return redirect()->route('formations.subscription', $formation)
                ->with('error', 'La période de validité de cette formation est expirée.');
        }

        return view('formations.access', compact('formation', 'enrollment'));
    }

    public function showModule(Formation $formation, $moduleId)
    {
        // Vérifier que l'utilisateur a accès à la formation
        $enrollment = $this->resolvePaidEnrollment($formation->id);
        if (!$enrollment) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('error', 'Vous devez acheter cette formation pour y accéder.');
        }

        // Récupérer le module
        $module = $formation->modules()
            ->with(['lessons', 'videos', 'exercises'])
            ->findOrFail($moduleId);

        $user = auth()->user();
        $progress = $module->getUserProgress($user->id);

        return view('formations.module', compact('formation', 'module', 'progress'));
    }

    public function updateModuleProgress(Request $request, Formation $formation, $moduleId)
    {
        // Vérifier que l'utilisateur a accès à la formation
        $enrollment = $this->resolvePaidEnrollment($formation->id);
        if (!$enrollment) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'percentage' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $module = $formation->modules()->findOrFail($moduleId);
        $user = auth()->user();

        $progressService = app(FormationProgressService::class);
        $progress = $progressService->updateModuleProgress(
            $user,
            $module,
            $request->input('percentage')
        );

        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }

    public function adminIndex()
    {
        $formations = Formation::query()
            ->withCount(['modules', 'enrollments'])
            ->latest()
            ->paginate(20);

        return view('admin.formations.index', compact('formations'));
    }

    public function create()
    {
        return view('admin.formations.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateFormation($request);

        $formation = Formation::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'level' => $validated['level'],
            'price' => $validated['price'],
            'validity_days' => $validated['validity_days'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->syncModules($formation, $validated['modules']);

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation créée avec succès.');
    }

    public function edit(Formation $formation)
    {
        $formation->load('modules');

        return view('admin.formations.edit', compact('formation'));
    }

    public function update(Request $request, Formation $formation)
    {
        $validated = $this->validateFormation($request);

        $formation->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'],
            'level' => $validated['level'],
            'price' => $validated['price'],
            'validity_days' => $validated['validity_days'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $formation->modules()->delete();
        $this->syncModules($formation, $validated['modules']);

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation mise à jour avec succès.');
    }

    public function destroy(Formation $formation)
    {
        $formation->delete();

        return redirect()->route('admin.formations.index')
            ->with('success', 'Formation supprimée avec succès.');
    }

    public function completion(Formation $formation)
    {
        // Vérifier que l'utilisateur a accès à cette formation
        $enrollment = $this->resolvePaidEnrollment($formation->id);

        if (!$enrollment) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('error', 'Vous devez acheter cette formation pour accéder à cette page.');
        }

        // Charger les données nécessaires pour la page de félicitations
        $user = auth()->user();
        $formation->load(['modules', 'quizzes.questions']);

        // Statistiques de l'utilisateur
        $quizSubmissions = $user->quizSubmissions()
            ->whereHas('quiz', function($query) use ($formation) {
                $query->where('formation_id', $formation->id);
            })
            ->with('quiz')
            ->get();

        $averageQuizScore = $quizSubmissions->avg('score') ?? 0;
        $totalQuizzes = $formation->quizzes->count();
        $passedQuizzes = $quizSubmissions->where('status', 'passed')->count();

        // Exercices complétés (si applicable)
        $exerciseCompletions = 0; // TODO: Implémenter la logique d'exercices

        // Vérifier si le projet final est soumis et approuvé
        $finalProject = $formation->finalProjects()->first();
        $projectSubmission = null;
        $projectApproved = false;

        if ($finalProject) {
            $projectSubmission = $user->projectSubmissions()
                ->where('final_project_id', $finalProject->id)
                ->latest()
                ->first();

            $projectApproved = $projectSubmission && $projectSubmission->status === 'approved';
        }

        // Calculer la progression globale
        $overallProgress = 0; // TODO: Utiliser FormationProgressService

        // Générer l'URL de partage
        $shareUrl = route('formations.show', $formation->slug);
        $shareText = "J'ai terminé la formation \"{$formation->title}\" sur CodeLearn Academy ! 🎓";

        return view('formations.completion', compact(
            'formation',
            'averageQuizScore',
            'totalQuizzes',
            'passedQuizzes',
            'exerciseCompletions',
            'projectSubmission',
            'projectApproved',
            'overallProgress',
            'shareUrl',
            'shareText'
        ));
    }


    private function resolvePaidEnrollment(int $formationId): ?FormationEnrollment
    {
        if (! auth()->check()) {
            return null;
        }

        return auth()->user()
            ->formationEnrollments()
            ->where('formation_id', $formationId)
            ->where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('access_expires_at')
                    ->orWhere('access_expires_at', '>=', now());
            })
            ->latest('paid_at')
            ->first();
    }

    private function validateFormation(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'level' => ['required', 'in:debutant,intermediaire,avance'],
            'price' => ['required', 'numeric', 'min:0'],
            'validity_days' => ['required', 'integer', 'min:1', 'max:3650'],
            'is_active' => ['nullable', 'boolean'],
            'modules' => ['required', 'array', 'min:1'],
            'modules.*.title' => ['required', 'string', 'max:255'],
            'modules.*.description' => ['nullable', 'string'],
            'modules.*.duration_minutes' => ['required', 'integer', 'min:1'],
        ]);
    }

    private function syncModules(Formation $formation, array $modules): void
    {
        foreach (array_values($modules) as $index => $module) {
            $formation->modules()->create([
                'title' => $module['title'],
                'description' => $module['description'] ?? null,
                'duration_minutes' => $module['duration_minutes'],
                'order' => $index + 1,
            ]);
        }
    }
}
