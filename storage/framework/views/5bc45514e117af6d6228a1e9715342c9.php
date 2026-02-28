

<?php $__env->startSection('title', 'Tableau de bord - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bonjour, <?php echo e(auth()->user()->name); ?> ! 👋</h1>
            <p class="text-gray-600 mt-2">Voici votre progression, vos quick wins et vos prochaines recommandations.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                    <i class="fas fa-fire text-2xl text-secondary-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900"><?php echo e($streak['current'] ?? 0); ?></div>
                    <div class="text-gray-500">Jours de streak</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
                <div class="w-14 h-14 bg-warning-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-2xl text-warning-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900"><?php echo e($stats['study_minutes_week'] ?? 0); ?> min</div>
                    <div class="text-gray-500">Temps d'étude (semaine)</div>
                </div>
            </div>
        </div>

        <?php if(!auth()->user()->isSubscribed()): ?>
            <div class="bg-gradient-to-r from-secondary-500 to-primary-600 rounded-xl p-6 mb-8 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-xl font-bold mb-2"><i class="fas fa-crown mr-2"></i> Passez à la version Premium</h3>
                        <p class="text-white/90">Accédez à tous les exercices, leçons PDF et vidéos exclusives.</p>
                    </div>
                    <a href="<?php echo e(route('subscription.plans')); ?>" class="btn bg-white text-primary-700 hover:bg-gray-100">Découvrir les offres</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🎯 Quick wins de la semaine</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Objectif exercices (<?php echo e($quickWins['exercise_goal']); ?>)</span>
                                <span class="font-medium"><?php echo e($quickWins['exercise_done']); ?>/<?php echo e($quickWins['exercise_goal']); ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-success-500 h-2.5 rounded-full" style="width: <?php echo e($quickWins['exercise_progress']); ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Objectif temps d'étude (<?php echo e($quickWins['minutes_goal']); ?> min)</span>
                                <span class="font-medium"><?php echo e($quickWins['minutes_done']); ?> min</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-primary-500 h-2.5 rounded-full" style="width: <?php echo e($quickWins['minutes_progress']); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">🤖 Recommandation leçon</h3>
                        <?php if($recommendations['lesson']): ?>
                            <h4 class="font-semibold text-gray-900"><?php echo e($recommendations['lesson']->title); ?></h4>
                            <p class="text-sm text-gray-500 mt-2"><?php echo e(\Illuminate\Support\Str::limit($recommendations['lesson']->description, 120)); ?></p>
                            <a href="<?php echo e(route('lessons.show', $recommendations['lesson']->slug)); ?>" class="text-primary-600 hover:text-primary-700 inline-block mt-3">Commencer la leçon <i class="fas fa-arrow-right ml-1"></i></a>
                        <?php else: ?>
                            <p class="text-gray-500">Aucune recommandation pour le moment.</p>
                        <?php endif; ?>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">🧠 Recommandation exercice</h3>
                        <?php if($recommendations['exercise']): ?>
                            <h4 class="font-semibold text-gray-900"><?php echo e($recommendations['exercise']->title); ?></h4>
                            <p class="text-sm text-gray-500 mt-2">Langage: <?php echo e($recommendations['exercise']->programming_language ?? 'N/A'); ?></p>
                            <a href="<?php echo e(route('exercises.show', $recommendations['exercise']->slug)); ?>" class="text-primary-600 hover:text-primary-700 inline-block mt-3">Lancer l'exercice <i class="fas fa-arrow-right ml-1"></i></a>
                        <?php else: ?>
                            <p class="text-gray-500">Excellent travail ! Vous avez complété les exercices recommandés.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🏅 Badges de progression</h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border rounded-lg p-4 <?php echo e($badge['unlocked'] ? 'border-success-300 bg-success-50' : 'border-gray-200 bg-gray-50'); ?>">
                                <div class="flex items-center gap-3">
                                    <i class="fas <?php echo e($badge['icon']); ?> <?php echo e($badge['unlocked'] ? 'text-success-600' : 'text-gray-400'); ?>"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900"><?php echo e($badge['name']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($badge['description']); ?></p>
                                    </div>
                                </div>
                                <span class="text-xs mt-2 inline-block <?php echo e($badge['unlocked'] ? 'text-success-700' : 'text-gray-500'); ?>"><?php echo e($badge['status']); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Derniers exercices soumis</h3>
                        <a href="<?php echo e(route('profile')); ?>" class="text-primary-600 hover:text-primary-700 text-sm">Voir tout <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $recentSubmissions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900"><?php echo e($submission->exercise->title); ?></h4>
                                    <p class="text-sm text-gray-500">Soumis le <?php echo e($submission->submitted_at?->format('d/m/Y') ?? 'En cours'); ?></p>
                                </div>
                                <span class="badge badge-<?php echo e($submission->status_badge_color); ?>"><?php echo e($submission->status_display); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                                <p>Vous n'avez pas encore soumis d'exercices.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🔥 Streak & progression</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <p>Streak actuel: <strong class="text-gray-900"><?php echo e($streak['current']); ?> jours</strong></p>
                        <p>Meilleur streak: <strong class="text-gray-900"><?php echo e($streak['longest']); ?> jours</strong></p>
                        <p>Dernière activité: <strong class="text-gray-900"><?php echo e($streak['last_activity_date'] ? \Illuminate\Support\Carbon::parse($streak['last_activity_date'])->format('d/m/Y') : 'Aucune'); ?></strong></p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">👥 <?php echo e($leaderboard['label']); ?></h3>
                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $leaderboard['top']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 rounded-lg <?php echo e($entry['id'] === auth()->id() ? 'bg-primary-50 border border-primary-100' : 'bg-gray-50'); ?>">
                                <div>
                                    <p class="font-medium text-gray-900">#<?php echo e($index + 1); ?> <?php echo e($entry['name']); ?></p>
                                    <p class="text-xs text-gray-500">Streak: <?php echo e($entry['streak']); ?> j</p>
                                </div>
                                <span class="text-sm font-semibold text-primary-700"><?php echo e($entry['score']); ?> pts</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-sm text-gray-500">Aucun classement disponible.</p>
                        <?php endif; ?>
                    </div>
                    <?php if($leaderboard['my_rank']): ?>
                        <p class="text-sm text-gray-600 mt-4">Votre rang actuel: <strong>#<?php echo e($leaderboard['my_rank']); ?></strong></p>
                    <?php endif; ?>
                    <?php if(!(auth()->user()->school_name && auth()->user()->class_name)): ?>
                        <p class="text-xs text-gray-500 mt-2">Ajoutez votre école et votre classe dans le profil pour activer le classement privé.</p>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Votre progression</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Exercices simples</span>
                                <span class="font-medium"><?php echo e($exerciseProgress['simple'] ?? 0); ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-success-500 h-2 rounded-full" style="width: <?php echo e(min(100, ($exerciseProgress['simple'] ?? 0) * 10)); ?>%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Exercices complexes</span>
                                <span class="font-medium"><?php echo e($exerciseProgress['complexe'] ?? 0); ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-secondary-500 h-2 rounded-full" style="width: <?php echo e(min(100, ($exerciseProgress['complexe'] ?? 0) * 5)); ?>%"></div></div>
                        </div>
                    </div>
                </div>

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
                                        <div class="flex justify-between text-xs text-gray-500 mb-1"><span>Progression</span><span><?php echo e($progress->progress_percentage); ?>%</span></div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5"><div class="bg-primary-500 h-1.5 rounded-full" style="width: <?php echo e($progress->progress_percentage); ?>%"></div></div>
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
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/dashboard.blade.php ENDPATH**/ ?>