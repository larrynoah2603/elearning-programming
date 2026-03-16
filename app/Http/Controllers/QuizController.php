<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizSubmission;
use App\Models\Formation;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Afficher un quiz avec ses questions
     */
    public function show(Quiz $quiz)
    {
        // Vérifier que l'utilisateur a accès à cette formation
        $formation = $quiz->formation;
        $enrollment = $this->resolvePaidEnrollment($formation->id);

        if (!$enrollment) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('error', 'Vous devez acheter cette formation pour accéder aux quizzes.');
        }

        // Vérifier si l'utilisateur a déjà passé ce quiz
        $existingSubmission = QuizSubmission::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->latest()
            ->first();

        // Si le quiz est passé et réussi, rediriger vers les résultats
        if ($existingSubmission && $existingSubmission->isPassed()) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('success', 'Vous avez déjà réussi ce quiz !');
        }

        // Vérifier le nombre de tentatives
        $attemptCount = QuizSubmission::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return redirect()->route('formations.show', $formation->slug)
                ->with('error', 'Vous avez épuisé toutes vos tentatives pour ce quiz.');
        }

        // Charger les questions avec leurs réponses
        $quiz->load(['questions.answers' => function($query) {
            $query->orderBy('order');
        }]);

        return view('formations.quiz.show', compact('quiz', 'formation', 'attemptCount'));
    }

    /**
     * Traiter la soumission d'un quiz
     */
    public function submit(Request $request, Quiz $quiz)
    {
        // Vérifier que l'utilisateur a accès à cette formation
        $formation = $quiz->formation;
        $enrollment = $this->resolvePaidEnrollment($formation->id);

        if (!$enrollment) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        // Vérifier le nombre de tentatives
        $attemptCount = QuizSubmission::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return response()->json(['error' => 'Nombre maximum de tentatives atteint'], 403);
        }

        // Validation des données
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculer le score
            $score = $this->calculateScore($quiz, $validated['answers']);

            // Créer la soumission
            $submission = QuizSubmission::create([
                'user_id' => auth()->id(),
                'quiz_id' => $quiz->id,
                'score' => $score,
                'status' => $score >= $quiz->passing_score ? 'passed' : 'failed',
                'answers' => json_encode($validated['answers']),
                'attempt_number' => $attemptCount + 1,
            ]);

            // Mettre à jour la progression du module
            $this->updateModuleProgress($formation, $quiz, $submission);

            DB::commit();

            // Vérifier si la formation est maintenant complète
            $isFormationComplete = $this->certificateService->isFormationCompleted(auth()->user(), $formation);

            if ($isFormationComplete) {
                // Générer le certificat automatiquement
                try {
                    $certificate = $this->certificateService->generateCertificate(auth()->user(), $formation);
                    Log::info("Certificat généré automatiquement pour l'utilisateur " . auth()->user()->id . " - Formation: " . $formation->id);
                } catch (\Exception $e) {
                    Log::error("Erreur lors de la génération automatique du certificat: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'score' => $score,
                'passed' => $submission->isPassed(),
                'formation_complete' => $isFormationComplete,
                'redirect_url' => $isFormationComplete
                    ? route('formations.completion', $formation->slug)
                    : route('formations.show', $formation->slug)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la soumission du quiz: " . $e->getMessage());

            return response()->json([
                'error' => 'Une erreur est survenue lors de la soumission du quiz.'
            ], 500);
        }
    }

    /**
     * Calculer le score d'un quiz basé sur les réponses
     */
    private function calculateScore(Quiz $quiz, array $userAnswers): int
    {
        $totalScore = 0;
        $maxScore = $quiz->questions->sum('points');

        foreach ($quiz->questions as $question) {
            $questionKey = 'question_' . $question->id;
            $userAnswer = $userAnswers[$questionKey] ?? null;

            if (!$userAnswer) continue;

            switch ($question->type) {
                case 'multiple_choice':
                case 'true_false':
                    // Vérifier si la réponse est correcte
                    $correctAnswer = $question->answers->where('is_correct', true)->first();
                    if ($correctAnswer && $correctAnswer->id == $userAnswer) {
                        $totalScore += $question->points;
                    }
                    break;

                case 'essay':
                    // Pour les essays, on donne le score maximum par défaut
                    // (devrait être corrigé manuellement plus tard)
                    $totalScore += $question->points;
                    break;
            }
        }

        // Convertir en pourcentage
        return $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;
    }

    /**
     * Mettre à jour la progression du module
     */
    private function updateModuleProgress(Formation $formation, Quiz $quiz, QuizSubmission $submission)
    {
        // Trouver le module associé à ce quiz
        $module = $formation->modules->first(function($module) use ($quiz) {
            return $module->id === $quiz->formation_module_id;
        });

        if (!$module) return;

        // Calculer la progression du module
        $moduleQuizzes = $module->quizzes ?? collect([$quiz]);
        $passedQuizzes = $moduleQuizzes->filter(function($q) {
            return QuizSubmission::where('user_id', auth()->id())
                ->where('quiz_id', $q->id)
                ->where('status', 'passed')
                ->exists();
        });

        $moduleProgress = round(($passedQuizzes->count() / $moduleQuizzes->count()) * 100);

        // Mettre à jour ou créer la progression du module
        DB::table('module_progress')->updateOrInsert(
            [
                'user_id' => auth()->id(),
                'formation_module_id' => $module->id,
            ],
            [
                'progress_percentage' => $moduleProgress,
                'status' => $moduleProgress >= 100 ? 'completed' : 'in_progress',
                'completed_at' => $moduleProgress >= 100 ? now() : null,
                'updated_at' => now(),
            ]
        );

        // Mettre à jour la progression de la formation
        $this->updateFormationProgress($formation);
    }

    /**
     * Mettre à jour la progression globale de la formation
     */
    private function updateFormationProgress(Formation $formation)
    {
        $totalModules = $formation->modules->count();
        $completedModules = DB::table('module_progress')
            ->where('user_id', auth()->id())
            ->whereIn('formation_module_id', $formation->modules->pluck('id'))
            ->where('status', 'completed')
            ->count();

        $formationProgress = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;

        // Mettre à jour ou créer la progression de formation
        DB::table('formation_progress')->updateOrInsert(
            [
                'user_id' => auth()->id(),
                'formation_id' => $formation->id,
            ],
            [
                'overall_progress' => $formationProgress,
                'module_statuses' => json_encode($this->getModuleStatuses($formation)),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Obtenir le statut de chaque module
     */
    private function getModuleStatuses(Formation $formation): array
    {
        $statuses = [];

        foreach ($formation->modules as $module) {
            $progress = DB::table('module_progress')
                ->where('user_id', auth()->id())
                ->where('formation_module_id', $module->id)
                ->first();

            $statuses[$module->id] = [
                'status' => $progress ? $progress->status : 'not_started',
                'progress' => $progress ? $progress->progress_percentage : 0,
            ];
        }

        return $statuses;
    }

    /**
     * Résoudre l'inscription payée (méthode utilitaire)
     */
    private function resolvePaidEnrollment(int $formationId)
    {
        if (!auth()->check()) {
            return null;
        }

        return auth()->user()
            ->formationEnrollments()
            ->where('formation_id', $formationId)
            ->where('status', 'paid')
            ->first();
    }
}