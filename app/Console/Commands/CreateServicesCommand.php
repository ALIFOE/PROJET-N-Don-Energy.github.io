<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateServicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create initial services';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $services = [
            [
                'nom' => 'ÉLECTRICITÉ BÂTIMENT ET INDUSTRIELLE',
                'description' => 'Installation, maintenance et réparation des systèmes électriques pour bâtiments résidentiels et installations industrielles. Services incluant la mise aux normes, le dépannage et le conseil en optimisation électrique.',
                'image' => 'services/electricite.jpg'
            ],
            [
                'nom' => 'EFFICACITÉ ÉNERGÉTIQUE',
                'description' => 'Audit énergétique, solutions d\'optimisation de la consommation d\'énergie, installation de systèmes de gestion énergétique intelligents et conseils personnalisés pour réduire votre empreinte écologique.',
                'image' => 'services/efficacite.jpg'
            ],
            [
                'nom' => 'ÉNERGIE SOLAIRE PHOTOVOLTAÏQUE',
                'description' => 'Installation de panneaux solaires, dimensionnement de systèmes photovoltaïques, maintenance et optimisation de la production d\'énergie solaire pour particuliers et professionnels.',
                'image' => 'services/solaire.jpg'
            ],
            [
                'nom' => 'SYSTEME D\'ALARME ET ÉCLAIRAGE DE SÉCURITÉ',
                'description' => 'Installation et maintenance de systèmes d\'alarme, éclairage de sécurité, détection incendie et systèmes d\'évacuation. Solutions sur mesure pour la protection de vos locaux.',
                'image' => 'services/securite.jpg'
            ],
            [
                'nom' => 'RÉSEAUX ET TÉLÉCOMUNICATION',
                'description' => 'Installation et configuration de réseaux informatiques et de télécommunication, câblage structuré, fibre optique et solutions de connectivité pour entreprises.',
                'image' => 'services/reseaux.jpg'
            ],
            [
                'nom' => 'CAMERA DE SURVEILLANCE ET ANTENNE PARABOLIQUE',
                'description' => 'Installation de systèmes de vidéosurveillance HD/4K, configuration d\'antennes paraboliques, maintenance et SAV. Solutions complètes pour la sécurité et le divertissement.',
                'image' => 'services/camera.jpg'
            ]
        ];

        \DB::table('services')->truncate();
        
        foreach ($services as $service) {
            \App\Models\Service::create($service);
            $this->info("Service '{$service['nom']}' créé avec succès.");
        }

        $this->info('Tous les services ont été créés avec succès !');
        return Command::SUCCESS;
    }
}
