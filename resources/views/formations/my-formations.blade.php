@extends('layouts.app')

@section('title', 'Mes formations achetées')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes formations achetées</h1>
        <p class="mt-2 text-gray-600">Ici vous voyez clairement les formations dont vous faites partie après paiement.</p>
    </div>

    <div class="space-y-4">
        @forelse($enrollments as $enrollment)
            <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $enrollment->formation->title }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Achat le {{ optional($enrollment->paid_at)->format('d/m/Y H:i') }} • Réf: {{ $enrollment->payment_reference ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Montant payé</p>
                        <p class="text-lg font-bold text-primary-700">{{ number_format($enrollment->amount_paid, 2, ',', ' ') }} €</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('formations.show', $enrollment->formation->slug) }}" class="btn btn-primary">Accéder à la formation</a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl p-8 text-center text-gray-500 border border-gray-100">
                Vous n'avez pas encore acheté de formation.
            </div>
        @endforelse
    </div>
</div>
@endsection
