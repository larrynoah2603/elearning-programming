<?php

namespace App\Http\Controllers;

use App\Services\LearningPlanGeneratorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(Request $request): View
    {
        $profile = $request->user()->learningProfile;

        return view('onboarding.show', compact('profile'));
    }

    public function store(Request $request, LearningPlanGeneratorService $generator): RedirectResponse
    {
        $validated = $request->validate([
            'level' => 'required|in:beginner,intermediate,advanced',
            'goal' => 'required|string|max:255',
            'minutes_per_day' => 'required|integer|min:15|max:180',
            'preferred_languages' => 'nullable|array',
            'preferred_languages.*' => 'string|max:50',
        ]);

        $user = $request->user();

        $user->learningProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'level' => $validated['level'],
                'goal' => $validated['goal'],
                'minutes_per_day' => $validated['minutes_per_day'],
                'preferred_languages' => $validated['preferred_languages'] ?? [],
                'onboarding_completed_at' => now(),
            ]
        );

        $generator->generateForUser($user);

        return redirect()->route('dashboard')->with('success', 'Profil enregistré. Votre plan personnalisé est prêt.');
    }
}
