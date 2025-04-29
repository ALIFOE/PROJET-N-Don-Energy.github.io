<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'payment_method' => 'required|in:carte,virement'
        ]);

        $product = Product::findOrFail($validatedData['product_id']);
        $total_price = $product->price * $validatedData['quantity'];

        $order = Order::create([
            'product_id' => $validatedData['product_id'],
            'user_id' => auth()->id(),
            'quantity' => $validatedData['quantity'],
            'total_price' => $total_price,
            'status' => 'pending',
            'payment_method' => $validatedData['payment_method'],
            'customer_name' => $validatedData['customer_name'],
            'customer_email' => $validatedData['customer_email'],
            'customer_phone' => $validatedData['customer_phone'],
            'customer_address' => $validatedData['customer_address']
        ]);

        // Envoyer l'e-mail à l'administrateur
        if (config('mail.admin_email')) {
            Mail::to(config('mail.admin_email'))->send(new AdminOrderNotificationMail($order));
        }

        // Envoyer l'e-mail de confirmation au client
        Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));

        // Rediriger vers la page de succès avec le message flash
        return redirect()->route('orders.success', $order->id)
            ->with('success', 'Votre commande a été enregistrée avec succès !');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }

    public function success(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.success', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}