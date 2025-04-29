<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormationConfirmationMail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the email sending functionality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $details = [
            'nom' => 'Test User',
            'email' => 'baudoinalifoe.dcli.dev24@gmail.com',
            'formation' => 'Test Formation',
            'date' => now()->format('d/m/Y'),
            'duree' => '3 mois',
        ];

        try {
            Mail::to($details['email'])->send(new FormationConfirmationMail($details));
            $this->info('E-mail envoyé avec succès à ' . $details['email']);
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
        }

        return 0;
    }
}
