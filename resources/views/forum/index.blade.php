<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900">Forum Q/R</h1>
                <p class="text-gray-600">Posez des questions sur une leçon ou un exercice, et entraidez-vous.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-semibold mb-3">Nouveau sujet</h2>
                <form method="POST" action="{{ route('forum.threads.store') }}" class="space-y-3">
                    @csrf
                    <input type="text" name="title" class="w-full rounded-md border-gray-300" placeholder="Titre du sujet" required>
                    <textarea name="body" rows="4" class="w-full rounded-md border-gray-300" placeholder="Votre question..." required></textarea>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <select name="lesson_id" class="w-full rounded-md border-gray-300">
                            <option value="">Associer à une leçon (optionnel)</option>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}" {{ (int) old('lesson_id', $lessonId) === $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>
                            @endforeach
                        </select>
                        <select name="exercise_id" class="w-full rounded-md border-gray-300">
                            <option value="">Associer à un exercice (optionnel)</option>
                            @foreach($exercises as $exercise)
                                <option value="{{ $exercise->id }}" {{ (int) old('exercise_id', $exerciseId) === $exercise->id ? 'selected' : '' }}>{{ $exercise->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-md">Publier</button>
                </form>
            </div>

            <div class="space-y-4">
                @forelse($threads as $thread)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex justify-between gap-3">
                            <div>
                                <h3 class="font-semibold">{{ $thread->title }}</h3>
                                <p class="text-xs text-gray-500">par {{ $thread->user->name }} • {{ $thread->created_at->diffForHumans() }}</p>
                                <p class="mt-2 text-sm text-gray-700">{{ $thread->body }}</p>
                                <div class="text-xs text-gray-500 mt-2">
                                    @if($thread->lesson) Leçon: {{ $thread->lesson->title }} @endif
                                    @if($thread->exercise) • Exercice: {{ $thread->exercise->title }} @endif
                                </div>
                            </div>
                            <div>
                                <form method="POST" action="{{ route('forum.threads.resolve', $thread) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-xs px-2 py-1 rounded {{ $thread->is_resolved ? 'bg-success-100 text-success-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $thread->is_resolved ? 'Résolu' : 'Marquer résolu' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2">
                            @foreach($thread->replies as $reply)
                                <div class="p-2 bg-gray-50 rounded text-sm">
                                    <span class="font-medium">{{ $reply->user->name }}:</span> {{ $reply->body }}
                                </div>
                            @endforeach
                        </div>

                        <form method="POST" action="{{ route('forum.replies.store', $thread) }}" class="mt-3 flex gap-2">
                            @csrf
                            <input type="text" name="body" class="flex-1 rounded-md border-gray-300" placeholder="Répondre..." required>
                            <button class="px-3 py-2 bg-gray-900 text-white rounded-md">Envoyer</button>
                        </form>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm p-6 text-gray-500">Aucun sujet pour le moment.</div>
                @endforelse
            </div>

            {{ $threads->links() }}
        </div>
    </div>
</x-app-layout>
