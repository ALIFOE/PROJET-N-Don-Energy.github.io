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
    public function up()
    {
        // Migration désactivée car la colonne duree_recherche est déjà gérée dans la migration principale
        // Schema::table('onduleurs', function (Blueprint $table) {
        //     $table->integer('duree_recherche')->default(5)->after('est_connecte');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('onduleurs', function (Blueprint $table) {
        //     $table->dropColumn('duree_recherche');
        // });
    }
};
