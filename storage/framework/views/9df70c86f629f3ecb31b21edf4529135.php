

<?php $__env->startSection('title', 'Mon Profil - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
            <p class="text-gray-600 mt-2">Gérez vos informations et consultez votre progression.</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Profile Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 bg-primary-500 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                            <?php echo e(substr($user->name, 0, 1)); ?>

                        </div>
                        <h2 class="text-xl font-bold text-gray-900"><?php echo e($user->name); ?></h2>
                        <p class="text-gray-500"><?php echo e($user->email); ?></p>
                        <span class="badge badge-<?php echo e($user->role_badge_color); ?> mt-2">
                            <?php echo e($user->subscription_status); ?>

                        </span>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['completed_exercises'] ?? 0); ?></div>
                                <div class="text-xs text-gray-500">Exercices</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_points'] ?? 0); ?></div>
                                <div class="text-xs text-gray-500">Points</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900"><?php echo e($stats['watched_videos'] ?? 0); ?></div>
                                <div class="text-xs text-gray-500">Vidéos</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Actions rapides</h3>
                    <div class="space-y-2">
                        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-tachometer-alt text-primary-500 w-6"></i>
                            <span>Tableau de bord</span>
                        </a>
                        <a href="<?php echo e(route('exercises.index')); ?>" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-laptop-code text-success-500 w-6"></i>
                            <span>Exercices</span>
                        </a>
                        <a href="<?php echo e(route('lessons.index')); ?>" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-book text-secondary-500 w-6"></i>
                            <span>Leçons</span>
                        </a>
                        <?php if($user->isSubscribed()): ?>
                            <a href="<?php echo e(route('subscription.manage')); ?>" class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <i class="fas fa-crown text-warning-500 w-6"></i>
                                <span>Mon abonnement</span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('subscription.plans')); ?>" class="flex items-center p-3 bg-warning-50 rounded-lg hover:bg-warning-100 transition-colors">
                                <i class="fas fa-crown text-warning-500 w-6"></i>
                                <span class="text-warning-700">Passer Premium</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Edit Profile -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Modifier mes informations</h3>
                    
                    <form method="POST" action="<?php echo e(route('profile.update')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                                <input type="text" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" 
                                    class="form-input mt-1 w-full" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                                <input type="email" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" 
                                    class="form-input mt-1 w-full" required>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Changer mon mot de passe</h3>
                    
                    <form method="POST" action="<?php echo e(route('profile.password')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password" 
                                    class="form-input mt-1 w-full" required>
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                                <input type="password" id="password" name="password" 
                                    class="form-input mt-1 w-full" required>
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                    class="form-input mt-1 w-full" required>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key mr-2"></i> Changer le mot de passe
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Submission History -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Historique des soumissions</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exercice</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $allSubmissions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <a href="<?php echo e(route('exercises.show', $submission->exercise->slug)); ?>" class="text-primary-600 hover:text-primary-700">
                                                <?php echo e($submission->exercise->title); ?>

                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge badge-<?php echo e($submission->status_badge_color); ?>">
                                                <?php echo e($submission->status_display); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3"><?php echo e($submission->score_percentage); ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            <?php echo e($submission->submitted_at?->format('d/m/Y') ?? '-'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                            <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                                            <p>Aucune soumission pour le moment.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($allSubmissions ?? false): ?>
                        <div class="mt-4">
                            <?php echo e($allSubmissions->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <!-- Watched Videos History -->
                <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Historique des vidéos vues</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vidéo</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progression</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terminée le</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $allVideoProgress ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $progress): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <a href="<?php echo e(route('videos.show', $progress->video->slug)); ?>" class="text-primary-600 hover:text-primary-700">
                                                <?php echo e($progress->video->title); ?>

                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            <?php echo e($progress->progress_percentage); ?>%
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            <?php echo e($progress->completed_at?->format('d/m/Y') ?? '-'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                            <i class="fas fa-video text-4xl mb-4 text-gray-300"></i>
                                            <p>Aucune vidéo terminée pour le moment.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($allVideoProgress ?? false): ?>
                        <div class="mt-4">
                            <?php echo e($allVideoProgress->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/profile.blade.php ENDPATH**/ ?>