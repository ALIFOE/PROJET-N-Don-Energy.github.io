<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->json('alerte_meteo_config')->nullable();
        });
    }

    public function down()
    {
        Schema::table('installations', function (Blueprint $table) {
            $table->dropColumn('alerte_meteo_config');
        });
    }
};