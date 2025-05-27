<?php

namespace App\Mail;

use App\Models\DemandeService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceRequestConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;    public function __construct(protected DemandeService $serviceRequest)
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
            view: 'emails.service-request-confirmation',
            with: [
                'serviceRequest' => $this->serviceRequest
            ]
        );
    }
}
