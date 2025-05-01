<x-app-layout>
    <head>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f9fa;
            }
            
            /* Animation d'apparition */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .hero-section {
                background: linear-gradient(rgba(30, 136, 229, 0.9), rgba(13, 71, 161, 0.9)), url('/images/solar-panels.jpg');
                background-size: cover;
                background-position: center;
                padding: 4rem 0;
                margin-bottom: 3rem;
                color: white;
                text-align: center;
            }

            .feature-card {
                background: white;
                border-radius: 12px;
                padding: 2rem;
                transition: all 0.4s ease;
                opacity: 0;
                animation: fadeInUp 0.6s ease forwards;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                border: 1px solid #e5e7eb;
                height: 100%;
            }

            .feature-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 30px rgba(30, 136, 229, 0.15);
            }

            .feature-icon {
                font-size: 2.5rem;
                color: #1e88e5;
                transition: all 0.3s ease;
            }

            .feature-card:hover .feature-icon {
                transform: scale(1.1);
            }

            .feature-list li {
                margin-bottom: 1rem;
                color: #4b5563;
                display: flex;
                align-items: center;
                transition: all 0.3s ease;
            }

            .feature-list li:hover {
                color: #1e88e5;
                transform: translateX(5px);
            }

            .feature-list i {
                color: #10b981;
                margin-right: 0.8rem;
                font-size: 1.1rem;
            }

            .cta-button {
                background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
                color: white;
                padding: 1rem 2.5rem;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-block;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .cta-button:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(30, 136, 229, 0.25);
            }

            @media (max-width: 768px) {
                .feature-card {
                    margin-bottom: 1.5rem;
                }
                
                .hero-section {
                    padding: 2rem 0;
                }
            }
        </style>
    </head>

    <div class="hero-section">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Nos Fonctionnalités Innovantes</h1>
            <p class="text-xl opacity-90">Découvrez comment notre technologie peut transformer votre expérience solaire</p>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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

                <div class="mt-16 text-center">
                    <a href="{{ route('register') }}" class="cta-button">
                        Commencer votre voyage solaire
                    </a>
                    <p class="mt-4 text-gray-600">Rejoignez des milliers d'utilisateurs satisfaits</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animation au défilement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.feature-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = `${index * 0.2}s`;
                        entry.target.style.opacity = 1;
                    }
                });
            }, { threshold: 0.1 });

            cards.forEach(card => observer.observe(card));
        });
    </script>
</x-app-layout>