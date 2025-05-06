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
            .stat-card {
                border-left: 4px solid #1e88e5;
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
            .feature-icon {
                font-size: 2.5rem;
                color: #1e88e5;
            }
            .navbar-active {
                color: #1e88e5;
                border-bottom: 2px solid #1e88e5;
            }
            .section-title {
                color: #1e88e5;
                font-weight: 600;
                margin-bottom: 1rem;
            }
            .feature-card {
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                transition: all 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
            .feature-list li {
                margin-bottom: 0.5rem;
                color: #4b5563;
            }
            .feature-list i {
                color: #10b981;
                margin-right: 0.5rem;
            }
        </style>
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center section-title">Fonctionnalités</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Suivi en temps réel -->
                        <a href="{{ route('suivi-production') }}" class="feature-card hover:cursor-pointer">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-chart-line text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-xl font-semibold">Suivi en temps réel</h2>
                            </div>
                            <p class="text-gray-600 mb-4">Visualisez la production de vos panneaux solaires en direct et accédez à des statistiques détaillées.</p>
                            <ul class="feature-list">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Production instantanée
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Historique des données
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Graphiques personnalisables
                                </li>
                            </ul>
                        </a>

                        <!-- Maintenance prédictive -->
                        <a href="{{ route('maintenance-predictive') }}" class="feature-card hover:cursor-pointer">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-tools text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-xl font-semibold">Maintenance prédictive</h2>
                            </div>
                            <p class="text-gray-600 mb-4">Détectez les anomalies avant qu'elles n'impactent votre production et planifiez les interventions.</p>
                            <ul class="feature-list">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Alertes automatiques
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Diagnostic intelligent
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Planification des maintenances
                                </li>
                            </ul>
                        </a>

                        <!-- Prévisions météo -->
                        <a href="{{ route('previsions-meteo') }}" class="feature-card hover:cursor-pointer">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-sun text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-xl font-semibold">Prévisions météo</h2>
                            </div>
                            <p class="text-gray-600 mb-4">Anticipez votre production grâce à l'intégration des données météorologiques locales.</p>
                            <ul class="feature-list">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Prévisions à 7 jours
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Alertes météo
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Estimation de production
                                </li>
                            </ul>
                        </a>

                        <!-- Rapports et analyses -->
                        <a href="{{ route('rapports-analyses') }}" class="feature-card hover:cursor-pointer">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-file-alt text-blue-500 text-2xl mr-3"></i>
                                <h2 class="text-xl font-semibold">Rapports et analyses</h2>
                            </div>
                            <p class="text-gray-600 mb-4">Générez des rapports détaillés et analysez les performances de votre installation.</p>
                            <ul class="feature-list">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Rapports personnalisables
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Export des données
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Analyses comparatives
                                </li>
                            </ul>
                        </a>
                    </div>

                    <div class="mt-12 text-center">
                        <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                            Démarrer maintenant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>