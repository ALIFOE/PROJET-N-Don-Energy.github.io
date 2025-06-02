<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\NotificationMarkable;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use NotificationMarkable;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $this->markNotificationsAsRead('App\Notifications\NewOrderNotification');
        $orders = Order::with('product', 'user')
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_EN_ATTENTE,
                Order::STATUS_EN_COURS,
                Order::STATUS_TERMINE,
                Order::STATUS_ANNULE
            ])
        ]);

        if ($validatedData['status'] === Order::STATUS_ANNULE) {
            // Mettre à jour le statut et cacher la commande
            $order->update([
                'status' => Order::STATUS_ANNULE,
                'hidden' => true,
                'message' => 'Votre commande a été annulée par l\'administrateur.'
            ]);

            return redirect()->back()
                ->with('success', 'La commande a été annulée et retirée de la liste du client.');
        }

        // Mise à jour normale du statut
        $order->update([
            'status' => $validatedData['status']
        ]);

        return redirect()->back()
            ->with('success', 'Le statut de la commande a été mis à jour avec succès.');
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('admin.orders.index')
                ->with('success', 'La commande a été supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de la commande.');
        }
    }
}