@extends('layouts.app')

@section('title', $formation->title)

@section('content')
@php
    $hasAccess = $hasAccess ?? false;
    $enrollment = $enrollment ?? null;
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="badge badge-info">{{ $formation->level_display }}</span>
            <span class="badge badge-warning">Paiement séparé de l'abonnement Premium</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $formation->title }}</h1>
        <p class="text-gray-600 mb-6">{{ $formation->description }}</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-primary-50 rounded-lg p-4">
                <p class="text-sm text-primary-700">Tarif unique</p>
                <p class="text-2xl font-bold text-primary-900">{{ number_format($formation->price, 0, ',', ' ') }} Ar</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4">
                <p class="text-sm text-orange-700">Validité</p>
                <p class="font-semibold text-orange-900">{{ $formation->validity_days }} jours pour terminer la formation.</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600">Accès</p>
                <p class="font-semibold text-gray-900">Disponible pour compte gratuit, premium ou sans abonnement actif.</p>
            </div>
        </div>

        @if($hasAccess && $enrollment)
            <div class="rounded-lg bg-success-50 border border-success-200 p-4 text-success-800 mb-8">
                <p class="font-semibold">✅ Vous faites partie de cette formation.</p>
                <p class="text-sm mt-1">Achetée le {{ optional($enrollment->paid_at)->format('d/m/Y H:i') }} via {{ $enrollment->payment_method }}.</p>
                <p class="text-xs mt-1">Référence de paiement: {{ $enrollment->payment_reference ?? 'N/A' }}</p>
            </div>
        @endif

        <h2 class="text-xl font-semibold text-gray-900 mb-4">Modules de la formation</h2>
        <div class="space-y-4">
            @forelse($formation->modules as $index => $module)
                <div class="border border-gray-100 rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex justify-between items-start gap-3 mb-4">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-lg">Module {{ $index + 1 }} - {{ $module->title }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $module->description }}</p>
                        </div>
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <span class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">{{ $module->duration_minutes }} min</span>
                            @if($hasAccess)
                                <a href="{{ route('formations.module.show', ['formation' => $formation, 'module' => $module->id]) }}" class="btn btn-sm btn-primary">Commencer le module</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Le programme détaillé sera bientôt disponible.</p>
            @endforelse
        </div>

        @if($hasAccess)
            <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Ressources complémentaires</h3>
                <p class="text-blue-700 mb-4">Accédez aux leçons et vidéos liées à cette formation :</p>
                <div class="flex gap-3">
                    <a href="{{ route('lessons.index') }}" class="btn btn-sm btn-outline-primary">Voir les leçons</a>
                    <a href="{{ route('videos.index') }}" class="btn btn-sm btn-outline-primary">Voir les vidéos</a>
                </div>
            </div>
        @endif

        @if($formation->quizzes->count() > 0)
            <h2 class="text-xl font-semibold text-gray-900 mb-4 mt-8">Quizzes de validation</h2>
            <div class="space-y-3">
                @foreach($formation->quizzes as $quiz)
                    <div class="border border-gray-100 rounded-lg p-4">
                        <div class="flex justify-between items-start gap-3">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $quiz->title }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $quiz->description ?? 'Quiz de validation' }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    {{ $quiz->questions->count() }} question{{ $quiz->questions->count() > 1 ? 's' : '' }} •
                                    Score min: {{ $quiz->passing_score }}% •
                                    {{ $quiz->max_attempts }} tentative{{ $quiz->max_attempts > 1 ? 's' : '' }} •
                                    {{ $quiz->duration_minutes }}min
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                @php
                                    $userSubmission = auth()->check() ? $quiz->submissions()->where('user_id', auth()->id())->first() : null;
                                    $hasPassed = $userSubmission && $userSubmission->isPassed();
                                    $attemptCount = auth()->check() ? $quiz->submissions()->where('user_id', auth()->id())->count() : 0;
                                @endphp

                                @if($hasPassed)
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        ✅ Réussi ({{ $userSubmission->score }}%)
                                    </span>
                                @elseif($userSubmission && !$hasPassed)
                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">
                                        ❌ Échec ({{ $userSubmission->score }}%)
                                    </span>
                                @endif

                                @if($hasAccess)
                                    @if($hasPassed)
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Validé ✓</span>
                                    @elseif($attemptCount >= $quiz->max_attempts)
                                        <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded">Tentatives épuisées</span>
                                    @else
                                        <a href="{{ route('quiz.show', $quiz) }}" class="btn btn-sm btn-primary">
                                            {{ $attemptCount > 0 ? 'Retenter' : 'Commencer' }}
                                        </a>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-500">Achetez pour accéder</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-8">
            @auth
                @if($hasAccess)
                    <a href="{{ route('formations.my') }}" class="btn btn-secondary">Voir mes formations</a>
                @else
                    <a href="{{ route('formations.checkout', $formation) }}" class="btn btn-primary">
                        Acheter cette formation
                    </a>
                @endif
            @else
                <div class="space-x-2">
                    <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">S'inscrire</a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection