

<?php $__env->startSection('title', 'Catégories - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Gestion des catégories</h1>
            <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">Ajouter</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Leçons</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($category->name); ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($category->order ?? 0); ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700"><?php echo e($category->lessons_count); ?></td>
                            <td class="px-4 py-3">
                                <span class="badge <?php echo e($category->is_active ? 'badge-success' : 'badge-warning'); ?>">
                                    <?php echo e($category->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="text-primary-600 hover:text-primary-800">Modifier</a>
                                <form method="POST" action="<?php echo e(route('admin.categories.toggle', $category)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-warning-600 hover:text-warning-800">Basculer</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>" class="inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-danger-600 hover:text-danger-800">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Aucune catégorie.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="p-4"><?php echo e($categories->links()); ?></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>