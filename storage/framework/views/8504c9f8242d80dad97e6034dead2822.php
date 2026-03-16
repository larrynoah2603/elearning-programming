

<?php $__env->startSection('title', 'Mes formations'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📚 Mes formations</h1>
        <p class="text-gray-600 mt-2">Suivez vos formations et votre progression.</p>
    </div>

    <?php if($enrollments->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-4 h-32 flex flex-col justify-between">
                        <div>
                            <p class="text-white font-bold text-lg truncate"><?php echo e($enrollment->formation->title); ?></p>
                            <span class="badge badge-info text-xs mt-2"><?php echo e(ucfirst($enrollment->formation->level)); ?></span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-4">
                        <!-- Description -->
                        <p class="text-sm text-gray-600 line-clamp-2">
                            <?php echo e($enrollment->formation->description); ?>

                        </p>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <p class="text-gray-500 text-xs">Modules</p>
                                <p class="font-bold text-gray-900"><?php echo e($enrollment->formation->modules->count()); ?></p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <p class="text-gray-500 text-xs">Quizzes</p>
                                <p class="font-bold text-gray-900"><?php echo e($enrollment->formation->quizzes->count()); ?></p>
                            </div>
                        </div>

                        <!-- Progress -->
                        <div>
                            <p class="text-xs text-gray-500 mb-2">Progression</p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Enrollment Info -->
                        <div class="bg-blue-50 rounded-lg p-3 text-xs text-blue-700 border border-blue-200">
                            <p><strong>Acheté le :</strong> <?php echo e(optional($enrollment->paid_at)->format('d/m/Y')); ?></p>
                            <p><strong>Ref :</strong> <?php echo e($enrollment->payment_reference); ?></p>
                        </div>

                        <!-- Button -->
                        <a href="<?php echo e(route('formations.show', $enrollment->formation->slug)); ?>" class="btn btn-primary w-full">
                            Continuer la formation
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-10 p-6 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-lg font-bold text-blue-900 mb-3">💡 Conseil</h3>
            <p class="text-blue-700 text-sm">
                Pour progresser rapidement, consacrez au moins 30 minutes par jour à votre formation. Commencez par les leçons, regardez les vidéos, puis pratiquez avec les exercices.
            </p>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📭</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucune formation achetée</h2>
            <p class="text-gray-600 mb-6">Vous n'avez pas encore acheté de formation. Découvrez nos formations disponibles.</p>
            <a href="<?php echo e(route('formations.index')); ?>" class="btn btn-primary">
                Parcourir les formations
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/formations/my-formations.blade.php ENDPATH**/ ?>