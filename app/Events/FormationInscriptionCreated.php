<?php

namespace App\Events;

use App\Models\FormationInscription;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FormationInscriptionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inscription;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\FormationInscription  $inscription
     * @return void
     */
    public function __construct(FormationInscription $inscription)
    {
        $this->inscription = $inscription;
    }
}
