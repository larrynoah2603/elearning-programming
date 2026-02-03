<?php $__env->startSection('title', 'Nouvelle Leçon - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Nouvelle Leçon</h1>
            <p class="text-gray-600 mt-2">Créez une nouvelle leçon PDF pour vos élèves.</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="<?php echo e(route('admin.lessons.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" id="title" name="title" value="<?php echo e(old('title')); ?>" 
                            class="form-input mt-1 w-full" required>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" 
                            class="form-textarea mt-1 w-full" required><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Level -->
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700">Niveau</label>
                            <select id="level" name="level" class="form-select mt-1 w-full" required>
                                <option value="debutant" <?php echo e(old('level') == 'debutant' ? 'selected' : ''); ?>>Débutant</option>
                                <option value="intermediaire" <?php echo e(old('level') == 'intermediaire' ? 'selected' : ''); ?>>Intermédiaire</option>
                                <option value="avance" <?php echo e(old('level') == 'avance' ? 'selected' : ''); ?>>Avancé</option>
                            </select>
                            <?php $__errorArgs = ['level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Access Level -->
                        <div>
                            <label for="access_level" class="block text-sm font-medium text-gray-700">Niveau d'accès</label>
                            <select id="access_level" name="access_level" class="form-select mt-1 w-full" required>
                                <option value="free" <?php echo e(old('access_level') == 'free' ? 'selected' : ''); ?>>Gratuit</option>
                                <option value="subscribed" <?php echo e(old('access_level') == 'subscribed' ? 'selected' : ''); ?>>Premium</option>
                            </select>
                            <?php $__errorArgs = ['access_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- PDF File -->
                    <div>
                        <label for="pdf_file" class="block text-sm font-medium text-gray-700">Fichier PDF</label>
                        <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" 
                            class="form-input mt-1 w-full" required>
                        <p class="mt-1 text-sm text-gray-500">Taille maximale : 10 Mo</p>
                        <?php $__errorArgs = ['pdf_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catégories</label>
                        <div class="grid md:grid-cols-3 gap-3">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="categories[]" value="<?php echo e($category->id); ?>" 
                                        <?php echo e(in_array($category->id, old('categories', [])) ? 'checked' : ''); ?>

                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm"><?php echo e($category->name); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700">Ordre d'affichage</label>
                            <input type="number" id="order" name="order" value="<?php echo e(old('order', 0)); ?>" 
                                class="form-input mt-1 w-full">
                            <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Active -->
                        <div class="flex items-center mt-8">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                <?php echo e(old('is_active', true) ? 'checked' : ''); ?>

                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Activer la leçon</label>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <a href="<?php echo e(route('admin.lessons.index')); ?>" class="btn bg-gray-100 text-gray-700 hover:bg-gray-200">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Créer la leçon
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/lessons/create.blade.php ENDPATH**/ ?>