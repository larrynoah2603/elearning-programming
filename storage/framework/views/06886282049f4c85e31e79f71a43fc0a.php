

<?php $__env->startSection('title', 'Soumissions en attente - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Soumissions en attente</h1>
                <a href="<?php echo e(route('admin.submissions.index')); ?>" class="btn btn-outline">Toutes les soumissions</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Étudiant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exercice</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pré-correction IA</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Soumis le</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-4 py-3"><?php echo e($submission->user->name); ?></td>
                                <td class="px-4 py-3"><?php echo e($submission->exercise->title); ?></td>
                                <td class="px-4 py-3">
                                    <span class="badge badge-<?php echo e($submission->status_badge_color); ?>"><?php echo e($submission->status_display); ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if($submission->ai_score !== null): ?>
                                        <span class="text-sm text-gray-700"><?php echo e($submission->ai_score); ?>%</span>
                                        <?php if($submission->ai_requires_human_review): ?>
                                            <span class="badge badge-warning ml-2">Validation humaine</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-400">Non disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3"><?php echo e(optional($submission->submitted_at)->format('d/m/Y H:i') ?? '-'); ?></td>
                                <td class="px-4 py-3 text-right">
                                    <a href="<?php echo e(route('admin.submissions.correct', $submission)); ?>" class="btn btn-primary btn-sm">Corriger</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune soumission en attente.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6"><?php echo e($submissions->links()); ?></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/submissions/pending.blade.php ENDPATH**/ ?>