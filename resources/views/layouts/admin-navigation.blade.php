<!-- Menu Administrateur -->
<nav class="flex items-center justify-center w-full">
    <!-- Logo -->
    {{-- <div class="flex items-center">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center mr-8">
            <i class="fas fa-solar-panel nav-icon text-2xl mr-2"></i>
            <span class="text-xl font-bold nav-brand">CREFER</span>
        </a>
    </div> --}}
    
    <!-- Navigation Items -->
    <div class="hidden md:flex space-x-8">
    <!-- <a href="{{ route('admin.dashboard') }}" class="navbar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> Tableau de bord
    </a> -->
    {{-- <a href="{{ route('admin.notifications.index') }}" class="navbar-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
        <i class="fas fa-bell"></i> 
        Notifications
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </a> --}}

    {{-- <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link">
            <i class="fas fa-cogs"></i> Services
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.services.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-list mr-2"></i>Liste des services
                </a>
                <a href="{{ route('admin.services.status') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-tachometer-alt mr-2"></i>État des services IA
                </a>
            </div>
        </div>
    </div> --}}

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link">
            <i class="fas fa-file-invoice-dollar"></i> Devis
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.devis.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-list mr-2"></i>Liste des devis
                </a>
            </div>
        </div>
    </div>

    <!-- Formations -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link inline-flex items-center">
            <i class="fas fa-graduation-cap"></i>
            <span class="ml-2">Formations</span>
            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
        <div x-show="open" 
             @click.away="open = false"
             class="absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
            <a href="{{ route('admin.formations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Liste des formations
            </a>
            <a href="{{ route('admin.formations.inscriptions.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Liste des inscriptions
            </a>
        </div>
    </div>

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link">
            <i class="fas fa-shopping-cart"></i> Boutique
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gérer les Produits</a>
                <a href="{{ route('admin.products.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ajouter un Produit</a>
                <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Commandes</a>
            </div>
        </div>
    </div>

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link">
            <i class="fas fa-concierge-bell"></i> Services
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">            <div class="py-1">
                <a href="{{ route('admin.services.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-list mr-2"></i>Liste des services
                </a>
                <a href="{{ route('admin.services.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-plus mr-2"></i>Nouveau service
                </a>
                <a href="{{ route('admin.services.requests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-inbox mr-2"></i>Demandes de services
                </a>
                <a href="{{ route('admin.services.status') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-tachometer-alt mr-2"></i>État des services IA
                </a>
            </div>
        </div>
    </div>    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link">
            <i class="fas fa-images"></i> Galerie
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('gallery') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-eye mr-2"></i>Voir la galerie
                </a>
                <a href="{{ route('admin.gallery.manage') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cog mr-2"></i>Gérer la galerie
                </a>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.users.index') }}" class="navbar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Utilisateurs
    </a>
</div>