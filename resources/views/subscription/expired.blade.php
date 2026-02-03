@extends('layouts.app')

@section('title', 'Abonnement expiré - CodeLearn')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 bg-gradient-to-r from-red-100 to-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clock text-4xl text-red-500"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Abonnement expiré</h1>
            <p class="text-xl text-gray-600">
                Votre période d'abonnement Premium est terminée
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Warning Header -->
            <div class="bg-gradient-to-r from-red-500 to-orange-500 px-8 py-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-white text-2xl mr-4"></i>
                    <div>
                        <h2 class="text-xl font-bold text-white">Accès Premium suspendu</h2>
                        <p class="text-red-100">Vous n'avez plus accès aux fonctionnalités Premium</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Impact -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-ban text-red-500 mr-2"></i> Limitations actuelles
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex items-center p-4 bg-red-50 rounded-lg">
                            <i class="fas fa-lock text-red-500 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Exercices avancés</p>
                                <p class="text-sm text-gray-600">Non accessibles</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-red-50 rounded-lg">
                            <i class="fas fa-video-slash text-red-500 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Vidéos de formation</p>
                                <p class="text-sm text-gray-600">Accès restreint</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-red-50 rounded-lg">
                            <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Leçons PDF complètes</p>
                                <p class="text-sm text-gray-600">Partiellement disponible</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-red-50 rounded-lg">
                            <i class="fas fa-headset text-red-500 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Support prioritaire</p>
                                <p class="text-sm text-gray-600">Temps d'attente augmenté</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Progress -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-chart-line text-blue-500 mr-2"></i> Votre progression
                    </h3>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="font-medium text-gray-900">Cours complétés</p>
                                <p class="text-2xl font-bold text-primary-600">24/30</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">Taux de réussite</p>
                                <p class="text-2xl font-bold text-green-600">92%</p>
                            </div>
                        </div>
                        <div class="bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 80%"></div>
                        </div>
                        <p class="text-sm text-gray-600">
                            Continuez votre apprentissage pour débloquer votre certificat !
                        </p>
                    </div>
                </div>

                <!-- Reactivation Options -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-redo text-primary-500 mr-2"></i> Réactiver votre abonnement
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Renew Same Plan -->
                        <div class="border-2 border-primary-200 rounded-xl p-6 bg-gradient-to-br from-primary-50 to-white">
                            <h4 class="font-bold text-gray-900 mb-2">Reprendre là où vous étiez</h4>
                            <p class="text-gray-600 text-sm mb-4">
                                Renouvelez votre abonnement précédent et retrouvez instantanément tous vos avantages.
                            </p>
                            <a href="{{ route('subscription.plans') }}" class="btn btn-primary w-full">
                                <i class="fas fa-redo mr-2"></i> Renouveler
                            </a>
                        </div>

                        <!-- Explore New Plans -->
                        <div class="border-2 border-gray-200 rounded-xl p-6 bg-white">
                            <h4 class="font-bold text-gray-900 mb-2">Découvrir nos offres</h4>
                            <p class="text-gray-600 text-sm mb-4">
                                Comparez toutes nos formules et choisissez celle qui vous convient le mieux.
                            </p>
                            <a href="{{ route('subscription.plans') }}" class="btn btn-outline w-full">
                                <i class="fas fa-eye mr-2"></i> Voir les offres
                            </a>
                        </div>
                    </div>
                </div>

                <!-- What You Keep -->
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                    <h4 class="font-bold text-green-800 mb-3">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i> Ce que vous gardez
                    </h4>
                    <ul class="text-green-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-2"></i>
                            <span>Toutes vos données et votre progression</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-2"></i>
                            <span>Accès au contenu gratuit</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-2"></i>
                            <span>Exercices basiques</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check mt-1 mr-2"></i>
                            <span>Certificats déjà obtenus</span>
                        </li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline flex-1">
                        <i class="fas fa-home mr-2"></i> Retour au tableau de bord
                    </a>
                    <a href="{{ route('subscription.plans') }}" class="btn btn-primary flex-1">
                        <i class="fas fa-crown mr-2"></i> Reprendre l'abonnement
                    </a>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="text-center">
            <p class="text-gray-600">
                <i class="fas fa-headset mr-2 text-primary-500"></i>
                Des questions sur votre abonnement ? Contactez notre 
                <a href="{{ route('support') }}" class="text-primary-600 hover:text-primary-700 font-medium">support client</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation pour les éléments d'alerte
        const warningItems = document.querySelectorAll('.bg-red-50');
        warningItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                item.style.transition = 'all 0.5s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 100 * index);
        });
    });
</script>
@endpush