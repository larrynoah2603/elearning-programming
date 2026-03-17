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
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-primary-500 to-primary-600 p-4 h-32 flex flex-col justify-between">
                        <div>
                            <p class="text-white font-bold text-lg truncate">{{ $enrollment->formation->title }}</p>
                            <span class="badge badge-info text-xs mt-2">{{ ucfirst($enrollment->formation->level) }}</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-4 space-y-4">
                        <!-- Description -->
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $enrollment->formation->description }}
                        </p>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <p class="text-gray-500 text-xs">Modules</p>
                                <p class="font-bold text-gray-900">{{ $enrollment->formation->modules->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-lg">
                                <p class="text-gray-500 text-xs">Quizzes</p>
                                <p class="font-bold text-gray-900">{{ $enrollment->formation->quizzes->count() }}</p>
                            </div>
                        </div>

                        <!-- Progress -->
                        <div>
                            <p class="text-xs text-gray-500 mb-2">Progression</p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Enrollment Info -->
                        @php
                            $deadline = $enrollment->access_expires_at ?? optional($enrollment->paid_at)?->copy()?->addDays($enrollment->formation->validity_days);
                        @endphp
                        <div class="bg-blue-50 rounded-lg p-3 text-xs text-blue-700 border border-blue-200">
                            <p><strong>Acheté le :</strong> {{ optional($enrollment->paid_at)->format('d/m/Y') }}</p>
                            <p><strong>Validité :</strong> {{ $enrollment->formation->validity_days }} jours</p>
                            <p><strong>Deadline :</strong> {{ $deadline?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                            <p><strong>Ref :</strong> {{ $enrollment->payment_reference }}</p>
                        </div>

                        <!-- Button -->
                        <a href="{{ route('formations.subscription', $enrollment->formation) }}" class="btn btn-primary w-full">
                            Continuer la formation
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 p-6 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-lg font-bold text-blue-900 mb-3">💡 Conseil</h3>
            <p class="text-blue-700 text-sm">
                Pour progresser rapidement, consacrez au moins 30 minutes par jour à votre formation. Commencez par les leçons, regardez les vidéos, puis pratiquez avec les exercices.
            </p>
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
