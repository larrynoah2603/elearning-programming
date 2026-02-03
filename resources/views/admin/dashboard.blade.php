@extends('layouts.app')

@section('title', 'Administration - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-cog mr-2"></i> Tableau de bord Admin
            </h1>
            <p class="text-gray-600 mt-2">Gérez votre plateforme e-learning.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Users -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-xl text-primary-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</span>
                </div>
                <p class="text-gray-600 text-sm">Utilisateurs totaux</p>
                <div class="mt-2 flex items-center text-xs text-gray-500">
                    <span class="text-success-600 mr-2"><i class="fas fa-user mr-1"></i> {{ $stats['free_users'] }} gratuits</span>
                    <span class="text-secondary-600"><i class="fas fa-crown mr-1"></i> {{ $stats['subscribed_users'] }} premium</span>
                </div>
            </div>

            <!-- Lessons -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-secondary-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book text-xl text-secondary-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $stats['total_lessons'] }}</span>
                </div>
                <p class="text-gray-600 text-sm">Leçons</p>
                <div class="mt-2 flex items-center text-xs text-gray-500">
                    <span class="text-success-600 mr-2"><i class="fas fa-check mr-1"></i> {{ $stats['active_lessons'] }} actives</span>
                </div>
            </div>

            <!-- Exercises -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-laptop-code text-xl text-success-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $stats['total_exercises'] }}</span>
                </div>
                <p class="text-gray-600 text-sm">Exercices</p>
                <div class="mt-2 flex items-center text-xs text-gray-500">
                    <span class="text-success-600 mr-2"><i class="fas fa-check mr-1"></i> {{ $stats['active_exercises'] }} actifs</span>
                </div>
            </div>

            <!-- Videos -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-video text-xl text-warning-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">{{ $stats['total_videos'] }}</span>
                </div>
                <p class="text-gray-600 text-sm">Vidéos</p>
                <div class="mt-2 flex items-center text-xs text-gray-500">
                    <span class="text-success-600 mr-2"><i class="fas fa-check mr-1"></i> {{ $stats['active_videos'] }} actives</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('admin.users.index') }}" class="btn bg-gray-100 text-gray-700 hover:bg-gray-200 py-4">
                <i class="fas fa-users mr-2"></i> Gérer les utilisateurs
            </a>
            <a href="{{ route('admin.lessons.create') }}" class="btn bg-primary-100 text-primary-700 hover:bg-primary-200 py-4">
                <i class="fas fa-plus mr-2"></i> Ajouter une leçon
            </a>
            <a href="{{ route('admin.exercises.create') }}" class="btn bg-success-100 text-success-700 hover:bg-success-200 py-4">
                <i class="fas fa-plus mr-2"></i> Ajouter un exercice
            </a>
            <a href="{{ route('admin.videos.create') }}" class="btn bg-warning-100 text-warning-700 hover:bg-warning-200 py-4">
                <i class="fas fa-plus mr-2"></i> Ajouter une vidéo
            </a>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Pending Submissions -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-clock mr-2 text-warning-500"></i> Soumissions en attente
                    </h3>
                    <a href="{{ route('admin.submissions.pending') }}" class="text-primary-600 hover:text-primary-700 text-sm">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($pendingSubmissions as $submission)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $submission->exercise->title }}</h4>
                                <p class="text-sm text-gray-500">
                                    Par {{ $submission->user->name }} • {{ $submission->submitted_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('admin.submissions.correct', $submission) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-check mr-1"></i> Corriger
                            </a>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-check-circle text-4xl mb-4 text-success-500"></i>
                            <p>Aucune soumission en attente.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-user-plus mr-2 text-primary-500"></i> Nouveaux utilisateurs
                    </h3>
                    <a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentUsers as $user)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="badge badge-{{ $user->role_badge_color }}">
                                {{ $user->subscription_status }}
                            </span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                            <p>Aucun utilisateur récent.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Submissions -->
        <div class="mt-8 bg-white rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-clipboard-check mr-2 text-success-500"></i> Dernières soumissions
                </h3>
                <a href="{{ route('admin.submissions.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exercice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentSubmissions as $submission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-2">
                                            {{ substr($submission->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-900">{{ $submission->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $submission->exercise->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="badge badge-{{ $submission->status_badge_color }}">
                                        {{ $submission->status_display }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $submission->submitted_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $submission->score_percentage }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-clipboard-list text-4xl mb-4 text-gray-300"></i>
                                    <p>Aucune soumission récente.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
