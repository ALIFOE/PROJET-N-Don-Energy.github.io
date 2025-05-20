@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Détails du produit</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="md:flex">
            <!-- Image du produit -->
            <div class="md:w-1/3">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" 
                         alt="{{ $product->nom }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="h-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-5xl"></i>
                    </div>
                @endif
            </div>

            <!-- Informations du produit -->
            <div class="p-6 md:w-2/3">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $product->nom }}</h3>
                        <span class="px-3 py-1 rounded-full text-xs {{ 
                            $product->categorie === 'panels' ? 'bg-blue-100 text-blue-800' : 
                            ($product->categorie === 'inverters' ? 'bg-green-100 text-green-800' : 
                            ($product->categorie === 'batteries' ? 'bg-purple-100 text-purple-800' : 
                            'bg-yellow-100 text-yellow-800')) 
                        }}">
                            {{ ucfirst($product->categorie) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($product->prix, 2, ',', ' ') }} FCFA
                        </p>
                        <span class="px-3 py-1 rounded-full text-xs {{ $product->en_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->en_stock ? 'En stock' : 'Rupture de stock' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-700">Description</h4>
                    <p class="mt-2 text-gray-600">{{ $product->description }}</p>
                </div>

                @if($product->specifications)
                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700">Spécifications</h4>
                        <ul class="mt-2 space-y-2">
                            @foreach($product->specifications as $spec)
                                <li class="flex items-center text-gray-600">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    {{ $spec }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-700">Informations additionnelles</h4>
                    <div class="mt-2 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">ID Produit</p>
                            <p class="font-medium">{{ $product->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Créé le</p>
                            <p class="font-medium">{{ $product->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dernière mise à jour</p>
                            <p class="font-medium">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    @if(isset($product->orders) && $product->orders->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Historique des commandes</h3>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Quantité
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Statut
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->orders as $order)
                            <tr>                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center">
                                        <div>
                                            <p class="text-gray-900 whitespace-no-wrap font-medium">
                                                {{ $order->customer_name ?? ($order->user->name ?? 'Client inconnu') }}
                                            </p>
                                            <p class="text-gray-600 whitespace-no-wrap">
                                                {{ $order->customer_email ?? ($order->user->email ?? 'Email inconnu') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap">
                                        {{ $order->created_at->format('H:i') }}
                                    </p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ $order->quantity }}
                                    </p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap font-medium">
                                        {{ number_format($order->total_price, 2, ',', ' ') }} FCFA
                                    </p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection