

<?php $__env->startSection('title', 'Modifier catégorie - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier la catégorie</h1>
    <form method="POST" action="<?php echo e(route('admin.categories.update', $category)); ?>" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PUT'); ?>
      <div>
        <label class="block text-sm font-medium text-gray-700">Nom</label>
        <input name="name" value="<?php echo e(old('name', $category->name)); ?>" class="form-input mt-1" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" class="form-textarea mt-1"><?php echo e(old('description', $category->description)); ?></textarea>
      </div>
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Icône (fa-...)</label>
          <input name="icon" value="<?php echo e(old('icon', $category->icon)); ?>" class="form-input mt-1">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Ordre</label>
          <input name="order" type="number" value="<?php echo e(old('order', $category->order)); ?>" class="form-input mt-1">
        </div>
      </div>
      <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $category->is_active) ? 'checked' : ''); ?>> Active</label>
      <div class="flex gap-3">
        <button class="btn btn-primary" type="submit">Enregistrer</button>
        <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn bg-gray-100 text-gray-700">Annuler</a>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/categories/edit.blade.php ENDPATH**/ ?>