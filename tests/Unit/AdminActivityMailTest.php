<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Events\ClientActivity;
use App\Mail\AdminActivityMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class AdminActivityMailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Config::set('mail.admin_email', 'admin@test.com');
    }

    /**
     * Test de l'envoi d'email pour une nouvelle commande
     */    public function test_order_notification_email()
    {
        // Données simulées d'une commande
        $orderData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'amount' => 1500.00,
            'id' => 1
        ];

        // Créer un listener et le faire écouter manuellement
        $listener = new \App\Listeners\NotifyAdminOfClientActivity();
        $event = new ClientActivity('order_placed', $orderData);
        $listener->handle($event);

        // Vérifier que l'email a été envoyé
        Mail::assertQueued(AdminActivityMail::class, function ($mail) use ($orderData) {
            return $mail->activityType === 'order_placed' &&
                   $mail->activityData === $orderData;
        });
    }

    public function test_devis_notification_email_links()
    {
        // Données simulées d'un devis
        $devisData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'type_batiment' => 'Maison',
            'id' => 1
        ];

        // Créer un listener et le faire écouter manuellement
        $listener = new \App\Listeners\NotifyAdminOfClientActivity();
        $event = new ClientActivity('devis_request', $devisData);
        $listener->handle($event);

        // Vérifier que l'email a été envoyé
        Mail::assertQueued(AdminActivityMail::class, function ($mail) use ($devisData) {
            return $mail->activityType === 'devis_request' &&
                   $mail->activityData === $devisData;
        });
    }

    public function test_contact_form_notification_email_links()
    {
        // Données simulées d'un message de contact
        $contactData = [
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'subject' => 'Question sur les services',
            'id' => 1
        ];

        // Créer un listener et le faire écouter manuellement
        $listener = new \App\Listeners\NotifyAdminOfClientActivity();
        $event = new ClientActivity('contact_form', $contactData);
        $listener->handle($event);

        // Vérifier que l'email a été envoyé
        Mail::assertQueued(AdminActivityMail::class, function ($mail) use ($contactData) {
            return $mail->activityType === 'contact_form' &&
                   $mail->activityData === $contactData;
        });
    }    public function test_formation_inscription_notification_email_links()
    {
        // Données simulées d'une inscription
        $inscriptionData = [
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'formation_title' => 'Formation Énergie Solaire',
            'id' => 1
        ];

        // Créer un listener et le faire écouter manuellement
        $listener = new \App\Listeners\NotifyAdminOfClientActivity();
        $event = new ClientActivity('formation_inscription', $inscriptionData);
        $listener->handle($event);

        // Vérifier que l'email a été envoyé
        Mail::assertQueued(AdminActivityMail::class, function ($mail) use ($inscriptionData) {
            return $mail->activityType === 'formation_inscription' &&
                   $mail->activityData === $inscriptionData;
        });
    }
}
