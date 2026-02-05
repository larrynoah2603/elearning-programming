@extends('layouts.app')

@section('title', $category->name . ' - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Avertissement pour les leçons premium non accessibles -->
        @if(($hasPremiumLessons ?? false) && (!auth()->check() || !auth()->user()?->isSubscribed()))
            <div class="bg-gradient-to-r from-warning-500 to-orange-600 rounded-xl p-6 mb-6 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-bold mb-2 flex items-center">
                            <i class="fas fa-crown mr-2 text-yellow-300"></i> Contenu Premium
                        </h3>
                        <p class="text-white/90">
                            Certaines leçons sont réservées aux membres Premium. 
                            <span class="font-semibold">Vous pouvez la prévisualiser mais le téléchargement est limité.</span>
                        </p>
                    </div>
                    <a href="{{ route('subscription.plans') }}" class="btn bg-white text-warning-700 hover:bg-gray-100">
                        <i class="fas fa-crown mr-2"></i> Devenir Premium
                    </a>
                </div>
            </div>
        @endif

        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <!-- ... reste du code inchangé ... -->
            <a href="{{ route('home') }}" class="hover:text-primary-600">Accueil</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="{{ route('categories.index') }}" class="hover:text-primary-600">Catégories</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">{{ $category->name }}</span>
        </nav>

        <!-- Category Header -->
        <div class="bg-gradient-to-r from-primary-500 to-secondary-600 rounded-xl p-8 mb-8 text-white">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-3xl mr-6">
                    <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $category->name }}</h1>
                    <p class="text-white/90">{{ $category->description }}</p>
                    <p class="text-white/80 text-sm mt-2">
                        <i class="fas fa-graduation-cap mr-1"></i> {{ $category->lessons_count ?? 0 }} leçons disponibles
                    </p>
                </div>
            </div>
        </div>

        <!-- Barre de recherche et filtres -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="space-y-4">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       class="form-input pl-10 w-full" 
                                       placeholder="Titre ou description...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                            <select name="level" class="form-select w-full">
                                <option value="all">Tous les niveaux</option>
                                <option value="debutant" {{ request('level') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermediaire" {{ request('level') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avance" {{ request('level') == 'avance' ? 'selected' : '' }}>Avancé</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="btn btn-primary w-full">
                                <i class="fas fa-filter mr-2"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Leçons dans cette catégorie -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Leçons dans cette catégorie</h2>
                <a href="{{ route('lessons.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                    Voir toutes les leçons <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @php
                // $lessons devrait être passé par le contrôleur
                $lessons = $lessons ?? collect();
            @endphp

            @if($lessons->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($lessons as $lesson)
                        <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden {{ $lesson->access_level == 'subscribed' && (!auth()->check() || !auth()->user()->isSubscribed()) ? 'opacity-75' : '' }}">
                            <div class="h-40 bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center">
                                <i class="fas fa-file-pdf text-5xl text-white/50"></i>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="badge badge-{{ $lesson->level_badge_color }}">
                                        {{ $lesson->level_display }}
                                    </span>
                                    @if($lesson->access_level == 'subscribed')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-crown mr-1"></i> Premium
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg font-bold text-gray-900 mb-2">
                                    <a href="{{ route('lessons.show', $lesson->slug) }}" class="hover:text-primary-600 transition-colors">
                                        {{ $lesson->title }}
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($lesson->description, 100) }}</p>

                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    @if($lesson->page_count)
                                        <span><i class="fas fa-file-alt mr-1"></i> {{ $lesson->page_count }} pages</span>
                                    @endif
                                    <span>
                                        <i class="fas fa-dumbbell mr-1"></i> {{ $lesson->exercises_count ?? 0 }} exercices
                                    </span>
                                </div>

                                @if($lesson->access_level == 'subscribed' && (!auth()->check() || !auth()->user()->isSubscribed()))
                                    <a href="{{ route('subscription.plans') }}" class="btn btn-secondary w-full">
                                        <i class="fas fa-lock mr-2"></i> Débloquer
                                    </a>
                                @else
                                    <a href="{{ route('lessons.show', $lesson->slug) }}" class="btn btn-primary w-full">
                                        <i class="fas fa-book-reader mr-2"></i> Lire la leçon
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                    <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">
                        @if(request('search'))
                            Aucune leçon trouvée pour "{{ request('search') }}"
                        @else
                            Aucune leçon disponible dans cette catégorie
                        @endif
                    </h3>
                    <p class="text-gray-500">Les leçons seront bientôt ajoutées.</p>
                    @if(request('search'))
                        <a href="{{ route('categories.show', $category->slug) }}" class="mt-4 inline-block text-primary-600 hover:text-primary-700">
                            Voir toutes les leçons
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Suggestions d'autres catégories -->
        @php
            $otherCategories = \App\Models\Category::active()
                ->where('id', '!=', $category->id)
                ->withCount(['lessons' => function($q) {
                    $q->active();
                }])
                ->orderBy('lessons_count', 'desc')
                ->take(3)
                ->get();
        @endphp

        @if($otherCategories->count() > 0)
            <div class="bg-gray-50 rounded-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Autres catégories qui pourraient vous intéresser</h3>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($otherCategories as $otherCategory)
                        <a href="{{ route('categories.show', $otherCategory->slug) }}" 
                           class="card-hover group bg-white rounded-xl p-6 flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-xl flex items-center justify-center text-white text-xl">
                                <i class="{{ $otherCategory->icon ?? 'fas fa-folder' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 group-hover:text-primary-600 transition-colors">
                                    {{ $otherCategory->name }}
                                </h4>
                                <p class="text-gray-500 text-sm">{{ $otherCategory->lessons_count }} leçons</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection