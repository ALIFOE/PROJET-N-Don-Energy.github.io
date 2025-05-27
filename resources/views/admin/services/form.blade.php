@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ isset($service) ? 'Modifier le service' : 'Créer un nouveau service' }}
        </h2>
        <a href="{{ route('admin.services.index') }}" class="btn-primary">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if(isset($service))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom du service</label>
                    <input type="text" 
                           name="nom" 
                           id="nom" 
                           value="{{ old('nom', $service->nom ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              required>{{ old('description', $service->description ?? '') }}</textarea>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                    <input type="file" 
                           name="image" 
                           id="image"
                           class="mt-1 block w-full"
                           accept="image/*">
                    @if(isset($service) && $service->image)
                        <div class="mt-2">
                            <img src="{{ Storage::url($service->image) }}" alt="{{ $service->nom }}" class="h-32 w-auto">
                        </div>
                    @endif
                </div>

                <div class="border-t pt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Champs personnalisés du formulaire</h3>
                    
                    <div id="champs-supplementaires">
                        @if(isset($service) && $service->champs_supplementaires)
                            @foreach($service->champs_supplementaires as $index => $champ)
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nom du champ</label>
                                        <input type="text" 
                                               name="champs_supplementaires[{{ $index }}][nom]"
                                               value="{{ $champ['nom'] }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Label affiché</label>
                                        <input type="text" 
                                               name="champs_supplementaires[{{ $index }}][label]"
                                               value="{{ $champ['label'] }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Type de champ</label>
                                        <select name="champs_supplementaires[{{ $index }}][type]"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 type-select"
                                                required>
                                            <option value="text" {{ $champ['type'] === 'text' ? 'selected' : '' }}>Texte</option>
                                            <option value="select" {{ $champ['type'] === 'select' ? 'selected' : '' }}>Liste déroulante</option>
                                            <option value="number" {{ $champ['type'] === 'number' ? 'selected' : '' }}>Nombre</option>
                                            <option value="date" {{ $champ['type'] === 'date' ? 'selected' : '' }}>Date</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Obligatoire</label>
                                        <select name="champs_supplementaires[{{ $index }}][required]"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                required>
                                            <option value="1" {{ $champ['required'] ? 'selected' : '' }}>Oui</option>
                                            <option value="0" {{ !$champ['required'] ? 'selected' : '' }}>Non</option>
                                        </select>
                                    </div>
                                    @if($champ['type'] === 'select')
                                        <div class="md:col-span-4">
                                            <label class="block text-sm font-medium text-gray-700">Options (une par ligne)</label>
                                            <textarea name="champs_supplementaires[{{ $index }}][options]"
                                                      rows="3"
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ implode("\n", $champ['options'] ?? []) }}</textarea>
                                        </div>
                                    @endif
                                    <div class="md:col-span-4 flex justify-end">
                                        <button type="button" class="text-red-600 hover:text-red-800" onclick="supprimerChamp(this)">
                                            <i class="fas fa-trash"></i> Supprimer ce champ
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" 
                            onclick="ajouterChamp()"
                            class="mt-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>Ajouter un champ
                    </button>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>{{ isset($service) ? 'Mettre à jour' : 'Créer' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let champIndex = {{ isset($service) ? count($service->champs_supplementaires ?? []) : 0 }};

function ajouterChamp() {
    const template = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom du champ</label>
                <input type="text" 
                       name="champs_supplementaires[${champIndex}][nom]"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Label affiché</label>
                <input type="text" 
                       name="champs_supplementaires[${champIndex}][label]"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Type de champ</label>
                <select name="champs_supplementaires[${champIndex}][type]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 type-select"
                        onchange="toggleOptions(this)"
                        required>
                    <option value="text">Texte</option>
                    <option value="select">Liste déroulante</option>
                    <option value="number">Nombre</option>
                    <option value="date">Date</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Obligatoire</label>
                <select name="champs_supplementaires[${champIndex}][required]"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>
            <div class="md:col-span-4 options-container" style="display: none;">
                <label class="block text-sm font-medium text-gray-700">Options (une par ligne)</label>
                <textarea name="champs_supplementaires[${champIndex}][options]"
                          rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="button" class="text-red-600 hover:text-red-800" onclick="supprimerChamp(this)">
                    <i class="fas fa-trash"></i> Supprimer ce champ
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('champs-supplementaires').insertAdjacentHTML('beforeend', template);
    champIndex++;
}

function supprimerChamp(button) {
    button.closest('.grid').remove();
}

function toggleOptions(select) {
    const optionsContainer = select.closest('.grid').querySelector('.options-container');
    if (select.value === 'select') {
        optionsContainer.style.display = 'block';
    } else {
        optionsContainer.style.display = 'none';
    }
}

// Initialiser l'affichage des options pour les champs existants
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.type-select').forEach(select => {
        toggleOptions(select);
    });
});
</script>
@endpush
@endsection
