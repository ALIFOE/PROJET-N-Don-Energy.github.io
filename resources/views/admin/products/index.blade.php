@extends('admin.layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Gestion des Produits</h2>
            <p class="mt-1 text-gray-600">Gérez votre catalogue de produits</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.orders.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                <i class="fas fa-shopping-cart mr-2"></i>Voir les commandes
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Ajouter un produit
            </a>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Produit
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Catégorie
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Prix
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Stock
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                @if($product->image)
                                    <div class="flex-shrink-0 w-16 h-16">
                                        <img class="w-full h-full rounded object-cover" 
                                             src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->nom }}">
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <p class="text-gray-900 whitespace-no-wrap font-medium">
                                        {{ $product->nom }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap">
                                        {{ Str::limit($product->description, 50) }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $product->categorie === 'panels' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $product->categorie === 'inverters' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $product->categorie === 'batteries' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $product->categorie === 'accessories' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ ucfirst($product->categorie) }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ number_format($product->prix, 2, ',', ' ') }} €
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-3 py-1 rounded-full text-xs {{ $product->en_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->en_stock ? 'En stock' : 'Rupture' }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('admin.products.show', $product) }}" class="text-green-600 hover:text-green-900" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            Aucun produit trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection