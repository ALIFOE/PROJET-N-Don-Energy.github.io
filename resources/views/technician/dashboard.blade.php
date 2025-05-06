@extends('layouts.technician')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Tableau de bord Technicien</h2>
        <p class="mt-1 text-gray-600">Bienvenue dans votre espace de gestion</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Installations en cours -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Installations en cours</h3>
                <span class="text-2xl font-bold text-blue-600">{{ $installations_en_cours }}</span>
            </div>
            <div class="text-sm text-gray-600">
                <a href="{{ route('technician.installations') }}" class="text-blue-600 hover:text-blue-800">
                    Voir toutes les installations →
                </a>
            </div>
        </div>

        <!-- Maintenances à venir -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Maintenances à venir</h3>
                <span class="text-2xl font-bold text-yellow-600">{{ $maintenances_prevues }}</span>
            </div>
            <div class="text-sm text-gray-600">
                <a href="{{ route('technician.maintenance') }}" class="text-blue-600 hover:text-blue-800">
                    Voir toutes les maintenances →
                </a>
            </div>
        </div>

        <!-- Interventions urgentes -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Interventions urgentes</h3>
                <span class="text-2xl font-bold text-red-600">{{ $interventions_urgentes }}</span>
            </div>
            <div class="text-sm text-gray-600">
                <a href="{{ route('technician.maintenance', ['priorite' => 'haute']) }}" class="text-blue-600 hover:text-blue-800">
                    Voir les interventions urgentes →
                </a>
            </div>
        </div>
    </div>

    <!-- Prochaines interventions -->
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Prochaines interventions</h3>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prochaines_interventions as $intervention)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                    $intervention->type === 'maintenance' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' 
                                }}">
                                    {{ ucfirst($intervention->type) }}
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ $intervention->client_name }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap text-xs">
                                    {{ $intervention->adresse }}
                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ $intervention->date_prevue->format('d/m/Y') }}
                                </p>
                                <p class="text-gray-600 whitespace-no-wrap text-xs">
                                    {{ $intervention->date_prevue->format('H:i') }}
                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="px-3 py-1 rounded-full text-xs {{ 
                                    $intervention->statut === 'planifiee' ? 'bg-blue-100 text-blue-800' : 
                                    ($intervention->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-green-100 text-green-800') 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $intervention->statut)) }}
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ 
                                    $intervention->type === 'maintenance' 
                                        ? route('technician.maintenance.show', $intervention->id)
                                        : route('technician.installations.show', $intervention->id)
                                }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye mr-1"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-5 bg-white text-sm text-center">
                                Aucune intervention prévue.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Dernières alertes -->
    <div class="mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Dernières alertes</h3>
        <div class="space-y-4">
            @forelse($alertes as $alerte)
                <div class="bg-white p-4 rounded-lg shadow flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full {{ 
                            $alerte->niveau === 'critique' ? 'bg-red-100 text-red-500' : 
                            ($alerte->niveau === 'warning' ? 'bg-yellow-100 text-yellow-500' : 
                            'bg-blue-100 text-blue-500') 
                        }}">
                            <i class="fas {{ 
                                $alerte->niveau === 'critique' ? 'fa-exclamation-triangle' : 
                                ($alerte->niveau === 'warning' ? 'fa-exclamation-circle' : 
                                'fa-info-circle') 
                            }}"></i>
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $alerte->titre }}
                            </p>
                            <span class="text-xs text-gray-500">
                                {{ $alerte->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ $alerte->message }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="bg-white p-4 rounded-lg shadow text-center text-gray-600">
                    Aucune alerte active.
                </div>
            @endforelse
        </div>
    </div>
@endsection