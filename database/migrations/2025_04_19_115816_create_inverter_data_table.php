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
        Schema::create('inverter_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inverter_id')->constrained('onduleurs')->onDelete('cascade');
            $table->decimal('power', 10, 2)->nullable(); // Puissance actuelle en kW
            $table->decimal('voltage', 10, 2)->nullable(); // Tension en V
            $table->decimal('current', 10, 2)->nullable(); // Courant en A
            $table->decimal('frequency', 5, 2)->nullable(); // Fréquence en Hz
            $table->decimal('temperature', 5, 2)->nullable(); // Température en °C
            $table->decimal('daily_energy', 10, 2)->nullable(); // Production journalière en kWh
            $table->decimal('total_energy', 15, 2)->nullable(); // Production totale en kWh
            $table->decimal('efficiency', 5, 2)->nullable(); // Efficacité en %
            $table->string('status')->nullable(); // Statut de l'onduleur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inverter_data');
    }
};
