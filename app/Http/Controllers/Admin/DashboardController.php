<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installation;
use App\Models\Formation;
use App\Models\Product;
use App\Models\Order;
use App\Models\Devis;
use App\Models\Functionality;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        // Statistiques globales
        $totalUsers = \App\Models\User::count();
        $pendingQuotes = Devis::where('statut', 'en_attente')->count();
        $activeInstallations = Installation::where('status', 'active')->count();

        // Activités récentes
        $recentActivities = \App\Models\LogActivite::latest()
            ->take(5)
            ->get();

        // Alertes système
        $systemAlerts = Notification::where('type', 'system_alert')
            ->where('read_at', null)
            ->latest()
            ->take(5)
            ->get();

        // Derniers devis
        $recentQuotes = Devis::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($devis) {
                return (object)[
                    'client_name' => $devis->user->name,
                    'created_at' => $devis->created_at,
                    'status' => $devis->statut
                ];
            });

        // Dernières installations
        $recentInstallations = Installation::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($installation) {
                return (object)[
                    'client_name' => $installation->user->name,
                    'type' => $installation->type,
                    'status' => $installation->status
                ];
            });

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingQuotes',
            'activeInstallations',
            'recentActivities',
            'systemAlerts',
            'recentQuotes',
            'recentInstallations'
        ));
    }
}
