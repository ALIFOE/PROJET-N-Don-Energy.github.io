@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Modifier l'onduleur</h2>

        <form method="POST" action="{{ route('onduleurs.update', ['onduleur' => $onduleur->id]) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="modele" class="block text-sm font-medium text-gray-700">Modèle</label>
                <input type="text" name="modele" id="modele" required 
                    value="{{ old('modele', $onduleur->modele) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="numero_serie" class="block text-sm font-medium text-gray-700">Numéro de série</label>
                <input type="text" name="numero_serie" id="numero_serie" required 
                    value="{{ old('numero_serie', $onduleur->numero_serie) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label for="est_connecte" class="block text-sm font-medium text-gray-700">État de connexion</label>
                <select name="est_connecte" id="est_connecte" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="1" {{ $onduleur->est_connecte ? 'selected' : '' }}>Connecté</option>
                    <option value="0" {{ !$onduleur->est_connecte ? 'selected' : '' }}>Non connecté</option>
                </select>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('onduleurs.index') }}" 
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Annuler
                </a>
                <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection