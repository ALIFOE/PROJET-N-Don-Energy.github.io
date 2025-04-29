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
        Schema::create('donnee_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained()->onDelete('cascade');
            $table->dateTime('date_heure');
            $table->float('puissance_instantanee')->comment('en W');
            $table->float('energie_jour')->comment('en kWh');
            $table->float('energie_mois')->comment('en kWh');
            $table->float('energie_annee')->comment('en kWh');
            $table->float('energie_totale')->comment('en kWh');
            $table->float('rendement')->comment('en %');
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
        Schema::dropIfExists('donnee_productions');
    }
};
