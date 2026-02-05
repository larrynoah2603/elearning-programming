<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/plans', [HomeController::class, 'plans'])->name('plans');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Subscription Routes
Route::get('/subscription/plans', [SubscriptionController::class, 'plans'])->name('subscription.plans');
Route::get('/subscription/expired', [SubscriptionController::class, 'expired'])->name('subscription.expired');

// Public Content Routes (Free content only for non-subscribed users)
Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/{slug}', [LessonController::class, 'show'])->name('lessons.show');
Route::get('/lessons/{lesson}/preview', [LessonController::class, 'previewPdf'])->name('lessons.preview');
Route::get('/lessons/{lesson}/download', [LessonController::class, 'download'])->name('lessons.download');

Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
Route::get('/exercises/{slug}', [ExerciseController::class, 'show'])->name('exercises.show');

Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/video-stream/{video}', [VideoController::class, 'stream'])->name('videos.stream');
Route::get('/videos/{slug}', [VideoController::class, 'show'])->name('videos.show');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'changePassword'])->name('profile.password');
    
    // Exercise Submissions
    Route::post('/exercises/{exercise}/submit', [ExerciseController::class, 'submit'])->name('exercises.submit');
    Route::post('/exercises/{exercise}/progress', [ExerciseController::class, 'saveProgress'])->name('exercises.progress');
    
    // Video Progress
    Route::post('/videos/{video}/progress', [VideoController::class, 'updateProgress'])->name('videos.progress');
    Route::post('/videos/{video}/complete', [VideoController::class, 'markCompleted'])->name('videos.complete');
    
    // Subscription Management (Subscribed users only)
    Route::middleware([\App\Http\Middleware\SubscribedMiddleware::class])->group(function () {
        Route::get('/subscription/manage', [SubscriptionController::class, 'manage'])->name('subscription.manage');
        Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
        Route::post('/subscription/cancel', [SubscriptionController::class, 'confirmCancel']);
        Route::post('/subscription/extend', [SubscriptionController::class, 'extend'])->name('subscription.extend');
    });
    
    // Subscription Checkout
    Route::get('/subscription/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy');
    Route::post('/users/{user}/upgrade', [AdminController::class, 'upgradeUser'])->name('users.upgrade');
    Route::post('/users/{user}/downgrade', [AdminController::class, 'downgradeUser'])->name('users.downgrade');
    
    // Lesson Management
    Route::get('/lessons', [LessonController::class, 'adminIndex'])->name('lessons.index');
    Route::get('/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::post('/lessons/{lesson}/toggle', [LessonController::class, 'toggleActive'])->name('lessons.toggle');
    
    // Exercise Management
    Route::get('/exercises', [ExerciseController::class, 'adminIndex'])->name('exercises.index');
    Route::get('/exercises/create', [ExerciseController::class, 'create'])->name('exercises.create');
    Route::post('/exercises', [ExerciseController::class, 'store'])->name('exercises.store');
    Route::get('/exercises/{exercise}/edit', [ExerciseController::class, 'edit'])->name('exercises.edit');
    Route::put('/exercises/{exercise}', [ExerciseController::class, 'update'])->name('exercises.update');
    Route::delete('/exercises/{exercise}', [ExerciseController::class, 'destroy'])->name('exercises.destroy');
    Route::post('/exercises/{exercise}/toggle', [ExerciseController::class, 'toggleActive'])->name('exercises.toggle');
    
    // Video Management - CORRIGÉ
    Route::get('/videos', [VideoController::class, 'adminIndex'])->name('videos.index');
    Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
    
    // SUPPRESSION: Route dupliquée (post et patch pour la même action)
    // Route::post('/videos/{video}/toggle', [VideoController::class, 'toggleActive'])->name('videos.toggle');
    
    // CONSERVÉ: Seule route pour toggle-active avec PATCH (convention REST)
    Route::patch('/videos/{video}/toggle-active', [VideoController::class, 'toggleActive'])->name('videos.toggle-active');
        
    // Category Management
    Route::get('/categories', [CategoryController::class, 'adminIndex'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{category}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');
    
    // Submission Management
    Route::get('/submissions', [AdminController::class, 'allSubmissions'])->name('submissions.index');
    Route::get('/submissions/pending', [AdminController::class, 'pendingSubmissions'])->name('submissions.pending');
    Route::get('/submissions/{submission}/correct', [AdminController::class, 'correctSubmission'])->name('submissions.correct');
    Route::post('/submissions/{submission}/correct', [AdminController::class, 'submitCorrection'])->name('submissions.submit');
});

/// Routes d'abonnement
Route::middleware(['auth'])->group(function () {
    // Pages d'affichage
    Route::get('/abonnement', [SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::get('/abonnement/expire', [SubscriptionController::class, 'expired'])->name('subscription.expired');
    Route::get('/abonnement/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/abonnement/gestion', [SubscriptionController::class, 'manage'])->name('subscription.manage');
    Route::get('/abonnement/historique', [SubscriptionController::class, 'paymentHistory'])->name('subscription.payment-history');
    
    // Pages de paiement par méthode
    Route::get('/abonnement/paiement/{plan}', [SubscriptionController::class, 'paymentMethod'])->name('subscription.payment-method');
    Route::get('/abonnement/paiement/carte/{plan}', [SubscriptionController::class, 'card'])->name('subscription.card');
    Route::get('/abonnement/paiement/mobile-money/{plan}', [SubscriptionController::class, 'mobileMoney'])->name('subscription.mobile-money');
    Route::get('/abonnement/paiement/crypto/{plan}', [SubscriptionController::class, 'crypto'])->name('subscription.crypto');
    Route::get('/abonnement/paiement/bank/{plan}', [SubscriptionController::class, 'bank'])->name('subscription.bank');
    
    // Pages d'attente de confirmation
    Route::get('/abonnement/en-attente/crypto/{id}', [SubscriptionController::class, 'cryptoWaiting'])->name('subscription.crypto-waiting');
    Route::get('/abonnement/en-attente/bank/{id}', [SubscriptionController::class, 'bankWaiting'])->name('subscription.bank-waiting');
    Route::get('/abonnement/en-attente/mobile-money/{id}', [SubscriptionController::class, 'mobileMoneyWaiting'])->name('subscription.mobile-money-waiting');
    
    // Traitement des paiements (POST)
    Route::post('/abonnement/traiter/mobile-money', [SubscriptionController::class, 'processMobileMoney'])->name('subscription.process-mobile-money');
    Route::post('/abonnement/confirmer/mobile-money/{id}', [SubscriptionController::class, 'confirmMobileMoneyCode'])->name('subscription.confirm-mobile-money');
    Route::post('/abonnement/traiter/crypto', [SubscriptionController::class, 'processCrypto'])->name('subscription.process-crypto');
    Route::post('/abonnement/traiter/bank', [SubscriptionController::class, 'processBank'])->name('subscription.process-bank');
    Route::post('/abonnement/traiter/card', [SubscriptionController::class, 'processCard'])->name('subscription.process-card');
    
    // Vérification des paiements
    Route::post('/abonnement/webhook/crypto', [SubscriptionController::class, 'verifyCryptoWebhook'])->name('subscription.crypto-webhook');
    Route::get('/abonnement/verifier/{id}', [SubscriptionController::class, 'verifyPayment'])->name('subscription.verify');
    Route::post('/abonnement/confirmer-bank', [SubscriptionController::class, 'confirmBank'])->name('subscription.confirm-bank')->middleware('admin');
    
    // Gestion d'abonnement
    Route::get('/abonnement/annuler', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/abonnement/annuler', [SubscriptionController::class, 'confirmCancel'])->name('subscription.confirm-cancel');
    Route::post('/abonnement/prolonger', [SubscriptionController::class, 'extend'])->name('subscription.extend');
    Route::get('/abonnement/reactiver', [SubscriptionController::class, 'reactivate'])->name('subscription.reactivate');
    
    // Téléchargement facture
    Route::get('/abonnement/facture/{id}', [SubscriptionController::class, 'invoice'])->name('subscription.invoice');
});

// Webhook accessible sans authentification
Route::post('/webhook/crypto-payment', [SubscriptionController::class, 'verifyCryptoWebhook']);

require __DIR__.'/auth.php';