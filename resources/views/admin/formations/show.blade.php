@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Détails de la formation</h2>
        <div class="space-x-2">
            <a href="{{ route('admin.formations.edit', $formation) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-edit mr-2"></i>Modifier
            </a>
            <a href="{{ route('admin.formations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Informations générales</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Titre</label>
                            <p class="mt-1">{{ $formation->titre }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prix</label>
                            <p class="mt-1">{{ number_format($formation->prix, 2, ',', ' ') }} FCFA</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Places disponibles</label>
                            <p class="mt-1">{{ $formation->places_disponibles }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut</label>
                            <p class="mt-1">
                                <span class="px-2 py-1 text-sm rounded-full {{ $formation->statut === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $formation->statut === 'active' ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Dates et documents</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de début</label>
                            <p class="mt-1">{{ $formation->date_debut->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                            <p class="mt-1">{{ $formation->date_fin->format('d/m/Y') }}</p>
                        </div>
                        @if($formation->image)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image</label>
                            <div class="mt-2">
                                <img src="{{ Storage::url($formation->image) }}" alt="Image de la formation" class="max-w-xs rounded-lg shadow">
                            </div>
                        </div>
                        @endif
                        @if($formation->flyer)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Flyer</label>
                            <div class="mt-2">
                                <a href="{{ route('admin.formations.flyer.download', $formation) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-file-pdf mr-2"></i>Voir le flyer
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Description</h3>
                <p class="whitespace-pre-line">{{ $formation->description }}</p>
            </div>

            @if($formation->prerequis)
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Prérequis</h3>
                <p class="whitespace-pre-line">{{ $formation->prerequis }}</p>
            </div>
            @endif
        </div>

        <div class="mt-8 border-t border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Liste des inscriptions</h3>
                @if($inscriptions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Nom
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Téléphone
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Date d'inscription
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inscriptions as $inscription)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $inscription->nom }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $inscription->email }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $inscription->telephone }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <span class="px-2 py-1 text-sm rounded-full 
                                                {{ $inscription->statut === 'acceptée' ? 'bg-green-100 text-green-800' : 
                                                   ($inscription->statut === 'refusée' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($inscription->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $inscription->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                            <form action="{{ route('admin.formations.inscriptions.destroy', $inscription) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Aucune inscription pour le moment.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
