@extends('layouts.app')

@section('title', 'Paiement formation')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement de la formation</h1>
        <p class="text-gray-600 mb-6">Ce paiement est indépendant de votre abonnement Free/Premium.</p>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-700">Compte connecté</p>
            <p class="font-semibold text-blue-900">{{ $user->name }} ({{ $user->email }})</p>
            <p class="text-sm text-blue-800 mt-1">Type de compte : <strong>{{ $accountType }}</strong></p>
            <p class="text-xs text-blue-700 mt-2">L'accès à la formation sera rattaché à ce compte utilisateur.</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="font-semibold text-gray-900">{{ $formation->title }}</p>
            <p class="text-primary-700 text-xl font-bold mt-2">{{ number_format($formation->price, 2, ',', ' ') }} €</p>
        </div>

        <form method="POST" action="{{ route('formations.purchase', $formation) }}" class="space-y-5">
            @csrf
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                <select id="payment_method" name="payment_method" class="w-full border-gray-300 rounded-lg" required>
                    <option value="card" @selected(old('payment_method') === 'card')>Carte bancaire</option>
                    <option value="mobile_money" @selected(old('payment_method') === 'mobile_money')>Mobile Money</option>
                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Virement bancaire</option>
                    <option value="cryptocurrency" @selected(old('payment_method') === 'cryptocurrency')>Cryptomonnaie</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="billing_email" class="block text-sm font-medium text-gray-700 mb-2">Email de facturation</label>
                <input
                    id="billing_email"
                    type="email"
                    name="billing_email"
                    value="{{ old('billing_email', $user->email) }}"
                    class="w-full border-gray-300 rounded-lg"
                    required
                >
                <p class="mt-1 text-xs text-gray-500">Doit correspondre à l'email de votre compte pour valider l'achat.</p>
                @error('billing_email')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <label class="inline-flex items-start gap-2 text-sm text-gray-700" for="accept_terms">
                    <input id="accept_terms" type="checkbox" name="accept_terms" value="1" class="mt-1" @checked(old('accept_terms')) required>
                    <span>
                        Je confirme que ce paiement sera rattaché à mon compte
                        <strong>{{ $user->email }}</strong> et j'accepte les conditions d'achat.
                    </span>
                </label>
                @error('accept_terms')
                    <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">Payer et débloquer la formation</button>
        </form>
    </div>
</div>
@endsection
