@extends('layouts.app')

@section('title', 'Défis hebdomadaires - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="surface-glow rounded-2xl p-6 bg-gradient-to-r from-primary-50 via-white to-secondary-50">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">🏁 Défis hebdomadaires</h1>
                    <p class="text-gray-600 mt-1">Classement du {{ $start->format('d/m/Y') }} au {{ $end->format('d/m/Y') }}.</p>
                </div>
                <span class="badge badge-info text-sm">Reset chaque semaine</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="surface-glow rounded-2xl p-6">
                <h2 class="font-semibold text-lg mb-4">🔥 Top défis de la semaine</h2>
                <div class="space-y-3">
                    @forelse($challenges as $challenge)
                        <a href="{{ route('exercises.show', $challenge->slug) }}" class="block p-4 rounded-xl border border-gray-100 hover:border-primary-200 hover:bg-primary-50/40 transition-all duration-200 card-float">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $challenge->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Tentatives cette semaine: {{ $challenge->weekly_attempts }}</div>
                                </div>
                                <i class="fas fa-arrow-right text-primary-500"></i>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500">Aucun défi disponible cette semaine.</p>
                    @endforelse
                </div>
            </div>

            <div class="surface-glow rounded-2xl p-6">
                <h2 class="font-semibold text-lg mb-4">🏆 Classement hebdomadaire</h2>
                <div class="space-y-2">
                    @forelse($leaderboard as $index => $row)
                        <div class="flex justify-between items-center rounded-lg px-3 py-2 {{ $index < 3 ? 'bg-amber-50 border border-amber-100' : 'border-b' }} text-sm">
                            <span class="font-medium">#{{ $index + 1 }} {{ $row['name'] }}</span>
                            <span class="font-bold text-primary-700">{{ $row['score'] }} pts</span>
                        </div>
                    @empty
                        <p class="text-gray-500">Aucune activité cette semaine.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
