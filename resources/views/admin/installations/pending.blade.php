@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Devis en Attente</h2>
            <p class="mt-1 text-gray-600">Gérez les demandes de devis des clients</p>
        </div>
        <a href="{{ route('admin.installations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Retour aux installations
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Client
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Détails du Projet
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Budget
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Date de Demande
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($devis as $devis)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div>
                                    <p class="text-gray-900 whitespace-no-wrap font-medium">
                                        {{ $devis->user->name }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap">
                                        {{ $devis->user->email }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap">
                                        {{ $devis->user->telephone }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div>
                                <p class="text-gray-900 whitespace-no-wrap font-medium">
                                    {{ $devis->type_projet }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                    {{ $devis->details }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap">
                                    {{ $devis->adresse }}, {{ $devis->code_postal }} {{ $devis->ville }}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ number_format($devis->budget_estimatif, 2, ',', ' ') }} €
                            </p>
                            @if($devis->flexible)
                                <span class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">Budget flexible</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $devis->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-gray-600 whitespace-no-wrap">
                                {{ $devis->created_at->diffForHumans() }}
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('admin.devis.show', $devis) }}" 
                                   class="text-green-600 hover:text-green-900" 
                                   title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.installations.create', ['devis_id' => $devis->id]) }}" 
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Créer une installation">
                                    <i class="fas fa-tools"></i>
                                </a>
                                <button type="button" 
                                        onclick="window.location.href='mailto:{{ $devis->user->email }}'"
                                        class="text-indigo-600 hover:text-indigo-900"
                                        title="Contacter le client">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <form action="{{ route('admin.devis.destroy', $devis) }}" 
                                      method="POST" 
                                      class="inline" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?');">
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
                            Aucun devis en attente.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
            {{ $devis->links() }}
        </div>
    </div>
@endsection