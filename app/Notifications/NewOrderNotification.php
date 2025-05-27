<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle commande - CREFER')
            ->line("Une nouvelle commande a été passée par {$this->order->user->name}.")
            ->line("Montant total : {$this->order->total} FCFA")
            ->action('Voir la commande', route('admin.orders.show', $this->order))
            ->line('Cette commande nécessite votre attention.');
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'user_name' => $this->order->user->name,
            'total' => $this->order->total,
            'status' => $this->order->status,
            'type' => 'order'
        ];
    }
}
