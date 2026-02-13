@extends('layouts.app')

@section('title', 'Créer une catégorie - Admin')

@section('content')
<div class="py-8">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Nouvelle catégorie</h1>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="bg-white rounded-xl shadow-sm p-6 space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700">Nom</label>
        <input name="name" value="{{ old('name') }}" class="form-input mt-1" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" class="form-textarea mt-1">{{ old('description') }}</textarea>
      </div>
      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Icône (fa-...)</label>
          <input name="icon" value="{{ old('icon') }}" class="form-input mt-1">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Ordre</label>
          <input name="order" type="number" value="{{ old('order', 0) }}" class="form-input mt-1">
        </div>
      </div>
      <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" checked> Active</label>
      <div class="flex gap-3">
        <button class="btn btn-primary" type="submit">Créer</button>
        <a href="{{ route('admin.categories.index') }}" class="btn bg-gray-100 text-gray-700">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
