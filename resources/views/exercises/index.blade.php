@extends('layouts.app')

@section('title', 'Exercices - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Exercices de Programmation</h1>
            <p class="text-gray-600 mt-2">Pratiquez et améliorez vos compétences avec nos exercices.</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('exercises.index') }}" class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="form-input pl-10 w-full" placeholder="Titre ou description...">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Difficulté</label>
                    <select name="difficulty" class="form-select w-full">
                        <option value="all">Toutes les difficultés</option>
                        <option value="simple" {{ request('difficulty') == 'simple' ? 'selected' : '' }}>Simple</option>
                        <option value="complexe" {{ request('difficulty') == 'complexe' ? 'selected' : '' }}>Complexe</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Langage</label>
                    <select name="language" class="form-select w-full">
                        <option value="all">Tous les langages</option>
                        @foreach($languages as $key => $name)
                            <option value="{{ $key }}" {{ request('language') == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Premium Notice -->
        @if(!auth()->check() || !auth()->user()->isSubscribed())
            <div class="bg-gradient-to-r from-secondary-500 to-primary-600 rounded-xl p-6 mb-8 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-bold mb-2">
                            <i class="fas fa-lock mr-2"></i> Contenu Premium
                        </h3>
                        <p class="text-white/90">Les exercices complexes sont réservés aux membres Premium.</p>
                    </div>
                    <a href="{{ route('subscription.plans') }}" class="btn bg-white text-primary-700 hover:bg-gray-100">
                        Devenir Premium
                    </a>
                </div>
            </div>
        @endif

        <!-- Exercises Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($exercises as $exercise)
                <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden {{ $exercise->access_level == 'subscribed' && (!auth()->check() || !auth()->user()->isSubscribed()) ? 'opacity-75' : '' }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <span class="badge badge-{{ $exercise->difficulty_badge_color }}">
                                    {{ $exercise->difficulty_display }}
                                </span>
                                @if($exercise->access_level == 'subscribed')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-crown mr-1"></i> Premium
                                    </span>
                                @endif
                            </div>
                            <i class="fab fa-{{ $exercise->programming_language }} text-2xl text-gray-400"></i>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $exercise->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($exercise->description, 120) }}</p>

                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-star text-yellow-400 mr-1"></i> {{ $exercise->points }} pts</span>
                            @if($exercise->estimated_time)
                                <span><i class="fas fa-clock mr-1"></i> {{ $exercise->estimated_time_display }}</span>
                            @endif
                        </div>

                        @if($exercise->access_level == 'subscribed' && (!auth()->check() || !auth()->user()->isSubscribed()))
                            <a href="{{ route('subscription.plans') }}" class="btn btn-secondary w-full">
                                <i class="fas fa-lock mr-2"></i> Débloquer
                            </a>
                        @else
                            <a href="{{ route('exercises.show', $exercise->slug) }}" class="btn btn-primary w-full">
                                <i class="fas fa-play mr-2"></i> Commencer
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucun exercice trouvé</h3>
                    <p class="text-gray-500">Essayez de modifier vos critères de recherche.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $exercises->links() }}
        </div>
    </div>
</div>
@endsection
