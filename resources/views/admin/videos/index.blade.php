@extends('layouts.app')

@section('title', 'Gestion des vidéos - Admin')

@php
// Helper function for level badge colors
function getLevelBadgeColor($level) {
    return match($level) {
        'debutant' => 'green',
        'intermediaire' => 'yellow',
        'avance' => 'red',
        default => 'gray',
    };
}

// Helper function for level display names
function getLevelDisplayName($level) {
    return match($level) {
        'debutant' => 'Débutant',
        'intermediaire' => 'Intermédiaire',
        'avance' => 'Avancé',
        default => ucfirst($level),
    };
}
@endphp

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestion des vidéos</h1>
                    <p class="text-gray-600 mt-2">Gérez toutes les vidéos de formation.</p>
                </div>
                <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Ajouter une vidéo
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('admin.videos.index') }}" class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="form-input pl-10 w-full" placeholder="Titre ou description...">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" class="form-select w-full">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                    <select name="level" class="form-select w-full">
                        <option value="">Tous les niveaux</option>
                        <option value="debutant" {{ request('level') == 'debutant' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediaire" {{ request('level') == 'intermediaire' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="avance" {{ request('level') == 'avance' ? 'selected' : '' }}>Avancé</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                    @if(request()->hasAny(['search', 'status', 'level']))
                        <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary" title="Réinitialiser">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Videos Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($videos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vidéo
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Niveau
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Accès
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vues
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($videos as $video)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-20 bg-gray-900 rounded overflow-hidden">
                                                @if($video->thumbnail)
                                                    <img src="{{ Storage::url($video->thumbnail) }}" alt="{{ $video->title }}" class="h-12 w-20 object-cover">
                                                @else
                                                    <div class="h-12 w-20 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                        <i class="fas fa-play text-white/50"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($video->title, 50) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $video->user?->name ?? 'Utilisateur inconnu' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $levelColor = getLevelBadgeColor($video->level); @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $levelColor }}-100 text-{{ $levelColor }}-800">
                                            {{ getLevelDisplayName($video->level) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($video->access_level == 'subscribed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-crown mr-1"></i> Premium
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Gratuit
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($video->views ?? 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($video->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('videos.show', $video->slug) }}" target="_blank" 
                                               class="text-gray-600 hover:text-blue-600 transition-colors" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.videos.edit', $video) }}" 
                                               class="text-gray-600 hover:text-blue-600 transition-colors" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.videos.toggle-active', $video) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-gray-600 hover:text-yellow-600 transition-colors" title="{{ $video->is_active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas {{ $video->is_active ? 'fa-toggle-on text-green-600' : 'fa-toggle-off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ? Cette action est irréversible.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $videos->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-video text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">Aucune vidéo trouvée</h3>
                    <p class="text-gray-500">Commencez par ajouter votre première vidéo.</p>
                    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary mt-4">
                        <i class="fas fa-plus mr-2"></i> Ajouter une vidéo
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection