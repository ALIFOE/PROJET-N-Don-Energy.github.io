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
        </style>
    </head>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8 text-center">À propos de Né Don Energy</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                        <div>
                            <h2 class="text-2xl font-semibold mb-4">Notre Mission</h2>
                            <p class="text-gray-600 mb-6">
                                Né Don Energy est né de la volonté de simplifier et d'optimiser la gestion des installations solaires photovoltaïques. Notre mission est de fournir aux propriétaires d'installations solaires et aux professionnels du secteur les outils nécessaires pour maximiser leur production d'énergie renouvelable.
                            </p>
                            <p class="text-gray-600">
                                Nous croyons en un avenir plus durable et nous nous engageons à contribuer à la transition énergétique en rendant l'énergie solaire plus accessible et plus efficace.
                            </p>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold mb-4">Notre Vision</h2>
                            <p class="text-gray-600 mb-6">
                                Nous aspirons à devenir le leader mondial des solutions de gestion d'installations solaires, en combinant innovation technologique et expertise du secteur.
                            </p>
                            <p class="text-gray-600">
                                Notre objectif est de permettre à chaque installation solaire d'atteindre son plein potentiel, tout en simplifiant la vie de nos utilisateurs grâce à des outils intuitifs et performants.
                            </p>
                        </div>
                    </div>

                    <div class="mb-12">
                        <h2 class="text-2xl font-semibold mb-6 text-center">Notre Équipe</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="text-center">
                                <div class="bg-gray-200 rounded-full w-32 h-32 mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-user text-4xl text-gray-500"></i>
                                </div>
                                <h3 class="text-xl font-semibold">Jean Dupont</h3>
                                <p class="text-gray-600">Fondateur & CEO</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-gray-200 rounded-full w-32 h-32 mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-user text-4xl text-gray-500"></i>
                                </div>
                                <h3 class="text-xl font-semibold">Marie Martin</h3>
                                <p class="text-gray-600">Directrice Technique</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-gray-200 rounded-full w-32 h-32 mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-user text-4xl text-gray-500"></i>
                                </div>
                                <h3 class="text-xl font-semibold">Pierre Durand</h3>
                                <p class="text-gray-600">Responsable Innovation</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-12">
                        <h2 class="text-2xl font-semibold mb-6 text-center">Nos Valeurs</h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="text-center">
                                <i class="fas fa-leaf text-4xl text-green-500 mb-4"></i>
                                <h3 class="text-lg font-semibold mb-2">Durabilité</h3>
                                <p class="text-gray-600">Nous nous engageons pour un avenir plus vert</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-lightbulb text-4xl text-yellow-500 mb-4"></i>
                                <h3 class="text-lg font-semibold mb-2">Innovation</h3>
                                <p class="text-gray-600">Nous repoussons les limites de la technologie</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-handshake text-4xl text-blue-500 mb-4"></i>
                                <h3 class="text-lg font-semibold mb-2">Collaboration</h3>
                                <p class="text-gray-600">Nous travaillons ensemble pour réussir</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-heart text-4xl text-red-500 mb-4"></i>
                                <h3 class="text-lg font-semibold mb-2">Passion</h3>
                                <p class="text-gray-600">Nous croyons en ce que nous faisons</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('contact') }}" class="inline-block bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition duration-300">
                            Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 