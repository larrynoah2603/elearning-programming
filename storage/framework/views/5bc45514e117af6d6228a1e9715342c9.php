<?php $__env->startSection('title', 'Tableau de bord - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Bonjour, <?php echo e(auth()->user()->name); ?> ! 👋
            </h1>
            <p class="text-gray-600 mt-2">
                Voici votre progression et les contenus recommandés pour vous.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
                <div class="w-14 h-14 bg-success-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-2xl text-success-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900"><?php echo e($stats['completed_exercises'] ?? 0); ?></div>
                    <div class="text-gray-500">Exercices réussis</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-star text-2xl text-primary-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_points'] ?? 0); ?></div>
                    <div class="text-gray-500">Points gagnés</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
                <div class="w-14 h-14 bg-secondary-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-play-circle text-2xl text-secondary-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900"><?php echo e($stats['watched_videos'] ?? 0); ?></div>
                    <div class="text-gray-500">Vidéos regardées</div>
                </div>
            </div>
        </div>

        <!-- Subscription Status -->
        <?php if(!auth()->user()->isSubscribed()): ?>
            <div class="bg-gradient-to-r from-secondary-500 to-primary-600 rounded-xl p-6 mb-8 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-xl font-bold mb-2">
                            <i class="fas fa-crown mr-2"></i> Passez à la version Premium
                        </h3>
                        <p class="text-white/90">Accédez à tous les exercices, leçons PDF et vidéos exclusives.</p>
                    </div>
                    <a href="<?php echo e(route('subscription.plans')); ?>" class="btn bg-white text-primary-700 hover:bg-gray-100">
                        Découvrir les offres
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Submissions -->
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Derniers exercices soumis</h3>
                        <a href="<?php echo e(route('profile')); ?>" class="text-primary-600 hover:text-primary-700 text-sm">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $recentSubmissions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900"><?php echo e($submission->exercise->title); ?></h4>
                                    <p class="text-sm text-gray-500">
                                        Soumis le <?php echo e($submission->submitted_at?->format('d/m/Y') ?? 'En cours'); ?>

                                    </p>
                                </div>
                                <span class="badge badge-<?php echo e($submission->status_badge_color); ?>">
                                    <?php echo e($submission->status_display); ?>

                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                                <p>Vous n'avez pas encore soumis d'exercices.</p>
                                <a href="<?php echo e(route('exercises.index')); ?>" class="text-primary-600 hover:text-primary-700 mt-2 inline-block">
                                    Commencer un exercice
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Available Content -->
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Contenu recommandé</h3>
                    </div>
                    <div class="p-6">
                        <!-- Exercises -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Exercices</h4>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <?php $__empty_1 = true; $__currentLoopData = $availableExercises ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e(route('exercises.show', $exercise->slug)); ?>" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-code text-primary-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-medium text-gray-900 truncate"><?php echo e($exercise->title); ?></h5>
                                            <p class="text-sm text-gray-500"><?php echo e($exercise->difficulty_display); ?></p>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-gray-500 text-sm">Aucun exercice disponible.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Lessons -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Leçons</h4>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <?php $__empty_1 = true; $__currentLoopData = $availableLessons ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e(route('lessons.show', $lesson->slug)); ?>" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="w-10 h-10 bg-secondary-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-book text-secondary-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-medium text-gray-900 truncate"><?php echo e($lesson->title); ?></h5>
                                            <p class="text-sm text-gray-500"><?php echo e($lesson->level_display); ?></p>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-gray-500 text-sm">Aucune leçon disponible.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Videos (Premium only) -->
                        <?php if(auth()->user()->isSubscribed()): ?>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Vidéos</h4>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <?php $__empty_1 = true; $__currentLoopData = $availableVideos ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <a href="<?php echo e(route('videos.show', $video->slug)); ?>" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                            <div class="w-10 h-10 bg-success-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-play text-success-600"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h5 class="font-medium text-gray-900 truncate"><?php echo e($video->title); ?></h5>
                                                <p class="text-sm text-gray-500"><?php echo e($video->duration_display); ?></p>
                                            </div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <p class="text-gray-500 text-sm">Aucune vidéo disponible.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Progress -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Votre progression</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Exercices simples</span>
                                <span class="font-medium"><?php echo e($exerciseProgress['simple'] ?? 0); ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-success-500 h-2 rounded-full" style="width: <?php echo e(min(100, ($exerciseProgress['simple'] ?? 0) * 10)); ?>%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Exercices complexes</span>
                                <span class="font-medium"><?php echo e($exerciseProgress['complexe'] ?? 0); ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-secondary-500 h-2 rounded-full" style="width: <?php echo e(min(100, ($exerciseProgress['complexe'] ?? 0) * 5)); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Videos -->
                <?php if(auth()->user()->isSubscribed()): ?>
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Vidéos en cours</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <?php $__empty_1 = true; $__currentLoopData = $recentVideoProgress ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="px-6 py-4">
                                    <h4 class="font-medium text-gray-900 truncate"><?php echo e($progress->video->title); ?></h4>
                                    <div class="mt-2">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Progression</span>
                                            <span><?php echo e($progress->progress_percentage); ?>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-primary-500 h-1.5 rounded-full" style="width: <?php echo e($progress->progress_percentage); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-play-circle text-4xl mb-4 text-gray-300"></i>
                                    <p>Aucune vidéo en cours.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Quick Links -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Liens rapides</h3>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('exercises.index')); ?>" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-laptop-code w-6"></i>
                            <span>Tous les exercices</span>
                        </a>
                        <a href="<?php echo e(route('lessons.index')); ?>" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-book w-6"></i>
                            <span>Toutes les leçons</span>
                        </a>
                        <?php if(auth()->user()->isSubscribed()): ?>
                            <a href="<?php echo e(route('videos.index')); ?>" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-video w-6"></i>
                                <span>Toutes les vidéos</span>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('profile')); ?>" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-user w-6"></i>
                            <span>Mon profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/dashboard.blade.php ENDPATH**/ ?>