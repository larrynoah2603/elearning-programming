<?php

namespace App\Console\Commands;

use App\Models\FormationProgress;
use App\Models\ModuleProgress;
use App\Models\QuizSubmission;
use App\Models\User;
use Illuminate\Console\Command;

class FormationResetProgressCommand extends Command
{
    protected $signature = 'formation:reset-progress {userId} {formationId}';

    protected $description = 'Réinitialiser la progression d\'un utilisateur pour une formation';

    public function handle(): int
    {
        $user = User::findOrFail($this->argument('userId'));
        $formation = Formation::findOrFail($this->argument('formationId'));

        // Supprimer la progression des modules
        ModuleProgress::where('user_id', $user->id)
            ->whereIn('formation_module_id', $formation->modules->pluck('id'))
            ->delete();

        // Supprimer la progression de formation
        FormationProgress::where('user_id', $user->id)
            ->where('formation_id', $formation->id)
            ->delete();

        // Supprimer les soumissions de quiz
        QuizSubmission::where('user_id', $user->id)
            ->whereHas('quiz', function($query) use ($formation) {
                $query->where('formation_id', $formation->id);
            })
            ->delete();

        $this->info("Progression réinitialisée pour {$user->name} sur '{$formation->title}'");

        return self::SUCCESS;
    }
}