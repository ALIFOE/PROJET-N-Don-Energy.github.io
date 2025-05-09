<x-app-layout>
    <head>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f9fa;
            }
            .solar-gradient {
                background: linear-gradient(135deg, #FF8C00 0%, #003366 100%);
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
                border-left: 4px solid #FF8C00;
            }
            .btn-primary {
                background-color: #FF8C00;
                color: #FFFFFF;
                padding: 0.5rem 1.5rem;
                border-radius: 5px;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background-color: #003366;
            }
            .feature-icon {
                font-size: 2.5rem;
                color: #FF8C00;
            }
            .navbar-active {
                color: #FF8C00;
                border-bottom: 2px solid #FF8C00;
            }
            .section-title {
                color: #212121;
                font-weight: 600;
                margin-bottom: 1rem;
            }
            .feature-card {
                background: white;
                border-radius: 12px;
                padding: 1.5rem;
                transition: all 0.3s ease;
                border-top: 3px solid #FF8C00;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }
            .feature-list li {
                margin-bottom: 0.5rem;
                color: #212121;
            }
            .feature-list i {
                color: #FF8C00;
                margin-right: 0.5rem;
            }
            .text-accent {
                color: #003366;
            }
            .cta-button {
                background-color: #003366;
                border: 2px solid #003366;
                color: white;
                padding: 0.75rem 2rem;
                border-radius: 6px;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            .cta-button:hover {
                background-color: #FF8C00;
                border-color: #FF8C00;
            }
        </style>
    </head>

    <div class="py-12" style="background-color: #f8f9fa;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center section-title">Fonctionnalités</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Suivi en temps réel -->
                        <a href="{{ route('suivi-production') }}" class="feature-card hover:cursor-pointer">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-chart-line text-2xl mr-3" style="color: #003366;"></i>
                                <h2 class="text-xl font-semibold" style="color: #212121;">Suivi en temps réel</h2>
                            </div>
                            <p class="text-gray-700 mb-4">Visualisez la production de vos panneaux solaires en direct avec une mise à jour toutes les 5 minutes. Interface intuitive avec données techniques détaillées.</p>
                            <ul class="feature-list">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Production instantanée (kW) et journalière (kWh)
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Historique détaillé sur 24 mois
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Graphiques personnalisables par heure/jour/mois
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Tableau de bord adaptatif
                                </li>
                            </ul>
                            <div class="mt-4">
                                <button class="btn-primary hover:bg-[#003366] transition duration-300">
                                    Explorer les fonctionnalités
                                </button>
                            </div>
                        </a>

                        <!-- Maintenance prédictive -->
                        <a href="{{ route('maintenance-predictive') }}" class="feature-card hover:cursor-pointer">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-tools text-2xl mr-3" style="color: #003366;"></i>
                                <h2 class="text-xl font-semibold" style="color: #212121;">Maintenance prédictive</h2>
                            </div>
                            <p class="text-gray-700 mb-4">Système d'IA avancé pour la détection précoce des anomalies avec une précision de 95%. Réduisez vos coûts de maintenance jusqu'à 30%.</p>
                            <ul class="feature-list">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Alertes SMS et email en temps réel
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Diagnostic basé sur l'IA et Machine Learning
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Planning intelligent des interventions
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Historique complet des maintenances
                                </li>
                            </ul>
                            <div class="mt-4">
                                <button class="btn-primary hover:bg-[#003366] transition duration-300">
                                    Découvrir la maintenance
                                </button>
                            </div>
                        </a>

                        <!-- Prévisions météo -->
                        <a href="{{ route('previsions-meteo') }}" class="feature-card hover:cursor-pointer">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-sun text-2xl mr-3" style="color: #003366;"></i>
                                <h2 class="text-xl font-semibold" style="color: #212121;">Prévisions météo</h2>
                            </div>
                            <p class="text-gray-700 mb-4">Données météorologiques haute précision avec mise à jour toutes les 3 heures. Intégration des données satellites et stations météo locales.</p>
                            <ul class="feature-list">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Prévisions détaillées sur 7 jours
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Alertes météo personnalisables
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Estimation précise de la production
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Cartographie interactive
                                </li>
                            </ul>
                            <div class="mt-4">
                                <button class="btn-primary hover:bg-[#003366] transition duration-300">
                                    Voir les prévisions
                                </button>
                            </div>
                        </a>

                        <!-- Rapports et analyses -->
                        <a href="{{ route('rapports-analyses') }}" class="feature-card hover:cursor-pointer">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-file-alt text-2xl mr-3" style="color: #003366;"></i>
                                <h2 class="text-xl font-semibold" style="color: #212121;">Rapports et analyses</h2>
                            </div>
                            <p class="text-gray-700 mb-4">Suite complète d'outils d'analyse avec export multiformat (PDF, Excel, CSV). Visualisation avancée des données de performance.</p>
                            <ul class="feature-list">
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Rapports automatisés hebdomadaires
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Export des données multiformat
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    Analyses comparatives avancées
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check"></i>
                                    KPIs personnalisables
                                </li>
                            </ul>
                            <div class="mt-4">
                                <button class="btn-primary hover:bg-[#003366] transition duration-300">
                                    Accéder aux rapports
                                </button>
                            </div>
                        </a>
                    </div>

                    <div class="mt-12 text-center">
                        <p class="text-gray-600 mb-4">Commencez à optimiser votre installation solaire dès aujourd'hui</p>
                        <a href="{{ route('register') }}" class="cta-button inline-block transition duration-300">
                            Démarrer maintenant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>