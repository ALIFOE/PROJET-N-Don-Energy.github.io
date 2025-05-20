@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ isset($functionality) ? 'Modifier la fonctionnalité' : 'Ajouter une fonctionnalité' }}
        </h2>
        <a href="{{ route('admin.functionalities.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($functionality) ? route('admin.functionalities.update', $functionality) : route('admin.functionalities.store') }}" 
              method="POST" 
              class="space-y-6">
            @csrf
            @if(isset($functionality))
                @method('PUT')
            @endif

            <div>
                <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" 
                       name="titre" 
                       id="titre" 
                       value="{{ old('titre', $functionality->titre ?? '') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
                @error('titre')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" 
                          id="description" 
                          rows="4" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          required>{{ old('description', $functionality->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="icone" class="block text-sm font-medium text-gray-700">Icône (classe Font Awesome)</label>
                <div class="mt-1 flex items-center space-x-3">
                    <input type="text" 
                           name="icone" 
                           id="icone" 
                           value="{{ old('icone', $functionality->icone ?? '') }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <i id="icone-preview" class="{{ old('icone', $functionality->icone ?? 'fas fa-question') }} text-2xl"></i>
                </div>
                @error('icone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" 
                        id="statut" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="active" {{ (old('statut', $functionality->statut ?? '') === 'active') ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ (old('statut', $functionality->statut ?? '') === 'inactive') ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('statut')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <button type="reset" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Réinitialiser
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ isset($functionality) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('icone').addEventListener('input', function() {
            document.getElementById('icone-preview').className = this.value + ' text-2xl';
        });
    </script>
    @endpush
@endsection