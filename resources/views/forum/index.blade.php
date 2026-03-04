@extends('layouts.app')

@section('title', 'Forum Q/R - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="surface-glow rounded-2xl p-6 bg-gradient-to-r from-secondary-50 via-white to-primary-50 dark:from-slate-800 dark:via-slate-800 dark:to-slate-700">
            <h1 class="text-2xl font-extrabold text-gray-900">💬 Forum Q/R</h1>
            <p class="text-gray-600">Posez des questions sur une leçon ou un exercice, et entraidez-vous.</p>
        </div>

        <div class="surface-glow rounded-2xl p-6">
            <h2 class="font-semibold mb-3">Nouveau sujet</h2>
            <form method="POST" action="{{ route('forum.threads.store') }}" class="space-y-3">
                @csrf
                <input type="text" name="title" class="w-full rounded-xl border-gray-200 dark:border-slate-600 focus:border-primary-400 dark:focus:border-primary-400" placeholder="Titre du sujet" required>
                <textarea name="body" rows="4" class="w-full rounded-xl border-gray-200 dark:border-slate-600 focus:border-primary-400 dark:focus:border-primary-400" placeholder="Votre question..." required></textarea>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <select name="lesson_id" class="w-full rounded-xl border-gray-200 dark:border-slate-600">
                        <option value="">Associer à une leçon (optionnel)</option>
                        @foreach($lessons as $lesson)
                            <option value="{{ $lesson->id }}" {{ (int) old('lesson_id', $lessonId) === $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>
                        @endforeach
                    </select>
                    <select name="exercise_id" class="w-full rounded-xl border-gray-200 dark:border-slate-600">
                        <option value="">Associer à un exercice (optionnel)</option>
                        @foreach($exercises as $exercise)
                            <option value="{{ $exercise->id }}" {{ (int) old('exercise_id', $exerciseId) === $exercise->id ? 'selected' : '' }}>{{ $exercise->title }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-gradient-to-r from-primary-600 to-secondary-600 text-white rounded-xl hover:opacity-95 transition">Publier</button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($threads as $thread)
                <div class="surface-glow rounded-2xl p-6 card-float dark:bg-slate-800/90">
                    <div class="flex justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">{{ $thread->title }}</h3>
                            <p class="text-xs text-gray-500">par {{ $thread->user->name }} • {{ $thread->created_at->diffForHumans() }}</p>
                            <p class="mt-2 text-sm text-gray-700">{{ $thread->body }}</p>
                            <div class="text-xs text-gray-500 mt-2">
                                @if($thread->lesson) <span class="badge badge-info">Leçon: {{ $thread->lesson->title }}</span> @endif
                                @if($thread->exercise) <span class="badge badge-warning">Exercice: {{ $thread->exercise->title }}</span> @endif
                            </div>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('forum.threads.resolve', $thread) }}">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs px-3 py-1.5 rounded-lg {{ $thread->is_resolved ? 'bg-success-100 dark:bg-success-500/20 text-success-700 dark:text-success-300' : 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-slate-600' }} transition">
                                    {{ $thread->is_resolved ? 'Résolu ✅' : 'Marquer résolu' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        @foreach($thread->replies as $reply)
                            <div class="p-3 bg-gray-50 dark:bg-slate-700 rounded-xl text-sm border border-gray-100 dark:border-slate-600">
                                <span class="font-medium text-gray-900">{{ $reply->user->name }}:</span> {{ $reply->body }}
                            </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('forum.replies.store', $thread) }}" class="mt-3 flex gap-2">
                        @csrf
                        <input type="text" name="body" class="flex-1 rounded-xl border-gray-200 dark:border-slate-600" placeholder="Répondre..." required>
                        <button class="px-3 py-2 bg-gray-900 dark:bg-primary-600 text-white rounded-xl hover:bg-black dark:hover:bg-primary-500 transition">Envoyer</button>
                    </form>
                </div>
            @empty
                <div class="surface-glow rounded-2xl p-6 text-gray-500">Aucun sujet pour le moment.</div>
            @endforelse
        </div>

        {{ $threads->links() }}
    </div>
</div>
@endsection
