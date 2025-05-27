<?php

namespace App\Notifications;

use App\Models\FormationInscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFormationInscriptionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected FormationInscription $inscription)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle inscription à une formation - CREFER')
            ->line("Nouvelle inscription à la formation {$this->inscription->formation->titre}")
            ->line("Participant : {$this->inscription->nom}")            ->line("Email : {$this->inscription->email}")
            ->action('Voir les inscriptions', route('admin.formations.inscriptions.index'))
            ->line('Cette inscription nécessite votre validation.');
    }

    public function toArray($notifiable): array
    {
        return [
            'inscription_id' => $this->inscription->id,
            'participant_name' => $this->inscription->nom,
            'formation_title' => $this->inscription->formation->titre,
            'type' => 'formation_inscription'
        ];
    }
}
