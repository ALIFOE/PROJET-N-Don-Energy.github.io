<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInverterSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('inverter_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('inverter_name');
            $table->time('start_time');
            $table->time('end_time');
            $table->float('power_limit');
            $table->json('days');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('inverter_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inverter_schedules');
    }
}
