@extends('layouts.app')

@section('title', 'Modifier une vidéo - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Modifier une vidéo</h1>
                <p class="text-gray-600 mt-2">Mettez à jour les informations de la vidéo.</p>
            </div>
            <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <form action="{{ route('admin.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                        <input type="text" name="title" id="title" class="form-input w-full" value="{{ old('title', $video->title) }}" required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" id="description" rows="4" class="form-textarea w-full" required>{{ old('description', $video->description) }}</textarea>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700 mb-2">Niveau *</label>
                            <select name="level" id="level" class="form-select w-full" required>
                                <option value="debutant" {{ old('level', $video->level) === 'debutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermediaire" {{ old('level', $video->level) === 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avance" {{ old('level', $video->level) === 'avance' ? 'selected' : '' }}>Avancé</option>
                            </select>
                        </div>
                        <div>
                            <label for="access_level" class="block text-sm font-medium text-gray-700 mb-2">Niveau d'accès *</label>
                            <select name="access_level" id="access_level" class="form-select w-full" required>
                                <option value="free" {{ old('access_level', $video->access_level) === 'free' ? 'selected' : '' }}>Gratuit</option>
                                <option value="subscribed" {{ old('access_level', $video->access_level) === 'subscribed' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="video_file" class="block text-sm font-medium text-gray-700 mb-2">Remplacer la vidéo</label>
                            <input type="file" name="video_file" id="video_file" class="form-input w-full" accept="video/mp4,video/webm,video/ogg">
                            @if($video->video_file)
                                <p class="text-xs text-gray-500 mt-1">Fichier actuel: {{ $video->video_file }}</p>
                            @endif
                        </div>
                        <div>
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Remplacer la miniature</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-input w-full" accept="image/*">
                            @if($video->thumbnail)
                                <p class="text-xs text-gray-500 mt-1">Miniature actuelle: {{ $video->thumbnail }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-6">
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Durée (secondes)</label>
                            <input type="number" name="duration" id="duration" class="form-input w-full" min="1" value="{{ old('duration', $video->duration) }}">
                        </div>
                        <div>
                            <label for="lesson_id" class="block text-sm font-medium text-gray-700 mb-2">Leçon associée</label>
                            <select name="lesson_id" id="lesson_id" class="form-select w-full">
                                <option value="">Aucune</option>
                                @foreach($lessons as $lesson)
                                    <option value="{{ $lesson->id }}" {{ (string) old('lesson_id', $video->lesson_id) === (string) $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Ordre</label>
                            <input type="number" name="order" id="order" class="form-input w-full" min="0" value="{{ old('order', $video->order) }}">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 text-primary-600 border-gray-300 rounded" {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Vidéo active</label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
