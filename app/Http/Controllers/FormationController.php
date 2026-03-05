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
        $this->ensureDefaultCatalog();

        $formations = Formation::query()
            ->withCount('modules')
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('formations.index', compact('formations'));
    }

    public function show(string $slug)
    {
        $this->ensureDefaultCatalog();

        $formation = Formation::query()
            ->with('modules')
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $hasAccess = auth()->check() && auth()->user()->hasPurchasedFormation($formation->id);

        return view('formations.show', compact('formation', 'hasAccess'));
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
}
