@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Ajouter une fonctionnalité</h2>
        <a href="{{ route('admin.functionalities.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.functionalities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                <input type="text" name="titre" id="titre" class="form-input w-full rounded-md shadow-sm @error('titre') border-red-500 @enderror" value="{{ old('titre') }}" required>
                @error('titre')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4" class="form-textarea w-full rounded-md shadow-sm @error('description') border-red-500 @enderror" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="icone" class="block text-sm font-medium text-gray-700 mb-2">Icône (Class FontAwesome)</label>
                <div class="flex items-center space-x-2">
                    <input type="text" name="icone" id="icone" class="form-input flex-1 rounded-md shadow-sm @error('icone') border-red-500 @enderror" value="{{ old('icone', 'fas fa-') }}" required>
                    <span class="preview-icon">
                        <i id="iconPreview" class="{{ old('icone', 'fas fa-star') }}"></i>
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-500">Exemple: fas fa-star, fas fa-home, etc.</p>
                @error('icone')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>            <div class="mb-6">
                <label for="statut" class="flex items-center">
                    <input type="checkbox" name="statut" id="statut" value="1" {{ old('statut', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm font-medium text-gray-700">Actif</span>
                </label>
                @error('statut')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Prévisualisation de l'icône en temps réel
        document.getElementById('icone').addEventListener('input', function() {
            const preview = document.getElementById('iconPreview');
            preview.className = this.value;
        });
    </script>
    @endpush
@endsection