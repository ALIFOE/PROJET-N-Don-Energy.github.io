<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('maintenance_tasks') && Schema::hasTable('installations')) {
            Schema::create('maintenance_tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('installation_id')->constrained('installations')->onDelete('cascade');
                $table->string('type');
                $table->text('description');
                $table->date('date');
                $table->string('statut')->default('en_cours');
                $table->string('priorite')->default('moyenne');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_tasks');
    }
}; 