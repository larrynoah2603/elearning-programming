

<?php $__env->startSection('title', 'Paiement - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-3xl text-primary-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Finaliser votre abonnement</h1>
            <p class="text-gray-600 mt-2">Choisissez votre méthode de paiement</p>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Votre commande</h2>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- Plan Details -->
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-crown text-xl text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Abonnement <?php echo e($planDetails['name']); ?></h3>
                            <p class="text-gray-600"><?php echo e($planDetails['days']); ?> jours d'accès</p>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="text-center md:text-right">
                    <!-- CORRECTION ICI : utiliser price au lieu de price_mga -->
                    <div class="text-3xl font-bold text-primary-600"><?php echo e(number_format($planDetails['price'], 0)); ?> MGA</div>
                    <!-- CORRECTION ICI : utiliser price_usd si disponible -->
                    <p class="text-gray-500">soit $<?php echo e(number_format($planDetails['price_usd'] ?? ($planDetails['price'] / 4500), 2)); ?> USD</p>
                </div>
            </div>

            <!-- Features -->
            <div class="mt-6 pt-6 border-t">
                <h4 class="font-medium text-gray-900 mb-3">Ce qui est inclus :</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span class="text-gray-700">Accès à tous les cours</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span class="text-gray-700">Exercices pratiques</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span class="text-gray-700">Support communautaire</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span class="text-gray-700">Certificats de complétion</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Choisissez votre méthode de paiement</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Card Payment -->
                <a href="<?php echo e(route('subscription.card', $plan)); ?>" 
                   class="border-2 border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-green-500 hover:bg-green-50 transition-all duration-200 block">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="far fa-credit-card text-3xl text-green-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Carte Bancaire</h3>
                    <p class="text-sm text-gray-600">Visa, Mastercard, Paypal</p>
                    <div class="flex justify-center space-x-2 mt-3">
                        <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                        <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                        <i class="fab fa-cc-paypal text-2xl text-blue-400"></i>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-green-600 font-medium">
                            <i class="fas fa-bolt mr-1"></i> Paiement instantané
                        </span>
                    </div>
                </a>

                <!-- Mobile Money -->
                <a href="<?php echo e(route('subscription.mobile-money', $plan)); ?>" 
                   class="border-2 border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all duration-200 block">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Mobile Money</h3>
                    <!-- CORRECTION : Opérateurs Madagascar -->
                    <p class="text-sm text-gray-600">Orange, Airtel, Mvola, Telma</p>
                    <div class="flex justify-center space-x-2 mt-3">
                        <i class="fas fa-sim-card text-2xl text-orange-500"></i>
                        <i class="fas fa-sim-card text-2xl text-red-500"></i>
                        <i class="fas fa-sim-card text-2xl text-blue-500"></i>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-purple-600 font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Populaire à Madagascar
                        </span>
                    </div>
                </a>

                <!-- Crypto -->
                <a href="<?php echo e(route('subscription.crypto', $plan)); ?>" 
                   class="border-2 border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-yellow-500 hover:bg-yellow-50 transition-all duration-200 block">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-bitcoin text-3xl text-yellow-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Cryptomonnaie</h3>
                    <p class="text-sm text-gray-600">Bitcoin, Ethereum, USDT</p>
                    <div class="flex justify-center space-x-2 mt-3">
                        <i class="fab fa-bitcoin text-2xl text-yellow-500"></i>
                        <i class="fab fa-ethereum text-2xl text-purple-500"></i>
                        <i class="fas fa-coins text-2xl text-green-500"></i>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-yellow-600 font-medium">
                            <i class="fas fa-globe mr-1"></i> International
                        </span>
                    </div>
                </a>

                <!-- Bank Transfer -->
                <a href="<?php echo e(route('subscription.bank', $plan)); ?>" 
                   class="border-2 border-gray-200 rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 block">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-university text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Virement Bancaire</h3>
                    <p class="text-sm text-gray-600">Transfert depuis votre banque</p>
                    <div class="flex justify-center space-x-2 mt-3">
                        <i class="fas fa-building text-2xl text-blue-500"></i>
                        <i class="fas fa-exchange-alt text-2xl text-blue-400"></i>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm text-blue-600 font-medium">
                            <i class="fas fa-building mr-1"></i> Local
                        </span>
                    </div>
                </a>
            </div>

            <!-- Method Details -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h4 class="font-bold text-gray-900 mb-3">Informations sur les méthodes de paiement</h4>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-medium text-gray-900 mb-2">
                            <i class="fas fa-shield-alt text-green-500 mr-2"></i>Sécurité
                        </h5>
                        <p class="text-sm text-gray-600">
                            Tous les paiements sont sécurisés et cryptés. Nous ne stockons jamais vos informations bancaires.
                        </p>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-900 mb-2">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>Activation
                        </h5>
                        <p class="text-sm text-gray-600">
                            Carte: Immédiate • Mobile Money: 2-5 min • Crypto: 10-30 min • Virement: 1-3 jours
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between pt-6 border-t">
                <a href="<?php echo e(route('subscription.plans')); ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux offres
                </a>
                <div class="text-sm text-gray-500">
                    En cliquant sur une méthode, vous serez redirigé vers le formulaire de paiement
                </div>
            </div>
        </div>

        <!-- Security Assurance -->
        <div class="mt-8 text-center">
            <div class="inline-flex flex-wrap items-center justify-center gap-6 text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-lock text-green-500 mr-2"></i>
                    <span>Paiement 100% sécurisé</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                    <span>SSL Encryption</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-receipt text-purple-500 mr-2"></i>
                    <span>Reçu garanti</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-headset text-yellow-500 mr-2"></i>
                    <span>Support 24/7</span>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">
                <i class="fas fa-question-circle text-primary-600 mr-2"></i> Questions fréquentes
            </h3>
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-900">Quand mon abonnement sera-t-il activé ?</h4>
                    <p class="text-gray-600 text-sm mt-1">
                        L'activation dépend de la méthode de paiement. Les cartes bancaires sont instantanées, 
                        le Mobile Money prend 2-5 minutes, les cryptomonnaies 10-30 minutes, et les virements 
                        bancaires 1-3 jours ouvrés.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Puis-je annuler mon abonnement ?</h4>
                    <p class="text-gray-600 text-sm mt-1">
                        Oui, vous pouvez annuler à tout moment depuis votre tableau de bord. 
                        Vous gardez l'accès jusqu'à la fin de la période payée.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Y a-t-il des frais cachés ?</h4>
                    <p class="text-gray-600 text-sm mt-1">
                        Non, le prix affiché est le prix final. Les frais éventuels de votre 
                        banque ou opérateur ne nous concernent pas.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .payment-method-card {
        transition: all 0.3s ease;
    }
    .payment-method-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation au survol des cartes de méthode de paiement
        const paymentCards = document.querySelectorAll('a[href*="subscription"]');
        
        paymentCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
        
        // Tooltip pour les icônes
        const tooltips = {
            'fa-bolt': 'Paiement instantané - activation immédiate',
            'fa-check-circle': 'Méthode populaire à Madagascar',
            'fa-globe': 'Accepté internationalement',
            'fa-building': 'Banques locales supportées'
        };
        
        Object.entries(tooltips).forEach(([iconClass, text]) => {
            const icons = document.querySelectorAll(`.${iconClass}`);
            icons.forEach(icon => {
                icon.setAttribute('title', text);
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/subscription/checkout.blade.php ENDPATH**/ ?>