@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Demandes de Services</h1>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($requests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $request->service->nom }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $request->nom }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $request->email }}<br>
                                                {{ $request->telephone }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ Str::limit($request->description, 100) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('admin.services.requests.status', $request) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="statut" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">                                                    <option value="{{ App\Models\DemandeService::STATUT_EN_ATTENTE }}" {{ $request->statut === App\Models\DemandeService::STATUT_EN_ATTENTE ? 'selected' : '' }}>En attente</option>
                                                    <option value="{{ App\Models\DemandeService::STATUT_EN_COURS }}" {{ $request->statut === App\Models\DemandeService::STATUT_EN_COURS ? 'selected' : '' }}>En cours</option>
                                                    <option value="{{ App\Models\DemandeService::STATUT_ACCEPTE }}" {{ $request->statut === App\Models\DemandeService::STATUT_ACCEPTE ? 'selected' : '' }}>Accepté</option>
                                                    <option value="{{ App\Models\DemandeService::STATUT_REFUSE }}" {{ $request->statut === App\Models\DemandeService::STATUT_REFUSE ? 'selected' : '' }}>Refusé</option>
                                                </select>
                                            </form>
                                        </td>                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-4">
                                            <a href="mailto:{{ $request->email }}" class="text-indigo-600 hover:text-indigo-900">Contacter</a>
                                            @if($request->statut === App\Models\DemandeService::STATUT_REFUSE || $request->statut === App\Models\DemandeService::STATUT_ACCEPTE)
                                                <form action="{{ route('admin.services.requests.destroy', $request) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
