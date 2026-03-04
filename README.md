# CodeLearn - Plateforme E-Learning de Programmation

Une plateforme e-learning complète pour l'apprentissage de la programmation au lycée, développée avec Laravel et Tailwind CSS.

## Fonctionnalités

### Espace Gratuit
- Exercices de programmation simples
- Leçons PDF téléchargeables (sélection)
- Suivi de progression
- Certificats de réussite

### Espace Premium (Abonnement)
- Tous les exercices simples et complexes
- Toutes les leçons PDF
- Vidéos de formation exclusives
- Support prioritaire
- Sessions de mentorat (abonnement annuel)

### Espace Enseignant (Nouveau)
- Création de groupes/classes (prof ↔ élèves)
- Assignation de devoirs (exercices/leçons) avec échéance
- Tableau de suivi : taux de complétion, élèves en retard, exercices bloquants
- Export CSV des résultats de classe

### Espace Administrateur
- Gestion des utilisateurs (rôles, abonnements)
- Gestion des leçons (CRUD + upload PDF)
- Gestion des exercices (CRUD)
- Gestion des vidéos (CRUD + upload)
- Correction des soumissions
- Statistiques détaillées


## Activer l'Espace Enseignant (après `git pull`)

Après un `git pull`, le code est présent mais l'interface n'apparaît que si :

1. les migrations ont été exécutées ;
2. le cache Laravel est vidé ;
3. votre utilisateur a le rôle `teacher`.

Commandes :

```bash
composer install
php artisan migrate
php artisan optimize:clear
php artisan serve
```

Puis attribuez le rôle enseignant (exemple SQL) :

```sql
UPDATE users SET role = 'teacher' WHERE email = 'votre-email@example.com';
```

Accès enseignant :
- URL directe : `/enseignant/dashboard`
- Un lien "Enseignant" apparaît aussi dans la navigation pour les comptes `teacher` et `admin`.


## Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js et NPM
- MySQL ou MariaDB
- Serveur web (Apache/Nginx)

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/votre-repo/codelearn.git
cd codelearn
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JavaScript

```bash
npm install
```

### 4. Configuration de l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Modifiez le fichier `.env` avec vos informations de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=codelearn
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 5. Créer la base de données

```bash
mysql -u root -p -e "CREATE DATABASE codelearn;"
```

### 6. Exécuter les migrations et les seeders

```bash
php artisan migrate --seed
```

### 7. Créer les liens symboliques pour le stockage

```bash
php artisan storage:link
```

### 8. Compiler les assets

```bash
npm run build
```

### 9. Démarrer le serveur

```bash
php artisan serve
```

Accédez à l'application : http://localhost:8000


## Dépannage (Windows / PowerShell)

### Avertissement `Module "openssl" is already loaded`

Si PowerShell affiche cet avertissement au lancement de `php artisan serve`, cela vient en général d'un double chargement de l'extension OpenSSL dans `php.ini`.

1. Vérifiez le fichier chargé :
```bash
php --ini
```
2. Ouvrez le `php.ini` listé et gardez une seule ligne OpenSSL (exemple) :
```ini
extension=openssl
```
3. Supprimez ou commentez les doublons éventuels (dans `php.ini`, `conf.d`, ou versions PHP multiples de WAMP).
4. Redémarrez PowerShell / WAMP puis relancez :
```bash
php artisan serve
```

### Les vidéos ne se lisent pas

Exécutez d'abord le lien de stockage puis videz le cache :

```bash
php artisan storage:link
php artisan optimize:clear
```

Ensuite vérifiez qu'un fichier vidéo existe dans `storage/app/public/videos` et que l'URL de stream `/video-stream/{id}` répond bien.

Cause fréquente : les chemins seedés (ex: `videos/introduction-python-1.mp4`) ne correspondent pas aux vrais fichiers du dossier `storage/app/public/videos`.
Dans ce cas, mettez à jour le champ `video_file` en base avec le nom réel du fichier.

### Mot de passe oublié (Gmail SMTP)

Le projet supporte maintenant l'envoi d'email de réinitialisation via Gmail SMTP.

1. Activez la validation en 2 étapes sur le compte Google.
2. Générez un **mot de passe d'application**.
3. Renseignez `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-adresse@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-adresse@gmail.com
MAIL_FROM_NAME="CodeLearn"
```

4. Videz le cache de configuration :

```bash
php artisan config:clear
```


## Configuration de la correction automatique IA (Gemini)

Ajoutez les variables suivantes dans `.env` :

```env
GEMINI_API_KEY=votre_cle_google_ai_studio
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta
GEMINI_MODEL=gemini-2.5-flash
# Optionnel: "models/gemini-2.5-flash" est aussi accepté
GEMINI_TIMEOUT=25
QUEUE_CONNECTION=database
```

Puis exécutez :

```bash
php artisan migrate
php artisan queue:work
```

### Dépannage rapide de la correction IA

- Vérifier la migration `add_ai_fields_to_exercise_submissions_table` (sinon erreurs SQL sur `ai_requires_human_review`).
- Vérifier la clé API Gemini (erreurs 401/403).
- En cas d'erreur 429 : la limite de requêtes/quota Gemini est atteinte, attendre puis réessayer ou augmenter le quota.
- Vérifier que le worker de queue tourne (sinon les soumissions restent en statut `soumis`).
- Vérifier les logs Laravel : `storage/logs/laravel.log`.
- Aucun plugin/extension Laravel spécifique n'est requis pour Gemini : une clé API valide suffit (Laravel HTTP client + OpenSSL/cURL PHP standards).

## Comptes de démonstration

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@codelearn.fr | password |
| Utilisateur Gratuit | free@codelearn.fr | password |
| Utilisateur Premium | premium@codelearn.fr | password |

## Structure du projet

```
codelearn/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/           # Contrôleurs d'authentification
│   │   │   ├── AdminController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ExerciseController.php
│   │   │   ├── HomeController.php
│   │   │   ├── LessonController.php
│   │   │   ├── SubscriptionController.php
│   │   │   └── VideoController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── SubscribedMiddleware.php
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Exercise.php
│   │   ├── ExerciseSubmission.php
│   │   ├── Lesson.php
│   │   ├── User.php
│   │   ├── Video.php
│   │   └── VideoProgress.php
│   └── Providers/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   │   └── app.css             # Styles Tailwind CSS
│   ├── js/
│   │   └── app.js              # JavaScript avec Alpine.js
│   └── views/
│       ├── auth/               # Vues d'authentification
│       ├── admin/              # Vues d'administration
│       ├── components/         # Composants Blade
│       ├── layouts/            # Layouts
│       └── ...                 # Autres vues
├── routes/
│   ├── web.php                 # Routes web
│   └── api.php                 # Routes API
└── storage/
    └── app/
        └── public/
            ├── lessons/        # Fichiers PDF des leçons
            ├── videos/         # Fichiers vidéo
            └── exercises/      # Fichiers d'exercices
```

## Commandes utiles

```bash
# Exécuter les migrations
php artisan migrate

# Exécuter les seeders
php artisan db:seed

# Créer un administrateur
php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin', 'email' => 'admin@codelearn.fr', 'password' => bcrypt('password'), 'role' => 'admin']);

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Compiler les assets en mode développement
npm run dev

# Compiler les assets pour la production
npm run build
```

## Fonctionnalités détaillées

### Gestion des utilisateurs
- Inscription/Connexion avec vérification d'email
- Rôles : Admin, Gratuit, Abonné
- Gestion des abonnements avec dates d'expiration
- Historique des soumissions et progression

### Gestion du contenu
- **Leçons** : Upload de PDF, catégorisation par niveau
- **Exercices** : Code starter, solution, indices, points
- **Vidéos** : Upload de fichiers MP4/WebM, miniatures, suivi de progression

### Système de soumission
- Soumission de code par les élèves
- Correction par les administrateurs
- Attribution de scores et feedback
- Suivi des tentatives

### API
- Endpoints pour la recherche
- Mise à jour de la progression vidéo
- Soumission d'exercices

## Sécurité

- Protection CSRF sur tous les formulaires
- Middleware d'authentification et d'autorisation
- Validation des requêtes
- Stockage sécurisé des fichiers
- Hashage des mots de passe avec Bcrypt

## Personnalisation

### Thèmes
Les couleurs principales sont définies dans `tailwind.config.js` :
- Primary : Bleu (primary-500: #3b82f6)
- Secondary : Violet (secondary-500: #8b5cf6)
- Success : Vert
- Warning : Jaune/Orange
- Danger : Rouge

### Logo
Remplacez le logo dans `resources/views/layouts/navigation.blade.php` et `resources/views/layouts/footer.blade.php`.

## Déploiement

### Configuration production

1. Définir `APP_ENV=production` dans `.env`
2. Définir `APP_DEBUG=false`
3. Configurer un serveur web (Apache/Nginx)
4. Configurer HTTPS
5. Configurer un service de mail (SMTP)
6. Configurer les tâches cron pour Laravel Scheduler

### Nginx configuration exemple

```nginx
server {
    listen 80;
    server_name codelearn.fr;
    root /var/www/codelearn/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
- Signaler des bugs
- Proposer des fonctionnalités
- Soumettre des pull requests

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.

## Support

Pour toute question ou problème :
- Email : support@codelearn.fr
- Documentation : https://docs.codelearn.fr
- Forum : https://forum.codelearn.fr

---

Développé avec ❤️ pour les étudiants en programmation.


## Simulation locale: passage compte gratuit -> premium

1. Connectez-vous avec un compte utilisateur standard.
2. Allez sur `Abonnement` puis choisissez un plan.
3. Choisissez **Mobile Money** et validez le formulaire.
4. En environnement local (`APP_DEBUG=true`), si l'API Orange SMS n'est pas configurée, un code de secours s'affiche sur la page d'attente.
5. Saisissez le code dans le formulaire **Valider votre paiement** pour activer le compte premium.

### Variables Orange SMS (optionnel en local)

```env
ORANGE_SMS_BASE_URL=https://api.orange.com
ORANGE_SMS_CLIENT_ID=...
ORANGE_SMS_CLIENT_SECRET=...
ORANGE_SMS_SENDER=CODELEARN
```

Sans ces variables, le flux reste testable en local via le code de secours (affiché uniquement en mode debug).
