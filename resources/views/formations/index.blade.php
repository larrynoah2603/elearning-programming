@extends('layouts.app')

@section('title', 'Formations modulaires payantes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Formations modulaires</h1>
        <p class="mt-2 text-gray-600">Un espace de formations payantes indépendant des contenus Free et Premium.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($formations as $formation)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <span class="badge badge-info">{{ $formation->level_display }}</span>
                    <span class="text-lg font-bold text-primary-700">{{ number_format($formation->price, 2, ',', ' ') }} €</span>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $formation->title }}</h2>
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($formation->description, 140) }}</p>
                <p class="text-sm text-gray-500 mb-6">{{ $formation->modules_count }} module(s)</p>
                <a href="{{ route('formations.show', $formation->slug) }}" class="mt-auto btn btn-primary text-center">
                    Voir la formation
                </a>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl p-8 text-center text-gray-500">
                Aucune formation disponible pour le moment.
            </div>
        @endforelse
    </div>
</div>
@endsection
