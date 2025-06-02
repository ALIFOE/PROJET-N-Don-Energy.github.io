<!-- Menu Administrateur -->
<nav class="flex items-center justify-between w-full">
    <!-- Logo -->
    {{-- <div class="flex items-center">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center mr-8">
            <i class="fas fa-solar-panel nav-icon text-2xl mr-2"></i>
            <span class="text-xl font-bold nav-brand">CREFER</span>
        </a>
    </div> --}}
      <!-- Navigation Items -->    <div class="hidden md:flex space-x-8 justify-center w-full">
    <!-- <a href="{{ route('admin.dashboard') }}" class="navbar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> Tableau de bord
    </a> -->

    <!-- Devis -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link relative">
            <i class="fas fa-file-invoice-dollar"></i> Devis
            <livewire:admin-notification-counter :count="$devisCount" />
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="dropdown-menu absolute mt-2 w-48 rounded-md z-50">
            <div class="py-1">
                <a href="{{ route('admin.devis.index') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">
                    <i class="fas fa-list mr-2"></i>Liste des devis
                </a>
            </div>
        </div>
    </div>

    <!-- Formations -->
    <div class="relative" x-data="{ open: false }">        <button @click="open = !open" class="navbar-link relative">
            <i class="fas fa-graduation-cap"></i>
            <span>Formations</span>
            <livewire:admin-notification-counter :count="$formationsCount" />
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="dropdown-menu absolute left-0 mt-2 w-48 rounded-md py-1">
            <a href="{{ route('admin.formations.index') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">
                <i class="fas fa-list mr-2"></i>Liste des formations
            </a>
            <a href="{{ route('admin.formations.inscriptions.index') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">
                <i class="fas fa-user-graduate mr-2"></i>Liste des inscriptions
            </a>
        </div>
    </div>

    <!-- Boutique -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link relative">            <i class="fas fa-shopping-cart"></i> Boutique
            <livewire:admin-notification-counter :count="$boutiqueCount" />
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="dropdown-menu absolute mt-2 w-48 rounded-md z-50">
            <div class="py-1">
                <a href="{{ route('admin.products.index') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">Gérer les Produits</a>
                <a href="{{ route('admin.products.create') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">Ajouter un Produit</a>
                <a href="{{ route('admin.orders.index') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">Commandes</a>
            </div>
        </div>
    </div>

    <!-- Services -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link relative">            <i class="fas fa-concierge-bell"></i> Services
            <livewire:admin-notification-counter :count="$servicesCount" />
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 rounded-md shadow-sm bg-transparent backdrop-blur-sm ring-1 ring-white ring-opacity-20 z-50">
            <div class="py-1">
                <a href="{{ route('admin.services.index') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">
                    <i class="fas fa-list mr-2"></i>Liste des services
                </a>
                <a href="{{ route('admin.services.create') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">
                    <i class="fas fa-plus mr-2"></i>Nouveau service
                </a>
                <a href="{{ route('admin.services.requests') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">
                    <i class="fas fa-inbox mr-2"></i>Demandes de services
                </a>
                <a href="{{ route('admin.services.status') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">
                    <i class="fas fa-tachometer-alt mr-2"></i>État des services IA
                </a>
            </div>
        </div>
    </div>

    <!-- Galerie -->
    <div class="relative" x-data="{ open: false }">
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

    <!-- Utilisateurs -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link relative">            <i class="fas fa-users"></i> Utilisateurs
            <livewire:admin-notification-counter :count="$usersCount" />
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="dropdown-menu absolute mt-2 w-48 rounded-md z-50">
            <div class="py-1">
                <a href="{{ route('admin.users.index') }}" class="dropdown-item block px-4 py-2 text-sm text-gray-700">
                    <i class="fas fa-list mr-2"></i>Liste des utilisateurs
                </a>
            </div>
        </div>    </div>
</div>