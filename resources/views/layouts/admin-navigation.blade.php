<!-- Menu Administrateur -->
<div class="hidden md:flex space-x-8">
    <a href="{{ route('admin.dashboard') }}" class="navbar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i> Tableau de bord
    </a>

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link">
            <i class="fas fa-solar-panel"></i> Installations
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.installations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Voir les installations</a>
                <a href="{{ route('admin.installations.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nouvelle installation</a>
            </div>
        </div>
    </div>

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="navbar-link">
            <i class="fas fa-graduation-cap"></i> Formations
            <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.formations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Voir les formations</a>
                <a href="{{ route('admin.formations.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nouvelle formation</a>
            </div>
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

    <a href="{{ route('admin.users.index') }}" class="navbar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Utilisateurs
    </a>
</div>