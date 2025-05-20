@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-gray-700 text-3xl font-medium">Créer une Formation</h3>
            <a href="{{ route('admin.formations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Retour à la liste
            </a>
        </div>

        <div class="mt-8">
            <form action="{{ route('admin.formations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                @csrf
                
                <div class="mb-6">
                    <label for="titre" class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
                    <input type="text" name="titre" id="titre" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('titre') border-red-500 @enderror"
                           value="{{ old('titre') }}" required>
                    @error('titre')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror"
                            required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-6">
                        <label for="date_debut" class="block text-gray-700 text-sm font-bold mb-2">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('date_debut') border-red-500 @enderror"
                               value="{{ old('date_debut') }}" required>
                        @error('date_debut')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="date_fin" class="block text-gray-700 text-sm font-bold mb-2">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('date_fin') border-red-500 @enderror"
                               value="{{ old('date_fin') }}" required>
                        @error('date_fin')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-6">
                        <label for="prix" class="block text-gray-700 text-sm font-bold mb-2">Prix (FCFA)</label>
                        <input type="number" name="prix" id="prix" step="0.01" min="0"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('prix') border-red-500 @enderror"
                               value="{{ old('prix') }}" required>
                        @error('prix')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="places_disponibles" class="block text-gray-700 text-sm font-bold mb-2">Places disponibles</label>
                        <input type="number" name="places_disponibles" id="places_disponibles" min="1"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('places_disponibles') border-red-500 @enderror"
                               value="{{ old('places_disponibles') }}" required>
                        @error('places_disponibles')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="prerequis" class="block text-gray-700 text-sm font-bold mb-2">Prérequis</label>
                    <textarea name="prerequis" id="prerequis" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('prerequis') border-red-500 @enderror">{{ old('prerequis') }}</textarea>
                    @error('prerequis')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image de couverture</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('image') border-red-500 @enderror">
                        @error('image')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="flyer" class="block text-gray-700 text-sm font-bold mb-2">Flyer de la formation (PDF)</label>
                        <input type="file" name="flyer" id="flyer" accept=".pdf"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('flyer') border-red-500 @enderror">
                        <p class="text-sm text-gray-500 mt-1">Format accepté: PDF uniquement</p>
                        @error('flyer')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="statut" class="flex items-center">
                        <input type="checkbox" name="statut" id="statut" value="active"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               {{ old('statut') == 'active' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm font-medium text-gray-700">Formation active</span>
                    </label>
                    @error('statut')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Créer la formation
                    </button>                </div>
            </form>
        </div>
    </div>
@endsection
