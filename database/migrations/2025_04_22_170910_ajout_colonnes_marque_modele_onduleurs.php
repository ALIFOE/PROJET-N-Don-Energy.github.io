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
        // Schema::table('onduleurs', function (Blueprint $table) {
        //     if (!Schema::hasColumn('onduleurs', 'marque')) {
        //         $table->string('marque')->nullable()->after('installation_id');
        //     }
        //     if (!Schema::hasColumn('onduleurs', 'modele')) {
        //         $table->string('modele')->nullable()->after('marque');
        //     }
        // });
    }

    public function down()
    {
        // Schema::table('onduleurs', function (Blueprint $table) {
        //     if (Schema::hasColumn('onduleurs', 'marque')) {
        //         $table->dropColumn('marque');
        //     }
        //     if (Schema::hasColumn('onduleurs', 'modele')) {
        //         $table->dropColumn('modele');
        //     }
        // });
    }
};
