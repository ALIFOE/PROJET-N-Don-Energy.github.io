<?php

namespace App\Notifications;

use App\Models\DemandeService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceRequestStatusChanged extends Notification
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
        $status = match($this->demande->statut) {
            'en_attente' => 'en attente',
            'en_cours' => 'en cours de traitement',
            'accepte' => 'acceptée',
            'refuse' => 'refusée',
            default => $this->demande->statut
        };

        return (new MailMessage)
            ->subject('Mise à jour de votre demande de service - ' . config('app.name'))
            ->greeting('Bonjour ' . $this->demande->nom)
            ->line("Le statut de votre demande pour le service \"{$this->demande->service->nom}\" a été mis à jour.")
            ->line("Votre demande est maintenant {$status}.")
            ->line('Si vous avez des questions, n\'hésitez pas à nous contacter.')
            ->action('Voir mes demandes', route('client.demandes-services.index'));
    }

    public function toArray($notifiable): array
    {
        return [
            'demande_id' => $this->demande->id,
            'service_name' => $this->demande->service->nom,
            'status' => $this->demande->statut,
            'type' => 'service_request_status'
        ];
    }
}
