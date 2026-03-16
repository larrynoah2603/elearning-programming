

<?php $__env->startSection('title', $formation->title); ?>

<?php $__env->startSection('content'); ?>
<?php
    $hasAccess = $hasAccess ?? false;
    $enrollment = $enrollment ?? null;
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="badge badge-info"><?php echo e($formation->level_display); ?></span>
            <span class="badge badge-warning">Paiement séparé de l'abonnement Premium</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($formation->title); ?></h1>
        <p class="text-gray-600 mb-6"><?php echo e($formation->description); ?></p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-primary-50 rounded-lg p-4">
                <p class="text-sm text-primary-700">Tarif unique</p>
                <p class="text-2xl font-bold text-primary-900"><?php echo e(number_format($formation->price, 2, ',', ' ')); ?> €</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600">Accès</p>
                <p class="font-semibold text-gray-900">Disponible pour compte gratuit, premium ou sans abonnement actif.</p>
            </div>
        </div>

        <?php if($hasAccess && $enrollment): ?>
            <div class="rounded-lg bg-success-50 border border-success-200 p-4 text-success-800 mb-8">
                <p class="font-semibold">✅ Vous faites partie de cette formation.</p>
                <p class="text-sm mt-1">Achetée le <?php echo e(optional($enrollment->paid_at)->format('d/m/Y H:i')); ?> via <?php echo e($enrollment->payment_method); ?>.</p>
                <p class="text-xs mt-1">Référence de paiement: <?php echo e($enrollment->payment_reference ?? 'N/A'); ?></p>
            </div>
        <?php endif; ?>

        <h2 class="text-xl font-semibold text-gray-900 mb-4">Modules de la formation</h2>
        <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $formation->modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="border border-gray-100 rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex justify-between items-start gap-3 mb-4">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-lg">Module <?php echo e($index + 1); ?> - <?php echo e($module->title); ?></p>
                            <p class="text-sm text-gray-600 mt-1"><?php echo e($module->description); ?></p>
                        </div>
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded"><?php echo e($module->duration_minutes); ?> min</span>
                            <?php if($hasAccess): ?>
                                <a href="<?php echo e(route('formations.module.show', ['formation' => $formation, 'module' => $module->id])); ?>" class="btn btn-sm btn-primary">Commencer le module</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500">Le programme détaillé sera bientôt disponible.</p>
            <?php endif; ?>
        </div>

        <?php if($hasAccess): ?>
            <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Ressources complémentaires</h3>
                <p class="text-blue-700 mb-4">Accédez aux leçons et vidéos liées à cette formation :</p>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('lessons.index')); ?>" class="btn btn-sm btn-outline-primary">Voir les leçons</a>
                    <a href="<?php echo e(route('videos.index')); ?>" class="btn btn-sm btn-outline-primary">Voir les vidéos</a>
                </div>
            </div>
        <?php endif; ?>

        <?php if($formation->quizzes->count() > 0): ?>
            <h2 class="text-xl font-semibold text-gray-900 mb-4 mt-8">Quizzes de validation</h2>
            <div class="space-y-3">
                <?php $__currentLoopData = $formation->quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-100 rounded-lg p-4">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900"><?php echo e($quiz->title); ?></p>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($quiz->description ?? 'Quiz de validation'); ?></p>
                                <p class="text-xs text-gray-500 mt-2">
                                    <?php echo e($quiz->questions->count()); ?> question<?php echo e($quiz->questions->count() > 1 ? 's' : ''); ?> •
                                    Score min: <?php echo e($quiz->passing_score); ?>% •
                                    <?php echo e($quiz->max_attempts); ?> tentative<?php echo e($quiz->max_attempts > 1 ? 's' : ''); ?> •
                                    <?php echo e($quiz->duration_minutes); ?>min
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <?php
                                    $userSubmission = auth()->check() ? $quiz->submissions()->where('user_id', auth()->id())->first() : null;
                                    $hasPassed = $userSubmission && $userSubmission->isPassed();
                                    $attemptCount = auth()->check() ? $quiz->submissions()->where('user_id', auth()->id())->count() : 0;
                                ?>

                                <?php if($hasPassed): ?>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        ✅ Réussi (<?php echo e($userSubmission->score); ?>%)
                                    </span>
                                <?php elseif($userSubmission && !$hasPassed): ?>
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                        ❌ Échec (<?php echo e($userSubmission->score); ?>%)
                                    </span>
                                <?php endif; ?>

                                <?php if($hasAccess): ?>
                                    <?php if($hasPassed): ?>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Validé ✓</span>
                                    <?php elseif($attemptCount >= $quiz->max_attempts): ?>
                                        <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded">Tentatives épuisées</span>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('quiz.show', $quiz)); ?>" class="btn btn-sm btn-primary">
                                            <?php echo e($attemptCount > 0 ? 'Retenter' : 'Commencer'); ?>

                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-xs text-gray-500">Achetez pour accéder</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <div class="mt-8">
            <?php if(auth()->guard()->check()): ?>
                <?php if($hasAccess): ?>
                    <a href="<?php echo e(route('formations.my')); ?>" class="btn btn-secondary">Voir mes formations</a>
                <?php else: ?>
                    <a href="<?php echo e(route('formations.checkout', $formation)); ?>" class="btn btn-primary">
                        Acheter cette formation
                    </a>
                <?php endif; ?>
            <?php else: ?>
                <div class="space-x-2">
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Se connecter</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-secondary">S'inscrire</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/formations/show.blade.php ENDPATH**/ ?>