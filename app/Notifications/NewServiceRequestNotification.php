<?php

namespace App\Notifications;

use App\Models\DemandeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewServiceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected DemandeService $demande)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de service - CREFER')
            ->line("Une nouvelle demande de service a été soumise par {$this->demande->nom}.")
            ->line("Service demandé : {$this->demande->service->nom}")
            ->line("Description : {$this->demande->details}")
            ->action('Voir la demande', route('admin.services.requests'))
            ->line('Cette demande nécessite votre attention.');
    }

    public function toArray($notifiable): array
    {
        return [
            'demande_id' => $this->demande->id,
            'user_name' => $this->demande->nom,
            'service_name' => $this->demande->service->nom,
            'status' => $this->demande->statut,
            'type' => 'service_request'
        ];
    }
}
