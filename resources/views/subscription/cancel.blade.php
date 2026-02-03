@extends('layouts.app')

@section('title', 'Annuler l\'abonnement - CodeLearn')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-red-100 to-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-slash text-3xl text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Annuler l'abonnement</h1>
            <p class="text-gray-600 mt-2">Nous sommes désolés de vous voir partir</p>
        </div>

        <!-- Warning Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Warning Header -->
            <div class="bg-gradient-to-r from-red-500 to-orange-500 px-6 py-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-white text-2xl mr-3"></i>
                    <h2 class="text-xl font-bold text-white">Attention !</h2>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Current Subscription -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Votre abonnement actuel</h3>
                    <div class="bg-gray-50 rounded-xl p-5">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h4 class="font-bold text-gray-900">Formule Premium</h4>
                                <p class="text-gray-600">Abonnement mensuel</p>
                            </div>
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                Actif
                            </span>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Date d'expiration</p>
                                <p class="font-bold text-gray-900">
                                    {{ auth()->user()->subscription_expires_at->format('d/m/Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Jours restants</p>
                                <p class="font-bold text-primary-600">
                                    {{ now()->diffInDays(auth()->user()->subscription_expires_at) }} jours
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-primary-600 h-2 rounded-full" style="width: 65%"></div>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Début</span>
                            <span>Fin</span>
                        </div>
                    </div>
                </div>

                <!-- What You'll Lose -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-times-circle text-red-500 mr-2"></i> Ce que vous allez perdre
                    </h3>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-ban text-red-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Accès aux exercices avancés</p>
                                    <p class="text-sm text-gray-600">Retour aux exercices basiques uniquement</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-video-slash text-red-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Vidéos de formation</p>
                                    <p class="text-sm text-gray-600">Plus d'accès aux tutoriels vidéo</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-file-pdf text-red-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Leçons PDF complètes</p>
                                    <p class="text-sm text-gray-600">Seules les leçons gratuites resteront accessibles</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-headset text-red-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Support prioritaire</p>
                                    <p class="text-sm text-gray-600">Temps d'attente augmenté pour le support</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- What You'll Keep -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i> Ce que vous garderez
                    </h3>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-5">
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Votre progression actuelle</p>
                                    <p class="text-sm text-gray-600">Tous vos cours et exercices complétés</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Certificats obtenus</p>
                                    <p class="text-sm text-gray-600">Tous les certificats déjà gagnés</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Accès gratuit</p>
                                    <p class="text-sm text-gray-600">Accès permanent au contenu gratuit</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Vos données</p>
                                    <p class="text-sm text-gray-600">Toutes vos informations personnelles</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Cancellation Form -->
                <form method="POST" action="{{ route('subscription.confirm-cancel') }}" id="cancel-form">
                    @csrf
                    
                    <!-- Reason for Cancellation -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-comment-alt text-blue-500 mr-2"></i> Raison de l'annulation
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="reason" value="price" class="mr-3">
                                <div>
                                    <p class="font-medium">Trop cher</p>
                                    <p class="text-sm text-gray-600">Le prix ne correspond pas à mon budget</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="reason" value="features" class="mr-3">
                                <div>
                                    <p class="font-medium">Fonctionnalités manquantes</p>
                                    <p class="text-sm text-gray-600">Je ne trouve pas ce que je recherche</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="reason" value="usage" class="mr-3">
                                <div>
                                    <p class="font-medium">Je n'utilise plus</p>
                                    <p class="text-sm text-gray-600">Je n'ai plus le temps d'utiliser la plateforme</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="reason" value="other" class="mr-3">
                                <div>
                                    <p class="font-medium">Autre raison</p>
                                    <p class="text-sm text-gray-600">J'ai une autre raison</p>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Other Reason Textarea -->
                        <div id="other-reason" class="mt-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Veuillez préciser votre raison
                            </label>
                            <textarea name="reason_other" rows="3" class="form-textarea w-full" 
                                      placeholder="Pourquoi annulez-vous votre abonnement ?"></textarea>
                        </div>
                    </div>

                    <!-- Feedback -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            Avez-vous des suggestions d'amélioration ?
                        </label>
                        <textarea name="feedback" rows="3" class="form-textarea w-full" 
                                  placeholder="Que pouvons-nous améliorer pour vous faire revenir ?"></textarea>
                    </div>

                    <!-- Confirmation -->
                    <div class="mb-8">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-bold text-yellow-800 mb-2">Important à savoir</h4>
                                    <ul class="text-yellow-700 space-y-2 text-sm">
                                        <li>• Votre abonnement restera actif jusqu'au {{ auth()->user()->subscription_expires_at->format('d/m/Y') }}</li>
                                        <li>• Aucun remboursement ne sera effectué pour la période déjà payée</li>
                                        <li>• Vous pourrez réactiver votre abonnement à tout moment</li>
                                        <li>• Votre progression sera sauvegardée</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Final Confirmation -->
                    <div class="mb-8">
                        <label class="flex items-start">
                            <input type="checkbox" name="confirmation" class="mt-1 rounded border-gray-300 text-red-600 focus:ring-red-500" required>
                            <span class="ml-2 text-sm text-gray-600">
                                Je confirme que je souhaite annuler mon abonnement Premium. 
                                Je comprends que je perdrai l'accès aux fonctionnalités Premium après le 
                                {{ auth()->user()->subscription_expires_at->format('d/m/Y') }}.
                            </span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('subscription.manage') }}" class="btn btn-outline flex-1">
                            <i class="fas fa-arrow-left mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 text-white flex-1">
                            <i class="fas fa-user-slash mr-2"></i> Confirmer l'annulation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alternatives -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6 text-center">
                <i class="fas fa-lightbulb text-primary-500 mr-2"></i>
                Avant de partir, avez-vous envisagé...
            </h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Downgrade -->
                <div class="border-2 border-primary-200 rounded-xl p-6 bg-gradient-to-br from-primary-50 to-white">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-exchange-alt text-primary-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Changer de formule</h4>
                            <p class="text-sm text-gray-600">Passez à une offre plus adaptée</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Peut-être qu'une formule différente correspondrait mieux à vos besoins et à votre budget.
                    </p>
                    <a href="{{ route('subscription.plans') }}" class="btn btn-primary w-full">
                        <i class="fas fa-eye mr-2"></i> Voir les autres offres
                    </a>
                </div>

                <!-- Pause -->
                <div class="border-2 border-gray-200 rounded-xl p-6 bg-white">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-pause text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Mettre en pause</h4>
                            <p class="text-sm text-gray-600">Gardez votre progression</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Si vous partez en vacances ou avez moins de temps, nous pouvons mettre votre abonnement en pause.
                    </p>
                    <button type="button" onclick="alert('Contactez le support pour mettre en pause votre abonnement')" 
                            class="btn btn-outline w-full">
                        <i class="fas fa-headset mr-2"></i> Contacter le support
                    </button>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="mt-8 text-center">
            <p class="text-gray-600">
                <i class="fas fa-question-circle text-primary-500 mr-2"></i>
                Des questions ? Notre 
                <a href="{{ route('support') }}" class="text-primary-600 hover:text-primary-700 font-medium">équipe support</a>
                est là pour vous aider
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('cancel-form');
        const reasonRadios = document.querySelectorAll('input[name="reason"]');
        const otherReasonDiv = document.getElementById('other-reason');
        
        // Gérer l'affichage de la zone "autre raison"
        reasonRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'other') {
                    otherReasonDiv.classList.remove('hidden');
                } else {
                    otherReasonDiv.classList.add('hidden');
                }
            });
        });
        
        // Validation du formulaire
        form.addEventListener('submit', function(e) {
            const confirmationCheckbox = form.querySelector('input[name="confirmation"]');
            
            if (!confirmationCheckbox.checked) {
                e.preventDefault();
                alert('Veuillez confirmer que vous comprenez les conséquences de l\'annulation.');
                confirmationCheckbox.focus();
                return;
            }
            
            // Animation de chargement
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
            submitButton.disabled = true;
            
            // Simulation de délai
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 2000);
        });
        
        // Animation pour les cartes d'alternatives
        const alternativeCards = document.querySelectorAll('.border-2');
        alternativeCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 300 * index);
        });
    });
</script>
@endpush