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
    public function up(): void
    {
        Schema::create('tarif_electricites', function (Blueprint $table) {
            $table->id();
            $table->string('fournisseur');
            $table->string('offre');
            $table->float('prix_kwh')->comment('en euros');
            $table->float('prix_abonnement')->comment('en euros/mois');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->string('statut')->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_electricites');
    }
};
