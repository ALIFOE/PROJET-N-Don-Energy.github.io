@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Gestion des Installations</h2>
            <p class="mt-1 text-gray-600">Suivi des installations solaires</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.installations.pending') }}" class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700">
                <i class="fas fa-clock mr-2"></i>Devis en attente
            </a>
            <a href="{{ route('admin.installations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Nouvelle installation
            </a>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Client
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Installation
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Localisation
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($installations as $installation)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div>
                                    <p class="text-gray-900 whitespace-no-wrap font-medium">
                                        {{ $installation->user->name }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap">
                                        ID: {{ $installation->user->id }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div>
                                <p class="text-gray-900 whitespace-no-wrap font-medium">
                                    {{ $installation->type_installation }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                    {{ $installation->puissance }} kWc
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $installation->adresse }}
                            </p>
                            <p class="text-gray-600 whitespace-no-wrap">
                                {{ $installation->code_postal }} {{ $installation->ville }}
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ \Carbon\Carbon::parse($installation->date_installation)->format('d/m/Y') }}
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $installation->statut === 'en_cours' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $installation->statut === 'terminee' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $installation->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $installation->statut === 'annulee' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ str_replace('_', ' ', ucfirst($installation->statut)) }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('admin.installations.show', $installation) }}" class="text-green-600 hover:text-green-900" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.installations.edit', $installation) }}" class="text-blue-600 hover:text-blue-900" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.installations.destroy', $installation) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette installation ?');">
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
                        <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            Aucune installation trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
            {{ $installations->links() }}
        </div>
    </div>
@endsection