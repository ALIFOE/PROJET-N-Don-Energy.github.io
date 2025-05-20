@extends('layouts.app')

@section('content')    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Inscriptions aux Formations</h1>
                        <p class="mt-1 text-gray-600">Gérez les inscriptions et suivez leur statut</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.formations.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i>Retour aux formations
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif                    <div class="overflow-x-auto bg-white rounded-lg shadow">
                        <div class="flex items-center justify-between p-4 border-b">                            <form action="{{ route('admin.formations.inscriptions.index') }}" method="GET" class="flex justify-between w-full">
                                <div class="flex space-x-4">
                                    <select name="formation_id" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Toutes les formations</option>
                                        @foreach($formations as $formation)
                                            <option value="{{ $formation->id }}" {{ request('formation_id') == $formation->id ? 'selected' : '' }}>
                                                {{ $formation->titre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="statut" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Tous les statuts</option>
                                        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="validee" {{ request('statut') === 'validee' ? 'selected' : '' }}>Validée</option>
                                        <option value="refusee" {{ request('statut') === 'refusee' ? 'selected' : '' }}>Refusée</option>
                                    </select>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <input type="text" name="search" placeholder="Rechercher..." 
                                               value="{{ request('search') }}"
                                               class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        <i class="fas fa-filter mr-2"></i>Filtrer
                                    </button>
                                    <a href="{{ route('admin.formations.inscriptions.index', ['export' => 'excel']) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        <i class="fas fa-file-excel mr-2"></i>Exporter
                                    </a>
                                </div>
                            </form>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Formation</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($inscriptions as $inscription)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $inscription->formation->titre }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $inscription->formation->date_debut->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $inscription->nom }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $inscription->email }}<br>
                                                {{ $inscription->telephone }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.formations.inscriptions.document.download', ['inscription' => $inscription->id, 'type' => 'acte_naissance']) }}" 
                                                   class="text-blue-600 hover:text-blue-800" title="Acte de naissance">
                                                    <i class="fas fa-id-card"></i>
                                                </a>
                                                <a href="{{ route('admin.formations.inscriptions.document.download', ['inscription' => $inscription->id, 'type' => 'cni']) }}" 
                                                   class="text-blue-600 hover:text-blue-800" title="CNI">
                                                    <i class="fas fa-passport"></i>
                                                </a>
                                                <a href="{{ route('admin.formations.inscriptions.document.download', ['inscription' => $inscription->id, 'type' => 'diplome']) }}" 
                                                   class="text-blue-600 hover:text-blue-800" title="Diplôme">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </a>
                                            </div>
                                        </td>                                        <td class="px-6 py-4 whitespace-nowrap">                                                <form action="{{ route('admin.formations.inscriptions.status', $inscription->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="statut" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                                        onchange="this.form.submit()">
                                                    <option value="en_attente" {{ $inscription->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                    <option value="validee" {{ $inscription->statut === 'validee' ? 'selected' : '' }}>Validée</option>
                                                    <option value="refusee" {{ $inscription->statut === 'refusee' ? 'selected' : '' }}>Refusée</option>
                                                </select>
                                            </form>
                                        </td>                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-4">
                                                <a href="mailto:{{ $inscription->email }}" 
                                                   class="text-blue-600 hover:text-blue-800"
                                                   title="Envoyer un email">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                                <a href="{{ route('admin.formations.inscriptions.show', $inscription->id) }}" 
                                                   class="text-green-600 hover:text-green-800"
                                                   title="Voir les détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($inscription->statut === 'refusee' || $inscription->statut === 'validee')
                                                    <form action="{{ route('admin.formations.inscriptions.destroy', $inscription) }}" 
                                                          method="POST" 
                                                          class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-800"
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?')"
                                                                title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="px-6 py-4 border-t">
                            {{ $inscriptions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
