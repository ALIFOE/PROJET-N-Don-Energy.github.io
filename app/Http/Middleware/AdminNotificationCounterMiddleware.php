<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Devis;
use App\Models\FormationInscription;
use App\Models\Order;
use App\Models\DemandeService;
use App\Models\User;
use Illuminate\Support\Facades\View;

class AdminNotificationCounterMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            View::share([
                'devisCount' => Devis::where('statut', 'en_attente')->count(),
                'formationsCount' => FormationInscription::where('statut', 'en_attente')->count(),
                'boutiqueCount' => Order::where('status', Order::STATUS_EN_ATTENTE)
                    ->where('hidden', false)
                    ->count(),
                'servicesCount' => DemandeService::where('statut', DemandeService::STATUT_EN_ATTENTE)->count(),
                'usersCount' => User::where('email_verified_at', null)->count(),
            ]);
        }

        return $next($request);
    }
}
