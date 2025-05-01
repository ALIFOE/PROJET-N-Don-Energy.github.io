<?php

namespace App\Notifications;

use App\Models\FormationInscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FormationInscriptionNotification extends Notification
{
    use Queueable;

    protected $inscription;

    public function __construct(FormationInscription $inscription)
    {
        $this->inscription = $inscription;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouvelle inscription à une formation')
            ->line('Une nouvelle inscription a été enregistrée pour une formation.')
            ->line('Formation : ' . $this->inscription->formation->nom)
            ->line('Nom : ' . $this->inscription->nom)
            ->action('Voir les détails', route('admin.formations.inscriptions.show', $this->inscription))
            ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Nouvelle inscription à la formation ' . $this->inscription->formation->nom,
            'inscription_id' => $this->inscription->id,
            'formation_id' => $this->inscription->formation_id,
            'nom' => $this->inscription->nom,
        ];
    }
}