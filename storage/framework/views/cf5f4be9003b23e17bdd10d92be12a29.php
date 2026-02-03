<?php $__env->startSection('title', $lesson->title . ' - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-primary-600">Accueil</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="<?php echo e(route('lessons.index')); ?>" class="hover:text-primary-600">Leçons</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900"><?php echo e($lesson->title); ?></span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Lesson Header -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="badge badge-<?php echo e($lesson->level_badge_color); ?>">
                                    <?php echo e($lesson->level_display); ?>

                                </span>
                                <?php if($lesson->access_level == 'subscribed'): ?>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-crown mr-1"></i> Premium
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900"><?php echo e($lesson->title); ?></h1>
                        </div>
                    </div>
                    
                    <p class="text-gray-600"><?php echo e($lesson->description); ?></p>

                    <div class="mt-6 flex items-center space-x-4">
                        <a href="<?php echo e(route('lessons.download', $lesson)); ?>" class="btn btn-primary">
                            <i class="fas fa-download mr-2"></i> Télécharger le PDF
                        </a>
                        <?php if($lesson->page_count): ?>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-file-alt mr-1"></i> <?php echo e($lesson->page_count); ?> pages
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- PDF Viewer -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-book-open mr-2 text-primary-500"></i> Contenu de la leçon
                    </h2>
                    <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-file-pdf text-6xl text-danger-500 mb-4"></i>
                            <p class="text-gray-600 mb-4">Prévisualisation du PDF</p>
                            <a href="<?php echo e(route('lessons.download', $lesson)); ?>" class="btn btn-primary">
                                <i class="fas fa-download mr-2"></i> Télécharger pour voir
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Exercises -->
                <?php if($lesson->exercises->count() > 0): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-laptop-code mr-2 text-primary-500"></i> Exercices associés
                        </h2>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $lesson->exercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('exercises.show', $exercise->slug)); ?>" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div>
                                        <div class="font-medium text-gray-900"><?php echo e($exercise->title); ?></div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            <span class="badge badge-<?php echo e($exercise->difficulty_badge_color); ?> text-xs">
                                                <?php echo e($exercise->difficulty_display); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-primary-600">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Lesson Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Informations</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Niveau</span>
                            <span class="font-medium"><?php echo e($lesson->level_display); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Accès</span>
                            <span class="font-medium"><?php echo e($lesson->access_level_display); ?></span>
                        </div>
                        <?php if($lesson->page_count): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Pages</span>
                                <span class="font-medium"><?php echo e($lesson->page_count); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Exercices</span>
                            <span class="font-medium"><?php echo e($lesson->exercises_count); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Vidéos</span>
                            <span class="font-medium"><?php echo e($lesson->videos_count); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Videos -->
                <?php if($lesson->videos->count() > 0 && auth()->user()?->isSubscribed()): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">
                            <i class="fas fa-video mr-2 text-secondary-500"></i> Vidéos
                        </h3>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $lesson->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('videos.show', $video->slug)); ?>" class="block">
                                    <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden mb-2">
                                        <?php if($video->thumbnail): ?>
                                            <img src="<?php echo e($video->thumbnail_url); ?>" alt="<?php echo e($video->title); ?>" class="w-full h-full object-cover">
                                        <?php endif; ?>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                                <i class="fas fa-play text-white"></i>
                                            </div>
                                        </div>
                                        <?php if($video->duration): ?>
                                            <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                                <?php echo e($video->duration_display); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="font-medium text-gray-900 text-sm"><?php echo e($video->title); ?></div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Author -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Auteur</h3>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            <?php echo e(substr($lesson->user->name, 0, 1)); ?>

                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900"><?php echo e($lesson->user->name); ?></div>
                            <div class="text-sm text-gray-500">Formateur</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/lessons/show.blade.php ENDPATH**/ ?>