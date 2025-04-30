<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInverterHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inverter_histories', function (Blueprint $table) {
            $table->id();
            $table->string('inverter_name');
            $table->timestamp('timestamp');
            $table->float('power')->nullable();
            $table->float('energy')->nullable();
            $table->float('voltage_dc')->nullable();
            $table->float('current_dc')->nullable();
            $table->float('voltage_ac')->nullable();
            $table->float('current_ac')->nullable();
            $table->float('frequency')->nullable();
            $table->float('temperature')->nullable();
            $table->float('efficiency')->nullable();
            $table->json('additional_data')->nullable();
            $table->timestamps();

            $table->index(['inverter_name', 'timestamp']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inverter_histories');
    }
}
