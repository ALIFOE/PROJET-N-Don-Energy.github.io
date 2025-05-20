@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Gestion des Formations</h2>
            <p class="mt-1 text-gray-600">Gérez vos formations et consultez les inscriptions</p>
        </div>        <div class="flex space-x-4">            <a href="{{ route('admin.formations.inscriptions.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                <i class="fas fa-users mr-2"></i>Voir les inscriptions
            </a>
            <a href="{{ route('admin.formations.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Ajouter une formation
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Formation
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Dates
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Prix
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Places
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($formations as $formation)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                @if($formation->image)
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ Storage::url($formation->image) }}"
                                             alt="{{ $formation->titre }}">
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <p class="text-gray-900 whitespace-no-wrap font-medium">
                                        {{ $formation->titre }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap">
                                        {{ $formation->inscriptions_count }} inscription(s)
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                Du {{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}
                            </p>
                            <p class="text-gray-600 whitespace-no-wrap">
                                au {{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }}
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ number_format($formation->prix, 0, ',', ' ') }} FCFA
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $formation->places_disponibles - $formation->inscriptions_count }}/{{ $formation->places_disponibles }}
                            </p>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($formation->inscriptions_count / $formation->places_disponibles) * 100 }}%"></div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $formation->statut === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $formation->statut === 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            <div class="flex justify-center space-x-4">
                                <a href="{{ route('admin.formations.show', $formation) }}" 
                                   class="text-blue-600 hover:text-blue-800"
                                   title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.formations.edit', $formation) }}" 
                                   class="text-yellow-600 hover:text-yellow-800"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.formations.destroy', $formation) }}" 
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-5 bg-white text-sm text-center">
                            Aucune formation trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>        <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
            {{ $formations->links() }}
        </div>
    </div>
                </div>            </div>
        </div>
    </div>
@endsection