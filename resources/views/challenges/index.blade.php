<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900">Défis hebdomadaires</h1>
                <p class="text-gray-600 mt-1">Classement du {{ $start->format('d/m/Y') }} au {{ $end->format('d/m/Y') }}.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="font-semibold text-lg mb-4">Top défis de la semaine</h2>
                    <div class="space-y-3">
                        @forelse($challenges as $challenge)
                            <a href="{{ route('exercises.show', $challenge->slug) }}" class="block p-3 rounded-lg border hover:bg-gray-50">
                                <div class="font-medium">{{ $challenge->title }}</div>
                                <div class="text-xs text-gray-500">Tentatives cette semaine: {{ $challenge->weekly_attempts }}</div>
                            </a>
                        @empty
                            <p class="text-gray-500">Aucun défi disponible cette semaine.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="font-semibold text-lg mb-4">Classement hebdomadaire</h2>
                    <div class="space-y-2">
                        @forelse($leaderboard as $index => $row)
                            <div class="flex justify-between items-center border-b pb-2 text-sm">
                                <span>#{{ $index + 1 }} {{ $row['name'] }}</span>
                                <span class="font-semibold">{{ $row['score'] }} pts</span>
                            </div>
                        @empty
                            <p class="text-gray-500">Aucune activité cette semaine.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
