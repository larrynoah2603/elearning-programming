@extends('layouts.app')

@section('title', 'Gérer mon abonnement - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-100 to-primary-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-cog text-3xl text-primary-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Gérer mon abonnement</h1>
            <p class="text-gray-600 mt-2">Contrôlez vos paramètres d'abonnement et paiements</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column: Subscription Details -->
            <div class="lg:col-span-2">
                <!-- Current Subscription Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Abonnement actuel</h2>
                            <p class="text-gray-600">Gérez vos informations d'abonnement</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle text-xs mr-2"></i> Actif
                            </span>
                        </div>
                    </div>

                    <!-- Plan Details -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="border rounded-lg p-5">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-crown text-xl text-primary-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Formule {{ ucfirst($subscription->plan) }}</h3>
                                    <p class="text-gray-600 text-sm">
                                        @if($subscription->plan === 'monthly')
                                            Mensuel
                                        @elseif($subscription->plan === 'quarterly')
                                            Trimestriel
                                        @else
                                            Annuel
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-lg p-5">
                            <h4 class="font-bold text-gray-900 mb-2">Période d'abonnement</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Début :</span>
                                    <span class="font-medium">{{ $subscription->start_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Fin :</span>
                                    <span class="font-medium">{{ $subscription->end_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jours restants :</span>
                                    <span class="font-bold text-primary-600">
                                        {{ now()->diffInDays($subscription->end_date) }} jours
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-8">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Progression de l'abonnement</span>
                            <span class="text-sm font-medium text-gray-700">
                                @php
                                    $totalDays = $subscription->start_date->diffInDays($subscription->end_date);
                                    $daysPassed = $subscription->start_date->diffInDays(now());
                                    $percentage = min(100, max(0, ($daysPassed / $totalDays) * 100));
                                @endphp
                                {{ number_format($percentage, 1) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-primary-600 h-3 rounded-full transition-all duration-500 ease-out" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-sm text-gray-500">
                            <span>Début : {{ $subscription->start_date->format('d/m/Y') }}</span>
                            <span>Fin : {{ $subscription->end_date->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Features -->
                    <div>
                        <h4 class="font-bold text-gray-900 mb-4">Fonctionnalités incluses</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700">Exercices avancés</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700">Vidéos de formation</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700">Toutes les leçons PDF</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700">Support prioritaire</span>
                            </div>
                            @if($subscription->plan === 'yearly')
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span class="text-gray-700">Sessions de mentorat</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Méthodes de paiement</h2>
                    
                    @if($payments->count() > 0)
                        <div class="space-y-4">
                            @foreach($payments->take(3) as $payment)
                                <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center mb-2">
                                                @if($payment->payment_method === 'card')
                                                    <i class="far fa-credit-card text-blue-500 mr-2"></i>
                                                @elseif($payment->payment_method === 'mobile_money')
                                                    <i class="fas fa-mobile-alt text-purple-500 mr-2"></i>
                                                @elseif($payment->payment_method === 'cryptocurrency')
                                                    <i class="fab fa-bitcoin text-yellow-500 mr-2"></i>
                                                @else
                                                    <i class="fas fa-university text-green-500 mr-2"></i>
                                                @endif
                                                <span class="font-medium">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                </span>
                                                <span class="ml-3 px-2 py-1 text-xs rounded-full 
                                                    {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                       ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $payment->status }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                {{ $payment->created_at->format('d/m/Y H:i') }} • 
                                                {{ number_format($payment->amount, 0) }} {{ $payment->currency }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-gray-900">
                                                {{ number_format($payment->amount, 0) }} {{ $payment->currency }}
                                            </div>
                                            <a href="{{ route('subscription.invoice', $payment->id) }}" 
                                               class="text-sm text-primary-600 hover:text-primary-700">
                                                <i class="fas fa-download mr-1"></i> Facture
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($payments->count() > 3)
                                <div class="text-center pt-2">
                                    <a href="{{ route('subscription.payment-history') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                        Voir tous les paiements ({{ $payments->count() }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-credit-card text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-600">Aucun paiement enregistré</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div>
                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Actions</h2>
                    
                    <div class="space-y-4">
                        <!-- Renew Button -->
                        <a href="{{ route('subscription.plans') }}" class="flex items-center justify-between p-4 border rounded-lg hover:bg-primary-50 hover:border-primary-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-redo text-primary-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Renouveler</h4>
                                    <p class="text-sm text-gray-600">Prolongez votre abonnement</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>

                        <!-- Upgrade/Downgrade -->
                        <a href="{{ route('subscription.plans') }}" class="flex items-center justify-between p-4 border rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-exchange-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Changer de formule</h4>
                                    <p class="text-sm text-gray-600">Passez à une autre offre</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>

                        <!-- Cancel Button -->
                        <a href="{{ route('subscription.cancel') }}" class="flex items-center justify-between p-4 border rounded-lg hover:bg-red-50 hover:border-red-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-times text-red-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Annuler</h4>
                                    <p class="text-sm text-gray-600">Résilier l'abonnement</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>

                        <!-- Download Invoices -->
                        <a href="{{ route('subscription.payment-history') }}" class="flex items-center justify-between p-4 border rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-file-invoice text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Factures</h4>
                                    <p class="text-sm text-gray-600">Télécharger vos reçus</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </div>
                </div>

                <!-- Billing Information -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Informations de facturation</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <p class="text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date d'inscription</label>
                            <p class="text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <!-- Update Billing Info Button -->
                    <button type="button" onclick="alert('Fonctionnalité à venir')" class="btn btn-outline w-full mt-6">
                        <i class="fas fa-edit mr-2"></i> Modifier les informations
                    </button>
                </div>

                <!-- Support -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-6">
                    <h3 class="font-bold text-blue-900 mb-3">
                        <i class="fas fa-headset mr-2"></i> Besoin d'aide ?
                    </h3>
                    <p class="text-blue-700 text-sm mb-4">
                        Notre équipe support est là pour vous aider avec votre abonnement.
                    </p>
                    <a href="{{ route('support') }}" class="btn btn-primary w-full">
                        <i class="fas fa-envelope mr-2"></i> Contacter le support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .progress-animation {
        transition: width 2s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation de la barre de progression
        const progressBar = document.querySelector('.bg-primary-600');
        if (progressBar) {
            // Forcer le recalcul pour déclencher l'animation
            void progressBar.offsetWidth;
        }
    });
</script>
@endpush