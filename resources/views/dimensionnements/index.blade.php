<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes demandes de dimensionnement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">{{ __('Liste des demandes') }}</h3>
                        <a href="{{ route('dimensionnements.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            {{ __('Nouvelle demande') }}
                        </a>
                    </div>

                    @if($dimensionnements->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Type') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Localisation') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Statut') }}
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($dimensionnements as $dimensionnement)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $dimensionnement->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ ucfirst($dimensionnement->type_installation) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $dimensionnement->ville }}, {{ $dimensionnement->pays }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $dimensionnement->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($dimensionnement->statut === 'validé' ? 'bg-green-100 text-green-800' : 
                                                       ($dimensionnement->statut === 'refusé' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $dimensionnement->statut)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <div class="flex space-x-3">
                                                    <a href="{{ route('dimensionnements.show', $dimensionnement) }}" class="text-blue-600 hover:text-blue-900">
                                                        {{ __('Voir') }}
                                                    </a>
                                                    @if($dimensionnement->statut === 'en_attente')
                                                        <a href="{{ route('dimensionnements.edit', $dimensionnement) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            {{ __('Modifier') }}
                                                        </a>
                                                        <form action="{{ route('dimensionnements.destroy', $dimensionnement) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette demande ?') }}')">
                                                                {{ __('Supprimer') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $dimensionnements->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">{{ __("Vous n'avez pas encore fait de demande de dimensionnement.") }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>