<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected array $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre message - CREFER',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-confirmation',
            with: [
                'contact' => $this->contact
            ]
        );
    }
}
