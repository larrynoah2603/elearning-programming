@extends('layouts.app')

@section('title', 'Statistiques - Admin')

@section('content')
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Statistiques</h1>

    <div class="grid md:grid-cols-4 gap-4">
      <div class="bg-white p-4 rounded-xl shadow-sm"><p class="text-sm text-gray-500">Soumissions</p><p class="text-2xl font-bold">{{ $submissionStats['total'] }}</p></div>
      <div class="bg-white p-4 rounded-xl shadow-sm"><p class="text-sm text-gray-500">Taux de réussite</p><p class="text-2xl font-bold">{{ number_format($submissionStats['success_rate'], 1) }}%</p></div>
      <div class="bg-white p-4 rounded-xl shadow-sm"><p class="text-sm text-gray-500">Vues vidéos</p><p class="text-2xl font-bold">{{ $videoStats['total_views'] }}</p></div>
      <div class="bg-white p-4 rounded-xl shadow-sm"><p class="text-sm text-gray-500">Utilisateurs</p><p class="text-2xl font-bold">{{ $userStats['by_role']->sum() }}</p></div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
      <h2 class="font-semibold mb-3">Top vidéos</h2>
      <ul class="space-y-2">
      @forelse($videoStats['most_viewed'] as $video)
        <li class="flex items-center justify-between text-sm"><span>{{ $video->title }}</span><span class="text-gray-500">{{ $video->views }} vues</span></li>
      @empty
        <li class="text-sm text-gray-500">Aucune vidéo.</li>
      @endforelse
      </ul>
    </div>
  </div>
</div>
@endsection
