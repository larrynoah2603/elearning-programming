@extends('layouts.app')

@section('title', 'Nouvelle Leçon - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Nouvelle Leçon</h1>
            <p class="text-gray-600 mt-2">Créez une nouvelle leçon PDF pour vos élèves.</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" 
                            class="form-input mt-1 w-full" required>
                        @error('title')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" 
                            class="form-textarea mt-1 w-full" required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Level -->
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700">Niveau</label>
                            <select id="level" name="level" class="form-select mt-1 w-full" required>
                                <option value="debutant" {{ old('level') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermediaire" {{ old('level') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avance" {{ old('level') == 'avance' ? 'selected' : '' }}>Avancé</option>
                            </select>
                            @error('level')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Access Level -->
                        <div>
                            <label for="access_level" class="block text-sm font-medium text-gray-700">Niveau d'accès</label>
                            <select id="access_level" name="access_level" class="form-select mt-1 w-full" required>
                                <option value="free" {{ old('access_level') == 'free' ? 'selected' : '' }}>Gratuit</option>
                                <option value="subscribed" {{ old('access_level') == 'subscribed' ? 'selected' : '' }}>Premium</option>
                            </select>
                            @error('access_level')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- PDF File -->
                    <div>
                        <label for="pdf_file" class="block text-sm font-medium text-gray-700">Fichier PDF</label>
                        <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" 
                            class="form-input mt-1 w-full" required>
                        <p class="mt-1 text-sm text-gray-500">Taille maximale : 10 Mo</p>
                        @error('pdf_file')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catégories</label>
                        <div class="grid md:grid-cols-3 gap-3">
                            @foreach($categories as $category)
                                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                        {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('categories')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Order -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700">Ordre d'affichage</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" 
                                class="form-input mt-1 w-full">
                            @error('order')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active -->
                        <div class="flex items-center mt-8">
                            <input type="checkbox" id="is_active" name="is_active" value="1" 
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Activer la leçon</label>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-between">
                    <a href="{{ route('admin.lessons.index') }}" class="btn bg-gray-100 text-gray-700 hover:bg-gray-200">
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
@endsection
