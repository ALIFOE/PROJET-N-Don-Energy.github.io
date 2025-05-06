<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la demande de dimensionnement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold">{{ __('Demande #') }}{{ $dimensionnement->id }}</h3>
                            <p class="text-sm text-gray-500">{{ __('Soumise le ') }} {{ $dimensionnement->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                {{ $dimensionnement->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($dimensionnement->statut === 'validé' ? 'bg-green-100 text-green-800' : 
                                   ($dimensionnement->statut === 'refusé' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $dimensionnement->statut)) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border-b border-gray-200 pb-6 md:border-b-0 md:border-r md:pr-6">
                            <h4 class="text-lg font-medium mb-4">{{ __('Informations personnelles') }}</h4>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Nom') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->nom }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Téléphone') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->telephone }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Adresse') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->adresse }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Ville') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->ville }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Code postal') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->code_postal }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Pays') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->pays }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-lg font-medium mb-4">{{ __('Informations techniques') }}</h4>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Type de logement') }}</dt>
                                    <dd class="mt-1">{{ ucfirst($dimensionnement->type_logement) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Surface disponible') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->surface_toiture }} m²</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Orientation') }}</dt>
                                    <dd class="mt-1">{{ ucfirst($dimensionnement->orientation) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Type d\'installation') }}</dt>
                                    <dd class="mt-1">{{ ucfirst($dimensionnement->type_installation) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Équipements') }}</dt>
                                    <dd class="mt-1">
                                        @if($dimensionnement->equipements)
                                            <ul class="list-disc list-inside">
                                                @foreach($dimensionnement->equipements as $equipement)
                                                    <li>{{ __($equipement) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ __('Aucun équipement spécifié') }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Objectifs') }}</dt>
                                    <dd class="mt-1">
                                        @if($dimensionnement->objectifs)
                                            <ul class="list-disc list-inside">
                                                @foreach($dimensionnement->objectifs as $objectif)
                                                    <li>{{ __($objectif) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ __('Aucun objectif spécifié') }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Facture annuelle') }}</dt>
                                    <dd class="mt-1">{{ number_format($dimensionnement->facture_annuelle, 2, ',', ' ') }} €</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Fournisseur d\'électricité') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->fournisseur }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Nombre de personnes') }}</dt>
                                    <dd class="mt-1">{{ $dimensionnement->nb_personnes }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Budget') }}</dt>
                                    <dd class="mt-1">{{ number_format($dimensionnement->budget, 2, ',', ' ') }} €</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($dimensionnement->statut === 'en_attente')
                        <div class="mt-6 flex justify-end space-x-4">
                            <form action="{{ route('dimensionnements.destroy', $dimensionnement) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit" onclick="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cette demande ?') }}')">
                                    {{ __('Supprimer') }}
                                </x-danger-button>
                            </form>
                            <x-primary-button onclick="window.location.href='{{ route('dimensionnements.edit', $dimensionnement) }}'">
                                {{ __('Modifier') }}
                            </x-primary-button>
                        </div>
                    @endif

                    <div class="mt-6">
                        <x-secondary-button onclick="window.location.href='{{ route('dimensionnements.index') }}'">
                            {{ __('Retour à la liste') }}
                        </x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>