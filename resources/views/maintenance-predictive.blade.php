@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- En-tête de la page -->
            <div class="bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6 border border-gray-700">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-semibold text-orange-400">Maintenance Prédictive</h1>
                            <p class="text-gray-300 mt-2">Planifiez et suivez vos maintenances d'installations</p>
                        </div>
                        <button
                            type="button"
                            id="planifierMaintenance"
                            class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-300">
                            <i class="fas fa-plus mr-2"></i>
                            Planifier une maintenance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal de planification -->
            <x-modal name="maintenance-modal" focusable>
                <form id="maintenanceForm" method="POST" action="{{ route('maintenance.store') }}" class="p-6 bg-gray-800">
                    @csrf
                    <h2 class="text-2xl font-semibold text-orange-400 mb-6">
                        Planifier une maintenance
                    </h2>

                    <!-- Installation -->
                    <div class="mb-6">
                        <x-input-label for="installation" value="Installation" class="text-gray-300 font-medium" />
                        <select name="installation_id" id="installation" required
                            class="mt-2 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                            <option value="">Sélectionnez une installation</option>
                            @foreach($installations as $installation)
                                <option value="{{ $installation->id }}">{{ $installation->nom }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('installation_id')" class="mt-2" />
                    </div>

                    <!-- Type de maintenance -->
                    <div class="mb-6">
                        <x-input-label for="type" value="Type de maintenance" class="text-gray-300 font-medium" />
                        <select name="type" id="type" required
                            class="mt-2 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                            <option value="preventive">Préventive</option>
                            <option value="corrective">Corrective</option>
                            <option value="predictive">Prédictive</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- Date prévue -->
                    <div class="mb-6">
                        <x-input-label for="date" value="Date prévue" class="text-gray-300 font-medium" />
                        <x-text-input
                            type="date"
                            name="date_prevue"
                            id="date"
                            required
                            class="mt-2 block w-full bg-gray-700 border-gray-600 text-gray-300 focus:border-orange-500" />
                        <x-input-error :messages="$errors->get('date_prevue')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <x-input-label for="description" value="Description" class="text-gray-300 font-medium" />
                        <textarea
                            name="description"
                            id="description"
                            rows="4"
                            required
                            class="mt-2 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                        </textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" x-on:click="$dispatch('close')"
                            class="inline-flex items-center px-4 py-2 bg-gray-700 border border-gray-600 rounded-md font-semibold text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-300">
                            Planifier
                        </button>
                    </div>
                </form>
            </x-modal>

            <!-- Liste des maintenances -->
            <div class="bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg border border-gray-700">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Installation</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date prévue</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @forelse($maintenances as $maintenance)
                                    <tr class="hover:bg-gray-700 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $maintenance->installation->nom }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 capitalize">{{ $maintenance->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ isset($maintenance->date_prevue) ? $maintenance->date_prevue->format('d/m/Y') : 'Non définie' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($maintenance->statut === 'planifiée') bg-yellow-900 text-yellow-200
                                                @elseif($maintenance->statut === 'en_cours') bg-blue-900 text-blue-200
                                                @elseif($maintenance->statut === 'terminée') bg-green-900 text-green-200
                                                @else bg-gray-700 text-gray-200
                                                @endif">
                                                {{ ucfirst($maintenance->statut) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('maintenance.edit', $maintenance->id) }}" 
                                                class="text-orange-400 hover:text-orange-300 transition duration-150">Modifier</a>
                                            <form action="{{ route('maintenance.destroy', $maintenance->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-blue-400 hover:text-blue-300 transition duration-150 ml-2"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette maintenance ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                            Aucune maintenance planifiée
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const planifierBtn = document.getElementById('planifierMaintenance');
            
            planifierBtn.addEventListener('click', () => {
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'maintenance-modal'
                }));
            });
        });
    </script>
@endpush