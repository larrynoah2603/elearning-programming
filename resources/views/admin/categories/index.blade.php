@extends('layouts.app')

@section('title', 'Catégories - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Gestion des catégories</h1>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Ajouter</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Leçons</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $category->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $category->order ?? 0 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $category->lessons_count }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-warning' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-primary-600 hover:text-primary-800">Modifier</a>
                                <form method="POST" action="{{ route('admin.categories.toggle', $category) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-warning-600 hover:text-warning-800">Basculer</button>
                                </form>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-danger-600 hover:text-danger-800">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Aucune catégorie.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $categories->links() }}</div>
        </div>
    </div>
</div>
@endsection
