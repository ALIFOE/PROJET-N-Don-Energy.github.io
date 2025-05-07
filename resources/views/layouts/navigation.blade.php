<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 fixed top-0 left-0 right-0 z-50">
    <head>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    </head>
    <div class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo et Nom -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <i class="fas fa-solar-panel text-blue-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-800">Né Don Energy</span>
                </a>
            </div>            <!-- Navigation Links -->
            @if(auth()->check())
                @if(auth()->user()->isAdmin())
                    @include('layouts.admin-navigation')
                @elseif(auth()->user()->role === 'technician')
                    @include('layouts.technician-navigation')
                @else
                    <!-- Menu Client -->
                    <div class="hidden md:flex space-x-10">
                        <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
                        <a href="{{ route('fonctionnalite') }}" class="navbar-link {{ request()->routeIs('fonctionnalite') ? 'active' : '' }}">Fonctionnalités</a>
                        <a href="{{ route('formation') }}" class="navbar-link {{ request()->routeIs('formation') ? 'active' : '' }}">Formations</a>
                        <a href="{{ route('installation') }}" class="navbar-link {{ request()->routeIs('installation') ? 'active' : '' }}">Devis-Installations</a>
                        <a href="{{ route('market-place') }}" class="navbar-link {{ request()->routeIs('market-place') ? 'active' : '' }}">Market-Place</a>
                    </div>
                @endif
            @else
                <!-- Menu Visiteur -->
                <div class="hidden md:flex space-x-10">
                    <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
                    <a href="{{ route('fonctionnalite') }}" class="navbar-link {{ request()->routeIs('fonctionnalite') ? 'active' : '' }}">Fonctionnalités</a>
                    <a href="{{ route('formation') }}" class="navbar-link {{ request()->routeIs('formation') ? 'active' : '' }}">Formations</a>
                    <a href="{{ route('market-place') }}" class="navbar-link {{ request()->routeIs('market-place') ? 'active' : '' }}">Market-Place</a>
                </div>
            @endif

            <!-- User Menu -->
            <div class="flex items-center">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center">
                            @if(Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="Photo de profil" class="h-8 w-8 rounded-full mr-2">
                            @else
                                <i class="fas fa-user-circle text-2xl mr-2"></i>
                            @endif
                            <span>{{ Auth::user()->prenom ?? Auth::user()->name ?? 'Utilisateur' }}</span>
                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tableau de bord</a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.contacts.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Messages de contact
                                    @php
                                        $unreadCount = \App\Models\Contact::where('statut', 'non_lu')->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full ml-2">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="mr-4 navbar-link">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary hover:bg-blue-700 transition duration-300">S'inscrire</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{'block': open, 'hidden': !open}" class="hidden md:hidden mt-4">
            <div class="flex flex-col space-y-3">                <!-- Contenu mobile en fonction du rôle -->
                @if(auth()->check())
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.functionalities.index') }}" class="navbar-link">Gérer les Installations</a>
                        <a href="{{ route('admin.formations.index') }}" class="navbar-link">Gérer les Formations</a>
                        <a href="{{ route('admin.installations.index') }}" class="navbar-link">Gérer les Devis</a>
                        <a href="{{ route('admin.products.index') }}" class="navbar-link">Gérer les Produits</a>
                    @elseif(auth()->user()->role === 'technician')
                        <a href="{{ route('fonctionnalite') }}" class="navbar-link">Fonctionnalités</a>
                        <a href="{{ route('technician.installations') }}" class="navbar-link">Installations</a>
                        <a href="{{ route('technician.maintenance') }}" class="navbar-link">Maintenance</a>
                    @else
                        <a href="{{ route('fonctionnalite') }}" class="navbar-link">Fonctionnalités</a>
                        <a href="{{ route('formation') }}" class="navbar-link">Formations</a>
                        <a href="{{ route('installation') }}" class="navbar-link">Devis-Installations</a>
                        <a href="{{ route('market-place') }}" class="navbar-link">Market-Place</a>
                    @endif
                @else
                    <a href="{{ route('fonctionnalite') }}" class="navbar-link">Fonctionnalités</a>
                    <a href="{{ route('formation') }}" class="navbar-link">Formations</a>
                    <a href="{{ route('market-place') }}" class="navbar-link">Market-Place</a>
                @endif
            </div>
        </div>
    </div>
</nav>
