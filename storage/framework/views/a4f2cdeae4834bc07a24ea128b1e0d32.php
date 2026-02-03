<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and Left Navigation -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="<?php echo e(route('home')); ?>" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-code text-white text-lg"></i>
                        </div>
                        <span class="font-bold text-xl text-gray-800 hidden sm:block">CodeLearn</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e(request()->routeIs('home') ? 'nav-link-active' : ''); ?>">
                        Accueil
                    </a>
                    <a href="<?php echo e(route('lessons.index')); ?>" class="nav-link <?php echo e(request()->routeIs('lessons.*') ? 'nav-link-active' : ''); ?>">
                        Leçons
                    </a>
                    <a href="<?php echo e(route('exercises.index')); ?>" class="nav-link <?php echo e(request()->routeIs('exercises.*') ? 'nav-link-active' : ''); ?>">
                        Exercices
                    </a>
                    <a href="<?php echo e(route('videos.index')); ?>" class="nav-link <?php echo e(request()->routeIs('videos.*') ? 'nav-link-active' : ''); ?>">
                        Vidéos
                    </a>
                    <a href="<?php echo e(route('categories.index')); ?>" class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'nav-link-active' : ''); ?>">
                        Catégories
                    </a>
                </div>
            </div>

            <!-- Right Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <?php if(auth()->guard()->check()): ?>
                    <!-- Admin Link -->
                    <?php if(auth()->user()->isAdmin()): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link mr-4 <?php echo e(request()->routeIs('admin.*') ? 'nav-link-active' : ''); ?>">
                            <i class="fas fa-cog mr-1"></i> Admin
                        </a>
                    <?php endif; ?>

                    <!-- Subscription Badge -->
                    <?php if(auth()->user()->isSubscribed()): ?>
                        <span class="badge badge-success mr-4">
                            <i class="fas fa-crown mr-1"></i> Premium
                        </span>
                    <?php else: ?>
                        <a href="<?php echo e(route('subscription.plans')); ?>" class="badge badge-warning mr-4 hover:bg-warning-200 transition-colors">
                            <i class="fas fa-star mr-1"></i> Passer Premium
                        </a>
                    <?php endif; ?>

                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                                    <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                                </div>
                                <span class="ml-2 text-gray-700 self-center"><?php echo e(auth()->user()->name); ?></span>
                                <i class="fas fa-chevron-down ml-2 text-gray-400 self-center text-xs"></i>
                            </button>
                        </div>

                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="py-1">
                                <a href="<?php echo e(route('dashboard')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord
                                </a>
                                <a href="<?php echo e(route('profile')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </a>
                                <?php if(auth()->user()->isSubscribed()): ?>
                                    <a href="<?php echo e(route('subscription.manage')); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-crown mr-2"></i> Mon abonnement
                                    </a>
                                <?php endif; ?>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-danger-600 hover:bg-danger-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center space-x-4">
                        <a href="<?php echo e(route('login')); ?>" class="text-gray-600 hover:text-primary-600 font-medium">
                            Connexion
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">
                            Inscription
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <i class="fas fa-bars text-xl" x-show="!open"></i>
                    <i class="fas fa-times text-xl" x-show="open" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" class="sm:hidden" style="display: none;">
        <div class="pt-2 pb-3 space-y-1">
            <a href="<?php echo e(route('home')); ?>" class="block pl-3 pr-4 py-2 border-l-4 <?php echo e(request()->routeIs('home') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'); ?> text-base font-medium transition duration-150 ease-in-out">
                Accueil
            </a>
            <a href="<?php echo e(route('lessons.index')); ?>" class="block pl-3 pr-4 py-2 border-l-4 <?php echo e(request()->routeIs('lessons.*') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'); ?> text-base font-medium transition duration-150 ease-in-out">
                Leçons
            </a>
            <a href="<?php echo e(route('exercises.index')); ?>" class="block pl-3 pr-4 py-2 border-l-4 <?php echo e(request()->routeIs('exercises.*') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'); ?> text-base font-medium transition duration-150 ease-in-out">
                Exercices
            </a>
            <a href="<?php echo e(route('videos.index')); ?>" class="block pl-3 pr-4 py-2 border-l-4 <?php echo e(request()->routeIs('videos.*') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'); ?> text-base font-medium transition duration-150 ease-in-out">
                Vidéos
            </a>
            <a href="<?php echo e(route('categories.index')); ?>" class="block pl-3 pr-4 py-2 border-l-4 <?php echo e(request()->routeIs('categories.*') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'); ?> text-base font-medium transition duration-150 ease-in-out">
                Catégories
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <?php if(auth()->guard()->check()): ?>
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                            <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?php echo e(auth()->user()->name); ?></div>
                        <div class="text-sm font-medium text-gray-500"><?php echo e(auth()->user()->email); ?></div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="<?php echo e(route('dashboard')); ?>" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Tableau de bord
                    </a>
                    <a href="<?php echo e(route('profile')); ?>" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Profil
                    </a>
                    <?php if(auth()->user()->isSubscribed()): ?>
                        <a href="<?php echo e(route('subscription.manage')); ?>" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                            Mon abonnement
                        </a>
                    <?php endif; ?>
                    <?php if(auth()->user()->isAdmin()): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                            Administration
                        </a>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-danger-600 hover:text-danger-800 hover:bg-danger-50">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="space-y-1">
                    <a href="<?php echo e(route('login')); ?>" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Connexion
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Inscription
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</nav>
<?php /**PATH C:\wamp64\www\elearning-programming\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>