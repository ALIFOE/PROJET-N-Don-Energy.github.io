<?php

namespace App\Listeners;

use App\Events\ClientActivity;
use App\Models\User;
use App\Notifications\AdminActivityNotification;
use App\Mail\AdminActivityMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfClientActivity
{
    public function handle(ClientActivity $event)
    {
        try {
            // Trouver tous les administrateurs
            $admins = User::where('role', 'admin')->get();
            
            $title = $this->getNotificationTitle($event->type);
            $message = $this->getNotificationMessage($event->type, $event->data);
            $actionUrl = $this->getActionUrl($event->type, $event->data);
            $actionText = $this->getActionText($event->type);            foreach ($admins as $admin) {
                // Envoyer une notification dans l'application
                $admin->notify(new AdminActivityNotification(
                    $title,
                    $message,
                    $actionUrl,
                    $actionText
                ));
            }            // Envoyer un email à tous les administrateurs
            Mail::to(AdminActivityMail::getAdminEmails())
                ->queue(new AdminActivityMail($event->type, $event->data));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification des administrateurs', [
                'error' => $e->getMessage(),
                'event_type' => $event->type
            ]);
        }
    }

    private function getNotificationTitle($type)
    {
        return match($type) {
            'formation_inscription' => 'Nouvelle inscription à une formation',
            'devis_request' => 'Nouvelle demande de devis',
            'order_placed' => 'Nouvelle commande',
            'contact_form' => 'Nouveau message de contact',
            default => 'Nouvelle activité client'
        };
    }

    private function getNotificationMessage($type, $data)
    {
        return match($type) {
            'formation_inscription' => "Nouvelle inscription à la formation par {$data['name']}",
            'devis_request' => "Nouvelle demande de devis de {$data['name']}",
            'order_placed' => "Nouvelle commande passée par {$data['name']}",
            'contact_form' => "Nouveau message de contact de {$data['name']}",
            default => "Un client a effectué une action sur le site"
        };
    }

    private function getActionUrl($type, $data)
    {
        return match($type) {
            'formation_inscription' => route('admin.formations.inscriptions'),
            'devis_request' => route('admin.devis.index'),
            'order_placed' => route('admin.orders.show', $data['id'] ?? ''),
            'contact_form' => route('admin.contacts.index'),
            default => route('admin.dashboard')
        };
    }

    private function getActionText($type)
    {
        return match($type) {
            'formation_inscription' => 'Voir les inscriptions',
            'devis_request' => 'Voir les devis',
            'order_placed' => 'Voir la commande',
            'contact_form' => 'Voir les messages',
            default => 'Voir le tableau de bord'
        };
    }
}
