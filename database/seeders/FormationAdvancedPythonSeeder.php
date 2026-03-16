<?php

namespace Database\Seeders;

use App\Models\Formation;
use App\Models\FormationModule;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\FinalProject;
use Illuminate\Database\Seeder;

/**
 * Seeder pour initialiser une formation complète avec tous les éléments
 * 
 * Utilisation: php artisan db:seed --class=FormationAdvancedPythonSeeder
 */
class FormationAdvancedPythonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer la formation
        $formation = Formation::create([
            'title' => 'Maîtrise du Python Avancé',
            'slug' => 'maitrise-python-avance',
            'description' => 'Devenez expert Python avec les patterns avancés, l\'asynchrone et l\'architecture. '
                . 'Cette formation de 25 heures couvre 5 modules progressifs + un projet final pour valoriser vos compétences.',
            'level' => 'avance',
            'price' => 149.99,
            'is_active' => true,
        ]);

        // Module 1: POO Avancée
        $this->createModule($formation, 1, 'Programmation Orientée Objet Avancée');
        
        // Module 2: Async & Concurrence
        $this->createModule($formation, 2, 'Programmation Asynchrone & Concurrence');
        
        // Module 3: Décorateurs
        $this->createModule($formation, 3, 'Décorateurs & Context Managers');
        
        // Module 4: Optimisation
        $this->createModule($formation, 4, 'Performances & Optimisation');
        
        // Module 5: Design Patterns
        $module5 = $this->createModule($formation, 5, 'Design Patterns & Architecture');

        // Créer le projet final associé au module 5
        FinalProject::create([
            'formation_id' => $formation->id,
            'title' => 'Construire une application OOP complète',
            'description' => 'Créez une application Python complète qui démontre votre expertise dans les patterns avancés, '
                . 'la POO, l\'asynchrone et les bonnes pratiques clean code.',
            'requirements' => json_encode([
                'Minimum 5 classes avec relations complexes',
                'Utilisation de décorateurs et context managers',
                'Tests unitaires couvrant au moins 80% du code',
                'Documentation complète (README, docstrings)',
                'Code conforme à PEP 8 et isort',
                'Type hints complets',
                'Gestion d\'erreurs appropriée',
            ]),
            'evaluation_criteria' => json_encode([
                [
                    'criterion' => 'Complexité et correcteness',
                    'weight' => 30,
                    'description' => 'Code correct, fonctionnel et complexe',
                ],
                [
                    'criterion' => 'Respect des patterns',
                    'weight' => 25,
                    'description' => 'Application des patterns SOLID et clean code',
                ],
                [
                    'criterion' => 'Tests et qualité',
                    'weight' => 25,
                    'description' => 'Tests unitaires et couverture adéquate',
                ],
                [
                    'criterion' => 'Documentation',
                    'weight' => 20,
                    'description' => 'Documentation claires et complètes',
                ],
            ]),
            'max_score' => 100,
            'passing_score' => 70,
        ]);

        \Log::info('✅ Formation "Maîtrise du Python Avancé" créée avec succès!');
        \Log::info('   - 5 modules');
        \Log::info('   - 5 quizzes');
        \Log::info('   - Projet final');
    }

    /**
     * Crée un module avec quiz associé
     */
    private function createModule(Formation $formation, int $moduleNumber, string $title): FormationModule
    {
        $module = FormationModule::create([
            'formation_id' => $formation->id,
            'title' => $title,
            'description' => "Module {$moduleNumber}: {$title}",
            'duration_minutes' => match($moduleNumber) {
                1, 2 => 300,  // 5h
                3, 4 => 240,  // 4h
                5 => 420,     // 7h
            },
            'order' => $moduleNumber,
        ]);

        // Créer le quiz pour ce module
        $quiz = Quiz::create([
            'formation_id' => $formation->id,
            'title' => "Module {$moduleNumber}: Validation des connaissances",
            'description' => "Quiz de validation pour le module {$moduleNumber}",
            'duration_minutes' => 30,
            'passing_score' => 70,
            'max_attempts' => 3,
            'order' => $moduleNumber,
            'is_active' => true,
        ]);

        // Créer les 3 questions du quiz
        $this->createQuizQuestions($quiz);

        return $module;
    }

    /**
     * Crée les questions du quiz
     */
    private function createQuizQuestions(Quiz $quiz): void
    {
        // Question 1: Multiple Choice
        $q1 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Quel concept permet de contrôler l\'accès aux attributs en POO Python?',
            'question_type' => 'multiple_choice',
            'points' => 25,
            'order' => 1,
            'explanation' => 'Les descripteurs implémentent le protocole descriptor avec __get__, __set__, __delete__',
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $q1->id,
            'answer_text' => 'Properties uniquement',
            'is_correct' => false,
            'order' => 1,
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $q1->id,
            'answer_text' => 'Descripteurs (descriptor protocol)',
            'is_correct' => true,
            'explanation' => 'Les descripteurs sont plus puissants que les properties',
            'order' => 2,
        ]);

        QuizAnswer::create([
            'quiz_question_id' => $q1->id,
            'answer_text' => '__getattr__ uniquement',
            'is_correct' => false,
            'order' => 3,
        ]);

        // Question 2: True/False
        $q2 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Un context manager (with statement) doit implémenter les méthodes __enter__ et __exit__',
            'question_type' => 'true_false',
            'points' => 25,
            'order' => 2,
            'explanation' => 'Oui, le protocole des context managers requiert ces deux méthodes spéciales',
        ]);

        // Question 3: Essay
        $q3 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Expliquez la différence fondamentale entre composition et héritage. '
                . 'Donnez un cas d\'usage pour chacun.',
            'question_type' => 'essay',
            'points' => 50,
            'order' => 3,
            'explanation' => 'L\'héritage modélise une relation "est-un" (IS-A), '
                . 'la composition modélise une relation "a-un" (HAS-A). '
                . 'Utilisez composition quand possible (préféré), héritage pour les vrai hiérarchies.',
            'metadata' => json_encode([
                'expected_length_min' => 150,
                'rubric' => [
                    'Explique héritage correctement',
                    'Explique composition correctement',
                    'Donne un cas d\'usage pour héritage',
                    'Donne un cas d\'usage pour composition',
                    'Exemple de code si possible',
                ],
            ]),
        ]);
    }
}
