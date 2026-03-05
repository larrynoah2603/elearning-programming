<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\FormationEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormationController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        $this->ensureDefaultCatalog();

=======
>>>>>>> 25ce8ad4c4aed7ebf98e1402eb44ed22fb41545b
        $formations = Formation::query()
            ->withCount('modules')
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();

<<<<<<< HEAD
        return view('formations.index', compact('formations'));
=======
        $purchasedFormationIds = [];

        if (auth()->check()) {
            $purchasedFormationIds = auth()->user()
                ->formationEnrollments()
                ->where('status', 'paid')
                ->pluck('formation_id')
                ->all();
        }

        return view('formations.index', compact('formations', 'purchasedFormationIds'));
>>>>>>> 25ce8ad4c4aed7ebf98e1402eb44ed22fb41545b
    }

    public function show(string $slug)
    {
<<<<<<< HEAD
        $this->ensureDefaultCatalog();

=======
>>>>>>> 25ce8ad4c4aed7ebf98e1402eb44ed22fb41545b
        $formation = Formation::query()
            ->with('modules')
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

<<<<<<< HEAD
        $hasAccess = auth()->check() && auth()->user()->hasPurchasedFormation($formation->id);

        return view('formations.show', compact('formation', 'hasAccess'));
=======
        $enrollment = null;
        $hasAccess = false;

        if (auth()->check()) {
            $enrollment = auth()->user()
                ->formationEnrollments()
                ->where('formation_id', $formation->id)
                ->where('status', 'paid')
                ->latest('paid_at')
                ->first();

            $hasAccess = $enrollment !== null;
        }

        return view('formations.show', compact('formation', 'hasAccess', 'enrollment'));
    }

    public function myFormations()
    {
        $enrollments = auth()->user()
            ->formationEnrollments()
            ->with('formation')
            ->where('status', 'paid')
            ->latest('paid_at')
            ->get();

        return view('formations.my-formations', compact('enrollments'));
>>>>>>> 25ce8ad4c4aed7ebf98e1402eb44ed22fb41545b
    }

    public function checkout(Formation $formation)
    {
        abort_unless($formation->is_active, 404);

        $user = auth()->user();

        if ($user->hasPurchasedFormation($formation->id)) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('info', 'Vous avez déjà acheté cette formation.');
        }

        return view('formations.checkout', compact('formation'));
    }

<<<<<<< HEAD

    private function ensureDefaultCatalog(): void
    {
        if (Formation::query()->exists()) {
            return;
        }

        $formation = Formation::query()->create([
            'title' => 'Formation Laravel Fullstack',
            'slug' => 'formation-laravel-fullstack',
            'description' => 'Un parcours modulaire payant pour construire une application complète avec Laravel, API, sécurité et déploiement.',
            'level' => 'intermediaire',
            'price' => 149.00,
            'is_active' => true,
        ]);

        $formation->modules()->createMany([
            ['title' => 'Architecture & bonnes pratiques', 'description' => 'Structurer un projet pro, conventions, services et tests.', 'duration_minutes' => 90, 'order' => 1],
            ['title' => 'Auth, rôles & permissions', 'description' => 'Gestion des rôles, ACL et sécurisation des endpoints.', 'duration_minutes' => 80, 'order' => 2],
            ['title' => 'API + Frontend intégré', 'description' => 'Construire un module API + interface utilisateur connectée.', 'duration_minutes' => 110, 'order' => 3],
        ]);
    }

=======
>>>>>>> 25ce8ad4c4aed7ebf98e1402eb44ed22fb41545b
    public function purchase(Request $request, Formation $formation)
    {
        abort_unless($formation->is_active, 404);

        $request->validate([
            'payment_method' => ['required', 'in:card,mobile_money,bank_transfer,cryptocurrency'],
        ]);

        $user = $request->user();

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
            ]
        );

        return redirect()
            ->route('formations.show', $formation->slug)
            ->with('success', 'Paiement validé. Vous pouvez maintenant suivre cette formation modulaire.');
    }
<<<<<<< HEAD
=======

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

    private function validateFormation(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'level' => ['required', 'in:debutant,intermediaire,avance'],
            'price' => ['required', 'numeric', 'min:0'],
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
>>>>>>> 25ce8ad4c4aed7ebf98e1402eb44ed22fb41545b
}
