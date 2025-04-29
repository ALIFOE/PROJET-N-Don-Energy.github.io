<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inverter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inverter_id')->constrained()->onDelete('cascade');
            $table->string('reading_type');
            $table->json('data');
            $table->timestamp('read_at');
            $table->timestamps();
            
            $table->index(['inverter_id', 'reading_type', 'read_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inverter_readings');
    }
};