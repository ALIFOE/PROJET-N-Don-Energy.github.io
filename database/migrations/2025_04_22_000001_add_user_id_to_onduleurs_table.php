<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToOnduleursTable extends Migration
{
    public function up()
    {
        // Migration désactivée car la colonne user_id est déjà gérée dans la migration principale
        // Schema::table('onduleurs', function (Blueprint $table) {
        //     $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        // });
    }

    public function down()
    {
        // Schema::table('onduleurs', function (Blueprint $table) {
        //     $table->dropForeign(['user_id']);
        //     $table->dropColumn('user_id');
        // });
    }
}