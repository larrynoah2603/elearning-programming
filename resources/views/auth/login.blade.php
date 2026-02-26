@extends('layouts.app')

@section('title', 'Connexion - CodeLearn')

@section('content')

<style>
    /* Configuration de l'arrière-plan avec diaporama */
    .bg-image-loop {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        transition: background-image 1s ease-in-out;
        animation: backgroundSlideshow 15s infinite;
    }

    @keyframes backgroundSlideshow {
        0%, 30% { background-image: url('{{ asset("images/stock-photo-children-learning-robotics-in-a-classroom-assembling-and-programming-a-robotic-hand-with-guidance-2659076193.jpg") }}'); }
        33%, 63% { background-image: url('{{ asset("images/stock-photo-machine-learning-to-help-coding-programming-and-website-development-man-with-laptop-head-with-2617501381.jpg") }}'); }
        66%, 100% { background-image: url('{{ asset("images/stock-vector-vector-illustration-set-of-online-education-cartoon-flat-characters-learning-online-courses-2588438379.jpg") }}'); }
    }

    .bg-image-loop::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 0;
    }

    .relative-content {
        position: relative;
        z-index: 10;
    }

    /* Style pour l'icône de visibilité */
    .password-toggle {
        cursor: pointer;
        transition: color 0.2s;
    }
    .password-toggle:hover {
        color: #818cf8; /* indigo-400 */
    }
</style>

<div class="min-h-screen flex items-center justify-center bg-image-loop py-12 px-4 sm:px-6 lg:px-8 relative">
    
    <div class="max-w-md w-full space-y-8 bg-slate-900/90 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-slate-700 relative-content">
        
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-code text-white text-2xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-white">Connexion</h2>
            <p class="mt-2 text-slate-300">Connectez-vous pour accéder à votre espace</p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-200">Adresse email</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-500"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                            class="block w-full pl-10 bg-slate-800 border-slate-600 text-white placeholder-slate-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                            placeholder="votre@email.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-200">Mot de passe</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-500"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-10 pr-10 bg-slate-800 border-slate-600 text-white placeholder-slate-400 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                            placeholder="••••••••">
                        
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" onclick="togglePassword()" class="focus:outline-none">
                                <i id="password-icon" class="fas fa-eye text-slate-500 password-toggle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-600 rounded bg-slate-800">
                    <label for="remember_me" class="ml-2 block text-sm text-slate-300">Se souvenir de moi</label>
                </div>
                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-400 hover:text-indigo-300">Mot de passe oublié ?</a>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Se connecter
                </button>
            </div>
        </form>

        <div class="text-center">
            <p class="text-sm text-slate-400">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="font-medium text-indigo-400 hover:text-indigo-300">Inscrivez-vous</a>
            </p>
        </div>
        
    </div> 
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Change l'icône pour l'œil barré
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            // Remet l'icône de l'œil normal
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }
</script>

@endsection