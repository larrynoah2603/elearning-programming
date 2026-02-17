@extends('layouts.app')

@section('title', 'Modifier Exercice - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Modifier l'exercice</h1>
            <p class="text-gray-600 mt-2">Mettez à jour les informations de l'exercice.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('admin.exercises.update', $exercise) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" name="title" class="form-input mt-1 w-full" value="{{ old('title', $exercise->title) }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3" class="form-textarea mt-1 w-full" required>{{ old('description', $exercise->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Difficulté</label>
                        <select name="difficulty" class="form-select mt-1 w-full" required>
                            <option value="simple" {{ old('difficulty', $exercise->difficulty) === 'simple' ? 'selected' : '' }}>Simple</option>
                            <option value="complexe" {{ old('difficulty', $exercise->difficulty) === 'complexe' ? 'selected' : '' }}>Complexe</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Accès</label>
                        <select name="access_level" class="form-select mt-1 w-full" required>
                            <option value="free" {{ old('access_level', $exercise->access_level) === 'free' ? 'selected' : '' }}>Gratuit</option>
                            <option value="subscribed" {{ old('access_level', $exercise->access_level) === 'subscribed' ? 'selected' : '' }}>Premium</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Langage</label>
                        <select name="programming_language" class="form-select mt-1 w-full" required>
                            @foreach($languages as $key => $label)
                                <option value="{{ $key }}" {{ old('programming_language', $exercise->programming_language) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instructions</label>
                        <textarea name="instructions" rows="4" class="form-textarea mt-1 w-full" required>{{ old('instructions', $exercise->instructions) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Indice</label>
                        <textarea name="hints" rows="3" class="form-textarea mt-1 w-full">{{ old('hints', $exercise->hints) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Leçon associée</label>
                        <select name="lesson_id" class="form-select mt-1 w-full">
                            <option value="">Aucune</option>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}" {{ (string) old('lesson_id', $exercise->lesson_id) === (string) $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ordre</label>
                            <input type="number" name="order" value="{{ old('order', $exercise->order) }}" class="form-input mt-1 w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Points</label>
                            <input type="number" name="points" value="{{ old('points', $exercise->points) }}" min="1" max="100" class="form-input mt-1 w-full">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $exercise->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Activer l'exercice</span>
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('admin.exercises.index') }}" class="btn bg-gray-100 text-gray-700">Retour</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
