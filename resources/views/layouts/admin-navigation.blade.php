@auth
    @if(auth()->user()->role === 'admin')
        <!-- Menu Administrateur -->
        <nav class="w-full bg-white shadow-lg border-b border-gray-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center h-16 relative">
                <!-- Logo -->
                <div class="flex items-center absolute left-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center mr-8">
                        <i class="fas fa-solar-panel nav-icon text-2xl text-yellow-500 mr-2"></i>
                        <span class="text-xl font-bold nav-brand text-gray-800">CREFER</span>
                    </a>
                </div>
                <!-- Navigation Items (centré) -->
                <div class="flex-1 flex justify-center">
                    <div class="flex space-x-6">
                        <!-- Devis -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button @click.prevent.stop="open = !open" class="navbar-link flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-yellow-100 transition relative">
                                <i class="fas fa-file-invoice-dollar mr-2"></i> Devis
                                @if(isset($devisCount) && $devisCount > 0)
                                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $devisCount }}</span>
                                @endif
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <a href="{{ route('admin.devis.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">
                                    <i class="fas fa-list mr-2"></i>Liste des devis
                                </a>
                            </div>
                        </div>
                        <!-- Formations -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button @click.prevent.stop="open = !open" class="navbar-link flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-yellow-100 transition relative">
                                <i class="fas fa-graduation-cap mr-2"></i> Formations
                                @if(isset($inscriptionsCount) && $inscriptionsCount > 0)
                                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $inscriptionsCount }}</span>
                                @endif
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <a href="{{ route('admin.formations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">Liste des formations</a>
                                <a href="{{ route('admin.formations.inscriptions.index') }}" class="flex px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 items-center">
                                    Liste des inscriptions
                                    @if(isset($inscriptionsCount) && $inscriptionsCount > 0)
                                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $inscriptionsCount }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                        <!-- Boutique -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button @click.prevent.stop="open = !open" class="navbar-link flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-yellow-100 transition relative">
                                <i class="fas fa-shopping-cart mr-2"></i> Boutique
                                @if(isset($ordersCount) && $ordersCount > 0)
                                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $ordersCount }}</span>
                                @endif
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">Gérer les Produits</a>
                                <a href="{{ route('admin.products.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">Ajouter un Produit</a>
                                <a href="{{ route('admin.orders.index') }}" class="flex px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 items-center">
                                    Commandes
                                    @if(isset($ordersCount) && $ordersCount > 0)
                                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $ordersCount }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                        <!-- Services -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button @click.prevent.stop="open = !open" class="navbar-link flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-yellow-100 transition relative">
                                <i class="fas fa-concierge-bell mr-2"></i> Services
                                @if(isset($servicesRequestsCount) && $servicesRequestsCount > 0)
                                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $servicesRequestsCount }}</span>
                                @endif
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <a href="{{ route('admin.services.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50"><i class="fas fa-list mr-2"></i>Liste des services</a>
                                <a href="{{ route('admin.services.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50"><i class="fas fa-plus mr-2"></i>Nouveau service</a>
                                <a href="{{ route('admin.services.requests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 flex items-center">
                                    <i class="fas fa-inbox mr-2"></i>Demandes de services
                                    @if(isset($servicesRequestsCount) && $servicesRequestsCount > 0)
                                        <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $servicesRequestsCount }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.services.status') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50"><i class="fas fa-tachometer-alt mr-2"></i>État des services IA</a>
                            </div>
                        </div>
                        <!-- Galerie -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button @click.prevent.stop="open = !open" class="navbar-link flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-yellow-100 transition">
                                <i class="fas fa-images mr-2"></i> Galerie
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <a href="{{ route('gallery') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50"><i class="fas fa-eye mr-2"></i>Voir la galerie</a>
                                <a href="{{ route('admin.gallery.manage') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50"><i class="fas fa-cog mr-2"></i>Gérer la galerie</a>
                            </div>
                        </div>
                        <!-- Utilisateurs -->
                        <a href="{{ route('admin.users.index') }}" class="navbar-link flex items-center px-3 py-2 rounded-md text-gray-700 hover:bg-yellow-100 transition {{ request()->routeIs('admin.users.*') ? 'bg-yellow-200' : '' }}">
                            <i class="fas fa-users mr-2"></i> Utilisateurs
                        </a>
                    </div>
                </div>
                <!-- Profil à droite -->
                <div class="flex items-center absolute right-0">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center relative">
                            @if(auth()->user()->profile_photo_url ?? false)
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="Photo de profil" class="h-8 w-8 rounded-full mr-2">
                            @else
                                <i class="fas fa-user-circle text-2xl mr-2"></i>
                            @endif
                            <span>{{ auth()->user()->prenom ?? auth()->user()->name ?? 'Utilisateur' }}</span>
                            @if(isset($adminNotificationsCount) && $adminNotificationsCount > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full absolute -top-1 -right-3">{{ $adminNotificationsCount }}</span>
                            @endif
                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('admin.users.show', auth()->user()->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-yellow-50">Se déconnecter</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    @endif
@endauth
<script>
    // Exemple : surligner l'élément actif de la navigation
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.navbar-link');
        links.forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('bg-yellow-200', 'font-bold');
            }
        });
    });
    // Vous pouvez ajouter d'autres interactions JS ici pour la navigation
</script>