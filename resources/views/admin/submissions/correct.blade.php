@extends('layouts.app')

@section('title', 'Corriger une soumission - Admin')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Correction de soumission</h1>
            <p class="text-gray-600">{{ $submission->user->name }} · {{ $submission->exercise->title }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Code soumis</h2>
            <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>{{ $submission->submitted_code }}</code></pre>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('admin.submissions.submit', $submission) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Score (0-100)</label>
                    <input type="number" name="score" min="0" max="100" value="{{ old('score', $submission->score ?? 0) }}" class="form-input w-full" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                    <textarea name="feedback" rows="5" class="form-textarea w-full" placeholder="Commentaires de correction...">{{ old('feedback', $submission->feedback) }}</textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.submissions.pending') }}" class="btn btn-outline">Retour</a>
                    <button type="submit" class="btn btn-primary">Enregistrer la correction</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
