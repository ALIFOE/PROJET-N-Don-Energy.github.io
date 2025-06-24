<?php

namespace App\Listeners;

use App\Events\FormationInscriptionCreated;
use App\Mail\AdminNotification;
use App\Mail\FormationConfirmationMail;
use App\Events\ClientActivity;
use App\Mail\AdminActivityMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

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
            $inscription = $event->inscription;            try {
                // Envoi de l'e-mail à tous les administrateurs
                Mail::to(User::getAdminEmails())
                    ->send(new AdminNotification($inscription));

                // Envoi de l'e-mail de confirmation au participant
                Mail::to($inscription->email)
                    ->send(new FormationConfirmationMail($inscription));

                \Log::info('Emails envoyés avec succès', [
                    'admin_email' => env('MAIL_ADMIN_EMAIL'),
                    'participant_email' => $inscription->email,
                    'formation' => $inscription->formation->titre
                ]);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi des emails', [
                    'error' => $e->getMessage(),
                    'participant_email' => $inscription->email,
                    'formation' => $inscription->formation->titre
                ]);
            }

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
