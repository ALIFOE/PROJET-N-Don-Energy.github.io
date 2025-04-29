<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes activités') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">{{ __("Historique des activités") }}</h1>
                        <div class="flex gap-4">
                            <!-- Filtre par type d'action -->
                            <select name="action" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                    onchange="window.location.href=`{{ route('activites.index') }}?action=${this.value}&date={{ request('date') }}`">
                                <option value="">{{ __("Toutes les actions") }}</option>
                                <option value="création" {{ request('action') === 'création' ? 'selected' : '' }}>{{ __("Créations") }}</option>
                                <option value="modification" {{ request('action') === 'modification' ? 'selected' : '' }}>{{ __("Modifications") }}</option>
                                <option value="suppression" {{ request('action') === 'suppression' ? 'selected' : '' }}>{{ __("Suppressions") }}</option>
                            </select>

                            <!-- Filtre par date -->
                            <select name="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                    onchange="window.location.href=`{{ route('activites.index') }}?date=${this.value}&action={{ request('action') }}`">
                                <option value="">{{ __("Toutes les dates") }}</option>
                                <option value="aujourd'hui" {{ request('date') === 'aujourd\'hui' ? 'selected' : '' }}>{{ __("Aujourd'hui") }}</option>
                                <option value="semaine" {{ request('date') === 'semaine' ? 'selected' : '' }}>{{ __("Cette semaine") }}</option>
                                <option value="mois" {{ request('date') === 'mois' ? 'selected' : '' }}>{{ __("Ce mois") }}</option>
                            </select>

                            <!-- Bouton de réinitialisation des filtres -->
                            <a href="{{ route('activites.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                {{ __("Réinitialiser") }}
                            </a>

                            <!-- Bouton d'export PDF -->
                            <a href="{{ route('activites.export-pdf', ['action' => request('action'), 'date' => request('date')]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                </svg>
                                {{ __("Exporter en PDF") }}
                            </a>

                            <!-- Bouton de suppression des historiques -->
                            <form action="{{ route('activites.reset') }}" method="POST" onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer tous les historiques ?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                    {{ __("Supprimer tout") }}
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($activites->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Date") }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Action") }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Description") }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($activites as $activite)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $activite->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @switch($activite->action)
                                                        @case('création')
                                                            bg-green-100 text-green-800
                                                            @break
                                                        @case('modification')
                                                            bg-blue-100 text-blue-800
                                                            @break
                                                        @case('suppression')
                                                            bg-red-100 text-red-800
                                                            @break
                                                        @default
                                                            bg-gray-100 text-gray-800
                                                    @endswitch">
                                                    {{ ucfirst($activite->action) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $activite->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $activites->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">{{ __("Aucune activité à afficher.") }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>