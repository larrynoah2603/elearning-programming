<?php

namespace App\Console\Commands;

use App\Models\Formation;
use App\Models\FormationEnrollment;
use App\Models\QuizSubmission;
use Illuminate\Console\Command;

class FormationStatsCommand extends Command
{
    protected $signature = 'formation:stats';

    protected $description = 'Afficher les statistiques des formations';

    public function handle(): int
    {
        $formations = Formation::with(['modules', 'quizzes'])->get();

        $this->info("📊 STATISTIQUES DES FORMATIONS");
        $this->info(str_repeat("=", 80));

        foreach ($formations as $formation) {
            $enrollments = FormationEnrollment::where('formation_id', $formation->id)
                ->where('status', 'paid')
                ->count();

            $completions = FormationEnrollment::where('formation_id', $formation->id)
                ->where('status', 'paid')
                ->whereHas('user.certificates', function($query) use ($formation) {
                    $query->where('formation_id', $formation->id);
                })
                ->count();

            $completionRate = $enrollments > 0 ? round(($completions / $enrollments) * 100, 1) : 0;

            $this->line("");
            $this->info("📚 {$formation->title} ({$formation->level_display})");
            $this->info("   Prix: {$formation->price}€ | Modules: {$formation->modules->count()} | Inscriptions: {$enrollments} | Complétions: {$completions} ({$completionRate}%)");

            // Statistiques par module
            foreach ($formation->modules as $index => $module) {
                $moduleQuizzes = $module->quizzes ?? collect([$module->quiz])->filter();
                $avgScore = 0;
                $totalSubmissions = 0;

                foreach ($moduleQuizzes as $quiz) {
                    $submissions = QuizSubmission::where('quiz_id', $quiz->id)->get();
                    if ($submissions->count() > 0) {
                        $avgScore += $submissions->avg('score');
                        $totalSubmissions += $submissions->count();
                    }
                }

                $avgScore = $moduleQuizzes->count() > 0 ? round($avgScore / $moduleQuizzes->count(), 1) : 0;

                $this->info("   ├─ Module " . ($index + 1) . ": {$module->title} - {$avgScore}% en moyenne");
            }

            $certificatesCount = \App\Models\Certificate::where('formation_id', $formation->id)->count();
            $this->info("   └─ 🎓 Certificats délivrés: {$certificatesCount}");
        }

        $this->line("");
        $this->info("Total formations: {$formations->count()}");

        return self::SUCCESS;
    }
}