

<?php $__env->startSection('title', 'Corriger une soumission - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Correction de soumission</h1>
            <p class="text-gray-600"><?php echo e($submission->user->name); ?> · <?php echo e($submission->exercise->title); ?></p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Code soumis</h2>
            <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code><?php echo e($submission->submitted_code); ?></code></pre>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="<?php echo e(route('admin.submissions.submit', $submission)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Score (0-100)</label>
                    <input type="number" name="score" min="0" max="100" value="<?php echo e(old('score', $submission->score ?? 0)); ?>" class="form-input w-full" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                    <textarea name="feedback" rows="5" class="form-textarea w-full" placeholder="Commentaires de correction..."><?php echo e(old('feedback', $submission->feedback)); ?></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="<?php echo e(route('admin.submissions.pending')); ?>" class="btn btn-outline">Retour</a>
                    <button type="submit" class="btn btn-primary">Enregistrer la correction</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/submissions/correct.blade.php ENDPATH**/ ?>