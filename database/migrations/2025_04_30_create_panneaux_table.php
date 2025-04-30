<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('panels', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('capacite_wc');
            $table->float('surface');
            $table->float('rendement');
            $table->string('fabricant');
            $table->string('modele');
            $table->integer('garantie_annees');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('panels');
    }
};