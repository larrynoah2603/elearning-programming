

<?php $__env->startSection('title', 'Gestion des Exercices - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des Exercices</h1>
                <p class="text-gray-600 mt-2">Gérez les exercices de la plateforme.</p>
            </div>

            <a href="<?php echo e(route('admin.exercises.create')); ?>"
               class="mt-4 md:mt-0 btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Nouvel exercice
            </a>
        </div>

        <!-- Exercises Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Niveau</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $exercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <?php echo e($exercise->title); ?>

                            </td>

                            <td class="px-6 py-4">
                                <span class="badge badge-<?php echo e($exercise->level_badge_color ?? 'secondary'); ?>">
                                    <?php echo e($exercise->level_display ?? ucfirst($exercise->level)); ?>

                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="badge badge-<?php echo e($exercise->is_active ? 'success' : 'danger'); ?>">
                                    <?php echo e($exercise->is_active ? 'Actif' : 'Inactif'); ?>

                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-3">
                                    <a href="<?php echo e(route('admin.exercises.edit', $exercise)); ?>"
                                       class="text-warning-600 hover:text-warning-900"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form method="POST"
                                          action="<?php echo e(route('admin.exercises.destroy', $exercise)); ?>"
                                          onsubmit="return confirm('Supprimer cet exercice ?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="text-danger-600 hover:text-danger-900"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-dumbbell text-4xl mb-4 text-gray-300"></i>
                                <p>Aucun exercice trouvé.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($exercises->links()); ?>

        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/exercises/index.blade.php ENDPATH**/ ?>