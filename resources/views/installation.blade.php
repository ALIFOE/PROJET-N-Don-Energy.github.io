<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- En-tête de la page -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Nos Services d'Installation</h1>
                <p class="text-xl text-gray-600">Solutions photovoltaïques sur mesure pour particuliers et professionnels</p>
            </div>

            <!-- Types d'installations -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Installation résidentielle -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/residentiel.jpg') }}" alt="Installation résidentielle" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Installation Résidentielle</h3>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Systèmes adaptés aux maisons individuelles</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Optimisation de l'autoconsommation</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Installation sur toiture ou au sol</span>
                            </li>
                        </ul>
                        <a href="{{ route('devis.create') }}" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                            Demander un devis
                        </a>
                    </div>
                </div>

                <!-- Installation commerciale -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/commercial.jpg') }}" alt="Installation commerciale" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Installation Commerciale</h3>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Solutions pour entreprises et commerces</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Systèmes de grande puissance</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Rentabilité optimisée</span>
                            </li>
                        </ul>
                        <a href="{{ route('devis.create') }}" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                            Demander un devis
                        </a>
                    </div>
                </div>

                <!-- Installation agricole -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/agricole.jpg') }}" alt="Installation agricole" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Installation Agricole</h3>
                        <ul class="space-y-3 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Installations sur hangars agricoles</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Systèmes d'irrigation solaire</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                                <span class="text-gray-600">Solutions agrivoltaïques</span>
                            </li>
                        </ul>
                        <a href="{{ route('devis.create') }}" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">
                            Demander un devis
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notre processus -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-16">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-8">Notre Processus d'Installation</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">1</div>
                        <h3 class="text-lg font-semibold mb-2">Étude Technique</h3>
                        <p class="text-gray-600">Analyse de votre site et de vos besoins énergétiques</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">2</div>
                        <h3 class="text-lg font-semibold mb-2">Proposition</h3>
                        <p class="text-gray-600">Devis détaillé et plan de financement personnalisé</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">3</div>
                        <h3 class="text-lg font-semibold mb-2">Installation</h3>
                        <p class="text-gray-600">Mise en place par nos équipes certifiées</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-4">4</div>
                        <h3 class="text-lg font-semibold mb-2">Suivi</h3>
                        <p class="text-gray-600">Maintenance et optimisation continues</p>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="bg-blue-600 rounded-lg shadow-lg p-8 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">Prêt à passer au solaire ?</h2>
                <p class="text-xl mb-6">Nos experts sont là pour vous accompagner dans votre projet photovoltaïque</p>
                <a href="{{ route('contact') }}" class="inline-block bg-white text-blue-600 py-3 px-8 rounded-md text-lg font-medium hover:bg-gray-100 transition duration-300">
                    Contactez-nous
                </a>
            </div>
        </div>
    </div>
</x-app-layout>