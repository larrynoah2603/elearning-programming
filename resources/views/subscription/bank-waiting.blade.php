@extends('layouts.app')

@section('title', 'Virement en attente - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-university text-4xl text-blue-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Virement en attente</h1>
            <p class="text-gray-600 mt-2">Votre virement bancaire a été enregistré</p>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">En attente de réception</h2>
                <p class="text-gray-600">Nous attendons la réception de votre virement bancaire</p>
            </div>

            <!-- Order Summary -->
            <div class="border rounded-lg p-6 mb-6">
                <h3 class="font-bold text-gray-900 mb-4">Récapitulatif de votre commande</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Référence :</span>
                        <span class="font-mono font-bold">CODE-{{ strtoupper($plan) }}-{{ auth()->user()->id }}-{{ date('Ymd') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Abonnement :</span>
                        <span class="font-bold">{{ $planDetails['name'] }} ({{ $planDetails['days'] }} jours)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Montant :</span>
                        <span class="text-xl font-bold text-primary-600">{{ number_format($planDetails['price'], 0) }} MGA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date prévue :</span>
                        <span class="font-medium">{{ session('transfer_date', date('d/m/Y')) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Votre banque :</span>
                        <span class="font-medium">{{ session('bank_name', 'Non spécifiée') }}</span>
                    </div>
                </div>
            </div>

            <!-- Bank Details (Again for reference) -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h4 class="font-bold text-gray-900 mb-4">
                    <i class="fas fa-university mr-2"></i> Coordonnées bancaires
                </h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Banque :</span>
                        <span class="font-medium">Banque Atlantique Côte d'Ivoire</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nom du compte :</span>
                        <span class="font-medium">CODELEARN SARL</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Numéro de compte :</span>
                        <span class="font-mono font-bold">CI008 01020 12345678901 98</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Code IBAN :</span>
                        <span class="font-mono font-bold">CI08 CI008 01020 12345678901 98</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Code BIC/SWIFT :</span>
                        <span class="font-mono font-bold">ATLACICI</span>
                    </div>
                </div>
            </div>

            <!-- Important Instructions -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <h4 class="font-bold text-yellow-800 mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i> Prochaines étapes
                </h4>
                <ol class="text-yellow-700 space-y-3">
                    <li class="flex items-start">
                        <span class="font-bold mr-2">1.</span>
                        <span>Effectuez le virement avec la référence <strong>CODE-{{ strtoupper($plan) }}-{{ auth()->user()->id }}-{{ date('Ymd') }}</strong></span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">2.</span>
                        <span>Envoyez une copie du reçu de virement à <a href="mailto:paiement@codelearn.com" class="underline font-medium">paiement@codelearn.com</a></span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">3.</span>
                        <span>Notre équipe vérifiera la réception des fonds (1-3 jours ouvrés)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">4.</span>
                        <span>Vous recevrez un email de confirmation et votre abonnement sera activé</span>
                    </li>
                </ol>
                <div class="mt-4 p-4 bg-white rounded border border-yellow-300">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-lightbulb mr-2"></i>
                        <strong>Conseil :</strong> Prenez une capture d'écran de votre confirmation de virement pour l'envoyer plus rapidement.
                    </p>
                </div>
            </div>

            <!-- Timeline -->
            <div class="mb-8">
                <h4 class="font-bold text-gray-900 mb-6">Suivi de votre demande</h4>
                <div class="relative">
                    <!-- Timeline line -->
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    
                    <!-- Steps -->
                    <div class="space-y-8">
                        <!-- Step 1 -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-900">Demande enregistrée</h5>
                                <p class="text-gray-600">Votre demande de virement a été enregistrée</p>
                                <p class="text-sm text-gray-500">{{ date('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-spinner fa-spin text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-900">En attente de virement</h5>
                                <p class="text-gray-600">En attente de réception de votre virement</p>
                                <p class="text-sm text-gray-500">En cours</p>
                            </div>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-file-invoice-dollar text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-400">Réception des fonds</h5>
                                <p class="text-gray-400">À venir</p>
                            </div>
                        </div>
                        
                        <!-- Step 4 -->
                        <div class="relative flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user-check text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-400">Activation de l'abonnement</h5>
                                <p class="text-gray-400">À venir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline flex-1">
                    <i class="fas fa-home mr-2"></i> Tableau de bord
                </a>
                <a href="mailto:paiement@codelearn.com?subject=Reçu virement CODE-{{ strtoupper($plan) }}-{{ auth()->user()->id }}-{{ date('Ymd') }}" 
                   class="btn btn-primary flex-1">
                    <i class="fas fa-paper-plane mr-2"></i> Envoyer le reçu
                </a>
            </div>
        </div>

        <!-- Support Info -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">Support Paiement</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">
                        <i class="fas fa-envelope text-primary-600 mr-2"></i> Email dédié
                    </h4>
                    <p class="text-gray-600">
                        <a href="mailto:paiement@codelearn.com" class="hover:text-primary-600">
                            paiement@codelearn.com
                        </a>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Pour envoyer vos reçus de virement</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">
                        <i class="fas fa-phone text-primary-600 mr-2"></i> Support téléphonique
                    </h4>
                    <p class="text-gray-600">
                        <a href="tel:+2252727272727" class="hover:text-primary-600">
                            +225 27 27 27 27 27
                        </a>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Lun-Ven, 9h-18h</p>
                </div>
            </div>
            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Le délai de traitement standard est de 1 à 3 jours ouvrés. Vous recevrez un email dès que votre paiement sera confirmé.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function sendReceipt() {
        // Trigger email client with pre-filled subject
        window.location.href = 'mailto:paiement@codelearn.com?subject=Reçu%20virement%20CODE-{{ strtoupper($plan) }}-{{ auth()->user()->id }}-{{ date('Ymd') }}&body=Bonjour,%0D%0A%0D%0AVeuillez trouver ci-joint le reçu de mon virement.%0D%0A%0D%0ARéférence : CODE-{{ strtoupper($plan) }}-{{ auth()->user()->id }}-{{ date('Ymd') }}%0D%0AMontant : {{ number_format($planDetails['price'], 0) }} MGA%0D%0ANom : {{ auth()->user()->name }}%0D%0A%0D%0ACordialement';
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Check payment status periodically
        let checkCount = 0;
        const maxChecks = 30; // Check for 30 minutes
        
        function checkPaymentStatus() {
            if (checkCount >= maxChecks) return;
            
            checkCount++;
            console.log(`Vérification du statut du virement (${checkCount}/${maxChecks})...`);
            
            // In production, this would be an API call
            // fetch('/api/payment/status/{{ session('payment_id') }}')
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.status === 'completed') {
            //             window.location.reload();
            //         }
            //     });
        }
        
        // Check every minute for 30 minutes
        for (let i = 1; i <= maxChecks; i++) {
            setTimeout(checkPaymentStatus, i * 60000);
        }
    });
</script>
@endpush
