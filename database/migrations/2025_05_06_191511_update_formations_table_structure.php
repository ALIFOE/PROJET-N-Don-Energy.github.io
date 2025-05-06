<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formations', function (Blueprint $table) {
            // Supprimer les anciennes colonnes
            $table->dropColumn(['nom', 'duree', 'niveau']);
            
            // Ajouter les nouvelles colonnes
            $table->string('titre')->after('id');
            $table->date('date_debut')->after('description');
            $table->date('date_fin')->after('date_debut');
            $table->integer('places_disponibles')->after('prix');
            $table->text('prerequis')->nullable()->after('places_disponibles');
            $table->string('image')->nullable()->after('prerequis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formations', function (Blueprint $table) {
            // Restaurer les anciennes colonnes
            $table->string('nom')->after('id');
            $table->string('duree')->after('description');
            $table->string('niveau')->default('Débutant')->after('duree');
            
            // Supprimer les nouvelles colonnes
            $table->dropColumn([
                'titre',
                'date_debut',
                'date_fin',
                'places_disponibles',
                'prerequis',
                'image'
            ]);
        });
    }
};
