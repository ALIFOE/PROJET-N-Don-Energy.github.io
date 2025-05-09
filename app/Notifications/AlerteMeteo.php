<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlerteMeteo extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $type;

    public function __construct($message, $type = 'danger')
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        $config = $notifiable->alerte_meteo_config ?? [];
        $channels = ['database'];

        if (!empty($config['notifications_email'])) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Alerte Météo - CREFER')
            ->greeting('Alerte Météo')
            ->line($this->message)
            ->line('Cette alerte est basée sur les conditions météorologiques actuelles de votre installation.')
            ->action('Voir les détails', route('previsions-meteo'))
            ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'installation_id' => $notifiable->id,
        ];
    }
}