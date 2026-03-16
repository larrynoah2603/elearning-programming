@extends('layouts.app')

@section('title', $module->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('formations.show', $formation->slug) }}" class="text-primary-600 hover:text-primary-700 font-medium">
            ← Retour à {{ $formation->title }}
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100 mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $module->title }}</h1>
        <p class="text-gray-600 mb-4">{{ $module->description }}</p>
        <div class="flex items-center gap-4 text-sm text-gray-500">
            <span>⏱️ {{ $module->duration_minutes }} min</span>
            @if($progress)
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                    Progression : {{ $progress->progress_percentage }}%
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            @if($module->lessons->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">📚 Leçons</h2>
                    <div class="space-y-3">
                        @foreach($module->lessons as $lesson)
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex-1">
                                        <a href="{{ route('lessons.show', $lesson->slug) }}" class="font-semibold text-gray-900 hover:text-primary-600">
                                            {{ $lesson->title }}
                                        </a>
                                        <p class="text-sm text-gray-600 mt-1">{{ $lesson->description }}</p>
                                    </div>
                                    <a href="{{ route('lessons.show', $lesson->slug) }}" class="btn btn-sm btn-outline-primary whitespace-nowrap">
                                        Lire
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($module->videos->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">🎥 Vidéos</h2>
                    <div class="space-y-3">
                        @foreach($module->videos as $video)
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex-1">
                                        <a href="{{ route('videos.show', $video->slug) }}" class="font-semibold text-gray-900 hover:text-primary-600">
                                            {{ $video->title }}
                                        </a>
                                        <p class="text-sm text-gray-600 mt-1">{{ $video->description }}</p>
                                        @if($video->duration_minutes)
                                            <p class="text-xs text-gray-500 mt-2">⏱️ {{ $video->duration_minutes }} min</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('videos.show', $video->slug) }}" class="btn btn-sm btn-outline-primary whitespace-nowrap">
                                        Regarder
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($module->exercises->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">💪 Exercices</h2>
                    <div class="space-y-3">
                        @foreach($module->exercises as $exercise)
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start gap-3">
                                    <div class="flex-1">
                                        <a href="{{ route('exercises.show', $exercise->slug) }}" class="font-semibold text-gray-900 hover:text-primary-600">
                                            {{ $exercise->title }}
                                        </a>
                                        <p class="text-sm text-gray-600 mt-1">{{ $exercise->description }}</p>
                                        <div class="flex gap-2 mt-2">
                                            <span class="badge badge-{{ $exercise->level === 'debutant' ? 'success' : ($exercise->level === 'intermediaire' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($exercise->level) }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('exercises.show', $exercise->slug) }}" class="btn btn-sm btn-outline-primary whitespace-nowrap">
                                        Résoudre
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($module->lessons->count() === 0 && $module->videos->count() === 0 && $module->exercises->count() === 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
                    <p class="text-blue-800">Aucun contenu disponible pour ce module pour le moment.</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-20">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Navigation</h3>
                <div class="space-y-2">
                    <a href="{{ route('formations.show', $formation->slug) }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        📖 Vue d'ensemble de la formation
                    </a>
                    <a href="{{ route('formations.my') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                        📚 Mes formations
                    </a>
                </div>

                @if($progress)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="font-semibold text-gray-900 mb-3">Progression</h4>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress->progress_percentage }}%"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2 text-center">{{ $progress->progress_percentage }}% complété</p>
                        @if($progress->is_completed)
                            <div class="mt-4 text-center text-green-600 font-semibold">
                                ✅ Module complété
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        <strong>Durée estimée :</strong> {{ $module->duration_minutes }} minutes
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
