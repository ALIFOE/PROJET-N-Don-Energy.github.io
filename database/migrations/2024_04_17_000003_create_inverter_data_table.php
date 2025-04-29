<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inverter_data') && Schema::hasTable('inverters')) {
            Schema::create('inverter_data', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inverter_id')->constrained()->onDelete('cascade');
                $table->float('power')->nullable();
                $table->float('voltage')->nullable();
                $table->float('current')->nullable();
                $table->float('frequency')->nullable();
                $table->float('temperature')->nullable();
                $table->float('daily_energy')->nullable();
                $table->float('total_energy')->nullable();
                $table->string('status')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inverter_data');
    }
}; 