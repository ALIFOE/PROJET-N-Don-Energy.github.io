<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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
                'status' => 'pending', // statut par défaut
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
            $emailSent = true;

            try {
                // Tentative d'envoi des emails
                Mail::to($validated['email'])->send(new OrderConfirmationMail($order));

                $adminEmail = config('mail.admin_email');
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new AdminOrderNotificationMail($order));
                }
            } catch (\Exception $mailException) {
                Log::warning('Erreur lors de l\'envoi des emails: ' . $mailException->getMessage());
                $emailSent = false;
            }

            // La commande est créée, on redirige vers la page de succès
            return redirect()->route('payment.success', ['order' => $order->id])
                ->with($emailSent ? 'success' : 'warning',
                    $emailSent 
                        ? 'Votre commande a été traitée avec succès ! Un email de confirmation vous a été envoyé.'
                        : 'Votre commande a été enregistrée avec succès, mais nous n\'avons pas pu vous envoyer l\'email de confirmation. Veuillez conserver votre numéro de commande : ' . $order->id
                );

        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            Log::error('Erreur de paiement: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Messages d'erreur plus spécifiques pour l'utilisateur
            $errorMessage = 'Une erreur est survenue lors du traitement du paiement.';
            
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $errorMessage = 'Le produit demandé n\'existe pas.';
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                $errorMessage = 'Veuillez vérifier les informations saisies.';
            } elseif ($e instanceof \PDOException) {
                $errorMessage = 'Erreur de connexion à la base de données. Veuillez réessayer plus tard.';
            }

            return back()->with('error', $errorMessage)->withInput();
        }
    }

    public function paymentSuccess($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('payment-success', compact('order'));
    }
}