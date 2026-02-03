<?php $__env->startSection('title', 'Vidéos - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Vidéos de Formation</h1>
            <p class="text-gray-600 mt-2">Apprenez visuellement avec nos tutoriels vidéo.</p>
        </div>

        <!-- Premium Notice for Free Users -->
        <?php if(!auth()->check() || !auth()->user()->isSubscribed()): ?>
            <div class="bg-gradient-to-r from-secondary-500 to-primary-600 rounded-xl p-6 mb-8 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-bold mb-2">
                            <i class="fas fa-crown mr-2"></i> Contenu Premium
                        </h3>
                        <p class="text-white/90">Les vidéos sont réservées aux membres Premium.</p>
                    </div>
                    <a href="<?php echo e(route('subscription.plans')); ?>" class="btn bg-white text-primary-700 hover:bg-gray-100">
                        Devenir Premium
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <?php if(auth()->check() && auth()->user()->isSubscribed()): ?>
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <form method="GET" action="<?php echo e(route('videos.index')); ?>" class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                class="form-input pl-10 w-full" placeholder="Titre ou description...">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                        <select name="level" class="form-select w-full">
                            <option value="all">Tous les niveaux</option>
                            <option value="debutant" <?php echo e(request('level') == 'debutant' ? 'selected' : ''); ?>>Débutant</option>
                            <option value="intermediaire" <?php echo e(request('level') == 'intermediaire' ? 'selected' : ''); ?>>Intermédiaire</option>
                            <option value="avance" <?php echo e(request('level') == 'avance' ? 'selected' : ''); ?>>Avancé</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-filter mr-2"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Videos Grid -->
        <?php if(auth()->check() && auth()->user()->isSubscribed()): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden">
                        <a href="<?php echo e(route('videos.show', $video->slug)); ?>" class="block">
                            <div class="relative aspect-video bg-gray-900 overflow-hidden">
                                <?php if($video->thumbnail): ?>
                                    <img src="<?php echo e($video->thumbnail_url); ?>" alt="<?php echo e($video->title); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center">
                                        <i class="fas fa-play-circle text-6xl text-white/50"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 hover:opacity-100 transition-opacity">
                                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                        <i class="fas fa-play text-white text-2xl"></i>
                                    </div>
                                </div>
                                <?php if($video->duration): ?>
                                    <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                        <?php echo e($video->duration_display); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="badge badge-<?php echo e($video->level_badge_color); ?>">
                                    <?php echo e($video->level_display); ?>

                                </span>
                                <?php if($video->access_level == 'subscribed'): ?>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-crown mr-1"></i> Premium
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-2">
                                <a href="<?php echo e(route('videos.show', $video->slug)); ?>" class="hover:text-primary-600 transition-colors">
                                    <?php echo e($video->title); ?>

                                </a>
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo e(Str::limit($video->description, 100)); ?></p>

                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span><i class="fas fa-eye mr-1"></i> <?php echo e($video->views_count); ?> vues</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-16">
                        <i class="fas fa-video text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune vidéo trouvée</h3>
                        <p class="text-gray-500">Essayez de modifier vos critères de recherche.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                <?php echo e($videos->links()); ?>

            </div>
        <?php else: ?>
            <!-- Preview for non-subscribed users -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php for($i = 0; $i < 3; $i++): ?>
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden opacity-50">
                        <div class="relative aspect-video bg-gray-900 overflow-hidden">
                            <div class="w-full h-full bg-gradient-to-br from-gray-600 to-gray-800 flex items-center justify-center">
                                <i class="fas fa-lock text-4xl text-white/50"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/50">
                                <span class="text-white font-medium">Contenu Premium</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div>
                            <div class="h-3 bg-gray-200 rounded w-full mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/videos/index.blade.php ENDPATH**/ ?>