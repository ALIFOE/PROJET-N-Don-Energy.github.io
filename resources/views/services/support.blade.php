@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h1>
            <p class="text-xl text-gray-600">{{ $description }}</p>
        </div>

        <!-- Types de support -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-orange-500 mb-4">
                    <i class="fas fa-headset text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Support Téléphonique</h3>
                <p class="text-gray-600">Assistance téléphonique disponible 24/7 pour tous vos besoins urgents.</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-orange-500 mb-4">
                    <i class="fas fa-comments text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Chat en Direct</h3>
                <p class="text-gray-600">Support instantané via notre plateforme de chat en ligne.</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-orange-500 mb-4">
                    <i class="fas fa-tools text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Support Technique</h3>
                <p class="text-gray-600">Assistance technique spécialisée pour les problèmes complexes.</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 transform hover:-translate-y-1 transition-transform duration-300">
                <div class="text-orange-500 mb-4">
                    <i class="fas fa-book text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Base de Connaissances</h3>
                <p class="text-gray-600">Accès à notre documentation détaillée et guides de dépannage.</p>
            </div>
        </div>

        <!-- Information de contact -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold mb-4">Heures de Support</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between">
                        <span class="text-gray-600">Lundi - Vendredi</span>
                        <span class="font-semibold">8h - 18h</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Samedi</span>
                        <span class="font-semibold">9h - 15h</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Dimanche</span>
                        <span class="font-semibold">Fermé</span>
                    </li>
                </ul>
                <p class="mt-4 text-sm text-gray-500">* Support d'urgence disponible 24/7</p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold mb-4">Contactez-nous</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-phone text-orange-500 mr-3"></i>
                        <span>+228 97 73 43 81</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-orange-500 mr-3"></i>
                        <span>support@crefer.com</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-orange-500 mr-3"></i>
                        <span>Rue KOPEGA 56.GB Lomé, Togo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section FAQ -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold mb-8 text-center">Questions Fréquentes</h2>
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Comment puis-je accéder au support technique ?</h3>
                    <p class="text-gray-600">Vous pouvez nous contacter par téléphone, email ou via notre plateforme de chat en ligne. Notre équipe est disponible pour vous aider.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Quel est le délai de réponse moyen ?</h3>
                    <p class="text-gray-600">Nous nous efforçons de répondre à toutes les demandes dans un délai de 2 heures pendant les heures d'ouverture.</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2">Le support est-il inclus dans mon contrat ?</h3>
                    <p class="text-gray-600">Oui, le support technique de base est inclus dans tous nos contrats de maintenance.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection