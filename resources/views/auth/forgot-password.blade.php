@extends('layouts.app')

@section('title', 'Mot de passe oublié - CodeLearn')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-xl shadow-sm p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Mot de passe oublié</h1>
            <p class="text-gray-600 mt-2">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 rounded-md bg-green-50 text-green-700 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    placeholder="votre@email.com">
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Envoyer le lien de réinitialisation
            </button>
        </form>

        <p class="text-sm text-gray-500 mt-6 text-center">
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700">Retour à la connexion</a>
        </p>
    </div>
</div>
@endsection
