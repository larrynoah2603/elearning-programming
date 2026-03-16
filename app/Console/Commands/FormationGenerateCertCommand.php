<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Models\Formation;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Console\Command;

class FormationGenerateCertCommand extends Command
{
    protected $signature = 'formation:generate-cert {userId} {formationId}';

    protected $description = 'Générer manuellement un certificat pour un utilisateur';

    public function handle(): int
    {
        $user = User::findOrFail($this->argument('userId'));
        $formation = Formation::findOrFail($this->argument('formationId'));

        $certificateService = app(CertificateService::class);

        try {
            $certificate = $certificateService->generateCertificate($user, $formation);
            $this->info("Certificat généré avec succès:");
            $this->info("- Numéro: {$certificate->certificate_number}");
            $this->info("- Fichier: {$certificate->file_path}");
            $this->info("- URL vérification: /certs/verify/{$certificate->verification_token}");
        } catch (\Exception $e) {
            $this->error("Erreur lors de la génération: {$e->getMessage()}");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}