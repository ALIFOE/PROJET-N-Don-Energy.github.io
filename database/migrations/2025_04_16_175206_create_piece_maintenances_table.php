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
        Schema::create('piece_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->string('reference');
            $table->float('quantite');
            $table->float('prix_unitaire')->comment('en euros');
            $table->float('prix_total')->comment('en euros');
            $table->string('fournisseur')->nullable();
            $table->string('statut')->default('commande');
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
        Schema::dropIfExists('piece_maintenances');
    }
};
