<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">Espace Enseignant</h1>
                <p class="text-gray-600 mt-2">Pilotez vos groupes, assignez des devoirs et suivez la progression de votre classe.</p>
                <a href="{{ route('teacher.export.csv') }}" class="inline-flex items-center mt-4 px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    Export CSV des résultats
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Groupes</p>
                    <p class="text-2xl font-semibold">{{ $stats['groups_count'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Élèves</p>
                    <p class="text-2xl font-semibold">{{ $stats['students_count'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Devoirs</p>
                    <p class="text-2xl font-semibold">{{ $stats['assignments_count'] }}</p>
                </div>
                <div class="bg-white p-5 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Taux de complétion</p>
                    <p class="text-2xl font-semibold">{{ $completionRate }}%</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="font-semibold text-lg mb-4">Créer un groupe / classe</h2>
                    <form method="POST" action="{{ route('teacher.groups.store') }}" class="space-y-3">
                        @csrf
                        <input type="text" name="name" placeholder="Nom du groupe" class="w-full rounded-md border-gray-300" required>
                        <input type="text" name="school_name" placeholder="Établissement (optionnel)" class="w-full rounded-md border-gray-300">
                        <input type="text" name="class_name" placeholder="Classe (optionnel)" class="w-full rounded-md border-gray-300">
                        <textarea name="description" placeholder="Description" class="w-full rounded-md border-gray-300"></textarea>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md">Créer</button>
                    </form>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h2 class="font-semibold text-lg mb-4">Élèves en retard</h2>
                    @forelse($lateStudents as $row)
                        <div class="border-b py-2 text-sm">
                            <span class="font-medium">{{ $row['student_name'] }}</span>
                            <span class="text-gray-500">({{ $row['group_name'] }})</span>
                            <span class="text-danger-600">- {{ $row['late_count'] }} devoir(s) en retard</span>
                        </div>
                    @empty
                        <p class="text-gray-500">Aucun retard détecté.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="font-semibold text-lg mb-4">Exercices les plus bloquants</h2>
                @forelse($blockingExercises as $exercise)
                    <div class="flex justify-between border-b py-2 text-sm">
                        <span>{{ $exercise['title'] }}</span>
                        <span class="text-gray-500">{{ $exercise['success_rate'] }}% réussite ({{ $exercise['attempted'] }} tentatives)</span>
                    </div>
                @empty
                    <p class="text-gray-500">Pas assez de données.</p>
                @endforelse
            </div>

            <div class="space-y-6">
                @forelse($groups as $group)
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="font-semibold text-lg">{{ $group->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $group->school_name }} {{ $group->class_name }}</p>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <form method="POST" action="{{ route('teacher.groups.students.attach', $group) }}" class="space-y-2">
                                @csrf
                                <label class="text-sm font-medium">Ajouter un élève</label>
                                <select name="student_id" class="w-full rounded-md border-gray-300" required>
                                    <option value="">Choisir...</option>
                                    @foreach($candidateStudents as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-3 py-2 bg-gray-900 text-white rounded-md text-sm">Ajouter</button>
                            </form>

                            <form method="POST" action="{{ route('teacher.groups.assignments.store', $group) }}" class="space-y-2">
                                @csrf
                                <label class="text-sm font-medium">Assigner un devoir</label>
                                <input type="text" name="title" class="w-full rounded-md border-gray-300" placeholder="Titre du devoir" required>
                                <select name="content_type" class="w-full rounded-md border-gray-300" required>
                                    <option value="exercise">Exercice</option>
                                    <option value="lesson">Leçon</option>
                                </select>
                                <select name="content_id" class="w-full rounded-md border-gray-300" required>
                                    <optgroup label="Exercices">
                                        @foreach($exercises as $exercise)
                                            <option value="{{ $exercise->id }}">[Exercice] {{ $exercise->title }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Leçons">
                                        @foreach($lessons as $lesson)
                                            <option value="{{ $lesson->id }}">[Leçon] {{ $lesson->title }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                <textarea name="instructions" class="w-full rounded-md border-gray-300" placeholder="Consignes"></textarea>
                                <input type="datetime-local" name="due_at" class="w-full rounded-md border-gray-300">
                                <button type="submit" class="px-3 py-2 bg-primary-600 text-white rounded-md text-sm">Assigner</button>
                            </form>
                        </div>

                        <div class="mt-5">
                            <p class="font-medium text-sm mb-2">Élèves ({{ $group->students->count() }})</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($group->students as $student)
                                    <span class="px-2 py-1 rounded bg-gray-100 text-sm">{{ $student->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-6 rounded-lg shadow-sm text-gray-500">Aucun groupe pour le moment.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
