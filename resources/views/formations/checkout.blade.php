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
            @php
                $selectedMethod = old('payment_method', 'card');
            @endphp

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                <select id="payment_method" name="payment_method" class="w-full border-gray-300 rounded-lg" required>
                    <option value="card" @selected($selectedMethod === 'card')>Carte bancaire</option>
                    <option value="mobile_money" @selected($selectedMethod === 'mobile_money')>Mobile Money</option>
                    <option value="bank_transfer" @selected($selectedMethod === 'bank_transfer')>Virement bancaire</option>
                    <option value="cryptocurrency" @selected($selectedMethod === 'cryptocurrency')>Cryptomonnaie</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="card-fields" class="rounded-lg border border-gray-200 p-4 space-y-4" data-payment-fields="card" @if($selectedMethod !== 'card') hidden @endif>
                <p class="text-sm font-semibold text-gray-800">Paiement par carte bancaire</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="card_name" class="block text-sm font-medium text-gray-700 mb-1">Nom sur la carte</label>
                        <input id="card_name" name="card_name" type="text" class="w-full border-gray-300 rounded-lg" value="{{ old('card_name') }}" placeholder="Ex: Jean Dupont">
                        @error('card_name')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                        <input id="card_number" name="card_number" type="text" class="w-full border-gray-300 rounded-lg" value="{{ old('card_number') }}" placeholder="0000 0000 0000 0000">
                        @error('card_number')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="card_expiry" class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                        <input id="card_expiry" name="card_expiry" type="text" class="w-full border-gray-300 rounded-lg" value="{{ old('card_expiry') }}" placeholder="MM/AA">
                        @error('card_expiry')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="card_cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                        <input id="card_cvv" name="card_cvv" type="password" class="w-full border-gray-300 rounded-lg" value="{{ old('card_cvv') }}" placeholder="123">
                        @error('card_cvv')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="mobile-money-fields" class="rounded-lg border border-gray-200 p-4 space-y-4" data-payment-fields="mobile_money" @if($selectedMethod !== 'mobile_money') hidden @endif>
                <p class="text-sm font-semibold text-gray-800">Paiement Mobile Money</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="mobile_operator" class="block text-sm font-medium text-gray-700 mb-1">Opérateur</label>
                        <select id="mobile_operator" name="mobile_operator" class="w-full border-gray-300 rounded-lg">
                            <option value="">Sélectionnez un opérateur</option>
                            @foreach(['Orange Money', 'MTN Mobile Money', 'Moov Money', 'Wave'] as $operator)
                                <option value="{{ $operator }}" @selected(old('mobile_operator') === $operator)>{{ $operator }}</option>
                            @endforeach
                        </select>
                        @error('mobile_operator')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                        <input id="mobile_number" name="mobile_number" type="text" class="w-full border-gray-300 rounded-lg" value="{{ old('mobile_number') }}" placeholder="Ex: +2250700000000">
                        @error('mobile_number')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="bank-transfer-fields" class="rounded-lg border border-gray-200 p-4 space-y-4" data-payment-fields="bank_transfer" @if($selectedMethod !== 'bank_transfer') hidden @endif>
                <p class="text-sm font-semibold text-gray-800">Paiement par virement bancaire</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la banque</label>
                        <input id="bank_name" name="bank_name" type="text" class="w-full border-gray-300 rounded-lg" value="{{ old('bank_name') }}" placeholder="Ex: Ecobank">
                        @error('bank_name')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="account_name" class="block text-sm font-medium text-gray-700 mb-1">Nom du titulaire</label>
                        <input id="account_name" name="account_name" type="text" class="w-full border-gray-300 rounded-lg" value="{{ old('account_name') }}" placeholder="Ex: Jean Dupont">
                        @error('account_name')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="transfer_reference" class="block text-sm font-medium text-gray-700 mb-1">Référence du virement</label>
                        <input id="transfer_reference" name="transfer_reference" type="text" class="w-full border-gray-300 rounded-lg" value="{{ old('transfer_reference') }}" placeholder="Ex: VIR-2026-001234">
                        @error('transfer_reference')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="crypto-fields" class="rounded-lg border border-gray-200 p-4 space-y-4" data-payment-fields="cryptocurrency" @if($selectedMethod !== 'cryptocurrency') hidden @endif>
                <p class="text-sm font-semibold text-gray-800">Paiement en cryptomonnaie</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="crypto_network" class="block text-sm font-medium text-gray-700 mb-1">Réseau / crypto</label>
                        <select id="crypto_network" name="crypto_network" class="w-full border-gray-300 rounded-lg">
                            <option value="">Sélectionnez un réseau</option>
                            @foreach(['Bitcoin (BTC)', 'Ethereum (ETH)', 'USDT (TRC20)', 'USDT (ERC20)'] as $crypto)
                                <option value="{{ $crypto }}" @selected(old('crypto_network') === $crypto)>{{ $crypto }}</option>
                            @endforeach
                        </select>
                        @error('crypto_network')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="wallet_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse du wallet</label>
                        <input id="wallet_address" name="wallet_address" type="text" class="w-full border-gray-300 rounded-lg" value="{{ old('wallet_address') }}" placeholder="Ex: 0x...">
                        @error('wallet_address')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
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


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('payment_method');
        if (!select) return;

        const sections = document.querySelectorAll('[data-payment-fields]');

        const toggleSections = () => {
            const value = select.value;

            sections.forEach(section => {
                const isActive = section.dataset.paymentFields === value;
                section.hidden = !isActive;

                section.querySelectorAll('input, select, textarea').forEach(field => {
                    field.disabled = !isActive;
                });
            });
        };

        toggleSections();
        select.addEventListener('change', toggleSections);
    });
</script>
@endpush
