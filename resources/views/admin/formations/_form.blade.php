@php
    $editing = isset($formation);
    $existingModules = old('modules', $editing ? $formation->modules->map(function ($module) {
        return [
            'title' => $module->title,
            'description' => $module->description,
            'duration_minutes' => $module->duration_minutes,
        ];
    })->toArray() : [
        ['title' => '', 'description' => '', 'duration_minutes' => 60],
    ]);
@endphp

<div class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
        <input type="text" name="title" class="w-full border-gray-300 rounded-lg" value="{{ old('title', $formation->title ?? '') }}" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="4" class="w-full border-gray-300 rounded-lg" required>{{ old('description', $formation->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Niveau</label>
            <select name="level" class="w-full border-gray-300 rounded-lg" required>
                @foreach(['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'avance' => 'Avancé'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('level', $formation->level ?? 'intermediaire') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix (€)</label>
            <input type="number" step="0.01" min="0" name="price" class="w-full border-gray-300 rounded-lg" value="{{ old('price', $formation->price ?? '0.00') }}" required>
        </div>
        <div class="flex items-center gap-2 pt-6">
            <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $formation->is_active ?? true))>
            <label for="is_active" class="text-sm font-medium text-gray-700">Formation active</label>
        </div>
    </div>

    <div>
        <p class="font-semibold text-gray-900 mb-3">Modules</p>
        <div id="modules-wrapper" class="space-y-4">
            @foreach($existingModules as $index => $module)
                <div class="border border-gray-200 rounded-lg p-4 module-item">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" name="modules[{{ $index }}][title]" placeholder="Titre du module" class="border-gray-300 rounded-lg" value="{{ $module['title'] ?? '' }}" required>
                        <input type="number" min="1" name="modules[{{ $index }}][duration_minutes]" placeholder="Durée (min)" class="border-gray-300 rounded-lg" value="{{ $module['duration_minutes'] ?? 60 }}" required>
                        <button type="button" class="btn bg-gray-100 text-gray-700 remove-module">Retirer</button>
                    </div>
                    <textarea name="modules[{{ $index }}][description]" rows="2" class="w-full mt-3 border-gray-300 rounded-lg" placeholder="Description (optionnel)">{{ $module['description'] ?? '' }}</textarea>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-module" class="btn btn-secondary mt-4">+ Ajouter un module</button>
    </div>

    <button class="btn btn-primary">{{ $editing ? 'Mettre à jour' : 'Créer la formation' }}</button>
</div>

@error('title') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
@error('description') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
@error('level') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
@error('price') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
@error('modules') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror

<script>
(() => {
    const wrapper = document.getElementById('modules-wrapper');
    const addBtn = document.getElementById('add-module');

    const bindRemove = (button) => {
        button.addEventListener('click', () => {
            if (wrapper.querySelectorAll('.module-item').length === 1) {
                return;
            }
            button.closest('.module-item').remove();
        });
    };

    wrapper.querySelectorAll('.remove-module').forEach(bindRemove);

    addBtn.addEventListener('click', () => {
        const index = wrapper.querySelectorAll('.module-item').length;
        const module = document.createElement('div');
        module.className = 'border border-gray-200 rounded-lg p-4 module-item';
        module.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" name="modules[${index}][title]" placeholder="Titre du module" class="border-gray-300 rounded-lg" required>
                <input type="number" min="1" name="modules[${index}][duration_minutes]" placeholder="Durée (min)" class="border-gray-300 rounded-lg" value="60" required>
                <button type="button" class="btn bg-gray-100 text-gray-700 remove-module">Retirer</button>
            </div>
            <textarea name="modules[${index}][description]" rows="2" class="w-full mt-3 border-gray-300 rounded-lg" placeholder="Description (optionnel)"></textarea>
        `;
        wrapper.appendChild(module);
        bindRemove(module.querySelector('.remove-module'));
    });
})();
</script>
