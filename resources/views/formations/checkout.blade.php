@extends('layouts.app')

@section('title', 'Paiement formation')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement de la formation</h1>
        <p class="text-gray-600 mb-6">Ce paiement est indépendant de votre abonnement Free/Premium.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="font-semibold text-gray-900">{{ $formation->title }}</p>
            <p class="text-primary-700 text-xl font-bold mt-2">{{ number_format($formation->price, 2, ',', ' ') }} €</p>
        </div>

        <form method="POST" action="{{ route('formations.purchase', $formation) }}" class="space-y-5">
            @csrf
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                <select id="payment_method" name="payment_method" class="w-full border-gray-300 rounded-lg" required>
                    <option value="card">Carte bancaire</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="bank_transfer">Virement bancaire</option>
                    <option value="cryptocurrency">Cryptomonnaie</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">Payer et débloquer la formation</button>
        </form>
    </div>
</div>
@endsection
