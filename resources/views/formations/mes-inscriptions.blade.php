@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Mes Inscriptions aux Formations</h1>

        @if(empty($inscriptions))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Vous n'avez pas encore d'inscriptions aux formations.                            <a href="{{ route('formation') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                Voir les formations disponibles
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Formation
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de début
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Documents
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($inscriptions as $inscription)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $inscription['formation']['titre'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($inscription['formation']['date_debut'])->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($inscription['statut'] === 'validee') bg-green-100 text-green-800 
                                        @elseif($inscription['statut'] === 'refusee') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($inscription['statut']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="space-x-2">
                                        <a href="{{ route('formation.document.download', ['inscription' => $inscription['id'], 'type' => 'acte_naissance']) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            Acte de naissance
                                        </a>
                                        <a href="{{ route('formation.document.download', ['inscription' => $inscription['id'], 'type' => 'cni']) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            CNI
                                        </a>
                                        <a href="{{ route('formation.document.download', ['inscription' => $inscription['id'], 'type' => 'diplome']) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            Diplôme
                                        </a>
                                        {{-- Documents optionnels --}}
                                        @if(!empty($inscription['documents_optionnels']) && is_array($inscription['documents_optionnels']))
                                            @foreach($inscription['documents_optionnels'] as $index => $doc)
                                                <a href="{{ route('formation.document.download', ['inscription' => $inscription['id'], 'type' => 'optionnel', 'index' => $index]) }}"
                                                   class="text-blue-600 hover:text-blue-900">
                                                    {{ $doc['nom'] ?? 'Document optionnel '.($index+1) }}
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
