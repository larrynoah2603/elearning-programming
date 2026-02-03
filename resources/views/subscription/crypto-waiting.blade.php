@extends('layouts.app')

@section('title', 'En attente de paiement - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fab fa-bitcoin text-4xl text-yellow-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Paiement en cours</h1>
            <p class="text-gray-600 mt-2">En attente de votre paiement en cryptomonnaie</p>
        </div>

        <!-- Payment Status Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-clock text-2xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">En attente de confirmation</h2>
                <p class="text-gray-600">Votre transaction est en cours de traitement sur le réseau blockchain</p>
            </div>

            <!-- Payment Details -->
            <div class="border rounded-lg p-6 mb-6">
                <h3 class="font-bold text-gray-900 mb-4">Détails du paiement</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cryptomonnaie :</span>
                        <span class="font-bold">{{ strtoupper(session('crypto_currency', 'BTC')) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Montant :</span>
                        <span class="font-mono font-bold">{{ session('crypto_amount') }} {{ strtoupper(session('crypto_currency', 'BTC')) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Équivalent :</span>
                        <span class="font-bold text-primary-600">{{ number_format($planDetails['price'], 0) }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Adresse de réception :</span>
                        <span class="font-mono text-sm break-all text-right">
                            {{ session('receiving_address', '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Transaction :</span>
                        <span class="font-mono text-sm">TX-{{ strtoupper(Str::random(12)) }}</span>
                    </div>
                </div>
            </div>

            <!-- QR Code & Countdown -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <!-- QR Code -->
                <div class="text-center">
                    <h4 class="font-bold text-gray-900 mb-4">QR Code pour paiement</h4>
                    <div class="bg-gray-100 p-4 rounded-lg inline-block">
                        <!-- QR Code Placeholder -->
                        <div class="w-48 h-48 bg-white p-2 mx-auto">
                            <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-500 text-sm">QR Code généré</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Scannez pour envoyer le paiement</p>
                </div>

                <!-- Countdown -->
                <div class="text-center">
                    <h4 class="font-bold text-gray-900 mb-4">Temps restant</h4>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div id="countdown-timer" class="text-3xl font-bold text-primary-600 mb-2">
                            15:00
                        </div>
                        <p class="text-gray-600">Le taux est garanti pendant :</p>
                        <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                            <p class="text-sm text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Après ce délai, le montant sera recalculé
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h4 class="font-bold text-blue-800 mb-4">
                    <i class="fas fa-info-circle mr-2"></i> Instructions importantes
                </h4>
                <ol class="text-blue-700 space-y-3">
                    <li class="flex items-start">
                        <span class="font-bold mr-2">1.</span>
                        <span>Envoyez exactement <strong>{{ session('crypto_amount') }} {{ strtoupper(session('crypto_currency', 'BTC')) }}</strong> à l'adresse ci-dessus</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">2.</span>
                        <span>Les frais de réseau sont à votre charge</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">3.</span>
                        <span>Attendez 1-3 confirmations réseau (10-30 minutes)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">4.</span>
                        <span>Vous recevrez un email de confirmation une fois le paiement validé</span>
                    </li>
                </ol>
            </div>

            <!-- Status Updates -->
            <div class="mb-8">
                <h4 class="font-bold text-gray-900 mb-4">Statut de la transaction</h4>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">Transaction initiée</p>
                            <p class="text-sm text-gray-500">{{ date('H:i:s') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-spinner fa-spin text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">En attente de paiement</p>
                            <p class="text-sm text-gray-500">En attente sur le réseau blockchain</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-gray-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-400">Confirmations réseau</p>
                            <p class="text-sm text-gray-500">En attente (0/3)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline flex-1">
                    <i class="fas fa-home mr-2"></i> Retour au tableau de bord
                </a>
                <button type="button" onclick="checkPaymentStatus()" class="btn btn-primary flex-1">
                    <i class="fas fa-sync-alt mr-2"></i> Vérifier le statut
                </button>
            </div>
        </div>

        <!-- Support -->
        <div class="text-center">
            <p class="text-sm text-gray-500">
                <i class="fas fa-headset mr-1"></i>
                Problème avec le paiement ? Contactez le support crypto au 
                <a href="mailto:crypto@codelearn.com" class="text-primary-600 hover:text-primary-700">crypto@codelearn.com</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let countdownTime = 15 * 60; // 15 minutes in seconds
    
    function updateCountdown() {
        const minutes = Math.floor(countdownTime / 60);
        const seconds = countdownTime % 60;
        
        document.getElementById('countdown-timer').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (countdownTime <= 0) {
            // Time's up - show warning
            document.getElementById('countdown-timer').classList.add('text-red-600');
            document.querySelector('.bg-yellow-50').classList.replace('bg-yellow-50', 'bg-red-50');
            document.querySelector('.text-yellow-700').classList.replace('text-yellow-700', 'text-red-700');
            document.querySelector('.fa-exclamation-triangle').classList.replace('text-yellow-700', 'text-red-700');
            document.querySelector('.fa-exclamation-triangle').parentElement.innerHTML = 
                '<i class="fas fa-exclamation-triangle mr-1"></i> Le temps est écoulé ! Le montant sera recalculé';
            return;
        }
        
        countdownTime--;
        setTimeout(updateCountdown, 1000);
    }
    
    function checkPaymentStatus() {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Vérification...';
        button.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
            alert('Paiement toujours en attente. Vérifiez votre portefeuille crypto.');
        }, 2000);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        updateCountdown();
        
        // Auto-refresh status every 30 seconds
        setInterval(() => {
            console.log('Vérification automatique du statut...');
        }, 30000);
        
        // Copy address to clipboard
        const address = '{{ session('receiving_address') }}';
        if (address) {
            const copyBtn = document.createElement('button');
            copyBtn.className = 'btn btn-sm btn-outline mt-2';
            copyBtn.innerHTML = '<i class="fas fa-copy mr-1"></i> Copier l\'adresse';
            copyBtn.onclick = function() {
                navigator.clipboard.writeText(address);
                alert('Adresse copiée dans le presse-papier !');
            };
            document.querySelector('span.font-mono').parentElement.appendChild(copyBtn);
        }
    });
</script>
@endpush