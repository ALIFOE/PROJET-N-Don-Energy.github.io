<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdminOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->to(User::getAdminEmails());
        Log::info('Préparation de l\'e-mail pour les administrateurs', ['order_id' => $this->order->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: User::getAdminEmails(),
            subject: 'Nouvelle commande - CREFER',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-order-notification',
            with: [
                'order' => $this->order
            ]
        );
    }
}