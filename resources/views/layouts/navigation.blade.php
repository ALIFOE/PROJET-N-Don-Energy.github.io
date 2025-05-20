<nav x-data="{ open: false, mobileMenu: false }" class="bg-white border-b border-gray-100 fixed top-0 left-0 right-0 z-50">
    <head>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
        <style>
            .nav-icon {
                color: var(--primary-color, #FFA500);
            }
            .nav-brand {
                color: var(--dark-color, #000000);
            }
            .dropdown-item:hover {
                background-color: rgba(255, 165, 0, 0.1);
                color: var(--primary-color, #FFA500);
            }
            .mobile-menu {
                transform: translateY(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .mobile-menu.active {
                transform: translateY(0);
            }
        </style>
    </head>
    <div class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <!-- Bouton Menu Mobile -->
            <button @click="mobileMenu = !mobileMenu" class="md:hidden text-gray-500 hover:text-gray-600">
                <i class="fas fa-bars text-2xl"></i>
            </button>
            <!-- Logo et Nom -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <i class="fas fa-solar-panel nav-icon text-2xl mr-2"></i>
                    <span class="text-xl font-bold nav-brand">CREFER</span>
                </a>
            </div>
            <!-- Navigation Links -->
            @if(auth()->check())
                @if(auth()->user()->isAdmin())
                    @include('layouts.admin-navigation')
                @elseif(auth()->user()->role === 'technician')
                    @include('layouts.technician-navigation')
                @else
                    <!-- Menu Client -->                    <div class="hidden md:flex space-x-10">
                        <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>                        <a href="{{ route('fonctionnalite') }}" class="navbar-link {{ request()->routeIs('fonctionnalite') ? 'active' : '' }}">Fonctionnalités</a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="navbar-link inline-flex items-center {{ request()->routeIs('services.*') || request()->routeIs('client.demandes-services.*') ? 'active' : '' }}">
                                Services
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('services.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nos services</a>
                                <a href="{{ route('client.demandes-services.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mes demandes</a>
                                <a href="{{ route('ia-services') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-robot mr-2"></i>Services IA
                                </a>
                            </div>
                        </div>                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="navbar-link inline-flex items-center {{ request()->routeIs('formation*') ? 'active' : '' }}">
                                Formations
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('formation') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Formations à CREFER</a>
                                <a href="{{ route('formations.mes-inscriptions') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mes inscriptions</a>
                            </div>                        </div>                        <a href="{{ route('installation') }}" class="navbar-link {{ request()->routeIs('installation') ? 'active' : '' }}">Devis</a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="navbar-link inline-flex items-center {{ request()->routeIs('marketplace') || request()->routeIs('mes-commandes') ? 'active' : '' }}">
                                Boutique EGENT
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('marketplace') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Catalogue</a>
                                <a href="{{ route('mes-commandes') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mes commandes</a>
                            </div>
                        </div>
                        <a href="{{ route('contact') }}" class="navbar-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                    </div>
                @endif
            @else
                <!-- Menu Visiteur -->
                <div class="hidden md:flex space-x-10">
                    <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>                    <a href="{{ route('fonctionnalite') }}" class="navbar-link {{ request()->routeIs('fonctionnalite') ? 'active' : '' }}">Fonctionnalités</a>
                    <a href="{{ route('formation') }}" class="navbar-link {{ request()->routeIs('formation') ? 'active' : '' }}">Formations à CREFER</a>
                    <a href="{{ route('marketplace') }}" class="navbar-link {{ request()->routeIs('marketplace') ? 'active' : '' }}">Boutique EGENT</a>
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
                        <a href="{{ route('admin.notifications.index') }}" class="navbar-link">Notifications</a>
                        <a href="{{ route('admin.formations.index') }}" class="navbar-link">Gérer les Formations</a>
                        <a href="{{ route('admin.installations.index') }}" class="navbar-link">Gérer les Devis</a>
                        <a href="{{ route('admin.products.index') }}" class="navbar-link">Gérer les Produits</a>
                        <a href="{{ route('admin.users.index') }}" class="navbar-link">Gérer les Utilisateurs</a>
                    @elseif(auth()->user()->role === 'technician')
                        <a href="{{ route('formation') }}" class="navbar-link">Formations à CREFER</a>
                        <a href="{{ route('installation') }}" class="navbar-link">Devis</a>
                        <a href="{{ route('technician.installations') }}" class="navbar-link">Installations</a>
                        <a href="{{ route('technician.maintenance') }}" class="navbar-link">Maintenance</a>                    @elseif (auth()->user()->role === 'client')
                        <a href="{{ route('home') }}" class="navbar-link">Accueil</a>
                        <a href="{{ route('fonctionnalite') }}" class="navbar-link">Fonctionnalités</a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full py-2 text-left navbar-link">
                                Services
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>
                            <div x-show="open" class="pl-4">
                                <a href="{{ route('services.index') }}" class="block py-2 navbar-link">Nos services</a>
                                <a href="{{ route('client.demandes-services.index') }}" class="block py-2 navbar-link">Mes demandes</a>
                                <a href="{{ route('ia-services') }}" class="block py-2 navbar-link">
                                    <i class="fas fa-robot mr-2"></i>Services IA
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('formation') }}" class="navbar-link">Formations à CREFER</a>
                        <a href="{{ route('installation') }}" class="navbar-link">Devis</a>
                        <a href="{{ route('marketplace') }}" class="navbar-link">Boutique EGENT</a>
                        <a href="{{ route('contact') }}" class="navbar-link">Contact</a>
                    @endif
                @else
                    <a href="{{ route('home') }}" class="navbar-link">Accueil</a>
                    <a href="{{ route('fonctionnalite') }}" class="navbar-link">Fonctionnalités</a>                    <a href="{{ route('formation') }}" class="navbar-link">Formations à CREFER</a>
                    <a href="{{ route('installation') }}" class="navbar-link">Devis</a>
                    <a href="{{ route('marketplace') }}" class="navbar-link">Boutique EGENT</a>
                    <a href="{{ route('contact') }}" class="navbar-link">Contact</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div x-show="mobileMenu" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-full"
         class="md:hidden bg-white border-t border-gray-200">
        <div class="px-6 py-4">
            <!-- Logo dans le menu mobile -->
            <div class="flex items-center justify-center mb-6">
                <a href="{{ route('home') }}" class="flex items-center">
                    <i class="fas fa-solar-panel nav-icon text-3xl mr-2"></i>
                    <span class="text-2xl font-bold nav-brand">CREFER</span>
                </a>
            </div>

            <!-- Liens de navigation mobile -->
            @if(auth()->check())
                @if(auth()->user()->isAdmin())
                    <!-- Menu Admin Mobile -->
                    <div class="space-y-4">
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 text-base">
                            <i class="fas fa-chart-line mr-2"></i>Tableau de bord
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="block py-2 text-base">
                            <i class="fas fa-bell mr-2"></i>Notifications
                        </a>
                    </div>
                @elseif(auth()->user()->role === 'technician')
                    <!-- Menu Technicien Mobile -->
                    <div class="space-y-4">
                        <a href="{{ route('technician.installations') }}" class="block py-2 text-base">Installations</a>
                        <a href="{{ route('technician.maintenance') }}" class="block py-2 text-base">Maintenance</a>
                    </div>
                @else                    <!-- Menu Client Mobile -->
                    <div class="space-y-4">
                        <a href="{{ route('home') }}" class="block py-2 text-base">Accueil</a>
                        <a href="{{ route('fonctionnalite') }}" class="block py-2 text-base">Fonctionnalités</a>                        <a href="{{ route('services.index') }}" class="block py-2 text-base">Services</a>
                        <a href="{{ route('formation') }}" class="block py-2 text-base">Formations</a>                        <a href="{{ route('installation') }}" class="block py-2 text-base">Devis</a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full py-2 text-left">
                                Boutique EGENT
                                <i class="fas fa-chevron-down ml-2 text-sm"></i>
                            </button>
                            <div x-show="open" class="pl-4">
                                <a href="{{ route('marketplace') }}" class="block py-2 text-base">Catalogue</a>
                                <a href="{{ route('mes-commandes') }}" class="block py-2 text-base">Mes commandes</a>
                            </div>
                        </div>
                        <a href="{{ route('contact') }}" class="block py-2 text-base">Contact</a>
                    </div>
                @endif
            @else
                <!-- Menu Visiteur Mobile -->                    <div class="space-y-4">
                        <a href="{{ route('home') }}" class="block py-2 text-base">Accueil</a>
                        <a href="{{ route('fonctionnalite') }}" class="block py-2 text-base">Fonctionnalités</a>
                        <a href="{{ route('formation') }}" class="block py-2 text-base">Formations</a>
                        <a href="{{ route('marketplace') }}" class="block py-2 text-base">Boutique EGENT</a>
                </div>
            @endif
        </div>
    </div>
</nav>
