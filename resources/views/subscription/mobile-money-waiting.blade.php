@extends('layouts.app')

@section('title', 'Mobile Money en attente - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-mobile-alt text-4xl text-purple-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Paiement Mobile Money en cours</h1>
            <p class="text-gray-600 mt-2">Vérifiez votre téléphone pour confirmer le paiement</p>
        </div>

        <!-- Payment Status Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-clock text-2xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">En attente de confirmation</h2>
                <p class="text-gray-600">Une demande de paiement a été envoyée à votre numéro Mobile Money</p>
            </div>

            <!-- Payment Details -->
            <div class="border rounded-lg p-6 mb-6">
                <h3 class="font-bold text-gray-900 mb-4">Détails du paiement</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Numéro :</span>
                        <span class="font-bold">{{ $payment->phone_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Opérateur :</span>
                        <span class="font-bold">{{ $payment->operator }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Montant :</span>
                        <!-- AFFICHER EN MGA -->
                        <span class="text-xl font-bold text-primary-600">{{ number_format($payment->amount, 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Référence :</span>
                        <span class="font-mono">{{ $payment->transaction_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Code marchand :</span>
                        @php
                            $metadata = json_decode($payment->metadata, true);
                            $plan = $metadata['plan'] ?? 'monthly';
                        @endphp
                        <span class="font-mono font-bold">CODELEARN{{ strtoupper($plan) }}</span>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <h4 class="font-bold text-yellow-800 mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i> Instructions
                </h4>
                <ol class="text-yellow-700 space-y-3">
                    <li class="flex items-start">
                        <span class="font-bold mr-2">1.</span>
                        <span>Gardez votre téléphone à proximité</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">2.</span>
                        <span>Vous recevrez une demande de paiement Mobile Money</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">3.</span>
                        <span>Entrez votre code PIN pour confirmer</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">4.</span>
                        <span>Votre abonnement sera activé automatiquement</span>
                    </li>
                </ol>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <h4 class="font-bold text-gray-900 mb-4">Statut</h4>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-spinner fa-spin text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">En attente de confirmation</p>
                        <p class="text-sm text-gray-500">Vérifiez votre téléphone</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline flex-1">
                    <i class="fas fa-home mr-2"></i> Tableau de bord
                </a>
                <button type="button" onclick="checkPaymentStatus('{{ $payment->id }}')" class="btn btn-primary flex-1">
                    <i class="fas fa-sync-alt mr-2"></i> Vérifier le statut
                </button>
            </div>
        </div>

        <!-- Support -->
        <div class="text-center">
            <p class="text-sm text-gray-500">
                <i class="fas fa-headset mr-1"></i>
                Problème ? Contactez le support au 
                <a href="tel:+261341234567" class="text-primary-600 hover:text-primary-700">+261 34 12 345 67</a>
            </p>
        </div>
    </div>
</div>
@endsection

<!-- Dans la partie scripts de mobile-money-waiting.blade.php -->
@push('scripts')
<script>
    function checkPaymentStatus(paymentId) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Vérification...';
        button.disabled = true;
        
        // CORRIGÉ: Utiliser la route correcte
        fetch(`/abonnement/verifier/${paymentId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'completed') {
                    window.location.reload();
                } else {
                    alert('Paiement toujours en attente. Vérifiez votre téléphone.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur de vérification.');
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }
    
    // Vérification automatique
    document.addEventListener('DOMContentLoaded', function() {
        let checkCount = 0;
        const maxChecks = 20;
        
        function autoCheck() {
            if (checkCount >= maxChecks) return;
            
            checkCount++;
            console.log(`Vérification automatique (${checkCount}/${maxChecks})...`);
            
            // CORRIGÉ: Utiliser la route correcte
            fetch(`/abonnement/verifier/{{ $payment->id }}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'completed') {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Auto-check error:', error);
                });
        }
        
        // Vérifier toutes les 30 secondes
        for (let i = 1; i <= maxChecks; i++) {
            setTimeout(autoCheck, i * 30000);
        }
    });
</script>
@endpush
