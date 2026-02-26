

<?php $__env->startSection('title', 'Modifier Exercice - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Modifier l'exercice</h1>
            <p class="text-gray-600 mt-2">Mettez à jour les informations de l'exercice.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="<?php echo e(route('admin.exercises.update', $exercise)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" name="title" class="form-input mt-1 w-full" value="<?php echo e(old('title', $exercise->title)); ?>" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3" class="form-textarea mt-1 w-full" required><?php echo e(old('description', $exercise->description)); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Difficulté</label>
                        <select name="difficulty" class="form-select mt-1 w-full" required>
                            <option value="simple" <?php echo e(old('difficulty', $exercise->difficulty) === 'simple' ? 'selected' : ''); ?>>Simple</option>
                            <option value="complexe" <?php echo e(old('difficulty', $exercise->difficulty) === 'complexe' ? 'selected' : ''); ?>>Complexe</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Accès</label>
                        <select name="access_level" class="form-select mt-1 w-full" required>
                            <option value="free" <?php echo e(old('access_level', $exercise->access_level) === 'free' ? 'selected' : ''); ?>>Gratuit</option>
                            <option value="subscribed" <?php echo e(old('access_level', $exercise->access_level) === 'subscribed' ? 'selected' : ''); ?>>Premium</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Langage</label>
                        <select name="programming_language" class="form-select mt-1 w-full" required>
                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('programming_language', $exercise->programming_language) === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instructions</label>
                        <textarea name="instructions" rows="4" class="form-textarea mt-1 w-full" required><?php echo e(old('instructions', $exercise->instructions)); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Indice</label>
                        <textarea name="hints" rows="3" class="form-textarea mt-1 w-full"><?php echo e(old('hints', $exercise->hints)); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Leçon associée</label>
                        <select name="lesson_id" class="form-select mt-1 w-full">
                            <option value="">Aucune</option>
                            <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lesson->id); ?>" <?php echo e((string) old('lesson_id', $exercise->lesson_id) === (string) $lesson->id ? 'selected' : ''); ?>><?php echo e($lesson->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ordre</label>
                            <input type="number" name="order" value="<?php echo e(old('order', $exercise->order)); ?>" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Points</label>
                            <input type="number" name="points" value="<?php echo e(old('points', $exercise->points)); ?>" min="1" max="100" class="form-input mt-1 w-full">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $exercise->is_active) ? 'checked' : ''); ?>

                               class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Activer l'exercice</span>
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="<?php echo e(route('admin.exercises.index')); ?>" class="btn bg-gray-100 text-gray-700">Retour</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/exercises/edit.blade.php ENDPATH**/ ?>