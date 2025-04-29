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
        Schema::create('dimensionnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->string('email');
            $table->string('telephone');
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('pays');
            $table->float('surface_disponible')->nullable();
            $table->float('consommation_annuelle')->nullable();
            $table->float('puissance_installee')->nullable();
            $table->float('production_annuelle_estimee')->nullable();
            $table->float('economie_annuelle_estimee')->nullable();
            $table->float('taux_autoconsommation')->nullable();
            $table->float('taux_autoproduction')->nullable();
            $table->float('prix_kwh')->nullable();
            $table->float('budget_total')->nullable();
            $table->float('duree_amortissement')->nullable();
            $table->float('rentabilite')->nullable();
            $table->string('statut')->default('en_cours');
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
        Schema::dropIfExists('dimensionnements');
    }
};
