<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormationSeeder extends Seeder
{
    public function run()
    {
        DB::table('formations')->insert([
            [
                'nom' => 'Installation de Panneaux Solaires',
                'description' => 'Apprenez à installer des panneaux solaires photovoltaïques.',
                'duree' => '3 mois',
                'prix' => 1500.00,
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Maintenance et Dépannage',
                'description' => 'Formation sur la maintenance et le dépannage des installations solaires.',
                'duree' => '1 mois',
                'prix' => 800.00,
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Conception de Projets Solaires',
                'description' => 'Apprenez à concevoir des projets solaires de A à Z.',
                'duree' => '3 semaines',
                'prix' => 1200.00,
                'statut' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
