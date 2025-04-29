<?php

namespace App\Mail;

use App\Models\Dimensionnement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DimensionnementRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $dimensionnement;

    /**
     * Create a new message instance.
     */
    public function __construct(Dimensionnement $dimensionnement)
    {
        $this->dimensionnement = $dimensionnement;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation de votre demande de dimensionnement')
                    ->markdown('emails.dimensionnement-request');
    }
}