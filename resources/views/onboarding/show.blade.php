@extends('layouts.app')

@section('title', 'Onboarding autodidacte - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-gray-900">Configurez votre plan personnalisé</h1>
            <p class="text-gray-600 mt-2">Répondez à 3 questions rapides pour générer un plan adapté à votre rythme.</p>

            <form method="POST" action="{{ route('onboarding.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Niveau actuel</label>
                    <select name="level" class="w-full rounded-xl border-gray-300" required>
                        <option value="beginner" @selected(old('level', $profile?->level) === 'beginner')>Débutant</option>
                        <option value="intermediate" @selected(old('level', $profile?->level) === 'intermediate')>Intermédiaire</option>
                        <option value="advanced" @selected(old('level', $profile?->level) === 'advanced')>Avancé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Objectif principal</label>
                    <input type="text" name="goal" value="{{ old('goal', $profile?->goal) }}" class="w-full rounded-xl border-gray-300" placeholder="Ex: Devenir autonome en Python" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Temps disponible par jour (minutes)</label>
                    <input type="number" min="15" max="180" name="minutes_per_day" value="{{ old('minutes_per_day', $profile?->minutes_per_day ?? 30) }}" class="w-full rounded-xl border-gray-300" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Langages préférés</label>
                    @php($selected = old('preferred_languages', $profile?->preferred_languages ?? []))
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-sm">
                        @foreach(['python', 'javascript', 'php', 'java', 'cpp', 'sql'] as $lang)
                            <label class="inline-flex items-center gap-2 p-2 rounded-lg bg-gray-50">
                                <input type="checkbox" name="preferred_languages[]" value="{{ $lang }}" @checked(in_array($lang, $selected))>
                                <span>{{ strtoupper($lang) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard') }}" class="btn bg-gray-100 text-gray-700 hover:bg-gray-200">Plus tard</a>
                    <button type="submit" class="btn btn-primary">Générer mon plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
