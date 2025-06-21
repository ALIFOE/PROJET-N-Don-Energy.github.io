@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6 pt-16">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ __('Ajouter un produit') }}
        </h2>
        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom du produit *</label>
                    <input type="text" 
                           name="nom" 
                           id="nom" 
                           value="{{ old('nom') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                           required
                           placeholder="Entrez le nom du produit">
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700">Prix (FCFA) *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="number" 
                               name="prix" 
                               id="prix" 
                               step="0.01" 
                               min="0" 
                               value="{{ old('prix') }}" 
                               class="block w-full rounded-md border-gray-300 pl-3 pr-12 focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                               required
                               placeholder="0.00">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">€</span>
                        </div>
                    </div>
                    @error('prix')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité en stock *</label>
                    <input type="number" 
                           name="quantite" 
                           id="quantite" 
                           min="0" 
                           value="{{ old('quantite', 0) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                           required>
                    @error('quantite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="categorie" class="block text-sm font-medium text-gray-700">Catégorie *</label>
                    <select name="categorie" 
                            id="categorie" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                            required>
                        <option value="">Sélectionner une catégorie</option>
                        <option value="Panneaux solaires" {{ old('categorie') == 'Panneaux solaires' ? 'selected' : '' }}>Panneaux solaires</option>
                        <option value="Onduleurs" {{ old('categorie') == 'Onduleurs' ? 'selected' : '' }}>Onduleurs</option>
                        <option value="Batteries" {{ old('categorie') == 'Batteries' ? 'selected' : '' }}>Batteries</option>
                        <option value="Accessoires" {{ old('categorie') == 'Accessoires' ? 'selected' : '' }}>Accessoires</option>
                    </select>
                    @error('categorie')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                <textarea name="description" 
                          id="description" 
                          rows="4" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                          required
                          placeholder="Décrivez les caractéristiques principales du produit">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="specifications" class="block text-sm font-medium text-gray-700">Spécifications techniques</label>
                <div id="specifications-container" class="space-y-2">
                    <div class="flex gap-2">
                        <input type="text" 
                               name="specifications[]" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                               placeholder="Exemple: Puissance: 400W">
                        <button type="button" 
                                onclick="addSpecification()"
                                class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                @error('specifications.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image du produit *</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-500 transition duration-150">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>Télécharger une image</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" required>
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 2MB</p>
                    </div>
                </div>
                <div id="image-preview" class="mt-2 hidden">
                    <img src="" alt="Aperçu" class="max-h-40 mx-auto">
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="window.history.back()"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 transition duration-150">
                    Annuler
                </button>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition duration-150">
                    Créer le produit
                </button>
            </div>
        </form>
    </div>

    <script>
        function addSpecification() {
            const container = document.getElementById('specifications-container');
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-2';
            newRow.innerHTML = `
                <input type="text" 
                       name="specifications[]" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"
                       placeholder="Exemple: Puissance: 400W">
                <button type="button" 
                        onclick="this.parentElement.remove()"
                        class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-150">
                    <i class="fas fa-minus"></i>
                </button>
            `;
            container.appendChild(newRow);
        }

        // Aperçu de l'image
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('image-preview');
                const previewImage = preview.querySelector('img');
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection