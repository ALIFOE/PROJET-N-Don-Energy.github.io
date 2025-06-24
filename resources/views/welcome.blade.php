<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CREFER</title>
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Nunito', sans-serif;
                background: linear-gradient(to bottom right, #1a1a1a, #2d2d2d);
                color: #fff;
            }
            .hero-gradient {
                background: linear-gradient(135deg, rgba(255, 140, 0, 0.1), rgba(0, 51, 102, 0.1));
            }
            .feature-card {
                background: rgba(26, 26, 26, 0.8);
                border-top: 3px solid #FF8C00;
                transition: all 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(255, 140, 0, 0.2);
            }
            .nav-link {
                color: #fff;
                transition: color 0.3s ease;
            }
            .nav-link:hover {
                color: #FF8C00;
            }
            .btn-primary {
                background: linear-gradient(to right, #FF8C00, #ff7b00);
                color: white;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background: linear-gradient(to right, #003366, #004080);
                transform: translateY(-2px);
            }
            .stats-card {
                background: rgba(26, 26, 26, 0.8);
                border-left: 4px solid #FF8C00;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen hero-gradient">
            <!-- Navigation -->
            <nav class="bg-gray-900 shadow-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ url('/') }}" class="text-2xl font-bold text-orange-500">N'CREFER</a>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="nav-link">Tableau de bord</a>
                                @else
                                    <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn-primary px-4 py-2 rounded-lg">Inscription</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="py-20 text-center">
                <h1 class="text-5xl font-bold mb-6 text-orange-400">Solutions Solaires Intelligentes</h1>
                <p class="text-xl text-gray-300 mb-12 max-w-3xl mx-auto">
                    Optimisez votre installation solaire avec notre plateforme de monitoring avancée.
                </p>
                <div class="flex justify-center gap-6">
                    <a href="{{ route('register') }}" class="btn-primary px-8 py-4 rounded-xl text-lg font-semibold">
                        Commencer maintenant
                    </a>
                    <a href="{{ route('contact') }}" class="px-8 py-4 rounded-xl text-lg font-semibold border-2 border-orange-500 text-orange-400 hover:bg-orange-500 hover:text-white transition duration-300">
                        Nous contacter
                    </a>
                </div>
            </div>

            <!-- Features Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <h2 class="text-3xl font-bold text-center mb-12 text-orange-400">Nos Fonctionnalités</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Suivi en temps réel -->
                    <div class="feature-card p-6 rounded-xl">
                        <i class="fas fa-chart-line text-4xl text-orange-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-4 text-gray-100">Suivi en temps réel</h3>
                        <p class="text-gray-400">Visualisez la production de vos panneaux solaires en direct avec des mises à jour toutes les 5 minutes.</p>
                    </div>

                    <!-- Maintenance prédictive -->
                    <div class="feature-card p-6 rounded-xl">
                        <i class="fas fa-tools text-4xl text-blue-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-4 text-gray-100">Maintenance prédictive</h3>
                        <p class="text-gray-400">Anticipez les pannes grâce à notre système d'intelligence artificielle avancé.</p>
                    </div>

                    <!-- Analyse de performance -->
                    <div class="feature-card p-6 rounded-xl">
                        <i class="fas fa-chart-bar text-4xl text-orange-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-4 text-gray-100">Analyse de performance</h3>
                        <p class="text-gray-400">Optimisez votre production avec des rapports détaillés et des recommandations personnalisées.</p>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="stats-card p-6 rounded-xl text-center">
                        <span class="text-4xl font-bold text-orange-400 block mb-2">+500</span>
                        <span class="text-gray-300">Installations</span>
                    </div>
                    <div class="stats-card p-6 rounded-xl text-center">
                        <span class="text-4xl font-bold text-blue-400 block mb-2">95%</span>
                        <span class="text-gray-300">Satisfaction client</span>
                    </div>
                    <div class="stats-card p-6 rounded-xl text-center">
                        <span class="text-4xl font-bold text-orange-400 block mb-2">24/7</span>
                        <span class="text-gray-300">Support technique</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-gray-900 text-gray-400 py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h4 class="text-xl font-bold text-orange-400 mb-4">N'CREFER</h4>
                            <p class="mb-4">Solutions solaires innovantes pour un avenir durable.</p>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-orange-400 mb-4">Contact</h4>
                            <p>+228 97 73 43 81</p>
                            <p>contact@ndon-energy.com</p>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-orange-400 mb-4">Suivez-nous</h4>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-orange-500"><i class="fab fa-facebook"></i></a>
                                <a href="#" class="text-gray-400 hover:text-orange-500"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="text-gray-400 hover:text-orange-500"><i class="fab fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                        <p>&copy; {{ date('Y') }} N'CREFER. Tous droits réservés.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
