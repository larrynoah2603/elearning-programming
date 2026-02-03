# Structure du Projet CodeLearn

## Vue d'ensemble

CodeLearn est une plateforme e-learning de programmation pour le lycée, développée avec Laravel 11 et Tailwind CSS.

## Architecture

```
elearning-programming/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/              # Contrôleurs d'authentification
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── ConfirmablePasswordController.php
│   │   │   │   ├── EmailVerificationNotificationController.php
│   │   │   │   ├── EmailVerificationPromptController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   ├── PasswordController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── VerifyEmailController.php
│   │   │   ├── AdminController.php      # Gestion admin
│   │   │   ├── CategoryController.php   # Gestion des catégories
│   │   │   ├── Controller.php           # Contrôleur de base
│   │   │   ├── DashboardController.php  # Tableau de bord utilisateur
│   │   │   ├── ExerciseController.php   # Gestion des exercices
│   │   │   ├── HomeController.php       # Page d'accueil
│   │   │   ├── LessonController.php     # Gestion des leçons
│   │   │   ├── SubscriptionController.php # Gestion des abonnements
│   │   │   └── VideoController.php      # Gestion des vidéos
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php      # Vérification rôle admin
│   │       └── SubscribedMiddleware.php # Vérification abonnement
│   ├── Models/
│   │   ├── Category.php           # Modèle Catégorie
│   │   ├── Exercise.php           # Modèle Exercice
│   │   ├── ExerciseSubmission.php # Modèle Soumission d'exercice
│   │   ├── Lesson.php             # Modèle Leçon
│   │   ├── User.php               # Modèle Utilisateur (avec rôles)
│   │   ├── Video.php              # Modèle Vidéo
│   │   └── VideoProgress.php      # Modèle Progression vidéo
│   └── Providers/
│       ├── AppServiceProvider.php
│       ├── AuthServiceProvider.php
│       ├── EventServiceProvider.php
│       └── RouteServiceProvider.php
├── bootstrap/
│   └── app.php                    # Configuration de l'application
├── config/
│   └── app.php                    # Configuration principale
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2024_01_15_000001_create_lessons_table.php
│   │   ├── 2024_01_15_000002_create_exercises_table.php
│   │   ├── 2024_01_15_000003_create_videos_table.php
│   │   ├── 2024_01_15_000004_create_exercise_submissions_table.php
│   │   ├── 2024_01_15_000005_create_video_progress_table.php
│   │   └── 2024_01_15_000006_create_categories_table.php
│   └── seeders/
│       ├── CategorySeeder.php
│       ├── DatabaseSeeder.php
│       ├── ExerciseSeeder.php
│       ├── LessonSeeder.php
│       └── VideoSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css                # Styles Tailwind CSS personnalisés
│   ├── js/
│   │   ├── app.js                 # JavaScript principal (Alpine.js)
│   │   └── bootstrap.js           # Configuration Axios
│   └── views/
│       ├── auth/                  # Vues d'authentification
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── admin/                 # Vues d'administration
│       │   ├── dashboard.blade.php
│       │   ├── lessons/
│       │   │   ├── create.blade.php
│       │   │   └── index.blade.php
│       │   └── users/
│       │       └── index.blade.php
│       ├── components/
│       │   └── flash-messages.blade.php
│       ├── layouts/
│       │   ├── app.blade.php      # Layout principal
│       │   ├── footer.blade.php
│       │   └── navigation.blade.php
│       ├── subscription/
│       │   └── plans.blade.php
│       ├── dashboard.blade.php
│       ├── exercises/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── home.blade.php
│       ├── lessons/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── profile.blade.php
│       └── videos/
│           ├── index.blade.php
│           └── show.blade.php
├── routes/
│   ├── api.php                    # Routes API
│   ├── auth.php                   # Routes d'authentification
│   ├── console.php                # Commandes console
│   └── web.php                    # Routes web principales
├── storage/
│   └── app/
│       └── public/
│           ├── lessons/           # Fichiers PDF des leçons
│           ├── videos/            # Fichiers vidéo
│           └── exercises/         # Fichiers d'exercices
├── artisan                        # Commandes Artisan
├── composer.json                  # Dépendances PHP
├── package.json                   # Dépendances Node.js
├── postcss.config.js              # Configuration PostCSS
├── tailwind.config.js             # Configuration Tailwind CSS
├── vite.config.js                 # Configuration Vite
├── .env.example                   # Exemple de variables d'environnement
├── .gitignore                     # Fichiers à ignorer par Git
├── README.md                      # Documentation du projet
└── STRUCTURE.md                   # Ce fichier

```

## Fonctionnalités par Module

### 1. Authentification
- Inscription avec vérification d'email
- Connexion/ Déconnexion
- Réinitialisation de mot de passe
- Rôles : Admin, Gratuit, Abonné

### 2. Espace Gratuit
- Exercices de programmation simples
- Leçons PDF sélectionnées
- Suivi de progression basique
- Certificats de réussite

### 3. Espace Premium
- Tous les exercices (simples et complexes)
- Toutes les leçons PDF
- Vidéos de formation
- Support prioritaire
- Sessions de mentorat (annuel)

### 4. Administration
- Gestion des utilisateurs (CRUD + rôles)
- Gestion des leçons (CRUD + upload PDF)
- Gestion des exercices (CRUD)
- Gestion des vidéos (CRUD + upload)
- Correction des soumissions
- Statistiques détaillées

## Base de Données

### Tables principales
- **users** : Utilisateurs avec rôles et dates d'abonnement
- **lessons** : Leçons PDF avec niveau et accès
- **exercises** : Exercices avec difficulté et langage
- **videos** : Vidéos avec durée et progression
- **categories** : Catégories de contenu
- **exercise_submissions** : Soumissions des élèves
- **video_progress** : Progression des vidéos

## Routes Principales

### Publiques
- `/` : Page d'accueil
- `/lessons` : Liste des leçons
- `/exercises` : Liste des exercices
- `/videos` : Liste des vidéos (aperçu)
- `/subscription/plans` : Offres d'abonnement

### Authentifiées
- `/dashboard` : Tableau de bord
- `/profile` : Profil utilisateur
- `/exercises/{slug}` : Détail exercice + soumission
- `/videos/{slug}` : Lecture vidéo

### Admin (`/admin/*`)
- `/admin` : Dashboard admin
- `/admin/users` : Gestion utilisateurs
- `/admin/lessons` : Gestion leçons
- `/admin/exercises` : Gestion exercices
- `/admin/videos` : Gestion vidéos
- `/admin/submissions` : Correction des soumissions

## Sécurité

- Middleware `auth` pour les routes protégées
- Middleware `admin` pour l'administration
- Middleware `subscribed` pour le contenu premium
- Validation des formulaires
- Protection CSRF
- Stockage sécurisé des fichiers

## Installation Rapide

```bash
# 1. Installer les dépendances
composer install
npm install

# 2. Configuration
cp .env.example .env
php artisan key:generate

# 3. Base de données
php artisan migrate --seed

# 4. Stockage
php artisan storage:link

# 5. Compilation
npm run build

# 6. Lancer
php artisan serve
```

## Comptes de Démo

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@codelearn.fr | password |
| Gratuit | free@codelearn.fr | password |
| Premium | premium@codelearn.fr | password |
