<!-- Menu Technicien -->
<div class="flex items-center">
    <a href="{{ route('home') }}" class="flex items-center mr-8">
        <i class="fas fa-solar-panel nav-icon text-2xl mr-2"></i>
        <span class="text-xl font-bold nav-brand">CREFER</span>
    </a>
</div>
<div class="hidden md:flex space-x-10">
    <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
    <a href="{{ route('fonctionnalite') }}" class="navbar-link {{ request()->routeIs('fonctionnalite') ? 'active' : '' }}">Fonctionnalités</a>
    <a href="{{ route('technician.installations') }}" class="navbar-link {{ request()->routeIs('technician.installations') ? 'active' : '' }}">Installations</a>
    <a href="{{ route('technician.maintenance') }}" class="navbar-link {{ request()->routeIs('technician.maintenance') ? 'active' : '' }}">Maintenance</a>
</div>