<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- En-tête de la page -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Nos Formations</h1>
                <p class="text-xl text-gray-600">Développez vos compétences dans le domaine de l'énergie solaire</p>
            </div>

            <!-- Grille des formations -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Formation 1 -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="relative">
                        <img src="{{ asset('images/image-3.jpg') }}" alt="Installation de panneaux solaires" class="w-full h-48 object-cover">
                        <div class="absolute top-0 right-0 bg-blue-600 text-white px-4 py-2 rounded-bl-lg">
                            3 jours
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Installation de Panneaux Solaires</h3>
                        <p class="text-gray-600 mb-4">Apprenez les techniques d'installation professionnelle de panneaux solaires photovoltaïques.</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Normes et sécurité
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Techniques de montage
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Raccordement électrique
                            </li>
                        </ul>
                        <a href="{{ route('inscription') }}" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                            S'inscrire
                        </a>
                    </div>
                </div>

                <!-- Formation 2 -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="relative">
                        <img src="{{ asset('images/29990.jpg') }}" alt="Maintenance des installations" class="w-full h-48 object-cover">
                        <div class="absolute top-0 right-0 bg-blue-600 text-white px-4 py-2 rounded-bl-lg">
                            2 jours
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Maintenance et Dépannage</h3>
                        <p class="text-gray-600 mb-4">Maîtrisez l'entretien et le dépannage des installations photovoltaïques.</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Diagnostic des pannes
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Maintenance préventive
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Optimisation des performances
                            </li>
                        </ul>
                        <a href="{{ route('inscription') }}" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                            S'inscrire
                        </a>
                    </div>
                </div>

                <!-- Formation 3 -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="relative">
                        <img src="{{ asset('images/4783.jpg') }}" alt="Conception de projets" class="w-full h-48 object-cover">
                        <div class="absolute top-0 right-0 bg-blue-600 text-white px-4 py-2 rounded-bl-lg">
                            4 jours
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Conception de Projets Solaires</h3>
                        <p class="text-gray-600 mb-4">Développez des compétences en conception et dimensionnement d'installations.</p>
                        <ul class="space-y-2 mb-6">
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Étude de faisabilité
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Dimensionnement
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Analyse financière
                            </li>
                        </ul>
                        <a href="{{ route('inscription') }}" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                            S'inscrire
                        </a>
                    </div>
                </div>
            </div>

            <!-- Section des avantages -->
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">Pourquoi choisir nos formations ?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <i class="fas fa-certificate text-4xl text-blue-600 mb-4"></i>
                        <h3 class="text-lg font-semibold mb-2">Certification Professionnelle</h3>
                        <p class="text-gray-600">Formations certifiantes reconnues dans le secteur</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <i class="fas fa-users text-4xl text-blue-600 mb-4"></i>
                        <h3 class="text-lg font-semibold mb-2">Formateurs Experts</h3>
                        <p class="text-gray-600">Encadrement par des professionnels expérimentés</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <i class="fas fa-tools text-4xl text-blue-600 mb-4"></i>
                        <h3 class="text-lg font-semibold mb-2">Pratique Intensive</h3>
                        <p class="text-gray-600">80% du temps dédié à la pratique</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <i class="fas fa-graduation-cap text-4xl text-blue-600 mb-4"></i>
                        <h3 class="text-lg font-semibold mb-2">Suivi Post-Formation</h3>
                        <p class="text-gray-600">Accompagnement personnalisé après la formation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
