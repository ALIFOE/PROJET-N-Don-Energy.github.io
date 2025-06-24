<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Devis;
use App\Models\Order;
use App\Models\FormationInscription;
use App\Models\DemandeService;
use Illuminate\Support\Facades\Auth;

class AdminNavigationComposer extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.admin-navigation', function ($view) {
            $devisCount = Devis::where('statut', 'en_attente')->count();
            $ordersCount = Order::where('status', 'pending')->count();
            $inscriptionsCount = \App\Models\FormationInscription::where('statut', 'en_attente')->count();
            $servicesRequestsCount = DemandeService::where('statut', DemandeService::STATUT_EN_ATTENTE)->count();
            $adminNotificationsCount = 0;
            if (Auth::check() && Auth::user()->role === 'admin') {
                $adminNotificationsCount = Auth::user()->unreadNotifications->count();
            }
            $view->with(compact('devisCount', 'ordersCount', 'inscriptionsCount', 'adminNotificationsCount', 'servicesRequestsCount'));
        });
    }

    public function register()
    {
        //
    }
}
