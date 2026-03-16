

<?php $__env->startSection('title', 'Administration - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord Admin
            </h1>
            <p class="text-gray-600 mt-2">Bienvenue! Voici un aperçu de votre plateforme e-learning.</p>
        </div>

        <!-- Important Notice -->
        <?php if($stats['pending_submissions'] > 0): ?>
        <div class="mb-8 bg-warning-50 border-l-4 border-warning-500 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-warning-600 mr-4 text-2xl"></i>
                <div>
                    <h3 class="font-semibold text-warning-900">Soumissions en attente</h3>
                    <p class="text-warning-700"><?php echo e($stats['pending_submissions']); ?> soumission(s) en attente de correction. <a href="<?php echo e(route('admin.submissions.pending')); ?>" class="underline font-semibold">Voir détails</a></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Users -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-xl text-primary-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_users']); ?></span>
                </div>
                <p class="text-gray-600 text-sm">Utilisateurs totaux</p>
                <div class="mt-2 flex items-center text-xs text-gray-500">
                    <span class="text-success-600 mr-2"><i class="fas fa-user mr-1"></i> <?php echo e($stats['free_users']); ?> gratuits</span>
                    <span class="text-secondary-600"><i class="fas fa-crown mr-1"></i> <?php echo e($stats['subscribed_users']); ?> premium</span>
                </div>
            </div>

            <!-- Lessons -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-secondary-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book text-xl text-secondary-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_lessons']); ?></span>
                </div>
                <p class="text-gray-600 text-sm">Leçons</p>
                <div class="mt-2 flex items-center text-xs text-gray-500">
                    <span class="text-success-600 mr-2"><i class="fas fa-check mr-1"></i> <?php echo e($stats['active_lessons']); ?> actives</span>
                </div>
            </div>

            <!-- Exercises -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-laptop-code text-xl text-success-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_exercises']); ?></span>
                </div>
                <p class="text-gray-600 text-sm">Exercices</p>
                <div class="mt-2 flex items-center text-xs text-gray-500">
                    <span class="text-success-600 mr-2"><i class="fas fa-check mr-1"></i> <?php echo e($stats['active_exercises']); ?> actifs</span>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-danger-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-folder text-xl text-danger-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900"><?php echo e($stats['total_categories']); ?></span>
                </div>
                <p class="text-gray-600 text-sm">Catégories</p>
                <div class="mt-2 flex items-center">
                    <a href="<?php echo e(route('admin.categories.index')); ?>" class="text-primary-600 hover:text-primary-700 text-xs font-semibold">
                        Gérer <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- Pending Submissions Alert -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 <?php echo e($stats['pending_submissions'] > 0 ? 'border-warning-500' : 'border-success-500'); ?>">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 <?php echo e($stats['pending_submissions'] > 0 ? 'bg-warning-100' : 'bg-success-100'); ?> rounded-xl flex items-center justify-center">
                        <i class="fas <?php echo e($stats['pending_submissions'] > 0 ? 'fa-hourglass-end' : 'fa-check-circle'); ?> text-xl <?php echo e($stats['pending_submissions'] > 0 ? 'text-warning-600' : 'text-success-600'); ?>"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900"><?php echo e($stats['pending_submissions']); ?></span>
                </div>
                <p class="text-gray-600 text-sm"><?php echo e($stats['pending_submissions'] > 0 ? 'Soumissions en attente' : 'Aucune soumission en attente'); ?></p>
                <div class="mt-2 flex items-center">
                    <a href="<?php echo e(route('admin.submissions.pending')); ?>" class="text-primary-600 hover:text-primary-700 text-xs font-semibold">
                        Détails <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Subscription Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-4">
                <p class="text-gray-600 text-sm mb-2">Utilisateurs Premium</p>
                <p class="text-2xl font-bold text-primary-900"><?php echo e($stats['subscribed_users']); ?></p>
                <p class="text-primary-700 text-xs mt-1">Actifs maintenant</p>
            </div>
            <div class="bg-gradient-to-br from-success-50 to-success-100 rounded-xl p-4">
                <p class="text-gray-600 text-sm mb-2">Utilisateurs Gratuits</p>
                <p class="text-2xl font-bold text-success-900"><?php echo e($stats['free_users']); ?></p>
                <p class="text-success-700 text-xs mt-1">Sur la plateforme</p>
            </div>
            <div class="bg-gradient-to-br from-danger-50 to-danger-100 rounded-xl p-4">
                <p class="text-gray-600 text-sm mb-2">Abonnements Expirés</p>
                <p class="text-2xl font-bold text-danger-900"><?php echo e($stats['expired_subscriptions']); ?></p>
                <p class="text-danger-700 text-xs mt-1">À renouveler</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="<?php echo e(route('admin.users.index')); ?>" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 border border-gray-100 hover:border-primary-300 group">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-primary-200 transition-colors">
                    <i class="fas fa-users text-primary-600"></i>
                </div>
                <p class="font-semibold text-gray-900 text-sm">Gérer les utilisateurs</p>
                <p class="text-gray-500 text-xs mt-1">Ajouter, éditer ou supprimer</p>
            </a>
            <a href="<?php echo e(route('admin.lessons.index')); ?>" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 border border-gray-100 hover:border-secondary-300 group">
                <div class="w-10 h-10 bg-secondary-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-secondary-200 transition-colors">
                    <i class="fas fa-book text-secondary-600"></i>
                </div>
                <p class="font-semibold text-gray-900 text-sm">Gérer les leçons</p>
                <p class="text-gray-500 text-xs mt-1">Créer ou modifier</p>
            </a>
            <a href="<?php echo e(route('admin.exercises.index')); ?>" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 border border-gray-100 hover:border-success-300 group">
                <div class="w-10 h-10 bg-success-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-success-200 transition-colors">
                    <i class="fas fa-laptop-code text-success-600"></i>
                </div>
                <p class="font-semibold text-gray-900 text-sm">Gérer les exercices</p>
                <p class="text-gray-500 text-xs mt-1">Coding challenges</p>
            </a>
            <a href="<?php echo e(route('admin.videos.index')); ?>" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 border border-gray-100 hover:border-warning-300 group">
                <div class="w-10 h-10 bg-warning-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-warning-200 transition-colors">
                    <i class="fas fa-video text-warning-600"></i>
                </div>
                <p class="font-semibold text-gray-900 text-sm">Gérer les vidéos</p>
                <p class="text-gray-500 text-xs mt-1">Contenu vidéo</p>
            </a>
            <a href="<?php echo e(route('admin.formations.index')); ?>" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 border border-gray-100 hover:border-info-300 group">
                <div class="w-10 h-10 bg-info-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-info-200 transition-colors">
                    <i class="fas fa-graduation-cap text-info-600"></i>
                </div>
                <p class="font-semibold text-gray-900 text-sm">Gérer les formations</p>
                <p class="text-gray-500 text-xs mt-1">Formations payantes</p>
            </a>
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 border border-gray-100 hover:border-danger-300 group">
                <div class="w-10 h-10 bg-danger-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-danger-200 transition-colors">
                    <i class="fas fa-folder text-danger-600"></i>
                </div>
                <p class="font-semibold text-gray-900 text-sm">Gérer les catégories</p>
                <p class="text-gray-500 text-xs mt-1">Organisation du contenu</p>
            </a>
            <a href="<?php echo e(route('admin.submissions.pending')); ?>" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 border border-gray-100 hover:border-warning-300 group">
                <div class="w-10 h-10 <?php echo e($stats['pending_submissions'] > 0 ? 'bg-warning-100' : 'bg-success-100'); ?> rounded-lg flex items-center justify-center mb-3 <?php echo e($stats['pending_submissions'] > 0 ? 'group-hover:bg-warning-200' : 'group-hover:bg-success-200'); ?> transition-colors">
                    <i class="fas <?php echo e($stats['pending_submissions'] > 0 ? 'fa-hourglass-end' : 'fa-check-circle'); ?> <?php echo e($stats['pending_submissions'] > 0 ? 'text-warning-600' : 'text-success-600'); ?>"></i>
                </div>
                <p class="font-semibold text-gray-900 text-sm">Soumissions</p>
                <p class="text-gray-500 text-xs mt-1"><?php echo e($stats['pending_submissions']); ?> en attente</p>
            </a>
            <a href="<?php echo e(route('admin.statistics')); ?>" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow p-4 border border-gray-100 hover:border-primary-300 group">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-primary-200 transition-colors">
                    <i class="fas fa-chart-bar text-primary-600"></i>
                </div>
                <p class="font-semibold text-gray-900 text-sm">Statistiques</p>
                <p class="text-gray-500 text-xs mt-1">Rapports détaillés</p>
            </a>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Pending Submissions -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-clock mr-2 text-warning-500"></i> Soumissions en attente
                    </h3>
                    <a href="<?php echo e(route('admin.submissions.pending')); ?>" class="text-primary-600 hover:text-primary-700 text-sm">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $pendingSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900"><?php echo e($submission->exercise?->title ?? 'Exercice supprimé'); ?></h4>
                                <p class="text-sm text-gray-500">
                                    Par <?php echo e($submission->user?->name ?? 'Utilisateur supprimé'); ?> • <?php echo e($submission->submitted_at->diffForHumans()); ?>

                                </p>
                            </div>
                            <a href="<?php echo e(route('admin.submissions.correct', $submission)); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-check mr-1"></i> Corriger
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-check-circle text-4xl mb-4 text-success-500"></i>
                            <p>Aucune soumission en attente.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-user-plus mr-2 text-primary-500"></i> Nouveaux utilisateurs
                    </h3>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="text-primary-600 hover:text-primary-700 text-sm">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <?php echo e(substr($user->name, 0, 1)); ?>

                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900"><?php echo e($user->name); ?></h4>
                                    <p class="text-sm text-gray-500"><?php echo e($user->email); ?></p>
                                </div>
                            </div>
                            <span class="badge badge-<?php echo e($user->role_badge_color); ?>">
                                <?php echo e($user->subscription_status); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                            <p>Aucun utilisateur récent.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Submissions -->
        <div class="mt-8 bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-clipboard-check mr-2 text-success-500"></i> Dernières soumissions
                </h3>
                <a href="<?php echo e(route('admin.submissions.index')); ?>" class="text-primary-600 hover:text-primary-700 text-sm">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exercice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $recentSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-2">
                                            <?php echo e(strtoupper(substr($submission->user?->name ?? '?', 0, 1))); ?>

                                        </div>
                                        <span class="text-sm text-gray-900"><?php echo e($submission->user?->name ?? 'Utilisateur supprimé'); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($submission->exercise?->title ?? 'Exercice supprimé'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-<?php echo e($submission->status_badge_color); ?>">
                                        <?php echo e($submission->status_display); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($submission->submitted_at?->format('d/m/Y H:i') ?? '-'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo e($submission->score_percentage); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                                    <p>Aucune soumission récente.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>