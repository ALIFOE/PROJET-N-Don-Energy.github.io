<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inverters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installation_id')->constrained()->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->string('serial_number')->nullable();
            $table->string('ip_address');
            $table->integer('port')->default(502);
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->default('disconnected');
            $table->timestamps();
            
            $table->index(['installation_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inverters');
    }
};