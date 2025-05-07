<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Mettre à jour toutes les formations qui n'ont pas de statut
        DB::table('formations')
            ->whereNull('statut')
            ->update(['statut' => 'active']);

        // S'assurer que la colonne statut a une valeur par défaut
        Schema::table('formations', function (Blueprint $table) {
            $table->string('statut')->default('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->string('statut')->default(null)->change();
        });
    }
};
