@extends('layouts.app')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700">
        État des Services IA
    </h2>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Carte d'état du service -->
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs">
            <h4 class="mb-4 font-semibold text-gray-800">
                État du Service OpenAI
            </h4>
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-600">Status actuel:</span>
                <span class="px-2 py-1 text-sm 
                    @if($quotaStatus['fallback_mode'])
                        bg-red-100 text-red-700
                    @elseif($quotaStatus['usage_percentage'] > 80)
                        bg-yellow-100 text-yellow-700
                    @else
                        bg-green-100 text-green-700
                    @endif 
                    rounded-full">
                    {{ $quotaStatus['status'] }}
                </span>
            </div>
            
            <!-- Indicateur d'utilisation -->
            <div class="mb-4">
                <span class="text-gray-600">Utilisation du quota:</span>
                <div class="w-full h-2 mt-2 bg-gray-200 rounded-full">
                    <div class="h-2 rounded-full 
                        @if($quotaStatus['usage_percentage'] > 90)
                            bg-red-500
                        @elseif($quotaStatus['usage_percentage'] > 70)
                            bg-yellow-500
                        @else
                            bg-green-500
                        @endif"
                        style="width: {{ $quotaStatus['usage_percentage'] }}%">
                    </div>
                </div>
                <span class="text-sm text-gray-600">{{ number_format($quotaStatus['usage_percentage'], 1) }}%</span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <span class="text-gray-600">Requêtes totales:</span>
                    <p class="text-lg font-semibold">{{ $quotaStatus['usage'] }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Erreurs récentes:</span>
                    <p class="text-lg font-semibold">{{ $quotaStatus['error_count'] }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex space-x-4">
                <form action="{{ route('admin.services.reset-fallback') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                        Désactiver le mode dégradé
                    </button>
                </form>

                <form action="{{ route('admin.services.reset-quota') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Réinitialiser le compteur
                    </button>
                </form>
            </div>
        </div>

        <!-- Journal des erreurs récentes -->
        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs">
            <h4 class="mb-4 font-semibold text-gray-800">
                Erreurs Récentes
            </h4>
            @if(count($recentErrors) > 0)
                <div class="overflow-y-auto max-h-96">
                    @foreach($recentErrors as $error)
                    <div class="mb-4 p-3 bg-red-50 rounded">
                        <p class="text-sm text-red-600">{{ $error['message'] }}</p>
                        <small class="text-gray-500">{{ $error['date'] }}</small>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">Aucune erreur récente</p>
            @endif
        </div>
    </div>

    <div class="text-sm text-gray-500 mt-4">
        Dernière mise à jour: {{ $lastCheck }}
    </div>
</div>
@endsection
