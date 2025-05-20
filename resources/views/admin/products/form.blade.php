@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ isset($product) ? 'Modifier le produit' : 'Ajouter un produit' }}
        </h2>
        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom du produit</label>
                    <input type="text" 
                           name="nom" 
                           id="nom" 
                           value="{{ old('nom', $product->nom ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="categorie" class="block text-sm font-medium text-gray-700">Catégorie</label>
                    <select name="categorie" 
                            id="categorie" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                        <option value="panels" {{ (old('categorie', $product->categorie ?? '') === 'panels') ? 'selected' : '' }}>Panneaux solaires</option>
                        <option value="inverters" {{ (old('categorie', $product->categorie ?? '') === 'inverters') ? 'selected' : '' }}>Onduleurs</option>
                        <option value="batteries" {{ (old('categorie', $product->categorie ?? '') === 'batteries') ? 'selected' : '' }}>Batteries</option>
                        <option value="accessories" {{ (old('categorie', $product->categorie ?? '') === 'accessories') ? 'selected' : '' }}>Accessoires</option>
                    </select>
                    @error('categorie')
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
                           value="{{ old('prix', $product->prix ?? '') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           required>
                    @error('prix')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="en_stock" class="block text-sm font-medium text-gray-700">Disponibilité</label>
                    <select name="en_stock" 
                            id="en_stock" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="1" {{ (old('en_stock', $product->en_stock ?? true) == true) ? 'selected' : '' }}>En stock</option>
                        <option value="0" {{ (old('en_stock', $product->en_stock ?? true) == false) ? 'selected' : '' }}>Rupture de stock</option>
                    </select>
                    @error('en_stock')
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
                          required>{{ old('description', $product->description ?? '') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Spécifications</label>
                <div id="specifications-container" class="space-y-3">
                    @if(isset($product) && $product->specifications)
                        @foreach($product->specifications as $spec)
                            <div class="specification-item flex items-center space-x-2">
                                <input type="text" 
                                       name="specifications[]" 
                                       value="{{ $spec }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <button type="button" 
                                        onclick="this.parentElement.remove()"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" 
                        onclick="addSpecification()"
                        class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                    <i class="fas fa-plus mr-1"></i>Ajouter une spécification
                </button>
                @error('specifications')
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
                           {{ !isset($product) ? 'required' : '' }}>
                </div>
                @if(isset($product) && $product->image)
                    <div class="mt-2">
                        <img src="{{ Storage::url($product->image) }}" alt="Image actuelle" class="h-32 w-auto object-cover rounded">
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
                    {{ isset($product) ? 'Mettre à jour' : 'Créer' }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function addSpecification() {
            const container = document.getElementById('specifications-container');
            const newItem = document.createElement('div');
            newItem.className = 'specification-item flex items-center space-x-2';
            newItem.innerHTML = `
                <input type="text" 
                       name="specifications[]" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="button" 
                        onclick="this.parentElement.remove()"
                        class="text-red-600 hover:text-red-900">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newItem);
        }
    </script>
    @endpush
@endsection