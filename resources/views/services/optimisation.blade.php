@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h1>
            <p class="text-xl text-gray-600">{{ $description }}</p>
        </div>

        <!-- Caractéristiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-orange-500 mb-4">
                    <i class="fas fa-chart-line text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Analyse de Performance</h3>
                <p class="text-gray-600">Analyse détaillée des performances de vos installations pour identifier les opportunités d'optimisation.</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-orange-500 mb-4">
                    <i class="fas fa-solar-panel text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Optimisation en Temps Réel</h3>
                <p class="text-gray-600">Ajustements automatiques pour maximiser la production d'énergie en fonction des conditions.</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-orange-500 mb-4">
                    <i class="fas fa-file-alt text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Rapports Détaillés</h3>
                <p class="text-gray-600">Rapports mensuels sur les performances et recommandations d'optimisation.</p>
            </div>
        </div>

        <!-- Section CTA -->
        <div class="bg-blue-600 text-white rounded-xl p-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Optimisez votre Installation Dès Maintenant</h2>
            <p class="text-xl mb-6">Découvrez comment nous pouvons améliorer le rendement de votre installation solaire.</p>
            <a href="{{ route('contact') }}" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-300">
                Contactez-nous
            </a>
        </div>
    </div>
</div>
@endsection