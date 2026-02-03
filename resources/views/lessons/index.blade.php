@extends('layouts.app')

@section('title', 'Leçons - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Leçons PDF</h1>
            <p class="text-gray-600 mt-2">Téléchargez nos leçons complètes pour apprendre à votre rythme.</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('lessons.index') }}" class="grid md:grid-cols-3 gap-4">
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
                        <p class="text-white/90">Certaines leçons sont réservées aux membres Premium.</p>
                    </div>
                    <a href="{{ route('subscription.plans') }}" class="btn bg-white text-primary-700 hover:bg-gray-100">
                        Devenir Premium
                    </a>
                </div>
            </div>
        @endif

        <!-- Lessons Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($lessons as $lesson)
                <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden {{ $lesson->access_level == 'subscribed' && (!auth()->check() || !auth()->user()->isSubscribed()) ? 'opacity-75' : '' }}">
                    <div class="h-40 bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center">
                        <i class="fas fa-file-pdf text-6xl text-white/50"></i>
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

                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $lesson->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($lesson->description, 120) }}</p>

                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            @if($lesson->page_count)
                                <span><i class="fas fa-file-alt mr-1"></i> {{ $lesson->page_count }} pages</span>
                            @endif
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
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune leçon trouvée</h3>
                    <p class="text-gray-500">Essayez de modifier vos critères de recherche.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $lessons->links() }}
        </div>
    </div>
</div>
@endsection
