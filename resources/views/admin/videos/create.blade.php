@extends('layouts.app')

@section('title', 'Ajouter une vidéo - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Ajouter une vidéo</h1>
                    <p class="text-gray-600 mt-2">Créez une nouvelle vidéo de formation.</p>
                </div>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data" id="videoForm">
                @csrf
                
                <div class="p-6 space-y-6">
                    <!-- Informations de base -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-4">Informations générales</h3>
                        
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre de la vidéo *
                            </label>
                            <input type="text" name="title" id="title" 
                                   class="form-input w-full @error('title') border-red-500 @enderror" 
                                   value="{{ old('title') }}" 
                                   placeholder="Ex: Introduction à PHP" 
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description *
                            </label>
                            <textarea name="description" id="description" rows="4"
                                      class="form-textarea w-full @error('description') border-red-500 @enderror" 
                                      placeholder="Décrivez le contenu de la vidéo..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Niveau *
                                </label>
                                <select name="level" id="level" 
                                        class="form-select w-full @error('level') border-red-500 @enderror" 
                                        required>
                                    <option value="">Sélectionnez un niveau</option>
                                    <option value="debutant" {{ old('level') == 'debutant' ? 'selected' : '' }}>
                                        Débutant
                                    </option>
                                    <option value="intermediaire" {{ old('level') == 'intermediaire' ? 'selected' : '' }}>
                                        Intermédiaire
                                    </option>
                                    <option value="avance" {{ old('level') == 'avance' ? 'selected' : '' }}>
                                        Avancé
                                    </option>
                                </select>
                                @error('level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="access_level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Niveau d'accès *
                                </label>
                                <select name="access_level" id="access_level" 
                                        class="form-select w-full @error('access_level') border-red-500 @enderror" 
                                        required>
                                    <option value="">Sélectionnez un accès</option>
                                    <option value="free" {{ old('access_level') == 'free' ? 'selected' : '' }}>
                                        Gratuit
                                    </option>
                                    <option value="subscribed" {{ old('access_level') == 'subscribed' ? 'selected' : '' }}>
                                        Premium (Abonnement requis)
                                    </option>
                                </select>
                                @error('access_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                                   class="sr-only @error('video_file') border-red-500 @enderror"
                                                   accept="video/mp4,video/webm,video/ogg"
                                                   required>
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">MP4, WebM ou OGG jusqu'à 512MB</p>
                                </div>
                                <video id="videoPreview" class="hidden max-h-48 rounded-lg" controls></video>
                            </div>
                            @error('video_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                                   class="sr-only @error('thumbnail') border-red-500 @enderror"
                                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-gray-500">JPEG, PNG, JPG ou GIF jusqu'à 5MB</p>
                                </div>
                                <img id="thumbnailPreview" class="hidden max-h-48 rounded-lg object-cover" alt="Preview">
                            </div>
                            @error('thumbnail')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                        class="form-select w-full @error('lesson_id') border-red-500 @enderror">
                                    <option value="">Aucune leçon</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" {{ old('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                            {{ $lesson->title }}
                                            @if($lesson->course)
                                                ({{ $lesson->course->title }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('lesson_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ordre d'affichage
                                </label>
                                <input type="number" name="order" id="order" 
                                       class="form-input w-full @error('order') border-red-500 @enderror" 
                                       value="{{ old('order', 0) }}"
                                       min="0"
                                       placeholder="0">
                                @error('order')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Détermine l'ordre d'affichage (plus petit = plus haut)</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" 
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Vidéo active (visible par les utilisateurs)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">
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
@endsection

@push('scripts')
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
@endpush