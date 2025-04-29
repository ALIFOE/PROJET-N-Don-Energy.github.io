<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-6 py-12">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">Blog</h1>
            <p class="text-xl text-gray-600 mb-12 text-center">Découvrez nos articles sur l'énergie solaire et les dernières innovations</p>

            <!-- Barre de recherche -->
            <div class="mb-12">
                <form action="{{ route('blog.search') }}" method="GET" class="flex gap-4">
                    <input type="text" name="search" placeholder="Rechercher un article..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-300">
                        Rechercher
                    </button>
                </form>
            </div>

            <!-- Catégories -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Catégories</h2>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('blog.category', 'technologie') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                        Technologie
                    </a>
                    <a href="{{ route('blog.category', 'installation') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                        Installation
                    </a>
                    <a href="{{ route('blog.category', 'financement') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                        Financement
                    </a>
                    <a href="{{ route('blog.category', 'actualites') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                        Actualités
                    </a>
                </div>
            </div>

            <!-- Liste des articles -->
            <div class="space-y-8">
                <!-- Article 1 -->
                <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/blog/solar-panels.jpg') }}" alt="Panneaux solaires" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-sm text-gray-500">15 Mars 2024</span>
                            <span class="text-sm text-blue-600">Technologie</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Les dernières innovations en matière de panneaux solaires</h2>
                        <p class="text-gray-600 mb-4">Découvrez les avancées technologiques qui révolutionnent l'industrie solaire et comment elles peuvent bénéficier à votre installation.</p>
                        <a href="{{ route('blog.show', 'innovations-panneaux-solaires') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                            Lire l'article →
                        </a>
                    </div>
                </article>

                <!-- Article 2 -->
                <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/blog/installation.jpg') }}" alt="Installation solaire" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-sm text-gray-500">10 Mars 2024</span>
                            <span class="text-sm text-blue-600">Installation</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Guide complet pour l'installation de panneaux solaires</h2>
                        <p class="text-gray-600 mb-4">Tout ce que vous devez savoir avant d'installer des panneaux solaires chez vous, des démarches administratives aux choix techniques.</p>
                        <a href="{{ route('blog.show', 'guide-installation-solaire') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                            Lire l'article →
                        </a>
                    </div>
                </article>

                <!-- Article 3 -->
                <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="{{ asset('images/blog/financement.jpg') }}" alt="Financement solaire" class="w-full h-64 object-cover">
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-sm text-gray-500">5 Mars 2024</span>
                            <span class="text-sm text-blue-600">Financement</span>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Les aides et subventions pour l'énergie solaire en 2024</h2>
                        <p class="text-gray-600 mb-4">Un tour d'horizon complet des différentes aides financières disponibles pour votre projet d'énergie solaire.</p>
                        <a href="{{ route('blog.show', 'aides-solaires-2024') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                            Lire l'article →
                        </a>
                    </div>
                </article>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                <nav class="flex items-center gap-2">
                    <a href="#" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                        ←
                    </a>
                    <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        1
                    </a>
                    <a href="#" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                        2
                    </a>
                    <a href="#" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                        3
                    </a>
                    <a href="#" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-300">
                        →
                    </a>
                </nav>
            </div>
        </div>
    </div>
</x-app-layout> 