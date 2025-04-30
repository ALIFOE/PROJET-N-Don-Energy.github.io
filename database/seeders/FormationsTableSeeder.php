<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormationsTableSeeder extends Seeder
{
    public function run()
    {
        $formations = [
            [
                'nom' => 'Installation de Panneaux Solaires',
                'description' => 'Formation complète sur l\'installation et la maintenance de panneaux solaires photovoltaïques. Apprenez les meilleures pratiques et les normes de sécurité.',
                'duree' => '4 semaines',
                'niveau' => 'Débutant',
                'prix' => 1200.00,
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Dimensionnement de Systèmes Solaires',
                'description' => 'Maîtrisez les techniques de dimensionnement des installations solaires pour répondre aux besoins spécifiques des clients.',
                'duree' => '3 semaines',
                'niveau' => 'Intermédiaire',
                'prix' => 1500.00,
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Maintenance Avancée des Systèmes Solaires',
                'description' => 'Formation approfondie sur la maintenance préventive et corrective des installations solaires photovoltaïques.',
                'duree' => '6 semaines',
                'niveau' => 'Avancé',
                'prix' => 2000.00,
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'Gestion de Projets Solaires',
                'description' => 'Développez vos compétences en gestion de projets d\'installations solaires, de la conception à la mise en service.',
                'duree' => '8 semaines',
                'niveau' => 'Expert',
                'prix' => 2500.00,
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('formations')->insert($formations);
    }
}
