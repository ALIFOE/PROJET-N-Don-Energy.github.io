resources/views/admin/formations/form.blade.php@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ isset($formation) ? 'Modifier la formation' : 'Ajouter une formation' }}
        </h2>
        <a href="{{ route('admin.formations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($formation) ? route('admin.formations.update', $formation) : route('admin.formations.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if(isset($formation))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                    <input type="text" 
                           name="titre" 
                           id="titre" 
                           value="{{ old('titre', $formation->titre ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700">Prix (FCFA)</label>
                    <input type="number" 
                           name="prix" 
                           id="prix" 
                           step="0.01" 
                           min="0" 
                           value="{{ old('prix', $formation->prix ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('prix')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                    <input type="date" 
                           name="date_debut" 
                           id="date_debut" 
                           value="{{ old('date_debut', isset($formation) ? $formation->date_debut->format('Y-m-d') : '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('date_debut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                    <input type="date" 
                           name="date_fin" 
                           id="date_fin" 
                           value="{{ old('date_fin', isset($formation) ? $formation->date_fin->format('Y-m-d') : '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('date_fin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="places_disponibles" class="block text-sm font-medium text-gray-700">Nombre de places</label>
                    <input type="number" 
                           name="places_disponibles" 
                           id="places_disponibles" 
                           min="1" 
                           value="{{ old('places_disponibles', $formation->places_disponibles ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('places_disponibles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" 
                            id="statut" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="active" {{ (old('statut', $formation->statut ?? '') === 'active') ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ (old('statut', $formation->statut ?? '') === 'inactive') ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" 
                          id="description" 
                          rows="4" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          required>{{ old('description', $formation->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="prerequis" class="block text-sm font-medium text-gray-700">Prérequis</label>
                <textarea name="prerequis" 
                          id="prerequis" 
                          rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('prerequis', $formation->prerequis ?? '') }}</textarea>
                @error('prerequis')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <div class="mt-1 flex items-center">
                    <input type="file" 
                           name="image" 
                           id="image" 
                           accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           {{ !isset($formation) ? 'required' : '' }}>
                </div>
                @if(isset($formation) && $formation->image)
                    <div class="mt-2">
                        <img src="{{ Storage::url($formation->image) }}" alt="Image actuelle" class="h-32 w-auto object-cover rounded">
                    </div>
                @endif
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <button type="reset" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Réinitialiser
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ isset($formation) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>
@endsection