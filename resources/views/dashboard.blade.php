@extends('layouts.app')

@section('title', 'Tableau de bord - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Bonjour, {{ auth()->user()->name }} ! 👋
            </h1>
            <p class="text-gray-600 mt-2">
                Voici votre progression et les contenus recommandés pour vous.
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
                <div class="w-14 h-14 bg-success-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-2xl text-success-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['completed_exercises'] ?? 0 }}</div>
                    <div class="text-gray-500">Exercices réussis</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-star text-2xl text-primary-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total_points'] ?? 0 }}</div>
                    <div class="text-gray-500">Points gagnés</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
                <div class="w-14 h-14 bg-secondary-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-play-circle text-2xl text-secondary-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['watched_videos'] ?? 0 }}</div>
                    <div class="text-gray-500">Vidéos regardées</div>
                </div>
            </div>
        </div>

        <!-- Subscription Status -->
        @if(!auth()->user()->isSubscribed())
            <div class="bg-gradient-to-r from-secondary-500 to-primary-600 rounded-xl p-6 mb-8 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-xl font-bold mb-2">
                            <i class="fas fa-crown mr-2"></i> Passez à la version Premium
                        </h3>
                        <p class="text-white/90">Accédez à tous les exercices, leçons PDF et vidéos exclusives.</p>
                    </div>
                    <a href="{{ route('subscription.plans') }}" class="btn bg-white text-primary-700 hover:bg-gray-100">
                        Découvrir les offres
                    </a>
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Submissions -->
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Derniers exercices soumis</h3>
                        <a href="{{ route('profile') }}" class="text-primary-600 hover:text-primary-700 text-sm">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentSubmissions ?? [] as $submission)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $submission->exercise->title }}</h4>
                                    <p class="text-sm text-gray-500">
                                        Soumis le {{ $submission->submitted_at?->format('d/m/Y') ?? 'En cours' }}
                                    </p>
                                </div>
                                <span class="badge badge-{{ $submission->status_badge_color }}">
                                    {{ $submission->status_display }}
                                </span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                                <p>Vous n'avez pas encore soumis d'exercices.</p>
                                <a href="{{ route('exercises.index') }}" class="text-primary-600 hover:text-primary-700 mt-2 inline-block">
                                    Commencer un exercice
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Available Content -->
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Contenu recommandé</h3>
                    </div>
                    <div class="p-6">
                        <!-- Exercises -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Exercices</h4>
                            <div class="grid sm:grid-cols-2 gap-4">
                                @forelse($availableExercises ?? [] as $exercise)
                                    <a href="{{ route('exercises.show', $exercise->slug) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-code text-primary-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-medium text-gray-900 truncate">{{ $exercise->title }}</h5>
                                            <p class="text-sm text-gray-500">{{ $exercise->difficulty_display }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-gray-500 text-sm">Aucun exercice disponible.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Lessons -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Leçons</h4>
                            <div class="grid sm:grid-cols-2 gap-4">
                                @forelse($availableLessons ?? [] as $lesson)
                                    <a href="{{ route('lessons.show', $lesson->slug) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <div class="w-10 h-10 bg-secondary-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-book text-secondary-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="font-medium text-gray-900 truncate">{{ $lesson->title }}</h5>
                                            <p class="text-sm text-gray-500">{{ $lesson->level_display }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-gray-500 text-sm">Aucune leçon disponible.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Videos (Premium only) -->
                        @if(auth()->user()->isSubscribed())
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Vidéos</h4>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    @forelse($availableVideos ?? [] as $video)
                                        <a href="{{ route('videos.show', $video->slug) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                            <div class="w-10 h-10 bg-success-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-play text-success-600"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h5 class="font-medium text-gray-900 truncate">{{ $video->title }}</h5>
                                                <p class="text-sm text-gray-500">{{ $video->duration_display }}</p>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="text-gray-500 text-sm">Aucune vidéo disponible.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Progress -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Votre progression</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Exercices simples</span>
                                <span class="font-medium">{{ $exerciseProgress['simple'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-success-500 h-2 rounded-full" style="width: {{ min(100, ($exerciseProgress['simple'] ?? 0) * 10) }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Exercices complexes</span>
                                <span class="font-medium">{{ $exerciseProgress['complexe'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-secondary-500 h-2 rounded-full" style="width: {{ min(100, ($exerciseProgress['complexe'] ?? 0) * 5) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Videos -->
                @if(auth()->user()->isSubscribed())
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">Vidéos en cours</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($recentVideoProgress ?? [] as $progress)
                                <div class="px-6 py-4">
                                    <h4 class="font-medium text-gray-900 truncate">{{ $progress->video->title }}</h4>
                                    <div class="mt-2">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Progression</span>
                                            <span>{{ $progress->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-primary-500 h-1.5 rounded-full" style="width: {{ $progress->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-play-circle text-4xl mb-4 text-gray-300"></i>
                                    <p>Aucune vidéo en cours.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif

                <!-- Quick Links -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Liens rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('exercises.index') }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-laptop-code w-6"></i>
                            <span>Tous les exercices</span>
                        </a>
                        <a href="{{ route('lessons.index') }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-book w-6"></i>
                            <span>Toutes les leçons</span>
                        </a>
                        @if(auth()->user()->isSubscribed())
                            <a href="{{ route('videos.index') }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                                <i class="fas fa-video w-6"></i>
                                <span>Toutes les vidéos</span>
                            </a>
                        @endif
                        <a href="{{ route('profile') }}" class="flex items-center text-gray-600 hover:text-primary-600 transition-colors">
                            <i class="fas fa-user w-6"></i>
                            <span>Mon profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
