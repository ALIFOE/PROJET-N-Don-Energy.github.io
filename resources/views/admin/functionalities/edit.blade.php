<x-admin-layout>
    <div class="container mx-auto px-6 py-8">
        <h3 class="text-gray-700 text-3xl font-medium">Modifier la Fonctionnalité</h3>

        <div class="mt-8">
            <form action="{{ route('admin.functionalities.update', $functionality) }}" method="POST" class="space-y-6 bg-white rounded-md shadow-md p-6">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre', $functionality->titre) }}"
                        class="form-input w-full rounded-md shadow-sm @error('titre') border-red-500 @enderror">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="form-textarea w-full rounded-md shadow-sm @error('description') border-red-500 @enderror">{{ old('description', $functionality->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="icone" class="block text-sm font-medium text-gray-700 mb-2">Icône (Classe Font Awesome)</label>
                    <input type="text" name="icone" id="icone" value="{{ old('icone', $functionality->icone) }}"
                        class="form-input w-full rounded-md shadow-sm @error('icone') border-red-500 @enderror"
                        placeholder="ex: fa-solid fa-home">
                    @error('icone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="statut" class="flex items-center">
                        <input type="checkbox" name="statut" id="statut" value="1" {{ old('statut', $functionality->statut) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm font-medium text-gray-700">Actif</span>
                    </label>
                    @error('statut')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.functionalities.index') }}" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annuler
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
