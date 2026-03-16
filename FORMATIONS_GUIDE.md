# 📚 SYSTÈME DE FORMATIONS MODULAIRES – GUIDE COMPLET

## **Table des matières**
1. [Vue d'ensemble](#vue-densemble)
2. [Architecture pédagogique](#architecture-pédagogique)
3. [Structure des données](#structure-des-données)
4. [Implémentation technique](#implémentation-technique)
5. [Flux utilisateur](#flux-utilisateur)
6. [Certification](#certification)
7. [Configuration et déploiement](#configuration-et-déploiement)

---

## **Vue d'ensemble**

### **Objectifs**
- ✅ Créer des formations **indépendantes** de l'abonnement Premium
- ✅ Implémenter un système de **validation pédagogique** robuste (quizzes + projets)
- ✅ Générer des **certificats PDF** personnalisés et vérifiables
- ✅ Offrir une **UX moderne** et encourageante
- ✅ Permettre le **partage social** des accomplissements

### **Caractéristiques principales**

| Fonctionnalité | Description |
|---|---|
| **Modules structurés** | 5 modules avec objectifs clairs |
| **Quizzes interactifs** | MC, V/F, essais avec correction auto |
| **Projet final** | Cahier des charges + évaluation rubrique |
| **Certificat PDF** | Design professionnel + QR code |
| **Verification** | URL avec token unique pour valider |
| **Partage social** | LinkedIn, Twitter, GitHub, Email |

---

## **Architecture pédagogique**

### **Structure modulaire (5 modules par défaut)**

```
Formation (25h)
├── Module 1 (5h)
│   ├── 2-3 leçons vidéo
│   ├── 4 exercices pratiques
│   ├── 3 questions de quiz
│   └── Documentation
├── Module 2 (5h)
├── Module 3 (4h)
├── Module 4 (4h)
└── Module 5 (7h) + Projet Final
```

### **Mécanismes de validation**

#### **Par module**
- **Quiz:** 3 questions (MC, V/F, essay)
- **Score minimum:** 70% de réussite
- **Tentatives:** 3 maximum
- **Durée:** 30 minutes

#### **Par formation**
- **Tous les modules complétés**
- **Score moyen aux quizzes:** ≥ 75%
- **Projet final validé:** ≥ 70/100
- **Résultat:** Déverrouille le certificat

### **Rubriques d'évaluation du projet**

```json
{
  "evaluation_criteria": [
    {
      "criterion": "Complexité et correctness",
      "weight": 30,
      "description": "Code correct, fonctionnel et complexe"
    },
    {
      "criterion": "Respect des patterns",
      "weight": 25,
      "description": "Application des patterns SOLID et clean code"
    },
    {
      "criterion": "Tests et qualité",
      "weight": 25,
      "description": "Couverture des tests ≥ 80%"
    },
    {
      "criterion": "Documentation",
      "weight": 20,
      "description": "README, docstrings, comments clairs"
    }
  ]
}
```

---

## **Structure des données**

### **JSON pour stocker une formation complète**

```json
{
  "id": 1,
  "title": "Maîtrise du Python Avancé",
  "slug": "python-avance",
  "description": "Devenez expert...",
  "level": "avance",
  "price": 149.99,
  "instructor": {
    "name": "John Doe",
    "bio": "10 ans d'expérience",
    "image": "/images/instructors/john-doe.jpg"
  },
  "requirements": [
    "Connaître les bases de Python",
    "Familiarité avec la POO"
  ],
  "learning_outcomes": [
    "Maîtriser la POO multi-niveaux",
    "Écrire du code asynchrone",
    "Appliquer les patterns SOLID"
  ],
  "modules": [
    {
      "id": 1,
      "title": "POO Avancée",
      "order": 1,
      "duration_minutes": 300,
      "learning_objectives": [
        "Comprendre les héritage multiples",
        "Implémenter les métaclasses"
      ],
      "lessons": [
        {
          "id": 1,
          "title": "Classes, héritage...",
          "order": 1,
          "video_duration": 45,
          "content_html": "...",
          "estimated_minutes": 60
        }
      ],
      "exercises": [
        {
          "id": 1,
          "title": "Héritage multiple et MRO",
          "difficulty": "advanced",
          "points": 50,
          "starter_code": "...",
          "solution_code": "..."
        }
      ],
      "quiz": {
        "id": 1,
        "title": "Module 1 - Validation",
        "passing_score": 70,
        "max_attempts": 3,
        "duration_minutes": 30,
        "questions": [
          {
            "id": 1,
            "type": "multiple_choice",
            "text": "Question MC...",
            "points": 25,
            "answers": [
              {
                "text": "Réponse 1",
                "is_correct": false
              },
              {
                "text": "Réponse 2",
                "is_correct": true,
                "explanation": "Explication..."
              }
            ]
          },
          {
            "id": 2,
            "type": "true_false",
            "text": "Les descripteurs...",
            "correct_answer": true
          },
          {
            "id": 3,
            "type": "essay",
            "text": "Expliquez la différence...",
            "expected_length_min": 100,
            "rubric": {
              "completeness": "Explique les 2 concepts",
              "examples": "Des exemples pertinents"
            }
          }
        ]
      }
    }
  ],
  "final_project": {
    "title": "Construire une app OOP",
    "requirements": [
      "Min 5 classes",
      "Tests unitaires ≥ 80%"
    ],
    "max_score": 100,
    "passing_score": 70
  },
  "completion_requirements": {
    "min_quiz_score": 75,
    "require_final_project": true,
    "min_project_score": 70
  }
}
```

---

## **Implémentation technique**

### **1. Installation des dépendances**

```bash
# Certificats PDF
composer require barryvdh/laravel-dompdf

# QR codes
composer require simplesoftwareio/simple-qrcode

# Seeding (optionnel)
composer require pestphp/pest --dev
```

### **2. Exécuter les migrations**

```bash
php artisan migrate

# Ou migrer une table spécifique
php artisan migrate --path=database/migrations/2026_03_06_120000_create_quizzes_and_certificates.php
```

### **3. Exemples de code métier**

#### **Soumettre une réponse de quiz**

```php
// Routes (routes/web.php)
Route::middleware('auth')->group(function () {
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
});

// Controller (app/Http/Controllers/QuizController.php)
class QuizController extends Controller
{
    public function submit(Request $request, Quiz $quiz)
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur peut tenter le quiz
        if (!$user->canRetakeQuiz($quiz)) {
            return back()->withErrors('Nombre max de tentatives atteint.');
        }
        
        // Valider les réponses
        $validated = $request->validate([
            'answers' => 'required|array',
        ]);
        
        // Calculer le score
        $score = $this->calculateQuizScore($quiz, $validated['answers']);
        
        // Créer la soumission
        $attempt = QuizSubmission::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'status' => $score >= $quiz->passing_score ? 'passed' : 'failed',
            'answers' => $validated['answers'],
            'attempt_number' => $quiz->getUserAttempts($user->id) + 1,
            'submitted_at' => now(),
        ]);
        
        // Mettre à jour le progress
        $this->updateModuleProgress($user, $quiz->formation_id);
        
        return redirect()->route('quiz.result', $attempt);
    }
    
    private function calculateQuizScore(Quiz $quiz, array $answers): int
    {
        $totalPoints = 0;
        $earnedPoints = 0;
        
        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            
            $userAnswer = $answers[$question->id] ?? null;
            
            if ($userAnswer && $this->isAnswerCorrect($question, $userAnswer)) {
                $earnedPoints += $question->points;
            }
        }
        
        return (int) (($earnedPoints / $totalPoints) * 100);
    }
}
```

#### **Gérer la progression aux modules**

```php
// app/Services/FormationProgressService.php
class FormationProgressService
{
    /**
     * Met à jour la progression d'un module
     */
    public function updateModuleProgress(User $user, FormationModule $module): void
    {
        $progress = ModuleProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'formation_module_id' => $module->id,
            ],
            [
                'started_at' => now(),
                'last_accessed_at' => now(),
            ]
        );
        
        // Calculer le % de complétion
        $completionPercentage = $this->calculateModuleCompletion($user, $module);
        $progress->update([
            'progress_percentage' => $completionPercentage,
            'status' => $completionPercentage === 100 ? 'completed' : 'in_progress',
            'completed_at' => $completionPercentage === 100 ? now() : null,
        ]);
        
        // Mettre à jour la formation
        $this->updateFormationProgress($user, $module->formation);
    }
    
    /**
     * Calcule le % de complétion d'un module
     */
    private function calculateModuleCompletion(User $user, FormationModule $module): int
    {
        $parts = [];
        
        // Vidéos (40%)
        $videos = $module->lessons()->count();
        $watchedVideos = VideoProgress::where('user_id', $user->id)
            ->where('is_completed', true)
            ->count();
        $videoProgress = $videos > 0 ? ($watchedVideos / $videos) * 40 : 40;
        $parts[] = $videoProgress;
        
        // Exercices (30%)
        $exercises = $module->exercises()->count();
        $completedExercises = ExerciseSubmission::where('user_id', $user->id)
            ->where('status', 'reussi')
            ->count();
        $exerciseProgress = $exercises > 0 ? ($completedExercises / $exercises) * 30 : 30;
        $parts[] = $exerciseProgress;
        
        // Quiz (30%)
        $quiz = Quiz::where('formation_module_id', $module->id)->first();
        $quizProgress = 0;
        if ($quiz) {
            $submission = QuizSubmission::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('status', 'passed')
                ->first();
            $quizProgress = $submission ? 30 : 0;
        } else {
            $quizProgress = 30; // Aucun quiz = 30 points gratuits
        }
        $parts[] = $quizProgress;
        
        return (int) array_sum($parts);
    }
    
    /**
     * Appelle la génération de certificat si formation complète
     */
    private function updateFormationProgress(User $user, Formation $formation): void
    {
        $allModules = $formation->modules;
        $completedModules = ModuleProgress::where('user_id', $user->id)
            ->whereIn('formation_module_id', $allModules->pluck('id'))
            ->where('progress_percentage', 100)
            ->count();
        
        $overallProgress = $allModules->count() > 0 
            ? (int) (($completedModules / $allModules->count()) * 100) 
            : 0;
        
        $progress = FormationProgress::updateOrCreate(
            ['user_id' => $user->id, 'formation_id' => $formation->id],
            [
                'overall_progress' => $overallProgress,
                'status' => $overallProgress === 100 ? 'completed' : 'in_progress',
                'completed_at' => $overallProgress === 100 ? now() : null,
                'last_accessed_at' => now(),
            ]
        );
        
        // 🎉 Si formation terminée, générer le certificat
        if ($overallProgress === 100) {
            (new CertificateService())->generateCertificate($user, $formation);
        }
    }
}
```

---

## **Flux utilisateur**

### **1. Inscription → Formation**
```
Utilisateur (guest)
  ↓
Voit les formations sur /formations
  ↓
Clique sur une formation
  ↓
Proposé : Se connecter ou S'inscrire
  ↓
Inscription complétée
  ↓
Redirection vers page checkout
  ↓
Paiement
  ↓
Accès à la formation
```

### **2. Progression de formation**
```
Formation achetée
  ↓
Accès au module 1
  ↓
Quiz 1 (70% = PASS)
  ↓
Accès module 2
  ...
  ↓
Module 5 + Projet Final soumis
  ↓
Attendre approbation projet
  ↓
Tous les critères ✓
  ↓
🎉 Page "Félicitations"
  ↓
Télécharger certificat PDF
```

---

## **Certification**

### **Code de génération (Laravel + DOMPDF)**

Déjà fourni dans `app/Services/CertificateService.php`

### **Vérification en ligne**

```php
// Routes
Route::get('/cert/verify/{token}', [CertificateController::class, 'verify'])->name('certificates.verify');

// Controller
class CertificateController extends Controller
{
    public function verify(string $token)
    {
        $cert = Certificate::where('verification_token', $token)->firstOrFail();
        
        return view('certificates.verify', [
            'certificate' => $cert,
            'user' => $cert->user,
            'formation' => $cert->formation,
            'isValid' => $cert->isValid(),
        ]);
    }
}
```

---

## **Configuration et déploiement**

### **Variables d'environnement (.env)**

```
CERTIFICATE_EXPIRY_DAYS=0 # 0 = jamais expire
CERTIFICATE_ISSUER_NAME=CodeLearn Academy
CERTIFICATE_ISSUER_EMAIL=admin@codelearn.dev
```

### **Stockage des fichiers**

```php
// config/filesystems.php
'disks' => [
    'certificates' => [
        'driver' => 'local',
        'root' => storage_path('app/certificates'),
        'url' => env('APP_URL') . '/storage/certificates',
        'visibility' => 'private',
    ],
],
```

### **Seeders pour tester**

```php
// database/seeders/FormationSeeder.php
class FormationSeeder extends Seeder
{
    public function run()
    {
        $formation = Formation::create([
            'title' => 'Maîtrise du Python Avancé',
            'slug' => 'python-avance',
            'description' => '...',
            'level' => 'avance',
            'price' => 149.99,
            'is_active' => true,
        ]);
        
        // Créer 5 modules
        for ($i = 1; $i <= 5; $i++) {
            $module = FormationModule::create([
                'formation_id' => $formation->id,
                'title' => "Module {$i}",
                'order' => $i,
                'duration_minutes' => 300,
            ]);
            
            // Quiz pour chaque module
            $quiz = Quiz::create([
                'formation_id' => $formation->id,
                'title' => "Module {$i} - Quiz",
                'duration_minutes' => 30,
                'passing_score' => 70,
                'max_attempts' => 3,
            ]);
        }
    }
}
```

---

## **Checklist de déploiement**

- [ ] Migrations exécutées
- [ ] Dépendances installées (DOMPDF, QrCode)
- [ ] Variables .env configurées
- [ ] Storage symlink: `php artisan storage:link`
- [ ] Routes enregistrées
- [ ] Seeder de formations exécutée
- [ ] Tests d'E2E confirmés
- [ ] Email de notification configuré
- [ ] Sitemap et robots.txt mis à jour

---

**Version:** 1.0  
**Dernière mise à jour:** 6 mars 2026  
**Auteur:** Équipe Pédagogique CodeLearn
