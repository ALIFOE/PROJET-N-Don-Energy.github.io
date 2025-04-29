<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\AdminOrderNotificationMail;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showCheckout($productId)
    {
        $product = Product::findOrFail($productId);
        return view('checkout', compact('product'));
    }

    public function processPayment(Request $request)
    {
        if ($request->isMethod('GET')) {
            return redirect()->route('market-place')
                ->with('error', 'Cette page ne peut être accédée directement. Veuillez utiliser le formulaire de paiement.');
        }

        try {
            // Validation des données
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'payment_method' => 'required|string|in:mobile_money,bank_transfer,cash',
            ]);

            // Récupération du produit
            $product = Product::findOrFail($validated['product_id']);

            // Création de la commande
            $orderData = [
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'total_price' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'customer_address' => $validated['address'],
            ];

            // Ajouter user_id seulement si l'utilisateur est connecté
            if (Auth::check()) {
                $orderData['user_id'] = Auth::id();
            }

            $order = Order::create($orderData);

            // Envoi de l'email de confirmation au client
            Mail::to($validated['email'])->send(new OrderConfirmationMail($order));

            // Envoi de l'email d'alerte à l'administrateur
            $adminEmail = config('mail.admin_email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));
            }

            // Redirection avec message de succès
            return redirect()->route('checkout', ['product' => $order->product_id])
                ->with('success', 'Votre commande a été passée avec succès !');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors du traitement du paiement.');
        }
    }

    public function paymentSuccess($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('payment-success', compact('order'));
    }
}