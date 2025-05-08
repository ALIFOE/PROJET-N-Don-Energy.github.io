<?php

namespace App\Notifications;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDevisNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Devis $devis)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau devis d\'installation - ' . config('app.name'))
            ->markdown('emails.devis-notification', ['devis' => $this->devis]);
    }

    public function toArray($notifiable): array
    {
        return [
            'devis_id' => $this->devis->id,
            'client_name' => $this->devis->nom . ' ' . $this->devis->prenom,
            'type_batiment' => $this->devis->type_batiment,
            'consommation_annuelle' => $this->devis->consommation_annuelle,
            'created_at' => $this->devis->created_at->format('Y-m-d H:i:s')
        ];
    }
}