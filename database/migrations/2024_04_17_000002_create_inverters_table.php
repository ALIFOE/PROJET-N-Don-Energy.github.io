<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inverters') && Schema::hasTable('installations')) {
            Schema::create('inverters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('installation_id')->constrained()->onDelete('cascade');
                $table->string('brand');
                $table->string('model');
                $table->string('ip_address');
                $table->integer('port');
                $table->string('username');
                $table->string('password');
                $table->string('driver')->nullable();
                $table->enum('status', ['connecting', 'connected', 'disconnected', 'error'])->default('connecting');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inverters');
    }
}; 