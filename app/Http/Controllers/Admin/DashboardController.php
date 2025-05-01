<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Contact;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administrateur
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'unread_contacts' => Contact::where('statut', 'non_lu')->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
