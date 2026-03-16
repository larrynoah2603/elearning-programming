<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Formation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

/**
 * Service de génération de certificats PDF
 * Utilise DOMPDF pour la génération et SimpleSoftwareIO/qrcode pour les QR codes
 */
class CertificateService
{
    /**
     * Génère un certificat PDF pour un utilisateur ayant complété une formation
     */
    public function generateCertificate(User $user, Formation $formation): Certificate
    {
        // Vérifier que l'utilisateur a complété la formation
        if (!$this->isFormationCompleted($user, $formation)) {
            throw new \Exception("L'utilisateur n'a pas complété cette formation.");
        }

        // Générer les identifiants uniques
        $certificateNumber = Certificate::generateCertificateNumber();
        $verificationToken = Certificate::generateVerificationToken();

        // Préparer les données
        $certificateData = [
            'user_name' => $user->name,
            'formation_title' => $formation->title,
            'formation_level' => $formation->level_display,
            'certificate_number' => $certificateNumber,
            'issued_date' => Carbon::now()->format('d F Y'),
            'issued_date_raw' => Carbon::now(),
            'verification_url' => route('certificates.verify', $verificationToken, false),
            'verification_token' => $verificationToken,
            'completion_percentage' => $this->getFormationCompletionPercentage($user, $formation),
        ];

        // Générer le QR code
        $qrCode = $this->generateQrCode($verificationToken);

        // Générer le PDF
        $pdf = Pdf::loadView('certificates.template', array_merge($certificateData, [
            'qr_code_base64' => $qrCode,
        ]))
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0);

        // Sauvegarder le fichier
        $filename = "certificate_{$user->id}_{$formation->id}_{$certificateNumber}.pdf";
        $filepath = "certificates/{$filename}";
        
        if (!file_exists(storage_path('app/certificates'))) {
            mkdir(storage_path('app/certificates'), 0755, true);
        }

        \Storage::disk('local')->put($filepath, $pdf->output());

        // Créer l'enregistrement en base de données
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'formation_id' => $formation->id,
            'certificate_number' => $certificateNumber,
            'file_path' => $filepath,
            'issued_at' => now(),
            'verification_token' => $verificationToken,
            'metadata' => [
                'completion_percentage' => $certificateData['completion_percentage'],
                'formation_level' => $formation->level,
                'qr_code' => $verificationToken,
            ],
        ]);

        return $certificate;
    }

    /**
     * Vérifie si une formation est complétée
     */
    private function isFormationCompleted(User $user, Formation $formation): bool
    {
        // Vérifier l'inscription payante
        if (!$user->hasPurchasedFormation($formation->id)) {
            return false;
        }

        // Récupérer les exigences de complétion
        $requirements = $formation->completion_requirements ?? [
            'min_quiz_score' => 75,
            'min_exercises_completion' => 80,
            'require_final_project' => true,
            'min_project_score' => 70,
        ];

        // Vérifier les quizzes
        if ($requirements['min_quiz_score'] ?? false) {
            $quizzes = \App\Models\Quiz::where('formation_id', $formation->id)->get();
            foreach ($quizzes as $quiz) {
                $bestScore = \App\Models\QuizSubmission::where('user_id', $user->id)
                    ->where('quiz_id', $quiz->id)
                    ->where('status', 'passed')
                    ->max('score');

                if (!$bestScore || $bestScore < $requirements['min_quiz_score']) {
                    return false;
                }
            }
        }

        // Vérifier le projet final si requis
        if ($requirements['require_final_project'] ?? false) {
            $projectSubmission = \App\Models\ProjectSubmission::where('user_id', $user->id)
                ->whereHas('project', fn ($q) => $q->where('formation_id', $formation->id))
                ->where('status', 'accepted')
                ->where('score', '>=', $requirements['min_project_score'] ?? 70)
                ->first();

            if (!$projectSubmission) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calcule le pourcentage de complétion d'une formation
     */
    private function getFormationCompletionPercentage(User $user, Formation $formation): int
    {
        $modules = $formation->modules;
        
        if ($modules->isEmpty()) {
            return 100;
        }

        $totalProgress = 0;
        foreach ($modules as $module) {
            $progress = \App\Models\ModuleProgress::where('user_id', $user->id)
                ->where('formation_module_id', $module->id)
                ->first();

            $totalProgress += $progress->progress_percentage ?? 0;
        }

        return (int) ($totalProgress / $modules->count());
    }

    /**
     * Génère un QR code pour la vérification du certificat
     */
    private function generateQrCode(string $verificationToken): string
    {
        $url = route('certificates.verify', $verificationToken, false);
        
        $qrCode = QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->generate($url);

        return 'data:image/png;base64,' . base64_encode($qrCode);
    }

    /**
     * Récupère un certificat d'après son token
     */
    public function verifyCertificate(string $token): ?Certificate
    {
        return Certificate::where('verification_token', $token)->first();
    }

    /**
     * Récupère ou génère un certificat pour un utilisateur
     */
    public function getOrCreateCertificate(User $user, Formation $formation): Certificate
    {
        $certificate = Certificate::where('user_id', $user->id)
            ->where('formation_id', $formation->id)
            ->first();

        if (!$certificate) {
            $certificate = $this->generateCertificate($user, $formation);
        }

        return $certificate;
    }
}
