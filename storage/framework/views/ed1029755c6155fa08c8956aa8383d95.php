

<?php $__env->startSection('title', 'Modifier une formation'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Modifier la formation</h1>
    </div>

    <form method="POST" action="<?php echo e(route('admin.formations.update', $formation)); ?>" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('admin.formations._form', ['formation' => $formation], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/formations/edit.blade.php ENDPATH**/ ?>