@extends('layouts.app')

@section('title', 'Mes formations')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📚 Mes formations</h1>
        <p class="text-gray-600 mt-2">Suivez vos formations et votre progression.</p>
    </div>

    @if($enrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrollments as $enrollment)
                @php
                    $formation = $enrollment->formation;
                    $deadline = $enrollment->access_expires_at ?? optional($enrollment->paid_at)?->copy()?->addDays($formation->validity_days ?? 30);
                    $isExpired = $deadline ? now()->greaterThan($deadline) : false;
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <div class="p-4 h-32 flex flex-col justify-between {{ $isExpired ? 'bg-gradient-to-r from-gray-500 to-gray-600' : 'bg-gradient-to-r from-primary-500 to-primary-600' }}">
                        <div>
                            <p class="text-white font-bold text-lg truncate">{{ $formation->title }}</p>
                            <span class="badge badge-info text-xs mt-2">{{ ucfirst($formation->level) }}</span>
                        </div>
                        <p class="text-xs text-white/90 mt-2">{{ $isExpired ? 'Accès expiré' : 'Accès actif' }}</p>
                    </div>

                    <div class="p-4 space-y-4">
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $formation->description }}
                        </p>

                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <p class="text-gray-500 text-xs">Modules</p>
                                <p class="font-bold text-gray-900">{{ $formation->modules_count ?? 0 }}</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <p class="text-gray-500 text-xs">Quizzes</p>
                                <p class="font-bold text-gray-900">{{ $formation->quizzes_count ?? 0 }}</p>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-3 text-xs text-blue-700 border border-blue-200">
                            <p><strong>Acheté le :</strong> {{ optional($enrollment->paid_at)->format('d/m/Y') }}</p>
                            <p><strong>Validité :</strong> {{ $formation->validity_days }} jours</p>
                            <p><strong>Deadline :</strong> {{ $deadline?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                            <p><strong>Ref :</strong> {{ $enrollment->payment_reference }}</p>
                        </div>

                        @if($isExpired)
                            <a href="{{ route('formations.subscription', ['formation' => $formation->id]) }}" class="btn btn-secondary w-full">
                                Voir les détails d'expiration
                            </a>
                        @else
                            <a href="{{ route('formations.access', ['formation' => $formation->id]) }}" class="btn btn-primary w-full">
                                Continuer la formation
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📭</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Aucune formation achetée</h2>
            <p class="text-gray-600 mb-6">Vous n'avez pas encore acheté de formation. Découvrez nos formations disponibles.</p>
            <a href="{{ route('formations.index') }}" class="btn btn-primary">
                Parcourir les formations
            </a>
        </div>
    @endif
</div>
@endsection
