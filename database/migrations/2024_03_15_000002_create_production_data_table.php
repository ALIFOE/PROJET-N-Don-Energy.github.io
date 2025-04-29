<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('production_data') && Schema::hasTable('installations')) {
            Schema::create('production_data', function (Blueprint $table) {
                $table->id();
                $table->foreignId('installation_id')->constrained('installations')->onDelete('cascade');
                $table->timestamp('timestamp');
                $table->decimal('current_power', 10, 2);
                $table->decimal('daily_energy', 10, 2);
                $table->decimal('total_energy', 10, 2);
                $table->decimal('temperature', 5, 2)->nullable();
                $table->decimal('irradiance', 8, 2)->nullable();
                $table->decimal('efficiency', 5, 2)->nullable();
                $table->timestamps();

                $table->index(['installation_id', 'timestamp']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('production_data');
    }
}; 