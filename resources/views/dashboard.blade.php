@extends('layouts.app')

@section('title', 'Tableau de bord - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ auth()->user()->name }} ! 👋</h1>
            <p class="text-gray-600 mt-2">Voici votre progression, vos quick wins et vos prochaines recommandations.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                    <i class="fas fa-fire text-2xl text-secondary-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $streak['current'] ?? 0 }}</div>
                    <div class="text-gray-500">Jours de streak</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center">
                <div class="w-14 h-14 bg-warning-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-2xl text-warning-600"></i>
                </div>
                <div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['study_minutes_week'] ?? 0 }} min</div>
                    <div class="text-gray-500">Temps d'étude (semaine)</div>
                </div>
            </div>
        </div>

        @if(!auth()->user()->isSubscribed())
            <div class="bg-gradient-to-r from-secondary-500 to-primary-600 rounded-xl p-6 mb-8 text-white">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-xl font-bold mb-2"><i class="fas fa-crown mr-2"></i> Passez à la version Premium</h3>
                        <p class="text-white/90">Accédez à tous les exercices, leçons PDF et vidéos exclusives.</p>
                    </div>
                    <a href="{{ route('subscription.plans') }}" class="btn bg-white text-primary-700 hover:bg-gray-100">Découvrir les offres</a>
                </div>
            </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🎯 Quick wins de la semaine</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Objectif exercices ({{ $quickWins['exercise_goal'] }})</span>
                                <span class="font-medium">{{ $quickWins['exercise_done'] }}/{{ $quickWins['exercise_goal'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-success-500 h-2.5 rounded-full" style="width: {{ $quickWins['exercise_progress'] }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Objectif temps d'étude ({{ $quickWins['minutes_goal'] }} min)</span>
                                <span class="font-medium">{{ $quickWins['minutes_done'] }} min</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-primary-500 h-2.5 rounded-full" style="width: {{ $quickWins['minutes_progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">🤖 Recommandation leçon</h3>
                        @if($recommendations['lesson'])
                            <h4 class="font-semibold text-gray-900">{{ $recommendations['lesson']->title }}</h4>
                            <p class="text-sm text-gray-500 mt-2">{{ \Illuminate\Support\Str::limit($recommendations['lesson']->description, 120) }}</p>
                            <a href="{{ route('lessons.show', $recommendations['lesson']->slug) }}" class="text-primary-600 hover:text-primary-700 inline-block mt-3">Commencer la leçon <i class="fas fa-arrow-right ml-1"></i></a>
                        @else
                            <p class="text-gray-500">Aucune recommandation pour le moment.</p>
                        @endif
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">🧠 Recommandation exercice</h3>
                        @if($recommendations['exercise'])
                            <h4 class="font-semibold text-gray-900">{{ $recommendations['exercise']->title }}</h4>
                            <p class="text-sm text-gray-500 mt-2">Langage: {{ $recommendations['exercise']->programming_language ?? 'N/A' }}</p>
                            <a href="{{ route('exercises.show', $recommendations['exercise']->slug) }}" class="text-primary-600 hover:text-primary-700 inline-block mt-3">Lancer l'exercice <i class="fas fa-arrow-right ml-1"></i></a>
                        @else
                            <p class="text-gray-500">Excellent travail ! Vous avez complété les exercices recommandés.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🏅 Badges de progression</h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($badges as $badge)
                            <div class="border rounded-lg p-4 {{ $badge['unlocked'] ? 'border-success-300 bg-success-50' : 'border-gray-200 bg-gray-50' }}">
                                <div class="flex items-center gap-3">
                                    <i class="fas {{ $badge['icon'] }} {{ $badge['unlocked'] ? 'text-success-600' : 'text-gray-400' }}"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $badge['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $badge['description'] }}</p>
                                    </div>
                                </div>
                                <span class="text-xs mt-2 inline-block {{ $badge['unlocked'] ? 'text-success-700' : 'text-gray-500' }}">{{ $badge['status'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Derniers exercices soumis</h3>
                        <a href="{{ route('profile') }}" class="text-primary-600 hover:text-primary-700 text-sm">Voir tout <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentSubmissions ?? [] as $submission)
                            <div class="px-6 py-4 flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $submission->exercise->title }}</h4>
                                    <p class="text-sm text-gray-500">Soumis le {{ $submission->submitted_at?->format('d/m/Y') ?? 'En cours' }}</p>
                                </div>
                                <span class="badge badge-{{ $submission->status_badge_color }}">{{ $submission->status_display }}</span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                                <p>Vous n'avez pas encore soumis d'exercices.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🔥 Streak & progression</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <p>Streak actuel: <strong class="text-gray-900">{{ $streak['current'] }} jours</strong></p>
                        <p>Meilleur streak: <strong class="text-gray-900">{{ $streak['longest'] }} jours</strong></p>
                        <p>Dernière activité: <strong class="text-gray-900">{{ $streak['last_activity_date'] ? \Illuminate\Support\Carbon::parse($streak['last_activity_date'])->format('d/m/Y') : 'Aucune' }}</strong></p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">👥 {{ $leaderboard['label'] }}</h3>
                    <div class="space-y-3">
                        @forelse($leaderboard['top'] as $index => $entry)
                            <div class="flex items-center justify-between p-3 rounded-lg {{ $entry['id'] === auth()->id() ? 'bg-primary-50 border border-primary-100' : 'bg-gray-50' }}">
                                <div>
                                    <p class="font-medium text-gray-900">#{{ $index + 1 }} {{ $entry['name'] }}</p>
                                    <p class="text-xs text-gray-500">Streak: {{ $entry['streak'] }} j</p>
                                </div>
                                <span class="text-sm font-semibold text-primary-700">{{ $entry['score'] }} pts</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Aucun classement disponible.</p>
                        @endforelse
                    </div>
                    @if($leaderboard['my_rank'])
                        <p class="text-sm text-gray-600 mt-4">Votre rang actuel: <strong>#{{ $leaderboard['my_rank'] }}</strong></p>
                    @endif
                    @if(!(auth()->user()->school_name && auth()->user()->class_name))
                        <p class="text-xs text-gray-500 mt-2">Ajoutez votre école et votre classe dans le profil pour activer le classement privé.</p>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Votre progression</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Exercices simples</span>
                                <span class="font-medium">{{ $exerciseProgress['simple'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-success-500 h-2 rounded-full" style="width: {{ min(100, ($exerciseProgress['simple'] ?? 0) * 10) }}%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Exercices complexes</span>
                                <span class="font-medium">{{ $exerciseProgress['complexe'] ?? 0 }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-secondary-500 h-2 rounded-full" style="width: {{ min(100, ($exerciseProgress['complexe'] ?? 0) * 5) }}%"></div></div>
                        </div>
                    </div>
                </div>

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
                                        <div class="flex justify-between text-xs text-gray-500 mb-1"><span>Progression</span><span>{{ $progress->progress_percentage }}%</span></div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5"><div class="bg-primary-500 h-1.5 rounded-full" style="width: {{ $progress->progress_percentage }}%"></div></div>
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
            </div>
        </div>
    </div>
</div>
@endsection
