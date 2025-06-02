<?php

namespace App\Notifications;

use App\Models\FormationInscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormationInscriptionCanceled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected FormationInscription $inscription
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Annulation d\'une inscription à une formation')
            ->greeting('Bonjour,')
            ->line('Un client a annulé son inscription à une formation.')
            ->line('Client : ' . $this->inscription->user->name)
            ->line('Formation : ' . $this->inscription->formation->titre)
            ->line('Date d\'annulation : ' . now()->format('d/m/Y H:i'))
            ->action('Voir les détails', route('admin.formations.inscriptions'));
    }
}
