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
        Schema::create('donnee_meteos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained()->onDelete('cascade');
            $table->dateTime('date_heure');
            $table->float('temperature')->comment('en °C');
            $table->float('humidite')->comment('en %');
            $table->float('vitesse_vent')->comment('en m/s');
            $table->float('direction_vent')->comment('en degrés');
            $table->float('irradiation')->comment('en W/m²');
            $table->float('ensoleillement')->comment('en heures');
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
        Schema::dropIfExists('donnee_meteos');
    }
};
