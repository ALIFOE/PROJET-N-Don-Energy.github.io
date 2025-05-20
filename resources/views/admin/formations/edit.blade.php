@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Modifier la formation</h1>

            <form action="{{ route('admin.formations.update', $formation) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="titre" class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre', $formation->titre) }}" 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titre') border-red-500 @enderror">
                    @error('titre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $formation->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">                    <div class="mb-6">
                        <label for="date_debut" class="block text-gray-700 text-sm font-bold mb-2">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut', \Carbon\Carbon::parse($formation->date_debut)->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_debut') border-red-500 @enderror">
                        @error('date_debut')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="date_fin" class="block text-gray-700 text-sm font-bold mb-2">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin', \Carbon\Carbon::parse($formation->date_fin)->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('date_fin') border-red-500 @enderror">
                        @error('date_fin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-6">
                        <label for="prix" class="block text-gray-700 text-sm font-bold mb-2">Prix</label>
                        <input type="number" name="prix" id="prix" value="{{ old('prix', $formation->prix) }}"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prix') border-red-500 @enderror">
                        @error('prix')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="places_disponibles" class="block text-gray-700 text-sm font-bold mb-2">Places disponibles</label>
                        <input type="number" name="places_disponibles" id="places_disponibles" value="{{ old('places_disponibles', $formation->places_disponibles) }}"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('places_disponibles') border-red-500 @enderror">
                        @error('places_disponibles')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="prerequis" class="block text-gray-700 text-sm font-bold mb-2">Prérequis</label>
                    <textarea name="prerequis" id="prerequis" rows="3"
                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prerequis') border-red-500 @enderror">{{ old('prerequis', $formation->prerequis) }}</textarea>
                    @error('prerequis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image de couverture</label>
                        @if($formation->image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($formation->image) }}" alt="Image actuelle" class="w-40 h-40 object-cover rounded">
                            </div>
                        @endif
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror">
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="flyer" class="block text-gray-700 text-sm font-bold mb-2">Flyer de la formation (PDF)</label>
                        @if($formation->flyer)
                            <div class="mb-2">
                                <a href="{{ route('admin.formations.flyer.download', $formation) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    Voir le flyer actuel
                                </a>
                            </div>
                        @endif
                        <input type="file" name="flyer" id="flyer" accept=".pdf"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('flyer') border-red-500 @enderror">
                        @error('flyer')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Format accepté: PDF uniquement (max 5MB)</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="statut" class="block text-gray-700 text-sm font-bold mb-2">Statut</label>
                    <select name="statut" id="statut"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('statut') border-red-500 @enderror">
                        <option value="active" {{ old('statut', $formation->statut) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('statut', $formation->statut) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('statut')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.formations.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
