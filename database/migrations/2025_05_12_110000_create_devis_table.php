<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->string('telephone');
            $table->text('adresse');
            $table->string('type_batiment');
            $table->decimal('facture_mensuelle', 10, 2)->nullable();
            $table->decimal('consommation_annuelle', 10, 2)->nullable();
            $table->string('type_toiture')->nullable();
            $table->string('orientation')->nullable();
            $table->json('objectifs')->nullable();
            $table->text('message')->nullable();
            $table->json('analyse_technique')->nullable();
            $table->string('statut')->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
