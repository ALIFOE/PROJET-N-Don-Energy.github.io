<!-- resources/views/home.blade.php -->
<x-app-layout>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Né Don Energy - Gestion d'installations solaires photovoltaïques</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-image: url('{{ asset('images/image-back.png') }}');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-color: #f8f9fa;
                animation: backgroundZoom 20s ease-in-out infinite alternate;
            }

            @keyframes backgroundZoom {
                0% {
                    background-size: 100% auto;
                }
                100% {
                    background-size: 120% auto;
                }
            }

            .solar-gradient {
                background: linear-gradient(135deg, #1e88e5 0%, #0d47a1 100%);
            }
            .card {
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
            .hero-section {
                min-height: 500px;
                background-image: url('{{ asset('images/image-2.jpg')}}');
                background-size: cover;
                background-position: center;
                position: relative;
                animation: heroFadeIn 2s ease-out;
            }

            @keyframes heroFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 2;
                left: 0;
                width: 99%;
                height: 100%;
                background-image: url('{{ asset('images/4813.jpg') }}');
                /* animation: overlayFade 3s ease-in-out infinite alternate; */
            }

            @keyframes overlayFade {
                0% {
                    opacity: 0.7;
                }
                100% {
                    opacity: 0.9;
                }
            }
            .feature-icon {
                font-size: 2.5rem;
                color: #1e88e5;
            }
            .navbar-active {
                color: #1e88e5;
                border-bottom: 2px solid #1e88e5;
            }
            .content-section {
                background-color: rgba(255, 255, 255, 0.95);
                border-radius: 12px;
                padding: 2rem;
                margin: 1rem;
                
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
            .hero-text {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            }
            .hero-description {
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
            }
            .section-title {
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
            }
            .section-description {
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            }
            .card-title {
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            }
            .feature-text {
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>

    <section class="hero-section relative flex items-center">
        <div class="container mx-auto px-6 z-10 text-center md:text-left">
            <div class="md:w-2/3">
                <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight hero-text">Optez pour la durabilité et meilleure performance de vos installations solaires</h1>
                <p class="mt-4 text-xl text-gray-200 hero-description">Suivi en temps réel, analyses avancées et maintenance prédictive pour maximiser votre production d'énergie photovoltaïque.</p>
                <div class="mt-8 flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 justify-center md:justify-start">
                    @auth
                        <a href="{{ route('service') }}" class="btn-primary text-center py-3 px-6 rounded-md text-lg font-medium hover:bg-blue-700">Démarrer un service</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary text-center py-3 px-6 rounded-md text-lg font-medium hover:bg-blue-700">Démarrer un service</a>
                    @endauth
                    <a href="{{ route('about') }}" class="bg-white text-blue-600 text-center py-3 px-6 rounded-md text-lg font-medium hover:bg-gray-100">En savoir plus</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-6">
            <div class="content-section">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 section-title">Pourquoi choisir Né Don Energy ?</h2>
                    <p class="mt-4 text-xl text-gray-600 section-description">Une solution complète pour optimiser vos installations photovoltaïques</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="card bg-white p-8 text-center">
                        <div class="mb-4">
                            <i class="fas fa-chart-line feature-icon"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2 card-title">Suivi en temps réel</h3>
                        <p class="text-gray-600 feature-text">Visualisez la production de vos panneaux solaires en direct et accédez à des statistiques détaillées.</p>
                    </div>
                    
                    <div class="card bg-white p-8 text-center">
                        <div class="mb-4">
                            <i class="fas fa-tools feature-icon"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2 card-title">Maintenance prédictive</h3>
                        <p class="text-gray-600 feature-text">Détectez les anomalies avant qu'elles n'impactent votre production et planifiez les interventions.</p>
                    </div>
                    
                    <div class="card bg-white p-8 text-center">
                        <div class="mb-4">
                            <i class="fas fa-sun feature-icon"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2 card-title">Prévisions météo</h3>
                        <p class="text-gray-600 feature-text">Anticipez votre production grâce à l'intégration des données météorologiques locales.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fonctionnalites" class="py-16">
        <div class="container mx-auto px-6">
            <div class="content-section">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 section-title">Fonctionnalités adaptées à chaque profil</h2>
                    <p class="mt-4 text-xl text-gray-600 section-description">Une solution polyvalente pour tous les acteurs du photovoltaïque</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="card bg-white">
                        <div class="p-6 solar-gradient">
                            <h3 class="text-2xl font-bold text-white">Client</h3>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-3">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Suivi de production en temps réel</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Comparaison avec prévisions météo</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Statistiques de rendement</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Tableau de bord personnalisé</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Alertes et notifications</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Rapports téléchargeables</span>
                                </li>
                            </ul>
                            <div class="mt-6">
                                <a href="{{ route('register') }}?role=client" class="btn-primary block text-center">Devenir client</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-white">
                        <div class="p-6 solar-gradient">
                            <h3 class="text-2xl font-bold text-white">Technicien</h3>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-3">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Gestion des installations</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Suivi de performance</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Détection d'anomalies</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Calendrier des interventions</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Recommandations d'optimisation</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Historique des maintenances</span>
                                </li>
                            </ul>
                            <div class="mt-6">
                                <a href="{{ route('register') }}?role=technicien" class="btn-primary block text-center">Devenir technicien</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-white">
                        <div class="p-6 solar-gradient">
                            <h3 class="text-2xl font-bold text-white">Administrateur</h3>
                        </div>
                        <div class="p-6">
                            <ul class="space-y-3">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Tableau de bord global</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Gestion des utilisateurs</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Suivi de toutes les installations</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Statistiques avancées</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Rapports analytiques</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>Configuration système</span>
                                </li>
                            </ul>
                            <div class="mt-6">
                                <a href="{{ route('contact') }}" class="btn-primary block text-center">Nous contacter</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Graphiques et données en temps réel</h2>
                <p class="mt-4 text-xl text-gray-600">Aperçu des fonctionnalités de visualisation</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="card bg-white p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Production journalière</h3>
                    <p id="lastUpdate" class="text-sm text-gray-600 mb-2"></p>
                    <div class="h-80">
                        <canvas id="dailyProductionChart"></canvas>
                    </div>
                </div>
                
                <div class="card bg-white p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Comparaison Production vs Consommation</h3>
                    <p id="lastUpdateComparison" class="text-sm text-gray-600 mb-2"></p>
                    <div class="h-80">
                        <canvas id="comparisonChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <div class="card bg-white p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Performance mensuelle</h3>
                    <div class="h-80">
                        <canvas id="monthlyPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Témoignages de nos clients</h2>
                <p class="mt-4 text-xl text-gray-600">Ce que nos utilisateurs disent de Né Don Energy</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card bg-white p-8">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Grâce à Né Don Energy, j'ai augmenté le rendement de mon installation de 15% en détectant rapidement des panneaux défectueux. L'interface est intuitive et les graphiques sont très clairs."</p>
                    <div class="mt-6 flex items-center">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xl font-bold">JD</div>
                        <div class="ml-4">
                            <p class="font-semibold">AFANKOUDTCHE Jean</p>
                            <p class="text-sm text-gray-500">Professeur Enseignant</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white p-8">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"En tant que technicienne, cette plateforme a révolutionné ma façon de travailler. Les alertes automatiques me permettent d'intervenir avant même que le client ne remarque un problème."</p>
                    <div class="mt-6 flex items-center">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xl font-bold">ML</div>
                        <div class="ml-4">
                            <p class="font-semibold">Justine DJESSOU</p>
                            <p class="text-sm text-gray-500">Docteur en Biotechnologie Humaine</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-white p-8">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">"Notre entreprise gère plus de 50 installations et Né Don Energy nous a permis de centraliser toutes les données. Les rapports automatiques nous font gagner un temps précieux."</p>
                    <div class="mt-6 flex items-center">
                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xl font-bold">PG</div>
                        <div class="ml-4">
                            <p class="font-semibold">Dorian AHONDO</p>
                            <p class="text-sm text-gray-500">Atiste peintre et plâtrier</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-blue-900 text-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-6">Prêt à optimiser vos installations solaires ?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Rejoignez les milliers d'utilisateurs qui font confiance à Né Don Energy pour maximiser la rentabilité de leurs panneaux photovoltaïques.</p>
            <a href="{{ route('register') }}" class="bg-white text-blue-900 py-3 px-8 rounded-md text-lg font-medium hover:bg-gray-100 transition duration-300">Créer un compte gratuitement</a>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-solar-panel text-blue-400 text-3xl mr-2"></i>
                        <span class="text-2xl font-bold">Né Don Energy</span>
                    </div>
                    <p class="text-gray-400">La solution complète pour le suivi et l'optimisation de vos installations solaires photovoltaïques.</p>
                    <div class="flex mt-4 space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="/home" class="text-gray-400 hover:text-white transition duration-300">Accueil</a></li>
                        <li><a href="/fonctionnalite" class="text-gray-400 hover:text-white transition duration-300">Fonctionnalités</a></li>
                        <li><a href="/tarifs" class="text-gray-400 hover:text-white transition duration-300">Tarifs</a></li>
                        <li><a href="/about" class="text-gray-400 hover:text-white transition duration-300">À propos</a></li>
                        <li><a href="contact" class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="/suivi-production" class="text-gray-400 hover:text-white transition duration-300">Suivi de production</a></li>
                        <li><a href="/maintenance-predictive" class="text-gray-400 hover:text-white transition duration-300">Maintenance prédictive</a></li>
                        <li><a href="/optimisation" class="text-gray-400 hover:text-white transition duration-300">Optimisation de rendement</a></li>
                        <li><a href="/assistance" class="text-gray-400 hover:text-white transition duration-300">Assistance technique</a></li>
                        <li><a href="/formation" class="text-gray-400 hover:text-white transition duration-300">Formation</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2 text-blue-400"></i>
                            <span class="text-gray-400">Rue KOPEGA 56.GB Lomé, Togo</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mt-1 mr-2 text-blue-400"></i>
                            <span class="text-gray-400">+228 97 73 43 81</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mt-1 mr-2 text-blue-400"></i>
                            <span class="text-gray-400">contact@Né Don Energy.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} Né Don Energy. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.10.2/cdn.min.js" defer></script>
    <script>
        // Fonction pour générer des données aléatoires basées sur l'heure
        function generateHourlyData(baseData, variance) {
            const hour = new Date().getHours();
            const newData = [...baseData];
            // Ajout d'une variation aléatoire pour l'heure actuelle
            newData[hour] = Math.max(0, baseData[hour] + (Math.random() - 0.5) * variance);
            return newData;
        }

        // Données de base
        const togoBaseData = [0.0, 0.0, 0.0, 0.0, 0.0, 0.1, 0.5, 1.8, 3.2, 4.5, 5.4, 5.8, 5.9, 5.7, 5.2, 4.3, 3.1, 1.9, 0.7, 0.2, 0.0, 0.0, 0.0, 0.0];
        const worldBaseData = [0.0, 0.0, 0.0, 0.0, 0.0, 0.2, 0.8, 2.1, 3.8, 5.2, 6.1, 6.5, 6.7, 6.4, 5.8, 4.9, 3.7, 2.3, 1.1, 0.4, 0.1, 0.0, 0.0, 0.0];

        // Graphique de production journalière
        const dailyProductionCtx = document.getElementById('dailyProductionChart').getContext('2d');
        const dailyProductionChart = new Chart(dailyProductionCtx, {
            type: 'line',
            data: {
                labels: ['00h', '01h', '02h', '03h', '04h', '05h', '06h', '07h', '08h', '09h', '10h', '11h', '12h', '13h', '14h', '15h', '16h', '17h', '18h', '19h', '20h', '21h', '22h', '23h'],
                datasets: [{
                    label: 'Production Togo (kW)',
                    data: togoBaseData,
                    borderColor: '#1e88e5',
                    backgroundColor: 'rgba(30, 136, 229, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Moyenne Mondiale (kW)',
                    data: worldBaseData,
                    borderColor: '#4caf50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Puissance (kW)'
                        },
                        suggestedMax: 7
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Heure'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });

        // Fonction de mise à jour du graphique
        function updateChart() {
            const currentHour = new Date().getHours();
            const currentMinute = new Date().getMinutes();
            
            // Mettre à jour les données avec une variation aléatoire
            dailyProductionChart.data.datasets[0].data = generateHourlyData(togoBaseData, 0.8);
            dailyProductionChart.data.datasets[1].data = generateHourlyData(worldBaseData, 0.5);
            
            // Ajouter un indicateur visuel de l'heure actuelle
            dailyProductionChart.options.plugins.annotation = {
                annotations: {
                    currentTime: {
                        type: 'line',
                        xMin: currentHour,
                        xMax: currentHour,
                        borderColor: 'rgba(255, 0, 0, 0.5)',
                        borderWidth: 2,
                        label: {
                            content: 'Heure actuelle',
                            enabled: true
                        }
                    }
                }
            };
            
            // Mettre à jour le graphique
            dailyProductionChart.update();
            
            // Afficher l'heure de la dernière mise à jour
            const updateTime = document.getElementById('lastUpdate');
            if (updateTime) {
                updateTime.textContent = `Dernière mise à jour : ${currentHour}h${currentMinute.toString().padStart(2, '0')}`;
            }
        }

        // Mettre à jour immédiatement et toutes les heures
        updateChart();
        setInterval(updateChart, 3600000); // 3600000 ms = 1 heure

        // Mise à jour plus fréquente pour la démo (toutes les 30 secondes)
        setInterval(() => {
            const now = new Date();
            if (now.getSeconds() === 0 || now.getSeconds() === 30) {
                updateChart();
            }
        }, 1000);

        // Données de base pour la comparaison Production vs Consommation
        const productionBaseData = {
            'Lundi': 28,
            'Mardi': 32,
            'Mercredi': 35,
            'Jeudi': 27,
            'Vendredi': 30,
            'Samedi': 25,
            'Dimanche': 22
        };

        const consommationBaseData = {
            'Lundi': 22,
            'Mardi': 24,
            'Mercredi': 25,
            'Jeudi': 26,
            'Vendredi': 23,
            'Samedi': 30,
            'Dimanche': 28
        };

        // Fonction pour générer des variations aléatoires
        function generateDailyVariation(baseValue, variance) {
            return Math.max(0, baseValue + (Math.random() - 0.5) * variance);
        }

        // Fonction pour obtenir le jour de la semaine en français
        function getFrenchDayOfWeek(date) {
            const days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            return days[date.getDay()];
        }

        // Graphique de comparaison production vs consommation
        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        const comparisonChart = new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(productionBaseData),
                datasets: [
                    {
                        label: 'Production (kWh)',
                        data: Object.values(productionBaseData),
                        backgroundColor: 'rgba(30, 136, 229, 0.8)',
                    },
                    {
                        label: 'Consommation (kWh)',
                        data: Object.values(consommationBaseData),
                        backgroundColor: 'rgba(255, 152, 0, 0.8)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Énergie (kWh)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Jour'
                        }
                    }
                }
            }
        });

        // Fonction de mise à jour du graphique de comparaison
        function updateComparisonChart() {
            const currentDay = getFrenchDayOfWeek(new Date());
            const currentHour = new Date().getHours();
            const currentMinute = new Date().getMinutes();

            // Mettre à jour les données avec des variations
            comparisonChart.data.datasets[0].data = Object.keys(productionBaseData).map(day => {
                const baseValue = productionBaseData[day];
                return day === currentDay ? generateDailyVariation(baseValue, 6) : baseValue;
            });

            comparisonChart.data.datasets[1].data = Object.keys(consommationBaseData).map(day => {
                const baseValue = consommationBaseData[day];
                return day === currentDay ? generateDailyVariation(baseValue, 4) : baseValue;
            });

            // Mettre en évidence le jour actuel
            comparisonChart.data.datasets.forEach(dataset => {
                dataset.backgroundColor = Object.keys(productionBaseData).map(day => 
                    day === currentDay 
                        ? (dataset.label.includes('Production') ? 'rgba(30, 136, 229, 1)' : 'rgba(255, 152, 0, 1)')
                        : (dataset.label.includes('Production') ? 'rgba(30, 136, 229, 0.6)' : 'rgba(255, 152, 0, 0.6)')
                );
            });

            // Mettre à jour le graphique
            comparisonChart.update();

            // Afficher l'heure de la dernière mise à jour
            const updateTimeComparison = document.getElementById('lastUpdateComparison');
            if (updateTimeComparison) {
                updateTimeComparison.textContent = `Dernière mise à jour : ${currentHour}h${currentMinute.toString().padStart(2, '0')}`;
            }
        }

        // Mettre à jour immédiatement et toutes les heures
        updateComparisonChart();
        setInterval(updateComparisonChart, 3600000); // 3600000 ms = 1 heure

        // Mise à jour plus fréquente pour la démo (toutes les 30 secondes)
        setInterval(() => {
            const now = new Date();
            if (now.getSeconds() === 0 || now.getSeconds() === 30) {
                updateComparisonChart();
            }
        }, 1000);

        // Graphique de performance mensuelle
        const monthlyPerformanceCtx = document.getElementById('monthlyPerformanceChart').getContext('2d');
        const monthlyPerformanceChart = new Chart(monthlyPerformanceCtx, {
            type: 'radar',
            data: {
                labels: ['Production', 'Efficacité', 'Maintenance', 'Météo', 'Rendement'],
                datasets: [{
                    label: 'Performance',
                    data: [85, 90, 75, 80, 88],
                    backgroundColor: 'rgba(30, 136, 229, 0.2)',
                    borderColor: '#1e88e5',
                    borderWidth: 2,
                    pointBackgroundColor: '#1e88e5',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#1e88e5',
                    pointHoverBorderColor: '#fff',
                    pointHitRadius: 10,
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>