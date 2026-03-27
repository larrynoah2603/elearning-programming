# Roadmap Autodidacte — Découpage technique Laravel + tickets Trello/Jira

## Objectif
Mettre en place, selon la logique métier de CodeLearn, les 5 axes suivants :

1. Onboarding + plan personnalisé
2. Feedback post-exercice + indices
3. Progression fiable + coach dashboard
4. Forum orienté résolution rapide
5. Optimisations mobile/connexion

---

## 1) Onboarding + plan personnalisé

### Règles métier
- Le nouvel utilisateur passe un mini diagnostic (niveau, objectif, disponibilité hebdomadaire).
- Le système génère un plan 7 jours (actions courtes et réalistes).
- L'utilisateur peut ignorer l'onboarding et le reprendre plus tard.

### Tables / migrations
- `user_learning_profiles`
  - `id`, `user_id` (unique), `level` (`beginner|intermediate|advanced`)
  - `goal` (string), `minutes_per_day` (int), `preferred_languages` (json nullable)
  - `onboarding_completed_at` (timestamp nullable), timestamps
- `learning_plans`
  - `id`, `user_id`, `starts_at` (date), `ends_at` (date), `status` (`active|completed|paused`)
  - timestamps
- `learning_plan_items`
  - `id`, `learning_plan_id`, `type` (`lesson|exercise|quiz|formation_module`)
  - `target_id` (bigint), `estimated_minutes` (int), `position` (int)
  - `is_done` (bool default false), `done_at` (timestamp nullable), timestamps

### Models
- `App\Models\UserLearningProfile`
- `App\Models\LearningPlan`
- `App\Models\LearningPlanItem`

### Controllers / Services
- `App\Http\Controllers\OnboardingController`
  - `show()`, `store()`
- `App\Http\Controllers\LearningPlanController`
  - `showCurrentPlan()`, `completeItem($item)`
- `App\Services\LearningPlanGeneratorService`
  - `generateForUser(User $user): LearningPlan`

### Routes (web.php)
```php
Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding.show');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    Route::get('/dashboard/plan', [LearningPlanController::class, 'showCurrentPlan'])->name('dashboard.plan');
    Route::patch('/dashboard/plan/items/{item}/done', [LearningPlanController::class, 'completeItem'])->name('dashboard.plan.items.done');
});
```

### Vues Blade
- `resources/views/onboarding/show.blade.php`
- `resources/views/dashboard/partials/current-plan.blade.php`

---

## 2) Feedback post-exercice + indices

### Règles métier
- Après soumission, montrer un feedback structuré: points forts, erreurs, prochaine action.
- Ajouter des indices progressifs (niveau 1 → 2 → 3).
- Tracer l'utilisation des indices pour analytics et éventuelle modulation de points.

### Tables / migrations
- `exercise_hints`
  - `id`, `exercise_id`, `level` (tinyint 1..3), `content` (text), timestamps
- `exercise_hint_views`
  - `id`, `user_id`, `exercise_id`, `hint_level` (tinyint), `viewed_at` (timestamp), timestamps
- Extension `exercise_submissions`
  - `feedback_structured` (json nullable)
  - `hint_penalty` (int default 0)

### Models
- `App\Models\ExerciseHint`
- `App\Models\ExerciseHintView`

### Controllers / Services
- `App\Http\Controllers\ExerciseHintController`
  - `show(Exercise $exercise, int $level)`
- `App\Services\SubmissionFeedbackFormatterService`
  - `format(array $aiCorrection, ?int $unitTestScore): array`

### Routes
```php
Route::middleware('auth')->group(function () {
    Route::get('/exercises/{exercise}/hints/{level}', [ExerciseHintController::class, 'show'])
        ->whereNumber('level')
        ->name('exercises.hints.show');
});
```

### Vues Blade
- `resources/views/exercises/partials/feedback-structured.blade.php`
- `resources/views/exercises/partials/hints-panel.blade.php`

---

## 3) Progression fiable + coach dashboard

### Règles métier
- La progression formation doit être calculée sur le nombre total réel de modules de la formation.
- Afficher un "coach": prochaine action courte, action de relance après inactivité, objectif hebdo.

### Ajustements techniques
- Refactor `FormationProgressService::calculateFormationProgress()` pour:
  1) charger `totalModules` depuis `formation_modules`
  2) compter `completedModules` depuis `formation_user_progress`
  3) calculer `%` fiable même si l'utilisateur n'a jamais ouvert certains modules
- Créer `App\Services\DashboardCoachService`
  - `getNextBestActions(User $user): array`
  - `getRecoveryAction(User $user): ?array`

### Controller
- Étendre `DashboardController@index()` pour injecter:
  - `coachActions`
  - `weeklyGoalStatus`

### Vues Blade
- `resources/views/dashboard/partials/coach-next-actions.blade.php`
- `resources/views/dashboard/partials/weekly-goals.blade.php`

---

## 4) Forum orienté résolution rapide

### Règles métier
- Les sujets doivent être catégorisables par tags (langage, thème, difficulté).
- Une réponse peut être marquée comme solution validée.
- Filtrer facilement les sujets non résolus.

### Tables / migrations
- `forum_tags`
  - `id`, `name`, `slug`, timestamps
- `forum_thread_tag`
  - `id`, `forum_thread_id`, `forum_tag_id`
- Extension `forum_replies`
  - `is_accepted` (bool default false)

### Models
- `App\Models\ForumTag`
- Relations à ajouter dans `ForumThread` et `ForumReply`

### Controllers
- Étendre `ForumController`
  - `index(Request $request)` avec filtres `tag`, `status=resolved|unresolved`
  - `markAcceptedReply(ForumThread $thread, ForumReply $reply)`

### Routes
```php
Route::middleware('auth')->group(function () {
    Route::patch('/forum/threads/{thread}/replies/{reply}/accept', [ForumController::class, 'markAcceptedReply'])
        ->name('forum.replies.accept');
});
```

### Vues Blade
- `resources/views/forum/partials/thread-filters.blade.php`
- `resources/views/forum/partials/reply-item.blade.php`

---

## 5) Optimisations mobile/connexion

### Règles métier
- Rendre l'expérience fluide en réseau limité.
- Prioriser contenu essentiel et navigation simple.

### Découpage technique
- Front (Blade/Tailwind)
  - Réduire surcharge visuelle sur mobile (hero, cartes, tableaux)
  - Ajouter fallback UI quand média indisponible
- Performance
  - Lazy-load images/video thumbnails
  - Pagination courte et progressive
- Réseau
  - Stratégie cache pour assets statiques (Vite + headers)
  - Message clair d'état réseau pour vidéos

### Fichiers cibles probables
- `resources/views/home.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/videos/show.blade.php`
- `resources/js/app.js`

---

## Tickets prêts Trello/Jira (S/M/L)

> Convention estimation:
> - **S**: 0.5–1.5 j
> - **M**: 2–3 j
> - **L**: 4–5 j

### Epic A — Autonomie d'apprentissage

1. **[M] ONB-01 — Créer onboarding diagnostique**
   - Formulaire niveau/objectif/temps
   - Sauvegarde profil
   - DoD: onboarding visible au 1er login

2. **[M] PLAN-01 — Générer plan personnalisé 7 jours**
   - Service `LearningPlanGeneratorService`
   - Items triés par difficulté + disponibilité
   - DoD: plan actif créé automatiquement

3. **[S] PLAN-02 — Marquer une action de plan terminée**
   - Endpoint PATCH + bouton dashboard
   - DoD: état persistant + feedback visuel

### Epic B — Feedback et remédiation

4. **[M] FB-01 — Structurer feedback post-soumission**
   - JSON `feedback_structured`
   - UI dédiée
   - DoD: 3 sections standard visibles

5. **[M] HINT-01 — Implémenter indices progressifs 1/2/3**
   - Tables hints + tracking
   - Contrôle d'accès séquentiel
   - DoD: impossible d'ouvrir indice 3 avant 1/2

6. **[S] HINT-02 — Ajouter analytics usage indices**
   - Tableau de bord admin simple
   - DoD: nombre de vues par indice/exercice

### Epic C — Progression et coaching

7. **[S] PROG-01 — Corriger calcul progression formation**
   - Refactor service + tests
   - DoD: % exact sur jeux de données de test

8. **[M] COACH-01 — Ajouter bloc “Que faire maintenant ?”**
   - Service + composant dashboard
   - DoD: 1 action courte et contextualisée affichée

9. **[S] COACH-02 — Relance après inactivité**
   - Détection `last_activity`
   - DoD: message/action de reprise visible

### Epic D — Forum orienté résolution

10. **[M] FORUM-01 — Ajouter tags et filtres**
    - CRUD minimal tags + filtres index
    - DoD: filtrage par tag fonctionnel

11. **[S] FORUM-02 — Marquer une réponse acceptée**
    - Flag `is_accepted`
    - DoD: 1 seule réponse acceptée par thread

12. **[S] FORUM-03 — Filtre sujets non résolus**
    - Paramètre `status=unresolved`
    - DoD: listing exact et paginé

### Epic E — Mobile/performance

13. **[M] MOB-01 — Refonte mobile dashboard/home (priorité lecture)**
    - Ajustements layout + densité
    - DoD: UX lisible < 390px

14. **[S] PERF-01 — Lazy-load médias + optimisation vignettes**
    - Attributs lazy + placeholders
    - DoD: baisse du poids initial perçu

15. **[S] PERF-02 — États réseau vidéo explicites**
    - UI “connexion faible / reprise”
    - DoD: message visible sur timeout/erreur

---

## Ordre d'implémentation recommandé
1. ONB-01, PLAN-01, PLAN-02
2. PROG-01, COACH-01, COACH-02
3. FB-01, HINT-01, HINT-02
4. FORUM-01, FORUM-02, FORUM-03
5. MOB-01, PERF-01, PERF-02

---

## Risques et garde-fous
- **Risque**: surcharge de complexité trop tôt.
  - **Mitigation**: livrer d'abord MVP de chaque epic (UI simple, logique robuste).
- **Risque**: feedback IA variable.
  - **Mitigation**: formater systématiquement la sortie AI en structure standard.
- **Risque**: régression performance mobile.
  - **Mitigation**: vérifier Lighthouse mobile avant/après.
