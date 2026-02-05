@extends('layouts.app')

@section('title', 'Toutes les soumissions - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Toutes les soumissions</h1>
                <a href="{{ route('admin.submissions.pending') }}" class="btn btn-outline">Voir en attente</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Étudiant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exercice</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($submissions as $submission)
                            <tr>
                                <td class="px-4 py-3">{{ $submission->user->name }}</td>
                                <td class="px-4 py-3">{{ $submission->exercise->title }}</td>
                                <td class="px-4 py-3"><span class="badge badge-{{ $submission->status_badge_color }}">{{ $submission->status_display }}</span></td>
                                <td class="px-4 py-3">{{ $submission->score_percentage }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.submissions.correct', $submission) }}" class="btn btn-primary btn-sm">Voir / corriger</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Aucune soumission.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $submissions->links() }}</div>
        </div>
    </div>
</div>
@endsection
