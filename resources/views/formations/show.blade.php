@extends('layouts.app')

@section('title', $formation->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="badge badge-info">{{ $formation->level_display }}</span>
            <span class="badge badge-warning">Paiement séparé de l'abonnement Premium</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $formation->title }}</h1>
        <p class="text-gray-600 mb-6">{{ $formation->description }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-primary-50 rounded-lg p-4">
                <p class="text-sm text-primary-700">Tarif unique</p>
                <p class="text-2xl font-bold text-primary-900">{{ number_format($formation->price, 2, ',', ' ') }} €</p>
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
        <div class="space-y-3">
            @forelse($formation->modules as $index => $module)
                <div class="border border-gray-100 rounded-lg p-4">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <p class="font-semibold text-gray-900">Module {{ $index + 1 }} - {{ $module->title }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $module->description }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $module->duration_minutes }} min</span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Le programme détaillé sera bientôt disponible.</p>
            @endforelse
        </div>

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
                <a href="{{ route('login') }}" class="btn btn-primary">Se connecter pour acheter</a>
            @endauth
        </div>
    </div>
</div>
@endsection
