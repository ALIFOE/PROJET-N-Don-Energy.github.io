<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class AdminActivityMail extends Mailable
{
    use Queueable, SerializesModels;    public static function getAdminEmails()
    {
        return User::getAdminEmails();
    }

    public $activityData;
    public $activityType;

    public function __construct($activityType, $activityData)
    {
        $this->activityType = $activityType;
        $this->activityData = $activityData;
    }

    public function build()
    {
        $subject = $this->getEmailSubject();
        return $this->subject($subject)
                   ->markdown('emails.admin.activity', [
                       'activityType' => $this->activityType,
                       'activityData' => $this->activityData
                   ]);
    }

    private function getEmailSubject()
    {
        return match($this->activityType) {
            'order_placed' => 'Nouvelle commande - ' . config('app.name'),
            'devis_request' => 'Nouvelle demande de devis - ' . config('app.name'),
            'formation_inscription' => 'Nouvelle inscription à une formation - ' . config('app.name'),
            'contact_form' => 'Nouveau message de contact - ' . config('app.name'),
            default => 'Nouvelle activité sur ' . config('app.name')
        };
    }
}
