@extends('layouts.app')

@section('title', 'Connexion - CodeLearn')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-code text-white text-2xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Connexion</h2>
            <p class="mt-2 text-gray-600">Connectez-vous pour accéder à votre espace</p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            class="form-input pl-10 w-full" 
                            placeholder="votre@email.com"
                            value="{{ old('email') }}">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                            class="form-input pl-10 w-full" 
                            placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Se souvenir de moi
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500">
                        Mot de passe oublié ?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary w-full py-3">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="text-sm text-gray-600">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    Inscrivez-vous gratuitement
                </a>
            </p>
        </div>

        <!-- Demo Accounts -->
        <div class="mt-8 border-t border-gray-200 pt-6">
            <p class="text-center text-sm text-gray-500 mb-4">Comptes de démonstration</p>
            <div class="grid grid-cols-3 gap-2 text-xs">
                <div class="bg-gray-100 rounded p-2 text-center">
                    <div class="font-semibold">Admin</div>
                    <div class="text-gray-500">admin@codelearn.fr</div>
                </div>
                <div class="bg-gray-100 rounded p-2 text-center">
                    <div class="font-semibold">Gratuit</div>
                    <div class="text-gray-500">free@codelearn.fr</div>
                </div>
                <div class="bg-gray-100 rounded p-2 text-center">
                    <div class="font-semibold">Premium</div>
                    <div class="text-gray-500">premium@codelearn.fr</div>
                </div>
            </div>
            <p class="text-center text-xs text-gray-400 mt-2">Mot de passe pour tous : password</p>
        </div>
    </div>
</div>
@endsection
