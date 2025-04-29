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
        Schema::create('panneaus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_panneau_id')->constrained('type_panneaus')->onDelete('cascade');
            $table->string('numero_serie')->unique();
            $table->float('orientation')->comment('en degrés');
            $table->float('inclinaison')->comment('en degrés');
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
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
        Schema::dropIfExists('panneaus');
    }
};
