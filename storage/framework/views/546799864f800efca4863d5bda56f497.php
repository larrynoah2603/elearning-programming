

<?php $__env->startSection('title', 'Accès à la formation'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100 text-center">
        <div class="mb-6">
            <svg class="mx-auto h-16 w-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-4">Accès validé</h1>
        <p class="text-gray-600 mb-6">Votre paiement a été confirmé. Vous pouvez désormais accéder à cette formation indépendamment de votre abonnement.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="font-semibold text-gray-900"><?php echo e($formation->title); ?></p>
            <p class="text-primary-700 text-xl font-bold mt-2"><?php echo e(number_format($formation->price, 2, ',', ' ')); ?> €</p>
            <p class="text-sm text-gray-500 mt-1">Référence : <?php echo e($enrollment->payment_reference); ?></p>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="space-y-3">
            <a href="<?php echo e(route('formations.show', $formation->slug)); ?>" class="btn btn-primary w-full">Voir le programme</a>
            <a href="<?php echo e(route('formations.my')); ?>" class="btn btn-secondary w-full">Mes formations</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/formations/access.blade.php ENDPATH**/ ?>