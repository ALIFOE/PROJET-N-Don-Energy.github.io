@extends('layouts.admin')

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
                    <h3 class="text-lg font-semibold text-gray-700">Détails Installation</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Type de Bâtiment</p>
                            <p class="font-medium">{{ $devis->type_batiment }}</p>
                        </div>
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
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Objectifs</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <ul class="list-disc list-inside space-y-2">
                        @foreach($devis->objectifs as $objectif)
                            <li class="text-gray-700">{{ $objectif }}</li>
                        @endforeach
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
            </div>

            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('admin.devis.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Retour
                </a>
                <div class="flex space-x-4">
                    <a href="{{ route('devis.download-pdf', $devis) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-download mr-2"></i> Télécharger PDF
                    </a>
                    <form action="{{ route('admin.devis.destroy', $devis) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?')">
                            <i class="fas fa-trash mr-2"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection