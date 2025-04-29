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
        Schema::create('systeme_stockages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained()->onDelete('cascade');
            $table->string('marque');
            $table->string('modele');
            $table->float('capacite_nominale')->comment('en kWh');
            $table->float('tension_nominale')->comment('en V');
            $table->float('courant_nominal')->comment('en A');
            $table->float('rendement')->comment('en %');
            $table->float('prix_unitaire')->comment('en euros');
            $table->date('date_installation');
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
        Schema::dropIfExists('systeme_stockages');
    }
};
