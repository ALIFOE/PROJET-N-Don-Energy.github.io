@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('admin.notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fas fa-check-double mr-2"></i>Tout marquer comme lu
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md">
        @if(auth()->user()->unreadNotifications->count() > 0)
            <div class="bg-blue-50 p-4 border-b border-blue-100">
                <h3 class="text-lg font-semibold text-blue-800">Notifications non lues</h3>
            </div>
            @foreach(auth()->user()->unreadNotifications as $notification)
                <div class="p-4 border-b hover:bg-gray-50">
                    @if($notification->type === 'App\Notifications\NewDevisNotification')
                        <div class="flex items-start justify-between">
                            <div class="flex-grow">
                                <p class="font-semibold text-gray-800">
                                    Nouveau devis de {{ $notification->data['client_name'] }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Type de bâtiment : {{ $notification->data['type_batiment'] }}<br>
                                    Consommation annuelle : {{ number_format($notification->data['consommation_annuelle'], 0, ',', ' ') }} kWh
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Reçu le {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.devis.show', $notification->data['devis_id']) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> Voir le devis
                                </a>
                                <form action="{{ route('admin.notifications.markAsRead', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-check"></i> Marquer comme lu
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

        @if(auth()->user()->readNotifications->count() > 0)
            <div class="bg-gray-50 p-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-600">Notifications lues</h3>
            </div>
            @foreach(auth()->user()->readNotifications()->latest()->take(10)->get() as $notification)
                <div class="p-4 border-b hover:bg-gray-50 opacity-75">
                    @if($notification->type === 'App\Notifications\NewDevisNotification')
                        <div class="flex items-start justify-between">
                            <div class="flex-grow">
                                <p class="font-medium text-gray-700">
                                    Devis de {{ $notification->data['client_name'] }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Type de bâtiment : {{ $notification->data['type_batiment'] }}<br>
                                    Consommation annuelle : {{ number_format($notification->data['consommation_annuelle'], 0, ',', ' ') }} kWh
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    Lu le {{ \Carbon\Carbon::parse($notification->read_at)->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('admin.devis.show', $notification->data['devis_id']) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> Voir le devis
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

        @if(auth()->user()->notifications->count() === 0)
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-bell-slash text-4xl mb-3"></i>
                <p>Aucune notification pour le moment</p>
            </div>
        @endif
    </div>
</div>
@endsection