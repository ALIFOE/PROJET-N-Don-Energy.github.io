<x-app-layout>
    <x-slot name="header">
                                       {{-- <a href="{{ route('dimensionnements.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                                        </svg>
                                        {{ __("Nouvelle demande") }}
                                    </a>h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2> --}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <!-- Bienvenue -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            @auth
                                <h1 class="text-2xl font-semibold">{{ __("Bienvenue sur votre tableau de bord, ") }} {{ Auth::user()->prenom ?? Auth::user()->name ?? 'Utilisateur' }}!</h1>
                            @else
                                {{ __("Veuillez vous connecter pour accéder au tableau de bord.") }}
                            @endauth
                        </div>
                    </div>

                    @auth
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Section Demande de dimensionnement -->
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold mb-4">{{ __("Demande de dimensionnement") }}</h2>
                                    <p class="mb-4">{{ __("Faites une demande de dimensionnement pour votre installation solaire.") }}</p>
                                    
                                    @if(session('dimensionnement_success'))
                                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                            {{ session('dimensionnement_success') }}
                                        </div>
                                    @endif
                                      <a href="{{ route('dimensionnements.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" />
                                        </svg>
                                        {{ __("Nouvelle demande") }}
                                    </a>
                                    
                                    <div class="mt-4">
                                        <h3 class="text-lg font-medium mb-2">{{ __("Mes demandes récentes") }}</h3>
                                        @if(isset($dimensionnements) && $dimensionnements->count() > 0)
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Date") }}</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Statut") }}</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Actions") }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @foreach($dimensionnements as $dimensionnement)
                                                            <tr>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $dimensionnement->created_at->format('d/m/Y') }}</td>
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                        {{ $dimensionnement->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 
                                                                           ($dimensionnement->statut === 'validé' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                                        {{ ucfirst(str_replace('_', ' ', $dimensionnement->statut)) }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                                    <a href="{{ route('dimensionnements.show', $dimensionnement) }}" class="text-blue-600 hover:text-blue-900">{{ __("Voir") }}</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ route('dimensionnements.index') }}" class="text-blue-600 hover:text-blue-800">{{ __("Voir toutes mes demandes") }}</a>
                                            </div>
                                        @else
                                            <p class="text-gray-500">{{ __("Vous n'avez pas encore fait de demande de dimensionnement.") }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Section Onduleurs -->
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold mb-4">{{ __("Mes onduleurs") }}</h2>
                                    <p class="mb-4">{{ __("Gérez vos onduleurs et surveillez leurs performances.") }}</p>
                                    
                                    @if(session('onduleur_success'))
                                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                            {{ session('onduleur_success') }}
                                        </div>
                                    @endif
                                    
                                    <a href="{{ route('onduleurs.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13 7h-2v2h2V7zm0 4h-2v2h2v-2zm2-1a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6a1 1 0 0 1 1-1h2V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6h2zm-6-6v6h4V4h-4z" />
                                        </svg>
                                        {{ __("Connecter un onduleur") }}
                                    </a>
                                    
                                    <div class="mt-4">
                                        @if(count(Auth::user()->onduleurs ?? []) > 0)
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                @foreach(Auth::user()->onduleurs()->latest()->take(2)->get() as $onduleur)
                                                    <div class="border rounded-lg p-4 {{ $onduleur->est_connecte ? 'border-green-500' : 'border-gray-300' }}">
                                                        <div class="flex justify-between items-center mb-2">
                                                            <h3 class="font-medium">{{ $onduleur->modele }}</h3>
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $onduleur->est_connecte ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ $onduleur->est_connecte ? __('Connecté') : __('Déconnecté') }}
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-gray-500 mb-2">{{ __("ID: ") }} {{ $onduleur->numero_serie }}</p>
                                                        <div class="flex justify-between mt-2">
                                                            <a href="{{ route('onduleurs.show', $onduleur->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">{{ __("Détails") }}</a>
                                                            <form action="{{ route('onduleurs.toggle-connection', $onduleur->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="text-sm text-{{ $onduleur->est_connecte ? 'red' : 'green' }}-600 hover:text-{{ $onduleur->est_connecte ? 'red' : 'green' }}-800">
                                                                    {{ $onduleur->est_connecte ? __('Déconnecter') : __('Connecter') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ route('onduleurs.index') }}" class="text-blue-600 hover:text-blue-800">{{ __("Voir tous mes onduleurs") }}</a>
                                            </div>
                                        @else
                                            <p class="text-gray-500">{{ __("Vous n'avez pas encore connecté d'onduleur.") }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>                        <!-- Section Rapports et Analyses -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold mb-4">{{ __("Rapports et Analyses") }}</h2>
                                    <p class="mb-4">{{ __("Consultez et générez des rapports détaillés sur vos installations.") }}</p>
                                    
                                    <div class="grid grid-cols-1 gap-4">
                                        @if(count(Auth::user()->onduleurs()->where('est_connecte', true)->get()) > 0)
                                            <a href="{{ route('rapports-analyses') }}" 
                                               class="inline-flex items-center justify-center px-4 py-4 bg-green-600 border border-transparent rounded-lg font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300 ease-in-out">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ __("Accéder aux Rapports") }}
                                            </a>
                                        @else
                                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm text-yellow-700">
                                                            {{ __("Vous devez d'abord connecter au moins un onduleur pour accéder aux rapports et analyses.") }}
                                                        </p>
                                                        <div class="mt-4">
                                                            <a href="{{ route('onduleurs.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                                </svg>
                                                                {{ __("Connecter un onduleur") }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif<div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                            <h3 class="font-medium text-blue-800 mb-2">{{ __("Rapports disponibles") }}</h3>
                                            <ul class="list-disc list-inside text-sm text-blue-700 space-y-1">
                                                <li>{{ __("Rapports de production") }}
                                                    <ul class="ml-6 mt-1 list-disc list-inside text-sm text-blue-600">
                                                        <li>{{ __("Production journalière") }}</li>
                                                        <li>{{ __("Production mensuelle") }}</li>
                                                        <li>{{ __("Production annuelle") }}</li>
                                                    </ul>
                                                </li>
                                                <li class="mt-2">{{ __("Analyses de performance") }}
                                                    <ul class="ml-6 mt-1 list-disc list-inside text-sm text-blue-600">
                                                        <li>{{ __("Rendement des onduleurs") }}</li>
                                                        <li>{{ __("Efficacité énergétique") }}</li>
                                                        <li>{{ __("Comparaison périodique") }}</li>
                                                    </ul>
                                                </li>
                                                <li class="mt-2">{{ __("Statistiques détaillées") }}
                                                    <ul class="ml-6 mt-1 list-disc list-inside text-sm text-blue-600">
                                                        <li>{{ __("Économies réalisées (XOF)") }}</li>
                                                        <li>{{ __("Impact environnemental") }}</li>
                                                        <li>{{ __("Prévisions de maintenance") }}</li>
                                                        <li>{{ __("Historique des incidents") }}</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Activités Récentes -->
                        {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold">{{ __("Mes activités récentes") }}</h2>
                                    <div class="flex gap-4">
                                        <!-- Filtre par type d'action -->
                                        <select name="action" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                                onchange="window.location.href=`{{ route('dashboard') }}?action=${this.value}&date={{ request('date') }}`">
                                            <option value="">{{ __("Toutes les actions") }}</option>
                                            <option value="création" {{ request('action') === 'création' ? 'selected' : '' }}>{{ __("Créations") }}</option>
                                            <option value="modification" {{ request('action') === 'modification' ? 'selected' : '' }}>{{ __("Modifications") }}</option>
                                            <option value="suppression" {{ request('action') === 'suppression' ? 'selected' : '' }}>{{ __("Suppressions") }}</option>
                                            <option value="connexion" {{ request('action') === 'connexion' ? 'selected' : '' }}>{{ __("Connexions") }}</option>
                                        </select>

                                        <!-- Filtre par date -->
                                        <select name="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                onchange="window.location.href=`{{ route('dashboard') }}?date=${this.value}&action={{ request('action') }}`">
                                            <option value="">{{ __("Toutes les dates") }}</option>
                                            <option value="aujourd'hui" {{ request('date') === 'aujourd\'hui' ? 'selected' : '' }}>{{ __("Aujourd'hui") }}</option>
                                            <option value="semaine" {{ request('date') === 'semaine' ? 'selected' : '' }}>{{ __("Cette semaine") }}</option>
                                            <option value="mois" {{ request('date') === 'mois' ? 'selected' : '' }}>{{ __("Ce mois") }}</option>
                                        </select>

                                        <!-- Bouton de réinitialisation des filtres -->
                                        @if(request('action') || request('date'))
                                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                {{ __("Réinitialiser") }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                @if(isset($activites) && $activites->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Date") }}</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Action") }}</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __("Description") }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($activites as $activite)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $activite->created_at->format('d/m/Y H:i') }}</td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                                @switch($activite->action)
                                                                    @case('création')
                                                                        bg-green-100 text-green-800
                                                                        @break
                                                                    @case('modification')
                                                                        bg-blue-100 text-blue-800
                                                                        @break
                                                                    @case('suppression')
                                                                        bg-red-100 text-red-800
                                                                        @break
                                                                    @default
                                                                        bg-gray-100 text-gray-800
                                                                @endswitch">
                                                                {{ ucfirst($activite->action) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $activite->description }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($activites->hasPages())
                                        <div class="mt-4">
                                            {{ $activites->links() }}
                                        </div>
                                    @endif
                                @else
                                    <p class="text-gray-500">{{ __("Aucune activité récente à afficher.") }}</p>
                                @endif
                            </div>
                        </div> --}}

                        <!-- Performance des onduleurs en temps réel -->
                        @if(count(Auth::user()->onduleurs()->where('est_connecte', true)->get()) > 0)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold mb-4">{{ __("Performance des onduleurs en temps réel") }}</h2>
                                    
                                    <div id="onduleur-performance-container" class="space-y-6">
                                        @foreach(Auth::user()->onduleurs()->where('est_connecte', true)->get() as $onduleur)
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h3 class="text-lg font-medium">{{ $onduleur->modele }} ({{ $onduleur->numero_serie }})</h3>
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800" id="status-{{ $onduleur->id }}">
                                                        {{ __("En ligne") }}
                                                    </span>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <!-- Production actuelle -->
                                                    <div class="bg-blue-50 rounded-lg p-4">
                                                        <p class="text-sm text-gray-500 mb-1">{{ __("Production actuelle") }}</p>
                                                        <div class="flex items-end">
                                                            <span class="text-2xl font-bold text-blue-600" id="production-{{ $onduleur->id }}">-- </span>
                                                            <span class="text-blue-600 ml-1">kW</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Production journalière -->
                                                    <div class="bg-green-50 rounded-lg p-4">
                                                        <p class="text-sm text-gray-500 mb-1">{{ __("Production journalière") }}</p>
                                                        <div class="flex items-end">
                                                            <span class="text-2xl font-bold text-green-600" id="daily-{{ $onduleur->id }}">-- </span>
                                                            <span class="text-green-600 ml-1">kWh</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Efficacité -->
                                                    <div class="bg-yellow-50 rounded-lg p-4">
                                                        <p class="text-sm text-gray-500 mb-1">{{ __("Efficacité") }}</p>
                                                        <div class="flex items-end">
                                                            <span class="text-2xl font-bold text-yellow-600" id="efficiency-{{ $onduleur->id }}">-- </span>
                                                            <span class="text-yellow-600 ml-1">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-4">
                                                    <h4 class="text-md font-medium mb-2">{{ __("Graphique de production") }}</h4>
                                                    <div class="w-full h-60 bg-gray-50 rounded-lg flex items-center justify-center" id="chart-{{ $onduleur->id }}">
                                                        <p class="text-gray-400">{{ __("Chargement des données...") }}</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex justify-end mt-4">
                                                    <a href="{{ route('onduleurs.performance', $onduleur->id) }}" class="text-blue-600 hover:text-blue-800">
                                                        {{ __("Voir les statistiques détaillées") }}
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            @auth
                @if(count(Auth::user()->onduleurs()->where('est_connecte', true)->get()) > 0)
                    @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Fonction pour simuler les données en temps réel
                            function fetchOnduleurData() {
                                @foreach(Auth::user()->onduleurs()->where('est_connecte', true)->get() as $onduleur)
                                    // Simuler la production actuelle (entre 2 et 7 kW)
                                    const production = (Math.random() * 5 + 2).toFixed(2);
                                    document.getElementById('production-{{ $onduleur->id }}').textContent = production;

                                    // Simuler la production journalière (entre 10 et 30 kWh)
                                    const daily = (Math.random() * 20 + 10).toFixed(1);
                                    document.getElementById('daily-{{ $onduleur->id }}').textContent = daily;

                                    // Simuler l'efficacité (entre 85% et 95%)
                                    const efficiency = (Math.random() * 10 + 85).toFixed(1);
                                    document.getElementById('efficiency-{{ $onduleur->id }}').textContent = efficiency;

                                    // Simuler le statut (en ligne/hors ligne)
                                    const status = document.getElementById('status-{{ $onduleur->id }}');
                                    if (Math.random() > 0.1) { // 90% de chance d'être en ligne
                                        status.textContent = "{{ __('En ligne') }}";
                                        status.className = "px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800";
                                    } else {
                                        status.textContent = "{{ __('Hors ligne') }}";
                                        status.className = "px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800";
                                    }
                                @endforeach
                            }
                            
                            // Mettre à jour toutes les 5 secondes
                            fetchOnduleurData();
                            setInterval(fetchOnduleurData, 5000);
                        });
                    </script>
                    @endpush
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>