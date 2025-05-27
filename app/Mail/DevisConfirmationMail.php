<?php

namespace App\Mail;

use App\Models\Devis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DevisConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected Devis $devis)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre demande de devis - CREFER',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.devis-confirmation',
            with: [
                'devis' => $this->devis
            ]
        );
    }
}
