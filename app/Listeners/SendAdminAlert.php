<?php

namespace App\Listeners;

use App\Events\FormationInscriptionCreated;
use App\Mail\AdminNotification;
use App\Events\ClientActivity;
use App\Mail\AdminActivityMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAdminAlert implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\FormationInscriptionCreated  $event
     * @return void
     */    public function handle(FormationInscriptionCreated $event)
    {
        try {
            $inscription = $event->inscription;

            // Envoi de l'e-mail à l'administrateur
            Mail::to(AdminActivityMail::getAdminEmails())
                ->send(new AdminNotification($inscription));

            \Log::info('Email envoyé à l\'administrateur', [
                'email' => env('MAIL_ADMIN_EMAIL'),
                'formation' => $inscription->formation->titre
            ]);

            // Déclencher l'événement pour la notification des administrateurs
            event(new ClientActivity('formation_inscription', [
                'name' => $inscription->nom,
                'email' => $inscription->email,
                'formation_title' => $inscription->formation->titre,
                'id' => $inscription->id
            ]));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi du mail admin', [
                'error' => $e->getMessage(),
                'formation_id' => $inscription->id
            ]);
        }
    }
}
