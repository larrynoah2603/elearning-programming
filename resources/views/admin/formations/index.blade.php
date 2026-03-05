@extends('layouts.app')

@section('title', 'Gestion des formations')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Gestion des formations payantes</h1>
        <a href="{{ route('admin.formations.create') }}" class="btn btn-primary">Ajouter une formation</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Titre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Prix</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Modules</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Inscrits</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Statut</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($formations as $formation)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $formation->title }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ number_format($formation->price, 2, ',', ' ') }} €</td>
                        <td class="px-4 py-3 text-gray-700">{{ $formation->modules_count }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $formation->enrollments_count }}</td>
                        <td class="px-4 py-3">{!! $formation->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' !!}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.formations.edit', $formation) }}" class="btn btn-secondary">Modifier</a>
                                <form method="POST" action="{{ route('admin.formations.destroy', $formation) }}" onsubmit="return confirm('Supprimer cette formation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn bg-red-600 text-white hover:bg-red-700">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Aucune formation. Ajoutez d'abord une formation depuis l'admin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $formations->links() }}
    </div>
</div>
@endsection
