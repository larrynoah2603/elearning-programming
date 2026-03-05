@extends('layouts.app')

@section('title', 'Modifier une formation')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Modifier la formation</h1>
    </div>

    <form method="POST" action="{{ route('admin.formations.update', $formation) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf
        @method('PUT')
        @include('admin.formations._form', ['formation' => $formation])
    </form>
</div>
@endsection
