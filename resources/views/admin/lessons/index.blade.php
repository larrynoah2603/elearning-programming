@extends('layouts.app')

@section('title', 'Gestion des Leçons - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des Leçons</h1>
                <p class="text-gray-600 mt-2">Gérez les leçons PDF de la plateforme.</p>
            </div>
            <a href="{{ route('admin.lessons.create') }}" class="mt-4 md:mt-0 btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Nouvelle leçon
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('admin.lessons.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="form-input pl-10 w-full" placeholder="Rechercher...">
                    </div>
                </div>
                <div class="md:w-48">
                    <select name="status" class="form-select w-full">
                        <option value="all">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
            </form>
        </div>

        <!-- Lessons Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accès</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lessons as $lesson)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-danger-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-file-pdf text-danger-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $lesson->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($lesson->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge badge-{{ $lesson->level_badge_color }}">
                                    {{ $lesson->level_display }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge badge-{{ $lesson->access_level == 'free' ? 'success' : 'warning' }}">
                                    {{ $lesson->access_level_display }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.lessons.toggle', $lesson) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="badge badge-{{ $lesson->is_active ? 'success' : 'danger' }} hover:opacity-80">
                                        {{ $lesson->is_active ? 'Actif' : 'Inactif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('lessons.show', $lesson->slug) }}" class="text-primary-600 hover:text-primary-900" title="Voir" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="text-warning-600 hover:text-warning-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger-600 hover:text-danger-900" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-book text-4xl mb-4 text-gray-300"></i>
                                <p>Aucune leçon trouvée.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $lessons->links() }}
        </div>
    </div>
</div>
@endsection
