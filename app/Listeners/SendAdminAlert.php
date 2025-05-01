<?php

namespace App\Listeners;

use App\Events\FormationInscriptionCreated;
use App\Notifications\FormationInscriptionNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAdminAlert implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(FormationInscriptionCreated $event)
    {
        $inscription = $event->inscription;

        // Envoyer la notification à tous les administrateurs
        User::where('role', 'admin')->get()->each(function ($admin) use ($inscription) {
            $admin->notify(new FormationInscriptionNotification($inscription));
        });
    }
}
