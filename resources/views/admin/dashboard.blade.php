@extends('admin.layouts.admin')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Tableau de bord administrateur</h1>
        <p class="mt-2 text-gray-600">Gérez votre entreprise et suivez vos activités</p>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Installations -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Installations totales</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['installations_count'] ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-solar-panel text-blue-500 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    <span class="text-green-500">
                        <i class="fas fa-arrow-up"></i>
                        {{ $stats['installations_this_month'] ?? 0 }}
                    </span>
                    ce mois
                </p>
            </div>
        </div>

        <!-- Formations -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Participants en formation</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['formations_participants'] ?? 0 }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-graduation-cap text-green-500 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    {{ $stats['formations_active'] ?? 0 }} formations actives
                </p>
            </div>
        </div>

        <!-- Commandes -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Commandes du mois</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['orders_this_month'] ?? 0 }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-shopping-cart text-purple-500 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    {{ number_format($stats['revenue_this_month'] ?? 0, 2, ',', ' ') }} € ce mois
                </p>
            </div>
        </div>

        <!-- Devis -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Devis en attente</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_quotes'] ?? 0 }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fas fa-file-invoice text-orange-500 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    {{ $stats['quotes_this_week'] ?? 0 }} nouveaux cette semaine
                </p>
            </div>
        </div>
    </div>

    <!-- Sections principales -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Gestion des installations -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Gestion des installations</h2>
                <div class="mt-4 space-y-4">
                    <a href="{{ route('admin.installations.index') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-list-ul w-6"></i>
                        <span>Liste des installations</span>
                        <span class="ml-auto text-gray-500">{{ $stats['installations_count'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.installations.pending') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-clock w-6"></i>
                        <span>Devis en attente</span>
                        <span class="ml-auto text-gray-500">{{ $stats['pending_quotes'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.installations.create') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-plus w-6"></i>
                        <span>Nouvelle installation</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestion des formations -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Gestion des formations</h2>
                <div class="mt-4 space-y-4">
                    <a href="{{ route('admin.formations.index') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-chalkboard-teacher w-6"></i>
                        <span>Liste des formations</span>
                        <span class="ml-auto text-gray-500">{{ $stats['formations_count'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.formations.inscriptions') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-users w-6"></i>
                        <span>Inscriptions</span>
                        <span class="ml-auto text-gray-500">{{ $stats['formations_participants'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.formations.create') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-plus w-6"></i>
                        <span>Nouvelle formation</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestion du Market-Place -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Gestion du Market-Place</h2>
                <div class="mt-4 space-y-4">
                    <a href="{{ route('admin.products.index') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-box w-6"></i>
                        <span>Catalogue produits</span>
                        <span class="ml-auto text-gray-500">{{ $stats['products_count'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-shopping-cart w-6"></i>
                        <span>Commandes</span>
                        <span class="ml-auto text-gray-500">{{ $stats['orders_count'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-plus w-6"></i>
                        <span>Nouveau produit</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestion des fonctionnalités -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Gestion des fonctionnalités</h2>
                <div class="mt-4 space-y-4">
                    <a href="{{ route('admin.functionalities.index') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-puzzle-piece w-6"></i>
                        <span>Liste des fonctionnalités</span>
                        <span class="ml-auto text-gray-500">{{ $stats['functionalities_count'] ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.functionalities.create') }}" class="flex items-center text-gray-700 hover:text-blue-500">
                        <i class="fas fa-plus w-6"></i>
                        <span>Nouvelle fonctionnalité</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides et notifications -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow col-span-2">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Actions rapides</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('admin.installations.create') }}" 
                       class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100">
                        <i class="fas fa-plus-circle text-blue-500 text-2xl mb-2"></i>
                        <span class="text-sm text-center">Nouvelle installation</span>
                    </a>
                    <a href="{{ route('admin.products.create') }}" 
                       class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100">
                        <i class="fas fa-box-open text-purple-500 text-2xl mb-2"></i>
                        <span class="text-sm text-center">Ajouter un produit</span>
                    </a>
                    <a href="{{ route('admin.formations.create') }}" 
                       class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100">
                        <i class="fas fa-chalkboard text-green-500 text-2xl mb-2"></i>
                        <span class="text-sm text-center">Créer une formation</span>
                    </a>
                    <a href="{{ route('admin.installations.pending') }}" 
                       class="flex flex-col items-center justify-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100">
                        <i class="fas fa-file-invoice text-orange-500 text-2xl mb-2"></i>
                        <span class="text-sm text-center">Voir les devis</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dernières notifications -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Dernières notifications</h2>
                <div class="space-y-4">
                    @forelse($notifications ?? [] as $notification)
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                @if($notification->type === 'order')
                                    <i class="fas fa-shopping-cart text-purple-500"></i>
                                @elseif($notification->type === 'installation')
                                    <i class="fas fa-solar-panel text-blue-500"></i>
                                @elseif($notification->type === 'formation')
                                    <i class="fas fa-graduation-cap text-green-500"></i>
                                @else
                                    <i class="fas fa-bell text-gray-500"></i>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-800">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm text-center">Aucune notification récente</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection