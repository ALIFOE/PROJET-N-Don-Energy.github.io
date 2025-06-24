<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-6">Résultats de l'analyse de votre projet solaire</h1>

                    @php
                        $analyseData = is_string($analyse) ? json_decode($analyse, true) : $analyse;
                    @endphp

                    @if($analyseData && isset($analyseData['status']) && $analyseData['status'] === 'success')
                        <!-- Faisabilité technique -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold mb-4">Faisabilité technique</h2>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-green-800">Score de faisabilité : {{ number_format($analyseData['faisabilite']['score_faisabilite'] * 100, 0) }}%</p>
                                <p class="mt-2">{{ $analyseData['faisabilite']['commentaires'] }}</p>
                            </div>
                        </div>

                        <!-- Dimensionnement -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold mb-4">Dimensionnement recommandé</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h3 class="font-medium text-blue-900">Caractéristiques des panneaux</h3>
                                    <ul class="mt-2 space-y-2 text-blue-800">
                                        <li>Type : {{ $analyseData['dimensionnement']['type_panneau'] }}</li>
                                        <li>Fabricant : {{ $analyseData['dimensionnement']['fabricant'] }}</li>
                                        <li>Modèle : {{ $analyseData['dimensionnement']['modele'] }}</li>
                                        <li>Capacité : {{ $analyseData['dimensionnement']['capacite_panneau'] }} Wc</li>
                                        <li>Rendement : {{ number_format($analyseData['dimensionnement']['rendement_panneau'] * 100, 1) }}%</li>
                                        <li>Garantie : {{ $analyseData['dimensionnement']['garantie_annees'] }} ans</li>
                                    </ul>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h3 class="font-medium text-blue-900">Installation</h3>
                                    <ul class="mt-2 space-y-2 text-blue-800">
                                        <li>Puissance totale : {{ $analyseData['dimensionnement']['puissance_kwc'] }} kWc</li>
                                        <li>Nombre de panneaux : {{ $analyseData['dimensionnement']['nombre_panneaux'] }}</li>
                                        <li>Nombre de batteries recommandé : {{ $analyseData['dimensionnement']['nombre_batteries'] }}</li>
                                        <li>Surface nécessaire : {{ $analyseData['dimensionnement']['surface_necessaire'] }} m²</li>
                                        <li>Production estimée : {{ number_format($analyseData['dimensionnement']['production_estimee'], 0) }} kWh/an</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Analyse financière -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold mb-4">Analyse financière</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <h3 class="font-medium text-yellow-900">Investissement</h3>
                                    <ul class="mt-2 space-y-2 text-yellow-800">
                                        <li>Coût estimé : {{ number_format($analyseData['analyse_financiere']['cout_installation'] * 655, 0, ',', ' ') }} FCFA</li>
                                        <li>Économies annuelles : {{ number_format($analyseData['analyse_financiere']['economies_annuelles'] * 655, 0, ',', ' ') }} FCFA</li>
                                        <li>Retour sur investissement : {{ $analyseData['analyse_financiere']['retour_investissement_annees'] }} ans</li>
                                    </ul>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <h3 class="font-medium text-yellow-900">Rentabilité à 20 ans</h3>
                                    <p class="mt-2 text-yellow-800">{{ number_format($analyseData['analyse_financiere']['rentabilite_20_ans'] * 655, 0, ',', ' ') }} FCFA</p>
                                </div>
                            </div>
                        </div>

                        <!-- Recommandations -->
                        @if(!empty($analyseData['recommandations']))
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold mb-4">Recommandations</h2>
                                <div class="bg-indigo-50 p-4 rounded-lg">
                                    <ul class="list-disc list-inside space-y-2 text-indigo-800">
                                        @foreach($analyseData['recommandations'] as $recommandation)
                                            <li>{{ $recommandation }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="mt-8">
                            <p class="text-gray-600 text-sm">
                                * Ces résultats sont basés sur des estimations et peuvent varier en fonction des conditions réelles d'installation et d'utilisation.
                            </p>
                        </div>

                        <div class="mt-6 flex gap-4">
                            <a href="{{ route('devis.create') }}" class="inline-block bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                                Nouvelle simulation
                            </a>
                            <a href="{{ url('/contact') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                                Contacter un expert
                            </a>
                            <a href="{{ route('devis.download-pdf', $devis) }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition duration-200">
                                <i class="fas fa-download mr-2"></i>Télécharger en PDF
                            </a>
                        </div>
                    @else
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-red-800">{{ $analyse['message'] }}</p>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('devis.create') }}" class="inline-block bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                                Retour au formulaire
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>