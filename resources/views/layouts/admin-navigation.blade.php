<!-- Menu Administrateur -->
<div class="hidden md:flex space-x-10">
    <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
    
    <div class="relative group" x-data="{ open: false }" @mouseover="open = true" @mouseleave="open = false">
        <button class="navbar-link flex items-center" @click="open = !open">
            Fonctionnalités
            <i class="fas fa-chevron-down ml-1 transition-transform" :class="{ 'transform rotate-180': open }"></i>
        </button>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.functionalities.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gérer les Fonctionnalités</a>
                <a href="{{ route('admin.functionalities.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ajouter une Fonctionnalité</a>
            </div>
        </div>
    </div>    <div class="relative group" x-data="{ open: false }" @mouseover="open = true" @mouseleave="open = false">
        <button class="navbar-link flex items-center" @click="open = !open">
            Formations
            <i class="fas fa-chevron-down ml-1 transition-transform" :class="{ 'transform rotate-180': open }"></i>
        </button>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.formations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gérer les Formations</a>
                <a href="{{ route('admin.formations.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ajouter une Formation</a>
                <a href="{{ route('admin.formations.inscriptions') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Inscriptions</a>
            </div>
        </div>
    </div>    <div class="relative group" x-data="{ open: false }" @mouseover="open = true" @mouseleave="open = false">
        <button class="navbar-link flex items-center" @click="open = !open">
            Devis-Installations
            <i class="fas fa-chevron-down ml-1 transition-transform" :class="{ 'transform rotate-180': open }"></i>
        </button>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.installations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gérer les Devis</a>
                <a href="{{ route('admin.installations.pending') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Devis en Attente</a>
            </div>
        </div>
    </div>

    <div class="relative group" x-data="{ open: false }" @mouseover="open = true" @mouseleave="open = false">
        <button class="navbar-link flex items-center" @click="open = !open">
            Market-Place
            <i class="fas fa-chevron-down ml-1 transition-transform" :class="{ 'transform rotate-180': open }"></i>
        </button>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
            <div class="py-1">
                <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gérer les Produits</a>
                <a href="{{ route('admin.products.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ajouter un Produit</a>
                <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Commandes</a>
            </div>
        </div>
    </div>
</div>