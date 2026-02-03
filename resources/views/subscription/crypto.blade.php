@extends('layouts.app')

@section('title', 'Paiement Crypto - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fab fa-bitcoin text-3xl text-yellow-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Paiement Cryptomonnaie</h1>
            <p class="text-gray-600 mt-2">Payez en cryptomonnaie de manière sécurisée</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Payment Details -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Détails du paiement</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <div>
                            <h3 class="font-medium text-gray-900">Abonnement {{ $planDetails['name'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $planDetails['days'] }} jours</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-primary-600">${{ number_format($planDetails['price'], 2) }}</div>
                            <div class="text-sm text-gray-500">USD</div>
                        </div>
                    </div>

                    <!-- Crypto Amounts -->
                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Montant en cryptomonnaie :</h4>
                        
                        <!-- Bitcoin -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fab fa-bitcoin text-yellow-500 text-xl mr-3"></i>
                                <div>
                                    <div class="font-medium">Bitcoin (BTC)</div>
                                    <div class="text-sm text-gray-500">Taux: ${{ number_format($cryptoRates['btc'] ?? 45000, 0) }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-bold">
                                    {{ number_format($planDetails['price'] / ($cryptoRates['btc'] ?? 45000), 6) }} BTC
                                </div>
                                <div class="text-sm text-gray-500">≈ ${{ number_format($planDetails['price'], 2) }}</div>
                            </div>
                        </div>

                        <!-- Ethereum -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fab fa-ethereum text-purple-500 text-xl mr-3"></i>
                                <div>
                                    <div class="font-medium">Ethereum (ETH)</div>
                                    <div class="text-sm text-gray-500">Taux: ${{ number_format($cryptoRates['eth'] ?? 3000, 0) }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-bold">
                                    {{ number_format($planDetails['price'] / ($cryptoRates['eth'] ?? 3000), 4) }} ETH
                                </div>
                                <div class="text-sm text-gray-500">≈ ${{ number_format($planDetails['price'], 2) }}</div>
                            </div>
                        </div>

                        <!-- USDT -->
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-coins text-green-500 text-xl mr-3"></i>
                                <div>
                                    <div class="font-medium">USDT (TRC20)</div>
                                    <div class="text-sm text-gray-500">Stablecoin 1:1</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-bold">
                                    {{ number_format($planDetails['price'], 2) }} USDT
                                </div>
                                <div class="text-sm text-gray-500">Tether</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Instructions de paiement</h2>
                
                <form method="POST" action="{{ route('subscription.process-crypto') }}" id="crypto-form">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $plan }}">
                    <input type="hidden" name="payment_method" value="crypto">
                    
                    <!-- Select Cryptocurrency -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Sélectionnez la cryptomonnaie</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="cryptocurrency" value="btc" class="text-yellow-600 focus:ring-yellow-500" required>
                                <i class="fab fa-bitcoin text-2xl text-yellow-500 my-2"></i>
                                <span class="text-sm font-medium">Bitcoin</span>
                            </label>
                            <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="cryptocurrency" value="eth" class="text-purple-600 focus:ring-purple-500" required>
                                <i class="fab fa-ethereum text-2xl text-purple-500 my-2"></i>
                                <span class="text-sm font-medium">Ethereum</span>
                            </label>
                            <label class="flex flex-col items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="cryptocurrency" value="usdt" class="text-green-600 focus:ring-green-500" required>
                                <i class="fas fa-coins text-2xl text-green-500 my-2"></i>
                                <span class="text-sm font-medium">USDT</span>
                            </label>
                        </div>
                    </div>

                    <!-- Wallet Address -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Votre adresse de portefeuille</label>
                        <input type="text" 
                               name="wallet_address"
                               class="form-input w-full font-mono text-sm"
                               placeholder="Ex: 1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Entrez l'adresse d'où vous enverrez les fonds</p>
                    </div>

                    <!-- Email for Notification -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email de confirmation</label>
                        <input type="email" 
                               name="email"
                               value="{{ auth()->user()->email ?? '' }}"
                               class="form-input w-full"
                               required>
                    </div>

                    <!-- Important Notice -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-bold text-blue-800 mb-1">Informations importantes</h4>
                                <ul class="text-blue-700 text-sm space-y-1">
                                    <li>• Les adresses de réception seront affichées après confirmation</li>
                                    <li>• Le taux est bloqué pendant 15 minutes</li>
                                    <li>• Minimum 1 confirmation réseau requise</li>
                                    <li>• Les frais de réseau sont à votre charge</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" name="terms" class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" required>
                            <span class="ml-2 text-sm text-gray-600">
                                Je comprends que les transactions cryptos sont irréversibles et j'ai vérifié l'adresse de destination.
                                J'accepte les <a href="#" class="text-primary-600 hover:text-primary-700">conditions de service</a>.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full py-3">
                        <i class="fas fa-arrow-right mr-2"></i> Continuer vers l'adresse de paiement
                    </button>
                </form>
            </div>
        </div>

        <!-- Security Info -->
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">Sécurité et Support</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="text-center p-4">
                    <i class="fas fa-shield-alt text-green-500 text-2xl mb-2"></i>
                    <h4 class="font-medium text-gray-900">Sécurisé</h4>
                    <p class="text-sm text-gray-600">Transactions cryptographiques sécurisées</p>
                </div>
                <div class="text-center p-4">
                    <i class="fas fa-bolt text-yellow-500 text-2xl mb-2"></i>
                    <h4 class="font-medium text-gray-900">Rapide</h4>
                    <p class="text-sm text-gray-600">Confirmation en 10-30 minutes</p>
                </div>
                <div class="text-center p-4">
                    <i class="fas fa-headset text-blue-500 text-2xl mb-2"></i>
                    <h4 class="font-medium text-gray-900">Support 24/7</h4>
                    <p class="text-sm text-gray-600">Support dédié cryptomonnaie</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('crypto-form');
        const cryptoRadios = form.querySelectorAll('input[name="cryptocurrency"]');
        const walletInput = form.querySelector('input[name="wallet_address"]');
        
        // Auto-validate wallet address format
        cryptoRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'btc') {
                    walletInput.placeholder = 'Ex: 1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa';
                } else if (this.value === 'eth') {
                    walletInput.placeholder = 'Ex: 0x742d35Cc6634C0532925a3b844Bc9e...';
                } else if (this.value === 'usdt') {
                    walletInput.placeholder = 'Ex: TXnsVtXgW9iWk... (TRC20)';
                }
            });
        });
        
        form.addEventListener('submit', function(e) {
            const selectedCrypto = form.querySelector('input[name="cryptocurrency"]:checked');
            if (!selectedCrypto) {
                e.preventDefault();
                alert('Veuillez sélectionner une cryptomonnaie');
                return;
            }
            
            // Show loading
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Génération de l\'adresse...';
            button.disabled = true;
        });
    });
</script>
@endpush