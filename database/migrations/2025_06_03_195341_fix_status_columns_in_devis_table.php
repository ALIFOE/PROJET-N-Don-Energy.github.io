<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Uniformiser d'abord les valeurs existantes
        DB::table('devis')->whereNotNull('statut')->update([
            'status' => DB::raw('CASE 
                WHEN statut = "en_attente" THEN "pending"
                WHEN statut = "en_cours" THEN "in_progress"
                WHEN statut = "accepte" THEN "accepted"
                WHEN statut = "refuse" THEN "rejected"
                ELSE statut
            END')
        ]);

        // Supprimer l'ancienne colonne statut
        Schema::table('devis', function (Blueprint $table) {
            $table->dropColumn('statut');
        });

        // S'assurer que le champ status a les bonnes valeurs par défaut
        Schema::table('devis', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->string('statut')->default('en_attente');
        });

        // Restaurer les anciennes valeurs
        DB::table('devis')->whereNotNull('status')->update([
            'statut' => DB::raw('CASE 
                WHEN status = "pending" THEN "en_attente"
                WHEN status = "in_progress" THEN "en_cours"
                WHEN status = "accepted" THEN "accepte"
                WHEN status = "rejected" THEN "refuse"
                ELSE status
            END')
        ]);
    }
};
