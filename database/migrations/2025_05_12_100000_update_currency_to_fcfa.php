<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mise à jour des commentaires pour les coûts de consommation
        Schema::table('donnee_consommations', function (Blueprint $table) {
            $table->float('cout_jour')->comment('en FCFA')->change();
            $table->float('cout_mois')->comment('en FCFA')->change();
            $table->float('cout_annee')->comment('en FCFA')->change();
        });

        // Mise à jour des commentaires pour les tarifs d'électricité
        Schema::table('tarif_electricites', function (Blueprint $table) {
            $table->float('prix_kwh')->comment('en FCFA')->change();
            $table->float('prix_abonnement')->comment('en FCFA/mois')->change();
        });

        // Vérifier et mettre à jour les prix dans les tables products et orders
        DB::statement('UPDATE products SET prix = prix * 655.957 WHERE prix > 0');
        DB::statement('UPDATE orders SET total_price = total_price * 655.957 WHERE total_price > 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retour aux commentaires en euros
        Schema::table('donnee_consommations', function (Blueprint $table) {
            $table->float('cout_jour')->comment('en euros')->change();
            $table->float('cout_mois')->comment('en euros')->change();
            $table->float('cout_annee')->comment('en euros')->change();
        });

        Schema::table('tarif_electricites', function (Blueprint $table) {
            $table->float('prix_kwh')->comment('en euros')->change();
            $table->float('prix_abonnement')->comment('en euros/mois')->change();
        });

        // Retour aux prix en euros
        DB::statement('UPDATE products SET prix = prix / 655.957 WHERE prix > 0');
        DB::statement('UPDATE orders SET total_price = total_price / 655.957 WHERE total_price > 0');
    }
};
