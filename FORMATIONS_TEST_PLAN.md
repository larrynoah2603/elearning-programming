# Plan de test - Système de Formations Modulaires

## Étapes de test du flux complet

### 1️⃣ Préparation de la base de données

```bash
php artisan migrate
```

Vérifier que les tables ont été créées :
- formations
- formation_modules
- formation_enrollments
- formation_module_lesson
- formation_module_video
- formation_module_exercise
- formation_user_progress

### 2️⃣ Créer des données de test

```php
// Dans tinker ou seeder
$formation = Formation::create([
    'title' => 'PHP Débutant',
    'description' => 'Apprenez les bases de PHP',
    'level' => 'debutant',
    'price' => 49.99,
    'is_active' => true
]);

// Ajouter des modules
$module1 = $formation->modules()->create([
    'title' => 'Introduction à PHP',
    'description' => 'Les concepts de base',
    'duration_minutes' => 120,
    'order' => 1
]);

$module2 = $formation->modules()->create([
    'title' => 'Variables et types',
    'description' => 'Gestion des variables',
    'duration_minutes' => 90,
    'order' => 2
]);
```

### 3️⃣ Associer du contenu aux modules

```php
// Ajouter une leçon au module
$lesson = Lesson::first();
$module1->lessons()->attach($lesson->id, ['order' => 1]);

// Ajouter une vidéo
$video = Video::first();
$module1->videos()->attach($video->id, ['order' => 1]);

// Ajouter un exercice
$exercise = Exercise::first();
$module1->exercises()->attach($exercise->id, ['order' => 1]);
```

### 4️⃣ Tester le flux utilisateur

#### A. Page d'accueil des formations
```
URL: http://localhost:8000/formations
Vérifier:
- ✅ List des formations
- ✅ Affichage du prix
- ✅ Bouton "Consulter"
- ✅ Badge "Acheter"
```

#### B. Détails d'une formation (non achetée)
```
URL: http://localhost:8000/formations/php-debutant
Vérifier:
- ✅ Titre et description
- ✅ Prix affiché
- ✅ Modules listés
- ✅ Bouton "Passer à la caisse"
- ✅ Message d'accès restreint
```

#### C. Checkout et paiement
```
URL: http://localhost:8000/formations/1/checkout
Vérifier:
- ✅ Formulaire de paiement
- ✅ Sélection du mode de paiement
- ✅ Récapitulatif du prix
```

Soumettre le formulaire avec payment_method=card

#### D. Page de validation
```
URL: http://localhost:8000/formations/1/validation
Vérifier:
- ✅ Message de succès
- ✅ Référence de paiement affichée
- ✅ Bouton "Commencer la formation"
- ✅ Bouton "Mes formations"
```

#### E. Vue d'ensemble formation (achetée)
```
URL: http://localhost:8000/formations/php-debutant
Vérifier:
- ✅ Badge "Vous faites partie"
- ✅ Boutons "Commencer le module" visibles
- ✅ Ressources complémentaires
```

#### F. Page de module
```
URL: http://localhost:8000/formations/1/modules/1
Vérifier:
- ✅ Breadcrumb de navigation
- ✅ Sections: Leçons, Vidéos, Exercices
- ✅ Barre de progression
- ✅ Sidebar avec navigation
```

#### G. Mes formations
```
URL: http://localhost:8000/mes-formations
Vérifier:
- ✅ Formation achetée affichée en carte
- ✅ Stats: nombre de modules, quizzes
- ✅ Barre de progression (0% au départ)
- ✅ Bouton "Continuer la formation"
```

### 5️⃣ Testes de sécurité

```php
// Test 1: Accès non authentifié au module
GET /formations/1/modules/1
Expected: Redirect to login ou message "Acheter d'abord"

// Test 2: Accès avec formation non achetée
$user2 = User::find(2); // Autre utilisateur
auth()->login($user2);
GET /formations/1/modules/1
Expected: Erreur ou lien to checkout

// Test 3: Accès formation inactive
$formation->update(['is_active' => false]);
GET /formations/php-debutant
Expected: 404
```

### 6️⃣ Tests de contenu

```php
// Insérer du contenu dans les modules
$lessons = Lesson::take(3)->get();
$videos = Video::take(2)->get();
$exercises = Exercise::take(3)->get();

foreach ($lessons as $i => $lesson) {
    $module1->lessons()->attach($lesson->id, ['order' => $i + 1]);
}

foreach ($videos as $i => $video) {
    $module1->videos()->attach($video->id, ['order' => $i + 1]);
}

foreach ($exercises as $i => $exercise) {
    $module1->exercises()->attach($exercise->id, ['order' => $i + 1]);
}

// Vérifier affichage
GET /formations/1/modules/1
Expected: Leçons, vidéos, exercices affichés
```

## Checklist de test complète

- [ ] Migration OK
- [ ] Formation créée avec modules
- [ ] Contenu associé aux modules
- [ ] Page formations.index OK
- [ ] Page formations.show OK (avant achat)
- [ ] Accès checkout restreint
- [ ] Paiement enregistré
- [ ] Page validation OK
- [ ] formations.show OK (après achat)
- [ ] Page formations.module OK
- [ ] Contenu affiché correctement
- [ ] Barre de progression visible
- [ ] Page mes-formations OK
- [ ] Navigation breadcrumb OK
- [ ] Sécurité: Accès non authentifié bloqué
- [ ] Sécurité: Accès formation non achetée bloqué

## Problèmes potentiels et solutions

| Problème | Solution |
|----------|----------|
| Boutons modules introuvables | Vérifier que le slugs est correct dans la route |
| Contenu non affiché | Vérifier les relations dans les modèles |
| Erreur 404 sur modules | Vérifier que le module_id existe dans la formation |
| Progression non affichée | Vérifier table formation_user_progress |
