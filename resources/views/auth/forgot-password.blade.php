@extends('layouts.app')

@section('title', 'Mot de passe oublié - CodeLearn')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Mot de passe oublié</h1>
        <p class="text-sm text-gray-600 mb-6">
            Entrez votre email. Nous allons vous envoyer un lien de réinitialisation via Gmail SMTP.
        </p>

        @if (session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2.5 text-white font-medium hover:bg-indigo-700 transition">
                Envoyer le lien de réinitialisation
            </button>
        </form>
    </div>
</div>
@endsection
