@extends('layouts.app')

@section('title', 'Abonnement formation')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl shadow-sm border border-orange-100 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-8 py-7 text-white">
            <h1 class="text-2xl font-bold">Abonnement à la formation activé</h1>
            <p class="text-orange-50 mt-2">Consultez la durée de validité et la date limite pour terminer la formation.</p>
        </div>

        <div class="p-8">
            @if(session('success'))
                <div class="bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-orange-50 rounded-xl border border-orange-100 p-5">
                    <p class="text-sm text-orange-700">Formation</p>
                    <p class="text-lg font-bold text-orange-900 mt-1">{{ $formation->title }}</p>
                    <p class="text-sm text-orange-700 mt-2">Paiement : {{ number_format($formation->price, 0, ',', ' ') }} Ar</p>
                </div>

                <div class="bg-amber-50 rounded-xl border border-amber-100 p-5">
                    <p class="text-sm text-amber-700">Durée de validité</p>
                    <p class="text-3xl font-bold text-amber-900 mt-1">{{ $formation->validity_days }} jours</p>
                    <p class="text-xs text-amber-700 mt-2">Référence : {{ $enrollment->payment_reference }}</p>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
                <p class="text-sm font-medium text-gray-600">Deadline pour terminer la formation</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    {{ $deadline ? $deadline->translatedFormat('d F Y à H:i') : 'Non définie' }}
                </p>
                @if(!is_null($remainingDays))
                    <p class="mt-2 text-sm {{ $remainingDays > 0 ? 'text-gray-600' : 'text-red-600 font-semibold' }}">
                        @if($remainingDays > 0)
                            Il vous reste <strong>{{ $remainingDays }} jour{{ $remainingDays > 1 ? 's' : '' }}</strong> pour finir cette formation.
                        @else
                            Le délai est dépassé. Veuillez renouveler votre accès.
                        @endif
                    </p>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('formations.access', $formation) }}" class="btn btn-primary flex-1 text-center">Continuer la formation</a>
                <a href="{{ route('formations.my') }}" class="btn btn-secondary flex-1 text-center">Mes formations</a>
            </div>
        </div>
    </div>
</div>
@endsection
