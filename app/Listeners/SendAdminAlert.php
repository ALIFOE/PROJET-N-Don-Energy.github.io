<?php

namespace App\Listeners;

use App\Events\FormationInscriptionCreated;
use App\Mail\AdminNotification;
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
     */
    public function handle(FormationInscriptionCreated $event)
    {
        $inscription = $event->inscription;

        // Envoi de l'e-mail à l'administrateur
        Mail::to('alifoebaudoin228@gmail.com')->send(new AdminNotification($inscription));
    }
}
