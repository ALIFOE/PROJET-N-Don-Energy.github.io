<x-app-layout>
    <head>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f9fa;
            }
            .solar-gradient {
                background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
            }
            .card {
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5e7eb;
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
            .btn-primary {
                background-color: #1e88e5;
                color: white;
                padding: 0.5rem 1.5rem;
                border-radius: 5px;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background-color: #0d47a1;
            }
            .section-title {
                color: #1e88e5;
                font-weight: 600;
                margin-bottom: 1rem;
            }
            .service-card {
                background: white;
                border-radius: 12px;
                padding: 2rem;
                transition: all 0.3s ease;
            }
            .service-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
            .service-features li {
                margin-bottom: 0.5rem;
                color: #4b5563;
            }
            .service-features i {
                color: #10b981;
                margin-right: 0.5rem;
            }
        </style>
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center section-title">Nos Services</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Installation de panneaux solaires -->
                        <div class="service-card">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-solar-panel text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-xl font-semibold">Installation de panneaux solaires</h2>
                            </div>
                            <p class="text-gray-600 mb-4">Professionnels certifiés pour l'installation de vos panneaux solaires photovoltaïques.</p>
                            <ul class="service-features">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Étude personnalisée
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Installation professionnelle
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Suivi de performance
                                </li>
                            </ul>
                            <div class="mt-6 text-center">
                                <a href="{{ route('dimensionnements.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                    Demander un dimensionnement
                                </a>
                            </div>
                        </div>

                        <!-- Maintenance et entretien -->
                        <div class="service-card">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-tools text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-xl font-semibold">Maintenance et entretien</h2>
                            </div>
                            <p class="text-gray-600 mb-4">Services de maintenance régulière pour optimiser la performance de votre installation.</p>
                            <ul class="service-features">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Nettoyage des panneaux
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Vérification technique
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Dépannage rapide
                                </li>
                            </ul>
                            <div class="mt-6 text-center">
                                <a href="{{ route('contact') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                    Prendre rendez-vous
                                </a>
                            </div>
                        </div>

                        <!-- Optimisation de performance -->
                        <div class="service-card">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-chart-line text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-xl font-semibold">Optimisation de performance</h2>
                            </div>
                            <p class="text-gray-600 mb-4">Analyse et amélioration de la performance de votre installation solaire.</p>
                            <ul class="service-features">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Audit de performance
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Recommandations personnalisées
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Suivi des améliorations
                                </li>
                            </ul>
                            <div class="mt-6 text-center">
                                <a href="{{ route('contact') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                    En savoir plus
                                </a>
                            </div>
                        </div>

                        <!-- Formation et conseil -->
                        <div class="service-card">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-graduation-cap text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-xl font-semibold">Formation et conseil</h2>
                            </div>
                            <p class="text-gray-600 mb-4">Formations et conseils pour une utilisation optimale de votre installation.</p>
                            <ul class="service-features">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Formation utilisateur
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Conseils d'optimisation
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Support technique
                                </li>
                            </ul>
                            <div class="mt-6 text-center">
                                <a href="{{ route('contact') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                    S'inscrire à une formation
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>