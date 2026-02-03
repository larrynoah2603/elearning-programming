@extends('layouts.app')

@section('title', 'Catégories - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Catégories de Formation</h1>
            <p class="text-gray-600 mt-2">Parcourez nos formations par catégorie.</p>
        </div>

        <!-- Categories Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
                <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center text-white text-xl">
                                <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $category->lessons_count ?? 0 }} cours</p>
                            </div>
                        </div>

                        @if($category->description)
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($category->description, 100) }}</p>
                        @endif

                        <div class="flex items-center justify-between">
                            <a href="{{ route('categories.show', $category->slug) }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                                Voir les cours <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-folder text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune catégorie disponible</h3>
                    <p class="text-gray-500">Les catégories seront bientôt ajoutées.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection