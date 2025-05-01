<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détails de l'inscription
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de l'inscription</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <dl>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500">Formation</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $inscription->formation->nom }}</dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $inscription->nom }}</dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $inscription->email }}</dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $inscription->telephone }}</dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @switch($inscription->statut)
                                            @case('en_attente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                                @break
                                            @case('acceptee')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Acceptée
                                                </span>
                                                @break
                                            @case('refusee')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Refusée
                                                </span>
                                                @break
                                        @endswitch
                                    </dd>
                                </div>
                            </dl>

                            <dl>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500">Acte de naissance</dt>
                                    <dd class="mt-1">
                                        <a href="{{ Storage::url($inscription->acte_naissance_path) }}" target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-900">Voir le document</a>
                                    </dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500">CNI</dt>
                                    <dd class="mt-1">
                                        <a href="{{ Storage::url($inscription->cni_path) }}" target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-900">Voir le document</a>
                                    </dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500">Diplôme</dt>
                                    <dd class="mt-1">
                                        <a href="{{ Storage::url($inscription->diplome_path) }}" target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-900">Voir le document</a>
                                    </dd>
                                </div>
                                @if($inscription->autres_documents_paths)
                                    <div class="mb-4">
                                        <dt class="text-sm font-medium text-gray-500">Autres documents</dt>
                                        <dd class="mt-1">
                                            @foreach($inscription->autres_documents_paths as $path)
                                                <a href="{{ Storage::url($path) }}" target="_blank" 
                                                   class="block text-indigo-600 hover:text-indigo-900 mb-1">
                                                    Document supplémentaire {{ $loop->iteration }}
                                                </a>
                                            @endforeach
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Modifier le statut</h3>
                        <form action="{{ route('admin.formations.inscriptions.update-status', $inscription) }}" method="POST" class="max-w-sm">
                            @csrf
                            @method('PUT')
                            <div class="flex items-center gap-4">
                                <select name="statut" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="en_attente" {{ $inscription->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="acceptee" {{ $inscription->statut === 'acceptee' ? 'selected' : '' }}>Acceptée</option>
                                    <option value="refusee" {{ $inscription->statut === 'refusee' ? 'selected' : '' }}>Refusée</option>
                                </select>
                                <x-primary-button>
                                    Mettre à jour
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.formations.inscriptions.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    ← Retour à la liste des inscriptions
                </a>
            </div>
        </div>
    </div>
</x-app-layout>