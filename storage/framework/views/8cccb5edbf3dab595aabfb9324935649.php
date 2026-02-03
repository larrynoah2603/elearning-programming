<?php $__env->startSection('title', 'Inscription - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-code text-white text-2xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Créer un compte</h2>
            <p class="mt-2 text-gray-600">Rejoignez CodeLearn et commencez à apprendre</p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                            class="form-input pl-10 w-full" 
                            placeholder="Jean Dupont"
                            value="<?php echo e(old('name')); ?>">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="form-input pl-10 w-full" 
                            placeholder="votre@email.com"
                            value="<?php echo e(old('email')); ?>">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                            class="form-input pl-10 w-full" 
                            placeholder="••••••••">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                            class="form-input pl-10 w-full" 
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary w-full py-3">
                    <i class="fas fa-user-plus mr-2"></i> Créer mon compte
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="text-sm text-gray-600">
                Déjà un compte ?
                <a href="<?php echo e(route('login')); ?>" class="font-medium text-primary-600 hover:text-primary-500">
                    Connectez-vous
                </a>
            </p>
        </div>

        <!-- Benefits -->
        <div class="mt-8 border-t border-gray-200 pt-6">
            <p class="text-center text-sm text-gray-500 mb-4">Avantages de l'inscription gratuite</p>
            <div class="space-y-3">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-success-500 mr-3"></i>
                    Accès aux exercices simples
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-success-500 mr-3"></i>
                    Leçons PDF gratuites
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-success-500 mr-3"></i>
                    Suivi de progression
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-check-circle text-success-500 mr-3"></i>
                    Certificats de réussite
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/auth/register.blade.php ENDPATH**/ ?>