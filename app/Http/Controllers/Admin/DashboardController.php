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
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();

        // Statistiques des installations
        $installations_count = Installation::count();
        $installations_this_month = Installation::where('created_at', '>=', $startOfMonth)->count();

        // Statistiques des formations
        $formations_count = Formation::count();
        $formations_active = Formation::where('date_fin', '>=', $now)->count();
        $formations_participants = Formation::where('date_fin', '>=', $now)
            ->withCount('inscriptions')
            ->get()
            ->sum('inscriptions_count');

        // Statistiques des commandes
        $orders_count = Order::count();
        $orders_this_month = Order::where('created_at', '>=', $startOfMonth)->count();
        $revenue_this_month = Order::where('created_at', '>=', $startOfMonth)
            ->where('status', 'completed')
            ->sum('total_price');

        // Statistiques des devis
        $pending_quotes = Devis::where('statut', 'en_attente')->count();
        $quotes_this_week = Devis::where('created_at', '>=', $startOfWeek)->count();

        // Statistiques des produits et fonctionnalités
        $products_count = Product::count();
        $functionalities_count = Functionality::count();

        // Récupération des dernières notifications
        $notifications = Notification::latest()
            ->take(5)
            ->get();

        $stats = compact(
            'installations_count',
            'installations_this_month',
            'formations_count',
            'formations_active',
            'formations_participants',
            'orders_count',
            'orders_this_month',
            'revenue_this_month',
            'pending_quotes',
            'quotes_this_week',
            'products_count',
            'functionalities_count'
        );

        return view('admin.dashboard', compact('stats', 'notifications'));
    }
}
