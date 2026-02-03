

<?php $__env->startSection('title', 'Catégories - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Catégories de Formation</h1>
            <p class="text-gray-600 mt-2">Parcourez nos formations par catégorie.</p>
        </div>

        <!-- Categories Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center text-white text-xl">
                                <i class="<?php echo e($category->icon ?? 'fas fa-folder'); ?>"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900"><?php echo e($category->name); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo e($category->lessons_count ?? 0); ?> cours</p>
                            </div>
                        </div>

                        <?php if($category->description): ?>
                            <p class="text-gray-600 text-sm mb-4"><?php echo e(Str::limit($category->description, 100)); ?></p>
                        <?php endif; ?>

                        <div class="flex items-center justify-between">
                            <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                Voir les cours <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-folder text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune catégorie disponible</h3>
                    <p class="text-gray-500">Les catégories seront bientôt ajoutées.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/categories/index.blade.php ENDPATH**/ ?>