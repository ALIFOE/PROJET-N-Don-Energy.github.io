<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
                'nom' => 'ÉLECTRICITÉ BÂTIMENT ET INDUSTRIELLE',
                'description' => 'Installation et maintenance électrique pour bâtiments et industries',
                'champs_requis' => [
                    'type_batiment' => ['type' => 'select', 'options' => ['Résidentiel', 'Commercial', 'Industriel']],
                    'surface_approximative' => ['type' => 'number', 'label' => 'Surface approximative (m²)'],
                    'type_intervention' => ['type' => 'select', 'options' => ['Installation', 'Rénovation', 'Maintenance']],
                    'urgence' => ['type' => 'boolean', 'label' => 'Intervention urgente requise']
                ]
            ],
            [
                'nom' => 'EFFICACITÉ ÉNERGÉTIQUE',
                'description' => 'Audit et optimisation de la consommation énergétique',
                'champs_requis' => [
                    'consommation_actuelle' => ['type' => 'number', 'label' => 'Consommation actuelle (kWh/an)'],
                    'type_audit' => ['type' => 'select', 'options' => ['Complet', 'Partiel', 'Spécifique']],
                    'objectifs' => ['type' => 'text', 'label' => 'Objectifs d\'amélioration'],
                    'facture_energetique' => ['type' => 'file', 'label' => 'Dernière facture énergétique']
                ]
            ],
            [
                'nom' => 'ÉNERGIE SOLAIRE PHOTOVOLTAÏQUE',
                'description' => 'Installation et maintenance de systèmes solaires photovoltaïques',
                'champs_requis' => [
                    'surface_toiture' => ['type' => 'number', 'label' => 'Surface de toiture disponible (m²)'],
                    'orientation' => ['type' => 'select', 'options' => ['Nord', 'Sud', 'Est', 'Ouest']],
                    'type_toiture' => ['type' => 'select', 'options' => ['Plate', 'Inclinée', 'Terrasse']],
                    'consommation_annuelle' => ['type' => 'number', 'label' => 'Consommation annuelle (kWh)']
                ]
            ],
            [
                'nom' => 'SYSTEME D\'ALARME ET ÉCLAIRAGE DE SÉCURITÉ',
                'description' => 'Installation et maintenance de systèmes de sécurité',
                'champs_requis' => [
                    'type_systeme' => ['type' => 'select', 'options' => ['Alarme intrusion', 'Éclairage de sécurité', 'Les deux']],
                    'nombre_points' => ['type' => 'number', 'label' => 'Nombre de points à sécuriser'],
                    'controle_acces' => ['type' => 'boolean', 'label' => 'Contrôle d\'accès requis'],
                    'camera_surveillance' => ['type' => 'boolean', 'label' => 'Caméras de surveillance requises']
                ]
            ],
            [
                'nom' => 'RÉSEAUX ET TÉLÉCOMUNICATION',
                'description' => 'Installation et configuration de réseaux et systèmes de télécommunication',
                'champs_requis' => [
                    'type_reseau' => ['type' => 'select', 'options' => ['LAN', 'WAN', 'Téléphonie', 'Fibre optique']],
                    'nombre_points_acces' => ['type' => 'number', 'label' => 'Nombre de points d\'accès'],
                    'debit_souhaite' => ['type' => 'text', 'label' => 'Débit souhaité'],
                    'besoin_specifique' => ['type' => 'text', 'label' => 'Besoins spécifiques']
                ]
            ],
            [
                'nom' => 'CAMERA DE SURVEILLANCE ET ANTENNE PARABOLIQUE',
                'description' => 'Installation et maintenance de systèmes de vidéosurveillance et antennes',
                'champs_requis' => [
                    'type_camera' => ['type' => 'select', 'options' => ['IP', 'Analogique', 'HD']],
                    'nombre_cameras' => ['type' => 'number', 'label' => 'Nombre de caméras'],
                    'stockage_requis' => ['type' => 'select', 'options' => ['Local', 'Cloud', 'Hybride']],
                    'antenne_parabolique' => ['type' => 'boolean', 'label' => 'Installation d\'antenne parabolique requise']
                ]
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
