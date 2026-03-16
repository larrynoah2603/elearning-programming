<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Quizzes
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->unsignedSmallInteger('passing_score')->default(70); // 0-100
            $table->unsignedSmallInteger('max_attempts')->default(3);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Questions de quiz
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'essay'])->default('multiple_choice');
            $table->unsignedSmallInteger('points')->default(25);
            $table->unsignedInteger('order')->default(0);
            $table->text('explanation')->nullable();
            $table->json('metadata')->nullable(); // Pour données supplémentaires
            $table->timestamps();
        });

        // Réponses aux questions (pour MC et TF)
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('cascade');
            $table->text('answer_text');
            $table->boolean('is_correct')->default(false);
            $table->text('explanation')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Soumissions de quiz par utilisateur
        Schema::create('quiz_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->unsignedSmallInteger('score')->default(0); // 0-100
            $table->enum('status', ['pending', 'passed', 'failed'])->default('pending');
            $table->json('answers')->nullable(); // Stockage des réponses
            $table->unsignedSmallInteger('attempt_number')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->text('grader_feedback')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'quiz_id', 'attempt_number']);
        });

        // Progress au niveau du module
        Schema::create('module_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('formation_module_id')->constrained('formation_modules')->onDelete('cascade');
            $table->unsignedSmallInteger('progress_percentage')->default(0); // 0-100
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->json('metadata')->nullable(); // Données de tracking détaillées
            $table->timestamps();

            $table->unique(['user_id', 'formation_module_id']);
        });

        // Progress au niveau de la formation
        Schema::create('formation_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('formation_id')->constrained('formations')->onDelete('cascade');
            $table->unsignedSmallInteger('overall_progress')->default(0); // 0-100
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->json('module_statuses')->nullable(); // Status de chaque module
            $table->timestamps();

            $table->unique(['user_id', 'formation_id']);
        });

        // Projets finaux
        Schema::create('final_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('requirements'); // JSON ou text
            $table->json('evaluation_criteria')->nullable();
            $table->unsignedSmallInteger('max_score')->default(100);
            $table->unsignedSmallInteger('passing_score')->default(70);
            $table->timestamps();
        });

        // Soumissions de projets finaux
        Schema::create('project_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('final_project_id')->constrained('final_projects')->onDelete('cascade');
            $table->text('submission_text')->nullable();
            $table->string('repository_url')->nullable(); // GitHub, GitLab, etc.
            $table->string('demo_url')->nullable(); // URL de démo live
            $table->json('files')->nullable(); // Métadonnées des fichiers uploadés
            $table->unsignedSmallInteger('score')->default(0);
            $table->enum('status', ['submitted', 'under_review', 'accepted', 'rejected'])->default('submitted');
            $table->text('feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Certificats générés
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('formation_id')->constrained('formations')->onDelete('cascade');
            $table->string('certificate_number')->unique(); // Numéro unique
            $table->string('file_path'); // Chemin du PDF stocké
            $table->timestamp('issued_at');
            $table->timestamp('expires_at')->nullable(); // Certificats optionnellement expirables
            $table->json('metadata')->nullable(); // QR code, signature, etc.
            $table->string('verification_token')->unique(); // Pour vérification en ligne
            $table->timestamps();

            $table->unique(['user_id', 'formation_id']);
        });

        // Templates de certificats (design customisable)
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained('formations')->onDelete('cascade');
            $table->string('template_name');
            $table->text('template_file'); // Chemin du template PDF/image
            $table->string('background_image')->nullable();
            $table->string('signature_image')->nullable();
            $table->string('issuer_name')->default('CodeLearn');
            $table->string('issuer_logo')->nullable();
            $table->json('design_config')->nullable(); // Positions, couleurs, fonts
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('project_submissions');
        Schema::dropIfExists('final_projects');
        Schema::dropIfExists('formation_progress');
        Schema::dropIfExists('module_progress');
        Schema::dropIfExists('quiz_submissions');
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
