<?php

namespace App\Mail;

use App\Models\FormationInscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $inscription;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\FormationInscription  $inscription
     * @return void
     */
    public function __construct(FormationInscription $inscription)
    {
        $this->inscription = $inscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(User::getAdminEmails())
                    ->subject('Nouvelle Inscription à une Formation')
                    ->view('emails.admin_notification')
                    ->with(['inscription' => $this->inscription]);
    }
}
