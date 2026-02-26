

<?php $__env->startSection('title', 'Historique des paiements - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-history text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Historique des paiements</h1>
            <p class="text-gray-600 mt-2">Consultez tous vos paiements et téléchargez vos factures</p>
        </div>

        <?php
            $completedPayments = $payments->where('status', 'completed');
            $totalMga = $completedPayments->where('currency', 'MGA')->sum('amount');
            $totalUsd = $completedPayments->where('currency', 'USD')->sum('amount');
        ?>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Paiements réussis</p>
                        <p class="text-2xl font-bold text-gray-900">
                            <?php echo e($payments->where('status', 'completed')->count()); ?>

                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-gray-900">
                            <?php echo e($payments->where('status', 'pending')->count()); ?>

                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Échoués</p>
                        <p class="text-2xl font-bold text-gray-900">
                            <?php echo e($payments->where('status', 'failed')->count()); ?>

                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-coins text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total dépensé</p>
                        <div class="text-2xl font-bold text-gray-900">
                            <div><?php echo e(number_format($totalMga, 0)); ?> Ar</div>
                            <div class="text-base text-gray-500"><?php echo e(number_format($totalUsd, 2)); ?> USD</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-gray-900">Tous vos paiements</h3>
                    <p class="text-sm text-gray-600"><?php echo e($payments->total()); ?> paiement(s) trouvé(s)</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select class="form-select" onchange="window.location.href = this.value">
                            <option value="<?php echo e(request()->fullUrlWithQuery(['status' => ''])); ?>">Tous</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['status' => 'completed'])); ?>" 
                                    <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Complétés</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['status' => 'pending'])); ?>"
                                    <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>En attente</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['status' => 'failed'])); ?>"
                                    <?php echo e(request('status') === 'failed' ? 'selected' : ''); ?>>Échoués</option>
                        </select>
                    </div>
                    
                    <!-- Method Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Méthode</label>
                        <select class="form-select" onchange="window.location.href = this.value">
                            <option value="<?php echo e(request()->fullUrlWithQuery(['method' => ''])); ?>">Toutes</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['method' => 'card'])); ?>"
                                    <?php echo e(request('method') === 'card' ? 'selected' : ''); ?>>Carte</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['method' => 'mobile_money'])); ?>"
                                    <?php echo e(request('method') === 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['method' => 'crypto'])); ?>"
                                    <?php echo e(request('method') === 'crypto' ? 'selected' : ''); ?>>Crypto</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['method' => 'bank'])); ?>"
                                    <?php echo e(request('method') === 'bank' ? 'selected' : ''); ?>>Virement</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <?php if($payments->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Date</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Référence</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Méthode</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Montant</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Statut</th>
                                <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900"><?php echo e($payment->created_at->format('d/m/Y')); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e($payment->created_at->format('H:i')); ?></div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="font-mono text-sm"><?php echo e($payment->transaction_id); ?></div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <?php if($payment->payment_method === 'card'): ?>
                                                <i class="far fa-credit-card text-blue-500 mr-2"></i>
                                                <span>Carte bancaire</span>
                                            <?php elseif($payment->payment_method === 'mobile_money'): ?>
                                                <i class="fas fa-mobile-alt text-purple-500 mr-2"></i>
                                                <span>Mobile Money</span>
                                            <?php elseif($payment->payment_method === 'cryptocurrency'): ?>
                                                <i class="fab fa-bitcoin text-yellow-500 mr-2"></i>
                                                <span>Cryptomonnaie</span>
                                            <?php else: ?>
                                                <i class="fas fa-university text-green-500 mr-2"></i>
                                                <span>Virement bancaire</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">
                                            <?php echo e(number_format($payment->amount, 0)); ?> <?php echo e($payment->currency_display); ?>

                                        </div>
                                        <?php if($payment->crypto_amount): ?>
                                            <div class="text-xs text-gray-500">
                                                <?php echo e(number_format($payment->crypto_amount, 6)); ?> 
                                                <?php echo e(strtoupper($payment->crypto_type ?? 'BTC')); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            <?php echo e($payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))); ?>">
                                            <?php if($payment->status === 'completed'): ?>
                                                <i class="fas fa-check-circle mr-1 text-xs"></i>
                                            <?php elseif($payment->status === 'pending'): ?>
                                                <i class="fas fa-clock mr-1 text-xs"></i>
                                            <?php elseif($payment->status === 'failed'): ?>
                                                <i class="fas fa-times-circle mr-1 text-xs"></i>
                                            <?php endif; ?>
                                            <?php echo e(ucfirst($payment->status)); ?>

                                        </span>
                                        <?php if($payment->confirmed_at): ?>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Confirmé le <?php echo e($payment->confirmed_at->format('d/m/Y')); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <a href="<?php echo e(route('subscription.invoice', $payment->id)); ?>" 
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                                <i class="fas fa-file-invoice mr-2"></i> Facture
                                            </a>

                                            <?php if($payment->status === 'failed' || $payment->status === 'expired'): ?>
                                                <a href="<?php echo e(route('subscription.payment-method', 'monthly')); ?>"
                                                   class="inline-flex items-center px-3 py-2 border border-primary-300 rounded-md text-sm font-medium text-primary-700 hover:bg-primary-50 transition-colors">
                                                    <i class="fas fa-redo mr-2"></i> Réessayer
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    <?php echo e($payments->withQueryString()->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Aucun paiement pour le moment</h3>
                    <p class="text-gray-600 mb-6">Votre historique apparaîtra ici après votre premier paiement.</p>
                    <a href="<?php echo e(route('subscription.plans')); ?>" class="btn btn-primary">
                        <i class="fas fa-crown mr-2"></i> Voir les formules
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/subscription/payment-history.blade.php ENDPATH**/ ?>