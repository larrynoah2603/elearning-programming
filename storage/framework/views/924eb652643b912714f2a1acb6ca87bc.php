<footer class="bg-gray-900 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-code text-white text-lg"></i>
                    </div>
                    <span class="font-bold text-xl">CodeLearn</span>
                </div>
                <p class="text-gray-400 text-sm mb-4">
                    Plateforme e-learning de programmation pour les lycéens. Apprenez à coder de manière interactive et amusante.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="<?php echo e(route('home')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Accueil</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('lessons.index')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Leçons</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('exercises.index')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Exercices</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('videos.index')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Vidéos</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('subscription.plans')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Tarifs</a>
                    </li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Ressources</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Documentation</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Blog</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">FAQ</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('contact')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('about')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">À propos</a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-2 text-primary-500"></i>
                        <span>123 Rue de la Programmation<br>75000 Paris, France</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-primary-500"></i>
                        <a href="mailto:contact@codelearn.fr" class="hover:text-white transition-colors">contact@codelearn.fr</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone mr-2 text-primary-500"></i>
                        <a href="tel:+33123456789" class="hover:text-white transition-colors">+33 1 23 45 67 89</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    &copy; <?php echo e(date('Y')); ?> CodeLearn. Tous droits réservés.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Politique de confidentialité</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Conditions d'utilisation</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Mentions légales</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\wamp64\www\elearning-programming\resources\views/layouts/footer.blade.php ENDPATH**/ ?>