<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccountNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected User $user,
        protected string $action,
        protected ?string $additionalInfo = null
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject("Activité compte utilisateur - CREFER");

        switch ($this->action) {
            case 'created':
                $mail->line("Un nouveau compte utilisateur a été créé")
                    ->line("Nom : {$this->user->name}")
                    ->line("Email : {$this->user->email}")
                    ->line("Rôle : {$this->user->role}");
                break;
            case 'role_changed':
                $mail->line("Le rôle d'un utilisateur a été modifié")
                    ->line("Utilisateur : {$this->user->name}")
                    ->line("Nouveau rôle : {$this->user->role}")
                    ->line($this->additionalInfo ? "Note : {$this->additionalInfo}" : '');
                break;
            case 'deleted':
                $mail->line("Un compte utilisateur a été supprimé")
                    ->line("Nom : {$this->user->name}")
                    ->line("Email : {$this->user->email}");
                break;
        }

        return $mail->action('Gérer les utilisateurs', route('admin.users.index'));
    }

    public function toArray($notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'action' => $this->action,
            'additional_info' => $this->additionalInfo,
            'type' => 'user_account'
        ];
    }
}
