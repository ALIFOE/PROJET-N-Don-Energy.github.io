<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'nom' => 'ÉLECTRICITÉ BÂTIMENT ET INDUSTRIELLE',
                'description' => 'Installation et maintenance des systèmes électriques pour bâtiments résidentiels et industriels. Notre expertise garantit des installations sûres et conformes aux normes.',
                'icon' => 'fas fa-bolt',
                'active' => true,
            ],
            [
                'nom' => 'EFFICACITÉ ÉNERGÉTIQUE',
                'description' => 'Solutions d\'optimisation énergétique pour réduire votre consommation et vos coûts. Audit énergétique et recommandations personnalisées.',
                'icon' => 'fas fa-leaf',
                'active' => true,
            ],
            [
                'nom' => 'ÉNERGIE SOLAIRE PHOTOVOLTAÏQUE',
                'description' => 'Installation de panneaux solaires et systèmes photovoltaïques complets. Production d\'énergie verte et durable.',
                'icon' => 'fas fa-solar-panel',
                'active' => true,
            ],
            [
                'nom' => 'SYSTEME D\'ALARME ET ÉCLAIRAGE DE SÉCURITÉ',
                'description' => 'Installation et maintenance de systèmes d\'alarme et d\'éclairage de sécurité. Protection optimale de vos locaux.',
                'icon' => 'fas fa-shield-alt',
                'active' => true,
            ],
            [
                'nom' => 'RÉSEAUX ET TÉLÉCOMUNICATION',
                'description' => 'Installation et configuration de réseaux informatiques et de systèmes de télécommunication. Solutions adaptées à vos besoins.',
                'icon' => 'fas fa-network-wired',
                'active' => true,
            ],
            [
                'nom' => 'CAMERA DE SURVEILLANCE ET ANTENNE PARABOLIQUE',
                'description' => 'Installation de systèmes de vidéosurveillance et d\'antennes paraboliques. Sécurité et connectivité optimales.',
                'icon' => 'fas fa-video',
                'active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
