<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('donnee_productions', function (Blueprint $table) {
            $table->float('temperature')->nullable()->comment('en °C')->after('rendement');
            $table->float('irradiance')->nullable()->comment('en W/m²')->after('temperature');
            $table->float('niveau_batterie')->nullable()->comment('en %')->after('irradiance');
            $table->string('code_erreur')->nullable()->after('niveau_batterie');
            $table->string('code_avertissement')->nullable()->after('code_erreur');
        });
    }

    public function down()
    {
        Schema::table('donnee_productions', function (Blueprint $table) {
            $table->dropColumn([
                'temperature',
                'irradiance',
                'niveau_batterie',
                'code_erreur',
                'code_avertissement'
            ]);
        });
    }
};
