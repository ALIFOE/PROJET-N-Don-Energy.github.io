<div class="bg-white shadow-sm rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Menu Onduleur</h3>
    <nav class="space-y-2">
        <a href="{{ route('onduleurs.index') }}" 
           class="block px-4 py-2 rounded-md {{ request()->routeIs('onduleurs.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-solar-panel mr-2"></i>
            Liste des onduleurs
        </a>
        
        <a href="{{ route('onduleur.config') }}" 
           class="block px-4 py-2 rounded-md {{ request()->routeIs('onduleur.config') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-cog mr-2"></i>
            Configuration
        </a>
        
        <a href="{{ route('rapports-analyses') }}" 
           class="block px-4 py-2 rounded-md {{ request()->routeIs('rapports-analyses') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-chart-line mr-2"></i>
            Rapports & Analyses
        </a>
        
        <a href="{{ route('maintenance-predictive') }}" 
           class="block px-4 py-2 rounded-md {{ request()->routeIs('maintenance-predictive') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i class="fas fa-tools mr-2"></i>
            Maintenance prédictive
        </a>
    </nav>

    <div class="mt-6 pt-6 border-t">
        <h4 class="text-sm font-medium text-gray-600 mb-3">État du système</h4>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Connexion</span>
                <span class="px-2 py-1 text-xs rounded-full {{ $connected ?? false ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $connected ?? false ? 'Connecté' : 'Déconnecté' }}
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Dernière mise à jour</span>
                <span class="text-sm text-gray-800">{{ $lastUpdate ?? 'Jamais' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Type d'onduleur</span>
                <span class="text-sm text-gray-800">{{ ucfirst(config('inverters.default')) }}</span>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('onduleur.config') }}" class="block w-full px-4 py-2 text-center text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">
            <i class="fas fa-wrench mr-2"></i>
            Configurer l'onduleur
        </a>
    </div>
</div>