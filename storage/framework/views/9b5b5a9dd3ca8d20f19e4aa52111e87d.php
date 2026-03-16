

<?php $__env->startSection('title', 'Formations modulaires payantes'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Formations modulaires</h1>
            <p class="mt-2 text-gray-600">Un espace de formations payantes indépendant des contenus Free et Premium.</p>
            <?php if(auth()->guard()->guest()): ?>
                <p class="mt-2 text-sm text-gray-600">
                    Pour acheter une formation, <a href="<?php echo e(route('login')); ?>" class="text-primary-600 hover:underline">connectez-vous</a> ou <a href="<?php echo e(route('register')); ?>" class="text-primary-600 hover:underline">inscrivez-vous</a>.
                </p>
            <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $formations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $formation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <span class="badge badge-info"><?php echo e($formation->level_display); ?></span>
                    <span class="text-lg font-bold text-primary-700"><?php echo e(number_format($formation->price, 2, ',', ' ')); ?> €</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2"><?php echo e($formation->title); ?></h2>
                <p class="text-gray-600 text-sm mb-4"><?php echo e(Str::limit($formation->description, 140)); ?></p>
                <p class="text-sm text-gray-500 mb-2"><?php echo e($formation->modules_count); ?> module(s)</p>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(in_array($formation->id, $purchasedFormationIds, true)): ?>
                        <p class="text-xs text-success-700 mb-4">✅ Vous faites déjà partie de cette formation</p>
                    <?php endif; ?>
                <?php endif; ?>
                <a href="<?php echo e(route('formations.show', $formation->slug)); ?>" class="mt-auto btn btn-primary text-center">
                    Voir la formation
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full bg-white rounded-xl p-8 text-center text-gray-500">
                Aucune formation disponible pour le moment. Contactez un administrateur pour en ajouter.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/formations/index.blade.php ENDPATH**/ ?>