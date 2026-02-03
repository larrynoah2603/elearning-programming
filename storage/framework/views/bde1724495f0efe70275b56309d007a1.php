<?php $__env->startSection('title', 'Nos Offres - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Choisissez votre formule</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Accédez à tout le contenu et accélérez votre apprentissage avec nos offres Premium.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative <?php echo e($plan['highlighted'] ? 'transform md:-translate-y-4' : ''); ?>">
                    <?php if($plan['highlighted']): ?>
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-1 rounded-full text-sm font-bold shadow-lg">
                                <i class="fas fa-star mr-1"></i> Populaire
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden <?php echo e($plan['highlighted'] ? 'ring-4 ring-primary-500' : ''); ?>">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2"><?php echo e($plan['name']); ?></h3>
                            <div class="flex items-baseline mb-6">
                                <!-- CORRIGÉ: Utiliser price_mga au lieu de price -->
                                <span class="text-5xl font-bold text-gray-900"><?php echo e(number_format($plan['price_mga'], 0)); ?> MGA</span>
                                <span class="text-gray-500 ml-2">/<?php echo e($plan['period']); ?></span>
                            </div>
                            <!-- Équivalent en USD -->
                            <div class="text-sm text-gray-500 mb-2">
                                ≈ $<?php echo e(number_format($plan['price_usd'] ?? 0, 2)); ?> USD
                            </div>
                            
                            <ul class="space-y-4 mb-8">
                                <?php $__currentLoopData = $plan['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-success-500 mt-1 mr-3"></i>
                                        <span class="text-gray-600"><?php echo e($feature); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            
                            <?php if(auth()->check() && auth()->user()->isSubscribed()): ?>
                                <button disabled class="btn w-full py-3 bg-gray-100 text-gray-500 cursor-not-allowed">
                                    <i class="fas fa-check mr-2"></i> Déjà abonné
                                </button>
                            <?php else: ?>
                                <a href="<?php echo e(route('subscription.checkout', $plan['id'])); ?>" class="btn <?php echo e($plan['highlighted'] ? 'btn-primary' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?> w-full py-3">
                                    <i class="fas fa-crown mr-2"></i> Choisir <?php echo e($plan['name']); ?>

                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Features Comparison -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-16">
            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-900">Comparaison des fonctionnalités</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-8 py-4 text-left text-sm font-medium text-gray-500">Fonctionnalité</th>
                            <th class="px-8 py-4 text-center text-sm font-medium text-gray-500">Gratuit</th>
                            <th class="px-8 py-4 text-center text-sm font-medium text-primary-600">Premium</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="px-8 py-4 text-gray-900">Exercices simples</td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                        </tr>
                        <tr>
                            <td class="px-8 py-4 text-gray-900">Exercices complexes</td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-times text-gray-300 text-xl"></i></td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                        </tr>
                        <tr>
                            <td class="px-8 py-4 text-gray-900">Leçons PDF gratuites</td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                        </tr>
                        <tr>
                            <td class="px-8 py-4 text-gray-900">Toutes les leçons PDF</td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-times text-gray-300 text-xl"></i></td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                        </tr>
                        <tr>
                            <td class="px-8 py-4 text-gray-900">Vidéos de formation</td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-times text-gray-300 text-xl"></i></td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                        </tr>
                        <tr>
                            <td class="px-8 py-4 text-gray-900">Certificats de réussite</td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                        </tr>
                        <tr>
                            <td class="px-8 py-4 text-gray-900">Support prioritaire</td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-times text-gray-300 text-xl"></i></td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                        </tr>
                        <tr>
                            <td class="px-8 py-4 text-gray-900">Sessions de mentorat</td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-times text-gray-300 text-xl"></i></td>
                            <td class="px-8 py-4 text-center"><i class="fas fa-check text-success-500 text-xl"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FAQ -->
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Questions fréquentes</h2>
            
            <div class="space-y-4" x-data="{ open: null }">
                <div class="bg-white rounded-lg shadow-sm">
                    <button @click="open === 1 ? open = null : open = 1" class="w-full px-6 py-4 text-left flex justify-between items-center">
                        <span class="font-medium text-gray-900">Puis-je annuler mon abonnement à tout moment ?</span>
                        <i class="fas fa-chevron-down text-gray-400" :class="{ 'transform rotate-180': open === 1 }"></i>
                    </button>
                    <div x-show="open === 1" class="px-6 pb-4 text-gray-600">
                        Oui, vous pouvez annuler votre abonnement à tout moment depuis votre espace personnel. Vous conserverez l'accès jusqu'à la fin de la période payée.
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm">
                    <button @click="open === 2 ? open = null : open = 2" class="w-full px-6 py-4 text-left flex justify-between items-center">
                        <span class="font-medium text-gray-900">Y a-t-il une période d'essai gratuite ?</span>
                        <i class="fas fa-chevron-down text-gray-400" :class="{ 'transform rotate-180': open === 2 }"></i>
                    </button>
                    <div x-show="open === 2" class="px-6 pb-4 text-gray-600">
                        Oui, nous offrons une période d'essai de 7 jours pour l'abonnement mensuel. Vous pouvez annuler à tout moment pendant cette période sans être débité.
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm">
                    <button @click="open === 3 ? open = null : open = 3" class="w-full px-6 py-4 text-left flex justify-between items-center">
                        <span class="font-medium text-gray-900">Puis-je changer de formule ?</span>
                        <i class="fas fa-chevron-down text-gray-400" :class="{ 'transform rotate-180': open === 3 }"></i>
                    </button>
                    <div x-show="open === 3" class="px-6 pb-4 text-gray-600">
                        Oui, vous pouvez passer à une formule supérieure à tout moment. La différence sera calculée au prorata de la période restante.
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm">
                    <button @click="open === 4 ? open = null : open = 4" class="w-full px-6 py-4 text-left flex justify-between items-center">
                        <span class="font-medium text-gray-900">Quels modes de paiement acceptez-vous ?</span>
                        <i class="fas fa-chevron-down text-gray-400" :class="{ 'transform rotate-180': open === 4 }"></i>
                    </button>
                    <div x-show="open === 4" class="px-6 pb-4 text-gray-600">
                        Nous acceptons Mobile Money (Orange, Airtel, Mvola, Telma), virement bancaire, carte bancaire et cryptomonnaies (Bitcoin, Ethereum, USDT).
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/subscription/plans.blade.php ENDPATH**/ ?>