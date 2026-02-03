

<?php $__env->startSection('title', 'Paiement Mobile Money - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-mobile-alt text-3xl text-purple-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Paiement Mobile Money</h1>
            <p class="text-gray-600 mt-2">Effectuez votre paiement via Mobile Money</p>
        </div>

        <!-- Payment Details Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="border-b pb-4 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-2">Récapitulatif</h2>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">Abonnement <?php echo e($planDetails['name']); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($planDetails['days']); ?> jours</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-primary-600"><?php echo e(number_format($planDetails['price'], 0)); ?> MGA</p>
                        <p class="text-sm text-gray-500">≈ $<?php echo e(number_format($planDetails['price_usd'] ?? ($planDetails['price'] / 4500), 2)); ?> USD</p>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="mb-6">
                <h3 class="font-bold text-gray-900 mb-4">Instructions de paiement :</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="font-bold text-purple-600">1</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Composez le code USSD</p>
                            <p class="text-gray-600 text-sm">*144# (Orange) *155# (Airtel) selon votre opérateur</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="font-bold text-purple-600">2</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Sélectionnez "Paiement Marchand"</p>
                            <p class="text-gray-600 text-sm">Dans le menu de votre Mobile Money</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="font-bold text-purple-600">3</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Entrez le code marchand</p>
                            <p class="text-gray-600 text-sm">Code : <span class="font-mono font-bold">CODELEARN<?php echo e(strtoupper($plan)); ?></span></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="font-bold text-purple-600">4</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Confirmez le montant</p>
                            <p class="text-gray-600 text-sm"><?php echo e(number_format($planDetails['price'], 0)); ?> MGA</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-bold text-yellow-800 mb-1">Important</h4>
                        <p class="text-yellow-700 text-sm">
                            Gardez votre téléphone à proximité. Vous recevrez une demande de confirmation sur votre mobile.
                            Entrez votre code PIN pour finaliser le paiement.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form method="POST" action="<?php echo e(route('subscription.process-mobile-money')); ?>" id="mobile-money-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="plan" value="<?php echo e($plan); ?>">
                
                <!-- Phone Number -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Numéro Mobile Money</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input type="tel" 
                               name="phone_number"
                               class="form-input pl-10 w-full"
                               placeholder="Ex: 0321234567 ou +261321234567"
                               required
                               pattern="[0-9\+]{10,13}">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Format: 0321234567 ou +261321234567</p>
                </div>

                <!-- Operator -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Opérateur</label>
                    <div class="grid grid-cols-2 gap-3">
                        <?php $__currentLoopData = $mobileMoneyOperators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="operator" value="<?php echo e($key); ?>" 
                                       class="text-purple-600 focus:ring-purple-500" required>
                                <div class="ml-3">
                                    <?php if($key === 'orange'): ?>
                                        <i class="fas fa-sim-card text-orange-500 text-xl"></i>
                                    <?php elseif($key === 'airtel'): ?>
                                        <i class="fas fa-sim-card text-red-500 text-xl"></i>
                                    <?php elseif($key === 'mvola'): ?>
                                        <i class="fas fa-sim-card text-blue-500 text-xl"></i>
                                    <?php else: ?>
                                        <i class="fas fa-sim-card text-green-500 text-xl"></i>
                                    <?php endif; ?>
                                    <span class="block mt-1 font-medium"><?php echo e($operator['name']); ?></span>
                                </div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Terms -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" name="terms" class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500" required>
                        <span class="ml-2 text-sm text-gray-600">
                            J'autorise le prélèvement de <?php echo e(number_format($planDetails['price'], 0)); ?> MGA sur mon compte Mobile Money
                            et j'accepte les <a href="#" class="text-primary-600 hover:text-primary-700">conditions de service</a>.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-full py-3">
                    <i class="fas fa-paper-plane mr-2"></i> Envoyer la demande de paiement
                </button>
            </form>
        </div>

        <!-- Support -->
        <div class="text-center">
            <p class="text-sm text-gray-500">
                <i class="fas fa-headset mr-1"></i>
                Besoin d'aide ? Contactez le support au 
                <a href="tel:+261341234567" class="text-primary-600 hover:text-primary-700">+261 34 12 345 67</a>
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('mobile-money-form');
        
        form.addEventListener('submit', function(e) {
            const phoneInput = form.querySelector('input[name="phone_number"]');
            let phoneValue = phoneInput.value.replace(/\D/g, '');
            
            // Si commence par 0, ajouter +261
            if (phoneValue.startsWith('0')) {
                phoneValue = '+261' + phoneValue.substring(1);
            }
            // Si pas de préfixe, ajouter +261
            else if (!phoneValue.startsWith('261') && !phoneValue.startsWith('+261')) {
                phoneValue = '+261' + phoneValue;
            }
            
            if (phoneValue.length < 12 || phoneValue.length > 13) {
                e.preventDefault();
                alert('Veuillez entrer un numéro de téléphone valide (ex: 0321234567 ou +261321234567)');
                phoneInput.focus();
                return;
            }
            
            // Format phone number
            phoneInput.value = phoneValue;
            
            // Show loading
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
            button.disabled = true;
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/subscription/mobile-money.blade.php ENDPATH**/ ?>