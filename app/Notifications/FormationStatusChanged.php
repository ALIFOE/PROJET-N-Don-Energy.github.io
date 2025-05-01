<?php

namespace App\Notifications;

use App\Models\FormationInscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormationStatusChanged extends Notification
{
    use Queueable;

    protected $inscription;

    public function __construct(FormationInscription $inscription)
    {
        $this->inscription = $inscription;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = [
            'acceptee' => 'acceptée',
            'refusee' => 'refusée',
            'en_attente' => 'mise en attente'
        ][$this->inscription->statut];

        return (new MailMessage)
            ->subject('Mise à jour de votre inscription à la formation')
            ->greeting('Bonjour ' . $this->inscription->nom)
            ->line('Le statut de votre inscription à la formation "' . $this->inscription->formation->nom . '" a été mis à jour.')
            ->line('Votre inscription a été ' . $status . '.')
            ->line($this->getStatusMessage())
            ->action('Voir les détails', route('formation'))
            ->line('Merci de votre intérêt pour nos formations !');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Le statut de votre inscription à la formation "' . $this->inscription->formation->nom . '" a été mis à jour.',
            'formation_id' => $this->inscription->formation_id,
            'statut' => $this->inscription->statut
        ];
    }

    private function getStatusMessage()
    {
        switch($this->inscription->statut) {
            case 'acceptee':
                return 'Nous vous contacterons prochainement pour les prochaines étapes.';
            case 'refusee':
                return 'N\'hésitez pas à nous contacter pour plus d\'informations ou à postuler pour d\'autres formations.';
            default:
                return 'Nous examinerons votre candidature dans les plus brefs délais.';
        }
    }
}