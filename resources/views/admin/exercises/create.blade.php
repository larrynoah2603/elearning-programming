@extends('layouts.app')

@section('title', 'Nouvel Exercice - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Nouvel Exercice</h1>
            <p class="text-gray-600 mt-2">Créer un exercice comme pour les leçons.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('admin.exercises.store') }}">
                @csrf

                <div class="space-y-6">

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" name="title" class="form-input mt-1 w-full" required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3" class="form-textarea mt-1 w-full" required></textarea>
                    </div>

                    <!-- Difficulty -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Difficulté</label>
                        <select name="difficulty" class="form-select mt-1 w-full" required>
                            <option value="">-- Choisir --</option>
                            <option value="simple">Simple</option>
                            <option value="complexe">Complexe</option>
                        </select>
                    </div>

                    <!-- Access Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Accès</label>
                        <select name="access_level" class="form-select mt-1 w-full" required>
                            <option value="free">Gratuit</option>
                            <option value="subscribed">Premium</option>
                        </select>
                    </div>

                    <!-- Programming Language -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Langage</label>
                        <select name="programming_language" class="form-select mt-1 w-full" required>
                            @foreach($languages as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Instructions -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instructions</label>
                        <textarea name="instructions" rows="4" class="form-textarea mt-1 w-full" required></textarea>
                    </div>

                    <!-- Lesson -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Leçon associée</label>
                        <select name="lesson_id" class="form-select mt-1 w-full">
                            <option value="">Aucune</option>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ordre</label>
                        <input type="number" name="order" value="0" class="form-input mt-1 w-full">
                    </div>

                    <!-- Active -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Activer l'exercice</span>
                    </div>

                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('admin.exercises.index') }}" class="btn bg-gray-100 text-gray-700">
                        Retour
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Créer l'exercice
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
