<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\Formation;
use App\Models\FormationProgress;
use App\Models\ModuleProgress;
use App\Models\QuizSubmission;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Console\Command;

class FormationCompleteUserCommand extends Command
{
    protected $signature = 'formation:complete-user {userId} {formationId}';

    protected $description = 'Marquer une formation comme complétée pour un utilisateur (dev/test)';

    public function handle(): int
    {
        $user = User::findOrFail($this->argument('userId'));
        $formation = Formation::findOrFail($this->argument('formationId'));

        // Marquer tous les modules comme complétés
        foreach ($formation->modules as $module) {
            ModuleProgress::updateOrCreate(
                ['user_id' => $user->id, 'formation_module_id' => $module->id],
                [
                    'progress_percentage' => 100,
                    'status' => 'completed',
                    'completed_at' => now(),
                ]
            );

            // Simuler un passage de quiz avec 80%
            $quiz = $module->quizzes()->first();
            if ($quiz) {
                QuizSubmission::updateOrCreate(
                    ['user_id' => $user->id, 'quiz_id' => $quiz->id],
                    [
                        'score' => 80,
                        'status' => 'passed',
                        'answers' => json_encode(['simulated' => true]),
                        'attempt_number' => 1,
                    ]
                );
            }
        }

        // Mettre à jour la progression de formation
        FormationProgress::updateOrCreate(
            ['user_id' => $user->id, 'formation_id' => $formation->id],
            [
                'overall_progress' => 100,
                'module_statuses' => json_encode([]),
            ]
        );

        // Générer le certificat
        $certificateService = app(CertificateService::class);
        try {
            $certificate = $certificateService->generateCertificate($user, $formation);
            $this->info("Certificat généré: {$certificate->file_path}");
        } catch (\Exception $e) {
            $this->error("Erreur génération certificat: {$e->getMessage()}");
        }

        $this->info("Formation '{$formation->title}' marquée comme complétée pour {$user->name}");

        return self::SUCCESS;
    }
}