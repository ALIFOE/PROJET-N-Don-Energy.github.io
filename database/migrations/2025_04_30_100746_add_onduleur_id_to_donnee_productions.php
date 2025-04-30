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
        Schema::table('donnee_productions', function (Blueprint $table) {
            $table->foreignId('onduleur_id')->after('installation_id')->constrained('onduleurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donnee_productions', function (Blueprint $table) {
            $table->dropForeign(['onduleur_id']);
            $table->dropColumn('onduleur_id');
        });
    }
};
