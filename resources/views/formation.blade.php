<x-app-layout>
    <div class="py-12 bg-gradient-to-b from-gray-50 to-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- En-tête avec animation et coordonnées -->
            <div class="text-center mb-16 animate-fade-in">
                <img src="{{ asset('images/logo.png') }}" alt="N'Don Energy Logo" class="mx-auto h-24 mb-8">
                <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Centre de Formation N'Don Energy</h1>
                <p class="text-2xl text-gray-600 max-w-3xl mx-auto mb-4">Expert en Solutions d'Énergie Solaire</p>
                <div class="text-gray-600">
                    <p>123 Avenue de l'Énergie Verte, 75000 Paris</p>
                    <p>Tél: +33 (0)1 23 45 67 89 | Email: formation@ndon-energy.com</p>
                    <p>Numéro d'agrément: 11756789100</p>
                </div>
            </div>

            <!-- Badges de certification -->
            <div class="flex justify-center gap-8 mb-16">
                <div class="text-center">
                    <img src="{{ asset('images/certif-1.png') }}" alt="Certification Qualibat" class="h-20 mx-auto mb-2">
                    <p class="text-sm text-gray-600">Certifié Qualibat</p>
                </div>
                <div class="text-center">
                    <img src="{{ asset('images/certif-2.png') }}" alt="Certification QualiPV" class="h-20 mx-auto mb-2">
                    <p class="text-sm text-gray-600">Certifié QualiPV</p>
                </div>
            </div>

            <!-- Filtres de formation -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">Toutes les formations</button>
                <button class="px-6 py-2 bg-white text-blue-600 rounded-full hover:bg-blue-50 transition">Débutant</button>
                <button class="px-6 py-2 bg-white text-blue-600 rounded-full hover:bg-blue-50 transition">Intermédiaire</button>
                <button class="px-6 py-2 bg-white text-blue-600 rounded-full hover:bg-blue-50 transition">Avancé</button>
            </div>

            <!-- Grille des formations -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Formation 1 -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                    <div class="relative">
                        <img src="{{ asset('images/image-3.jpg') }}" alt="Installation de panneaux solaires" class="w-full h-56 object-cover">
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-full">
                            3 jours
                        </div>
                        <div class="absolute bottom-4 left-4 bg-green-500 text-white px-4 py-1 rounded-full text-sm">
                            Débutant
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Installation de Panneaux Solaires</h3>
                            <span class="text-2xl font-bold text-blue-600">999€</span>
                        </div>
                        <p class="text-gray-600 mb-6">Maîtrisez l'installation professionnelle de systèmes photovoltaïques.</p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span>Certification professionnelle incluse</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tools text-green-500 mr-3"></i>
                                <span>Pratique sur installations réelles</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-user-shield text-green-500 mr-3"></i>
                                <span>Formation aux normes de sécurité</span>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('inscription') }}" class="flex-1 text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                                S'inscrire
                            </a>
                            <button class="px-4 py-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formation 2 -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                    <div class="relative">
                        <img src="{{ asset('images/29990.jpg') }}" alt="Maintenance des installations" class="w-full h-56 object-cover">
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-full">
                            2 jours
                        </div>
                        <div class="absolute bottom-4 left-4 bg-yellow-500 text-white px-4 py-1 rounded-full text-sm">
                            Intermédiaire
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Maintenance et Dépannage</h3>
                            <span class="text-2xl font-bold text-blue-600">799€</span>
                        </div>
                        <p class="text-gray-600 mb-6">Perfectionnez-vous dans la maintenance des installations solaires.</p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span>Diagnostic avancé</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tools text-green-500 mr-3"></i>
                                <span>Maintenance préventive et curative</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-chart-line text-green-500 mr-3"></i>
                                <span>Optimisation des performances</span>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('inscription') }}" class="flex-1 text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                                S'inscrire
                            </a>
                            <button class="px-4 py-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Formation 3 -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                    <div class="relative">
                        <img src="{{ asset('images/4783.jpg') }}" alt="Conception de projets" class="w-full h-56 object-cover">
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-full">
                            4 jours
                        </div>
                        <div class="absolute bottom-4 left-4 bg-red-500 text-white px-4 py-1 rounded-full text-sm">
                            Avancé
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Conception de Projets</h3>
                            <span class="text-2xl font-bold text-blue-600">1299€</span>
                        </div>
                        <p class="text-gray-600 mb-6">Maîtrisez la conception complète de projets solaires.</p>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span>Études techniques complètes</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calculator text-green-500 mr-3"></i>
                                <span>Dimensionnement professionnel</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-file-invoice-dollar text-green-500 mr-3"></i>
                                <span>Analyse financière détaillée</span>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('inscription') }}" class="flex-1 text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                                S'inscrire
                            </a>
                            <button class="px-4 py-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Avantages -->
            <div class="mt-24">
                <h2 class="text-3xl font-bold text-center mb-12">Pourquoi choisir N'Don Energy ?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <i class="fas fa-medal text-4xl text-blue-600 mb-4"></i>
                        <h3 class="text-xl font-bold mb-3">Expertise Reconnue</h3>
                        <p class="text-gray-600">Plus de 10 ans d'expérience dans la formation aux énergies renouvelables</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <i class="fas fa-user-tie text-4xl text-blue-600 mb-4"></i>
                        <h3 class="text-xl font-bold mb-3">Formateurs Qualifiés</h3>
                        <p class="text-gray-600">Une équipe de professionnels expérimentés et passionnés</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <i class="fas fa-certificate text-4xl text-blue-600 mb-4"></i>
                        <h3 class="text-xl font-bold mb-3">Certifications Reconnues</h3>
                        <p class="text-gray-600">Formations certifiantes reconnues par l'État et les professionnels</p>
                    </div>
                </div>
            </div>

            <!-- Section FAQ -->
            <div class="mt-24">
                <h2 class="text-3xl font-bold text-center mb-12">Questions Fréquentes</h2>
                <div class="max-w-3xl mx-auto space-y-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <button class="flex justify-between items-center w-full">
                            <span class="text-lg font-semibold">Quels sont les prérequis pour suivre ces formations ?</span>
                            <i class="fas fa-chevron-down text-blue-600"></i>
                        </button>
                        <div class="mt-4 text-gray-600">
                            Chaque formation a des prérequis différents. Les formations débutantes sont accessibles à tous, tandis que les niveaux intermédiaire et avancé nécessitent une expérience préalable dans le domaine.
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <button class="flex justify-between items-center w-full">
                            <span class="text-lg font-semibold">Les formations sont-elles certifiantes ?</span>
                            <i class="fas fa-chevron-down text-blue-600"></i>
                        </button>
                        <div class="mt-4 text-gray-600">
                            Oui, toutes nos formations sont certifiantes et reconnues par les professionnels du secteur. Vous recevrez un certificat à la fin de chaque formation.
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <button class="flex justify-between items-center w-full">
                            <span class="text-lg font-semibold">Comment se déroule l'inscription ?</span>
                            <i class="fas fa-chevron-down text-blue-600"></i>
                        </button>
                        <div class="mt-4 text-gray-600">
                            L'inscription se fait en ligne via notre plateforme. Après validation de votre inscription, vous recevrez une confirmation par email avec tous les détails pratiques.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call-to-action final -->
            <div class="mt-24 text-center bg-blue-600 rounded-2xl p-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-blue-700 opacity-50 pattern-dots"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold text-white mb-6">Prêt à développer vos compétences ?</h2>
                    <p class="text-blue-100 mb-4 text-lg">Inscrivez-vous maintenant et bénéficiez de -10% sur votre première formation</p>
                    <p class="text-blue-100 mb-8">
                        <i class="fas fa-phone-alt mr-2"></i> +33 (0)1 23 45 67 89
                        <span class="mx-4">|</span>
                        <i class="fas fa-envelope mr-2"></i> formation@ndon-energy.com
                    </p>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('inscription') }}" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-blue-50 transition">
                            S'inscrire
                        </a>
                        <a href="{{ route('contact') }}" class="inline-block bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-800 transition">
                            Contactez-nous
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer avec informations légales -->
            <div class="mt-16 text-center text-sm text-gray-600">
                <p>N'Don Energy - SIRET: 123 456 789 00001 - TVA: FR12 123456789</p>
                <p>Organisme de formation enregistré sous le numéro 11756789100</p>
                <p>© {{ date('Y') }} N'Don Energy. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</x-app-layout>
