<?php

namespace App\Services;

use App\Models\FormationModule;
use App\Models\FormationUserProgress;
use App\Models\User;

class FormationProgressService
{
    /**
     * Mettre à jour la progression d'un module
     */
    public function updateModuleProgress(User $user, FormationModule $module, int $percentage): FormationUserProgress
    {
        return FormationUserProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'formation_module_id' => $module->id,
            ],
            [
                'formation_id' => $module->formation_id,
                'progress_percentage' => min($percentage, 100),
                'is_completed' => $percentage >= 100,
                'completed_at' => $percentage >= 100 ? now() : null,
            ]
        );
    }

    /**
     * Marquer un module comme complété
     */
    public function completeModule(User $user, FormationModule $module): FormationUserProgress
    {
        return $this->updateModuleProgress($user, $module, 100);
    }

    /**
     * Calculer la progression globale d'une formation
     */
    public function calculateFormationProgress($userId, $formationId): array
    {
        $totalModules = FormationModule::where('formation_id', $formationId)->count();

        if ($totalModules === 0) {
            return [
                'percentage' => 0,
                'completed' => 0,
                'total' => 0,
            ];
        }

        $completedModules = FormationUserProgress::where('user_id', $userId)
            ->where('formation_id', $formationId)
            ->where('is_completed', true)
            ->count();

        return [
            'percentage' => round(($completedModules / $totalModules) * 100),
            'completed' => $completedModules,
            'total' => $totalModules,
        ];
    }

    /**
     * Obtenir les statistiques par module
     */
    public function getModuleStats($userId, $formationId)
    {
        return FormationUserProgress::where('user_id', $userId)
            ->where('formation_id', $formationId)
            ->with('formationModule')
            ->orderBy('progress_percentage', 'desc')
            ->get();
    }
}
