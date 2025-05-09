<x-app-layout>
    <div class="py-12 bg-gradient-to-b from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- En-tête de la page -->
            <div class="text-center mb-12 bg-white p-8 rounded-lg shadow-sm">
                <h1 class="text-4xl font-bold text-blue-900 mb-4">Nos Services d'Installation</h1>
                <p class="text-xl text-gray-700">Solutions photovoltaïques sur mesure pour particuliers et professionnels</p>
            </div>

            <!-- Types d'installations -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Installation résidentielle -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="relative">
                        <img src="{{ asset('images/residentiel.jpg') }}" alt="Installation résidentielle" class="w-full h-56 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <h3 class="absolute bottom-4 left-6 text-2xl font-bold text-white">Installation Résidentielle</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Systèmes adaptés aux maisons individuelles</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Optimisation de l'autoconsommation</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Installation sur toiture ou au sol</span>
                            </li>
                        </ul>
                        <a href="{{ route('devis.create') }}" class="block text-center bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transform hover:-translate-y-1 transition duration-300 shadow-md">
                            Demander un devis gratuit
                        </a>
                    </div>
                </div>

                <!-- Installation commerciale -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="relative">
                        <img src="{{ asset('images/commercial.jpg') }}" alt="Installation commerciale" class="w-full h-56 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <h3 class="absolute bottom-4 left-6 text-2xl font-bold text-white">Installation Commerciale</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Solutions pour entreprises et commerces</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Systèmes de grande puissance</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Rentabilité optimisée</span>
                            </li>
                        </ul>
                        <a href="{{ route('devis.create') }}" class="block text-center bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transform hover:-translate-y-1 transition duration-300 shadow-md">
                            Demander un devis gratuit
                        </a>
                    </div>
                </div>

                <!-- Installation agricole -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="relative">
                        <img src="{{ asset('images/agricole.jpg') }}" alt="Installation agricole" class="w-full h-56 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <h3 class="absolute bottom-4 left-6 text-2xl font-bold text-white">Installation Agricole</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4 mb-6">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Installations sur hangars agricoles</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Systèmes d'irrigation solaire</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mt-1 mr-3 text-lg"></i>
                                <span class="text-gray-700">Solutions agrivoltaïques</span>
                            </li>
                        </ul>
                        <a href="{{ route('devis.create') }}" class="block text-center bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transform hover:-translate-y-1 transition duration-300 shadow-md">
                            Demander un devis gratuit
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notre processus -->
            <div class="bg-white rounded-xl shadow-xl p-12 mb-16">
                <h2 class="text-3xl font-bold text-blue-900 text-center mb-12">Notre Processus d'Installation</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center group">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-6 group-hover:bg-blue-700 transform group-hover:scale-110 transition-all duration-300 shadow-lg">1</div>
                        <h3 class="text-xl font-semibold mb-3 text-blue-900">Étude Technique</h3>
                        <p class="text-gray-700">Analyse approfondie de votre site et de vos besoins énergétiques</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-6 group-hover:bg-blue-700 transform group-hover:scale-110 transition-all duration-300 shadow-lg">2</div>
                        <h3 class="text-xl font-semibold mb-3 text-blue-900">Proposition</h3>
                        <p class="text-gray-700">Devis détaillé et plan de financement personnalisé</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-6 group-hover:bg-blue-700 transform group-hover:scale-110 transition-all duration-300 shadow-lg">3</div>
                        <h3 class="text-xl font-semibold mb-3 text-blue-900">Installation</h3>
                        <p class="text-gray-700">Mise en place par nos équipes certifiées</p>
                    </div>
                    <div class="text-center group">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl mx-auto mb-6 group-hover:bg-blue-700 transform group-hover:scale-110 transition-all duration-300 shadow-lg">4</div>
                        <h3 class="text-xl font-semibold mb-3 text-blue-900">Suivi</h3>
                        <p class="text-gray-700">Maintenance et optimisation continues</p>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-xl p-12 text-center text-white">
                <h2 class="text-4xl font-bold mb-6">Prêt à passer au solaire ?</h2>
                <p class="text-xl mb-8 opacity-90">Nos experts sont là pour vous accompagner dans votre projet photovoltaïque</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('devis.create') }}" class="inline-block bg-white text-blue-600 py-3 px-8 rounded-lg text-lg font-semibold hover:bg-gray-50 transform hover:-translate-y-1 transition duration-300 shadow-md">
                        Demander un devis
                    </a>
                    <a href="{{ route('contact') }}" class="inline-block bg-transparent border-2 border-white text-white py-3 px-8 rounded-lg text-lg font-semibold hover:bg-white hover:text-blue-600 transform hover:-translate-y-1 transition duration-300">
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>