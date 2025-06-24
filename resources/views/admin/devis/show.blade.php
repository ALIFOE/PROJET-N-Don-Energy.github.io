@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.devis.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Détails du Devis</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">Informations Client</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nom</p>
                            <p class="font-medium">{{ $devis->nom }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Prénom</p>
                            <p class="font-medium">{{ $devis->prenom }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium">{{ $devis->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Téléphone</p>
                            <p class="font-medium">{{ $devis->telephone }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600">Adresse</p>
                            <p class="font-medium">{{ $devis->adresse }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">Détails Installation</h3>                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Statut</p>
                            <div class="flex items-center space-x-4">
                                <span id="status-badge" class="px-2 py-1 text-sm rounded-full bg-{{ $devis->status_color }}-100 text-{{ $devis->status_color }}-800">
                                    {{ $devis->status_label }}
                                </span>
                                <form id="status-form" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" id="status" class="form-select rounded-md shadow-sm mt-1 block w-full" 
                                            onchange="updateStatus(this)">
                                        <option value="en_attente" {{ $devis->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="en_cours" {{ $devis->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="accepte" {{ $devis->statut === 'accepte' ? 'selected' : '' }}>Accepté</option>
                                        <option value="refuse" {{ $devis->statut === 'refuse' ? 'selected' : '' }}>Refusé</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Type de Bâtiment</p>
                            <p class="font-medium">{{ $devis->type_batiment }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Type de Toiture</p>
                            <p class="font-medium">{{ $devis->type_toiture ?? 'Non spécifié' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Orientation</p>
                            <p class="font-medium">{{ $devis->orientation ?? 'Non spécifiée' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Facture Mensuelle</p>
                            <p class="font-medium">{{ number_format($devis->facture_mensuelle, 0, ',', ' ') }} FCFA</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Consommation Annuelle</p>
                            <p class="font-medium">{{ number_format($devis->consommation_annuelle, 2, ',', ' ') }} kWh</p>
                        </div>
                    </div>

                    @if($devis->objectifs)
                    <div class="mt-6">
                        <h4 class="text-md font-semibold text-gray-700 mb-2">Objectifs</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($devis->objectifs as $objectif)
                            <li class="text-gray-600">{{ $objectif }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                        <div>
                            <p class="text-sm text-gray-600">Type de Toiture</p>
                            <p class="font-medium">{{ $devis->type_toiture }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Orientation</p>
                            <p class="font-medium">{{ $devis->orientation }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Consommation Annuelle</p>
                            <p class="font-medium">{{ $devis->consommation_annuelle }} kWh</p>
                        </div>
                        @if($devis->facture_mensuelle)
                        <div>
                            <p class="text-sm text-gray-600">Facture Mensuelle</p>
                            <p class="font-medium">{{ $devis->facture_mensuelle }} €</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Objectifs</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-2">
                        @if(isset($devis->objectifs) && is_array($devis->objectifs))
                            @foreach($devis->objectifs as $objectif)
                                <li class="text-gray-700">{{ $objectif }}</li>
                            @endforeach
                        @else
                            <li class="text-gray-500">Aucun objectif spécifié</li>
                        @endif
                    </ul>
                </div>
            </div>

            @if($devis->message)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Message</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-line">{{ $devis->message }}</p>
                </div>
            </div>
            @endif

            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Résultats de l'Analyse Technique</h3>
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    @if(isset($devis->analyse_technique['status']))
                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <span class="text-lg font-semibold mr-3">Statut :</span>
                                <span class="px-4 py-1 rounded-full text-white {{ $devis->analyse_technique['status'] === 'non_faisable' ? 'bg-red-500' : 'bg-green-500' }}">
                                    {{ $devis->analyse_technique['status'] === 'non_faisable' ? 'Non Faisable' : 'Faisable' }}
                                </span>
                            </div>
                            @if(isset($devis->analyse_technique['message']))
                                <p class="text-gray-700">{{ $devis->analyse_technique['message'] }}</p>
                            @endif
                        </div>
                    @endif

                    @if(isset($devis->analyse_technique['dimensionnement']))
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-blue-600 mb-3">Dimensionnement Recommandé</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-lg">
                                @foreach($devis->analyse_technique['dimensionnement'] as $key => $value)
                                    <div class="border-b border-gray-200 pb-2">
                                        <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }} :</span>
                                        <span class="font-medium ml-2">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(isset($devis->analyse_technique['analyse_financiere']))
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-green-600 mb-3">Analyse Financière</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-lg">
                                @foreach($devis->analyse_technique['analyse_financiere'] as $key => $value)
                                    <div class="border-b border-gray-200 pb-2">
                                        <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }} :</span>
                                        <span class="font-medium ml-2">
                                            @if(strpos($key, 'cout') !== false || strpos($key, 'economie') !== false)
                                                {{ number_format($value, 2, ',', ' ') }} €
                                            @else
                                                {{ is_array($value) ? implode(', ', $value) : $value }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(isset($devis->analyse_technique['recommendations']))
                        <div>
                            <h4 class="text-lg font-semibold text-purple-600 mb-3">Recommandations</h4>
                            <div class="bg-white p-4 rounded-lg">
                                <ul class="list-disc list-inside space-y-2">
                                    @foreach($devis->analyse_technique['recommendations'] as $recommendation)
                                        <li class="text-gray-700">{{ $recommendation }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>            <div class="mt-8">
                <div class="flex justify-between items-center mb-6">
                    <div>                        <a href="{{ route('admin.devis.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-4">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                        <span class="text-gray-600">
                            <i class="far fa-clock mr-1"></i> Créé le: {{ $devis->created_at ? $devis->created_at->format('d/m/Y à H:i') : 'Date inconnue' }}
                        </span>
                    </div>                    <div class="flex space-x-4">
                        <a href="{{ url('/admin/devis/download-pdf/'.$devis->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            <i class="fas fa-download mr-2"></i> Télécharger PDF
                        </a>
                        
                        <a href="#" onclick="window.print()" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
                            <i class="fas fa-print mr-2"></i> Imprimer                        </a>                        <a href="mailto:{{ $devis->email }}?subject=Suivi de votre devis N°{{ $devis->id }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            <i class="fas fa-envelope mr-2"></i> Contacter le client
                        </a>

                        <form action="{{ url('/admin/devis/'.$devis->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?')">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                @if(isset($devis->analyse_technique['status']) && $devis->analyse_technique['status'] !== 'non_faisable')
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h4 class="text-lg font-semibold text-blue-700 mb-3">Actions recommandées</h4>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="contact_client" class="form-checkbox h-5 w-5 text-blue-600">
                            <label for="contact_client" class="ml-2 text-gray-700">Contacter le client pour un rendez-vous</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="validation_technique" class="form-checkbox h-5 w-5 text-blue-600">
                            <label for="validation_technique" class="ml-2 text-gray-700">Validation technique sur site</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="preparation_contrat" class="form-checkbox h-5 w-5 text-blue-600">
                            <label for="preparation_contrat" class="ml-2 text-gray-700">Préparation du contrat</label>
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($devis->analyse_technique['status']) && $devis->analyse_technique['status'] === 'non_faisable')
                <div class="bg-red-50 p-4 rounded-lg mb-6">
                    <h4 class="text-lg font-semibold text-red-700 mb-3">Motifs de non-faisabilité</h4>
                    <ul class="list-disc list-inside text-red-600">
                        @if(isset($devis->analyse_technique['raisons_non_faisable']))
                            @foreach($devis->analyse_technique['raisons_non_faisable'] as $raison)
                                <li>{{ $raison }}</li>
                            @endforeach
                        @endif
                    </ul>
                    <div class="mt-4">
                        <a href="#" class="text-red-600 hover:text-red-800 font-medium">
                            <i class="fas fa-envelope mr-2"></i> Envoyer un email d'explication au client
                        </a>
                    </div>
                </div>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus(select) {
    fetch("/admin/devis/{{ $devis->id }}/status", {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
        },
        body: JSON.stringify({
            status: select.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById('status-badge');
            badge.className = `px-2 py-1 text-sm rounded-full bg-${data.status_color}-100 text-${data.status_color}-800`;
            badge.textContent = data.status_label;
            // Afficher un message de succès
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg';
            toast.textContent = data.message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        // Afficher un message d'erreur
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg';
        toast.textContent = "Une erreur s'est produite lors de la mise à jour du statut.";
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    });
}
</script>
@endpush