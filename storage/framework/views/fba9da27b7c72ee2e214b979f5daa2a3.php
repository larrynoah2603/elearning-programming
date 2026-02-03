

<?php $__env->startSection('title', 'Ajouter une vidéo - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Ajouter une vidéo</h1>
                    <p class="text-gray-600 mt-2">Créez une nouvelle vidéo de formation.</p>
                </div>
                <a href="<?php echo e(route('admin.videos.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <form action="<?php echo e(route('admin.videos.store')); ?>" method="POST" enctype="multipart/form-data" id="videoForm">
                <?php echo csrf_field(); ?>
                
                <div class="p-6 space-y-6">
                    <!-- Informations de base -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Informations générales</h3>
                        
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre de la vidéo *
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="form-input w-full <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('title')); ?>" 
                                   placeholder="Ex: Introduction à PHP" 
                                   required>
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description *
                            </label>
                            <textarea name="description" id="description" rows="4"
                                      class="form-textarea w-full <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      placeholder="Décrivez le contenu de la vidéo..."
                                      required><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Niveau *
                                </label>
                                <select name="level" id="level" 
                                        class="form-select w-full <?php $__errorArgs = ['level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        required>
                                    <option value="">Sélectionnez un niveau</option>
                                    <option value="debutant" <?php echo e(old('level') == 'debutant' ? 'selected' : ''); ?>>
                                        Débutant
                                    </option>
                                    <option value="intermediaire" <?php echo e(old('level') == 'intermediaire' ? 'selected' : ''); ?>>
                                        Intermédiaire
                                    </option>
                                    <option value="avance" <?php echo e(old('level') == 'avance' ? 'selected' : ''); ?>>
                                        Avancé
                                    </option>
                                </select>
                                <?php $__errorArgs = ['level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="access_level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Niveau d'accès *
                                </label>
                                <select name="access_level" id="access_level" 
                                        class="form-select w-full <?php $__errorArgs = ['access_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        required>
                                    <option value="">Sélectionnez un accès</option>
                                    <option value="free" <?php echo e(old('access_level') == 'free' ? 'selected' : ''); ?>>
                                        Gratuit
                                    </option>
                                    <option value="subscribed" <?php echo e(old('access_level') == 'subscribed' ? 'selected' : ''); ?>>
                                        Premium (Abonnement requis)
                                    </option>
                                </select>
                                <?php $__errorArgs = ['access_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Fichiers -->
                    <div class="space-y-6 pt-6 border-t">
                        <h3 class="text-lg font-medium text-gray-900">Fichiers multimédias</h3>
                        
                        <div>
                            <label for="video_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Fichier vidéo *
                                <span class="text-gray-500 text-sm font-normal">(Max: 512MB, Formats: MP4, WebM, OGG)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-500 transition-colors relative" id="videoDropZone">
                                <div class="space-y-1 text-center" id="videoPreviewContainer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="video_file" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span id="videoFileName">Télécharger un fichier</span>
                                            <input id="video_file" name="video_file" type="file" 
                                                   class="sr-only <?php $__errorArgs = ['video_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   accept="video/mp4,video/webm,video/ogg"
                                                   required>
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">MP4, WebM ou OGG jusqu'à 512MB</p>
                                </div>
                                <video id="videoPreview" class="hidden max-h-48 rounded-lg" controls></video>
                            </div>
                            <?php $__errorArgs = ['video_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <input type="hidden" name="duration" id="duration">
                        </div>

                        <div>
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">
                                Miniature (Image)
                                <span class="text-gray-500 text-sm font-normal">(Optionnel, Max: 5MB, Formats: JPEG, PNG, JPG, GIF)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-500 transition-colors relative" id="thumbnailDropZone">
                                <div class="space-y-1 text-center" id="thumbnailPreviewContainer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                            <span id="thumbnailFileName">Télécharger une image</span>
                                            <input id="thumbnail" name="thumbnail" type="file" 
                                                   class="sr-only <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">JPEG, PNG, JPG ou GIF jusqu'à 5MB</p>
                                </div>
                                <img id="thumbnailPreview" class="hidden max-h-48 rounded-lg object-cover" alt="Preview">
                            </div>
                            <?php $__errorArgs = ['thumbnail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Paramètres avancés -->
                    <div class="space-y-6 pt-6 border-t">
                        <h3 class="text-lg font-medium text-gray-900">Paramètres avancés</h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="lesson_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Leçon associée (optionnel)
                                </label>
                                <select name="lesson_id" id="lesson_id" 
                                        class="form-select w-full <?php $__errorArgs = ['lesson_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Aucune leçon</option>
                                    <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($lesson->id); ?>" <?php echo e(old('lesson_id') == $lesson->id ? 'selected' : ''); ?>>
                                            <?php echo e($lesson->title); ?>

                                            <?php if($lesson->course): ?>
                                                (<?php echo e($lesson->course->title); ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['lesson_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ordre d'affichage
                                </label>
                                <input type="number" name="order" id="order" 
                                       class="form-input w-full <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       value="<?php echo e(old('order', 0)); ?>"
                                       min="0"
                                       placeholder="0">
                                <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="mt-1 text-sm text-gray-500">Détermine l'ordre d'affichage (plus petit = plus haut)</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                   <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Vidéo active (visible par les utilisateurs)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <a href="<?php echo e(route('admin.videos.index')); ?>" class="btn btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save mr-2"></i> Créer la vidéo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Video file handling
    const videoInput = document.getElementById('video_file');
    const videoFileName = document.getElementById('videoFileName');
    const videoPreview = document.getElementById('videoPreview');
    const videoPreviewContainer = document.getElementById('videoPreviewContainer');
    const videoDropZone = document.getElementById('videoDropZone');
    const durationInput = document.getElementById('duration');

    videoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            videoFileName.textContent = file.name;
            
            // Create preview
            const url = URL.createObjectURL(file);
            videoPreview.src = url;
            videoPreview.classList.remove('hidden');
            videoPreviewContainer.classList.add('hidden');
            
            // Get duration when metadata is loaded
            videoPreview.onloadedmetadata = function() {
                durationInput.value = Math.round(videoPreview.duration);
                URL.revokeObjectURL(url);
            };
        }
    });

    // Drag and drop for video
    videoDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        videoDropZone.classList.add('border-primary-500');
    });

    videoDropZone.addEventListener('dragleave', () => {
        videoDropZone.classList.remove('border-primary-500');
    });

    videoDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        videoDropZone.classList.remove('border-primary-500');
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('video/')) {
            videoInput.files = files;
            videoInput.dispatchEvent(new Event('change'));
        }
    });

    // Thumbnail handling
    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailFileName = document.getElementById('thumbnailFileName');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const thumbnailPreviewContainer = document.getElementById('thumbnailPreviewContainer');
    const thumbnailDropZone = document.getElementById('thumbnailDropZone');

    thumbnailInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            thumbnailFileName.textContent = file.name;
            
            // Create preview
            const url = URL.createObjectURL(file);
            thumbnailPreview.src = url;
            thumbnailPreview.classList.remove('hidden');
            thumbnailPreviewContainer.classList.add('hidden');
            
            thumbnailPreview.onload = function() {
                URL.revokeObjectURL(url);
            };
        }
    });

    // Drag and drop for thumbnail
    thumbnailDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        thumbnailDropZone.classList.add('border-primary-500');
    });

    thumbnailDropZone.addEventListener('dragleave', () => {
        thumbnailDropZone.classList.remove('border-primary-500');
    });

    thumbnailDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        thumbnailDropZone.classList.remove('border-primary-500');
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            thumbnailInput.files = files;
            thumbnailInput.dispatchEvent(new Event('change'));
        }
    });

    // Form validation
    document.getElementById('videoForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création en cours...';
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/videos/create.blade.php ENDPATH**/ ?>