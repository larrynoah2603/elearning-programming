@extends('layouts.app')

@section('title', 'Paiement par Carte - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="far fa-credit-card text-3xl text-green-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Paiement par Carte</h1>
            <p class="text-gray-600 mt-2">Paiement sécurisé par carte bancaire</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Récapitulatif de commande</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <div>
                            <h3 class="font-medium text-gray-900">Abonnement {{ $planDetails['name'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $planDetails['days'] }} jours</p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-primary-600">{{ number_format($planDetails['price_usd'], 2) }} USD</div>
                            <div class="text-sm text-gray-500">TTC</div>
                        </div>
                    </div>

                    <!-- Accepted Cards -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-3">Cartes acceptées</h4>
                        <div class="flex space-x-3">
                            <div class="p-2 bg-gray-100 rounded-lg">
                                <i class="fab fa-cc-visa text-3xl text-blue-600"></i>
                            </div>
                            <div class="p-2 bg-gray-100 rounded-lg">
                                <i class="fab fa-cc-mastercard text-3xl text-red-600"></i>
                            </div>
                            <div class="p-2 bg-gray-100 rounded-lg">
                                <i class="fab fa-cc-paypal text-3xl text-blue-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Security Badges -->
                    <div class="pt-4 border-t">
                        <div class="flex items-center justify-center space-x-6">
                            <div class="text-center">
                                <i class="fas fa-lock text-green-500 text-2xl mb-1"></i>
                                <p class="text-xs text-gray-600">Paiement sécurisé</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-shield-alt text-blue-500 text-2xl mb-1"></i>
                                <p class="text-xs text-gray-600">SSL 256-bit</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-user-shield text-purple-500 text-2xl mb-1"></i>
                                <p class="text-xs text-gray-600">Protection des données</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informations de paiement</h2>
                
                <form method="POST" action="{{ route('subscription.process-card') }}" id="card-form">
                    @csrf
                    <input type="hidden" name="plan" value="{{ $plan }}">
                    <input type="hidden" name="payment_method" value="card">
                    
                    <!-- Card Number -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de carte</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="far fa-credit-card text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="card_number"
                                   class="form-input pl-10 w-full"
                                   placeholder="1234 5678 9012 3456"
                                   maxlength="19"
                                   required
                                   data-inputmask="'mask': '9999 9999 9999 9999'">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <!-- Expiry Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date d'expiration</label>
                            <input type="text" 
                                   name="expiry_date"
                                   class="form-input w-full"
                                   placeholder="MM/AA"
                                   maxlength="5"
                                   required
                                   data-inputmask="'mask': '99/99'">
                        </div>

                        <!-- CVV -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                            <div class="relative">
                                <input type="text" 
                                       name="cvv"
                                       class="form-input w-full pr-10"
                                       placeholder="123"
                                       maxlength="4"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-question-circle text-gray-400" 
                                       title="3 ou 4 chiffres au dos de votre carte"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cardholder Name -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom sur la carte</label>
                        <input type="text" 
                               name="cardholder_name"
                               class="form-input w-full uppercase"
                               placeholder="M. JOHN DOE"
                               required>
                    </div>

                    <!-- Email for Receipt -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email pour reçu</label>
                        <input type="email" 
                               name="email"
                               value="{{ auth()->user()->email ?? '' }}"
                               class="form-input w-full"
                               required>
                    </div>

                    <!-- Terms -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" name="terms" class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" required>
                            <span class="ml-2 text-sm text-gray-600">
                                J'autorise le prélèvement de {{ number_format($planDetails['price_usd'], 2) }} USD sur ma carte.
                                Je comprends que cette transaction est sécurisée et cryptée.
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full py-3">
                        <i class="fas fa-lock mr-2"></i> Payer {{ number_format($planDetails['price_usd'], 2) }} USD
                    </button>
                </form>
            </div>
        </div>

        <!-- Security Info -->
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">
                <i class="fas fa-shield-alt text-green-500 mr-2"></i> Sécurité garantie
            </h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Cryptage SSL</h4>
                        <p class="text-sm text-gray-600">Toutes les données sont cryptées en 256-bit</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">3D Secure</h4>
                        <p class="text-sm text-gray-600">Authentification renforcée pour votre sécurité</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Sans stockage</h4>
                        <p class="text-sm text-gray-600">Nous ne stockons pas vos informations bancaires</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('card-form');
        
        // Format card number
        const cardNumberInput = form.querySelector('input[name="card_number"]');
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = value.substring(0, 19);
        });

        // Validate expiry date
        const expiryInput = form.querySelector('input[name="expiry_date"]');
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value.substring(0, 5);
            
            // Validate month
            if (value.length >= 2) {
                const month = parseInt(value.substring(0, 2));
                if (month < 1 || month > 12) {
                    e.target.setCustomValidity('Mois invalide (01-12)');
                } else {
                    e.target.setCustomValidity('');
                }
            }
        });

        form.addEventListener('submit', function(e) {
            // Basic validation
            const cardNumber = form.querySelector('input[name="card_number"]').value.replace(/\s/g, '');
            const expiryDate = form.querySelector('input[name="expiry_date"]').value;
            
            if (cardNumber.length < 16) {
                e.preventDefault();
                alert('Veuillez entrer un numéro de carte valide');
                return;
            }
            
            if (expiryDate.length !== 5) {
                e.preventDefault();
                alert('Veuillez entrer une date d\'expiration valide (MM/AA)');
                return;
            }
            
            // Show loading
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement en cours...';
            button.disabled = true;
            
            // 3D Secure simulation
            setTimeout(() => {
                // In production, this would redirect to 3D Secure page
                form.submit();
            }, 1000);
        });
    });
</script>
@endpush
