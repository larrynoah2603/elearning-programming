@extends('layouts.app')

@section('title', 'Ajouter une formation')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Ajouter une formation payante</h1>
        <p class="text-gray-600 mt-2">Créez d'abord les formations depuis l'administration, ensuite les utilisateurs pourront les acheter.</p>
    </div>

    <form method="POST" action="{{ route('admin.formations.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf
        @include('admin.formations._form')
    </form>
</div>
@endsection
