<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer un nouvel administrateur';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('Entrez le nom de l\'administrateur:');
        $email = $this->argument('email') ?? $this->ask('Entrez l\'email de l\'administrateur:');
        $password = $this->argument('password') ?? $this->secret('Entrez le mot de passe de l\'administrateur:');

        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);

            $this->info('Administrateur créé avec succès!');
            $this->table(
                ['Nom', 'Email', 'Rôle'],
                [[$user->name, $user->email, $user->role]]
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Erreur lors de la création de l\'administrateur: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
