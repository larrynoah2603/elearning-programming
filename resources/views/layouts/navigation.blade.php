<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and Left Navigation -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-code text-white text-lg"></i>
                        </div>
                        <span class="font-bold text-xl text-gray-800 hidden sm:block">CodeLearn</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">
                        Accueil
                    </a>
                    <a href="{{ route('lessons.index') }}" class="nav-link {{ request()->routeIs('lessons.*') ? 'nav-link-active' : '' }}">
                        Leçons
                    </a>
                    <a href="{{ route('exercises.index') }}" class="nav-link {{ request()->routeIs('exercises.*') ? 'nav-link-active' : '' }}">
                        Exercices
                    </a>
                    <a href="{{ route('videos.index') }}" class="nav-link {{ request()->routeIs('videos.*') ? 'nav-link-active' : '' }}">
                        Vidéos
                    </a>
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'nav-link-active' : '' }}">
                        Catégories
                    </a>
                </div>
            </div>

            <!-- Right Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Admin Link -->
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link mr-4 {{ request()->routeIs('admin.*') ? 'nav-link-active' : '' }}">
                            <i class="fas fa-cog mr-1"></i> Admin
                        </a>
                    @endif

                    <!-- Subscription Badge -->
                    @if(auth()->user()->isSubscribed())
                        <span class="badge badge-success mr-4">
                            <i class="fas fa-crown mr-1"></i> Premium
                        </span>
                    @else
                        <a href="{{ route('subscription.plans') }}" class="badge badge-warning mr-4 hover:bg-warning-200 transition-colors">
                            <i class="fas fa-star mr-1"></i> Passer Premium
                        </a>
                    @endif

                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="ml-2 text-gray-700 self-center">{{ auth()->user()->name }}</span>
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
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord
                                </a>
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </a>
                                @if(auth()->user()->isSubscribed())
                                    <a href="{{ route('subscription.manage') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-crown mr-2"></i> Mon abonnement
                                    </a>
                                @endif
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-danger-600 hover:bg-danger-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-600 font-medium">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Inscription
                        </a>
                    </div>
                @endauth
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
            <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('home') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                Accueil
            </a>
            <a href="{{ route('lessons.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('lessons.*') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                Leçons
            </a>
            <a href="{{ route('exercises.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('exercises.*') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                Exercices
            </a>
            <a href="{{ route('videos.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('videos.*') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                Vidéos
            </a>
            <a href="{{ route('categories.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('categories.*') ? 'border-primary-500 text-primary-700 bg-primary-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                Catégories
            </a>
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Tableau de bord
                    </a>
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Profil
                    </a>
                    @if(auth()->user()->isSubscribed())
                        <a href="{{ route('subscription.manage') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                            Mon abonnement
                        </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                            Administration
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-danger-600 hover:text-danger-800 hover:bg-danger-50">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="space-y-1">
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">
                        Inscription
                    </a>
                </div>
            </div>
        @endauth
    </div>
</nav>
