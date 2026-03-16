

<?php $__env->startSection('title', 'Paiement formation'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement de la formation</h1>
        <p class="text-gray-600 mb-6">Ce paiement est indépendant de votre abonnement Free/Premium.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="font-semibold text-gray-900"><?php echo e($formation->title); ?></p>
            <p class="text-primary-700 text-xl font-bold mt-2"><?php echo e(number_format($formation->price, 2, ',', ' ')); ?> €</p>
        </div>

        <form method="POST" action="<?php echo e(route('formations.purchase', $formation)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement</label>
                <select id="payment_method" name="payment_method" class="w-full border-gray-300 rounded-lg" required>
                    <option value="card">Carte bancaire</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="bank_transfer">Virement bancaire</option>
                    <option value="cryptocurrency">Cryptomonnaie</option>
                </select>
                <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-danger-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn btn-primary w-full">Payer et débloquer la formation</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/formations/checkout.blade.php ENDPATH**/ ?>