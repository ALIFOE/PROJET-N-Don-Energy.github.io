<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('exigence_normes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('norme_technique_id')->constrained('norme_techniques')->onDelete('cascade');
            $table->string('reference');
            $table->string('titre');
            $table->text('description');
            $table->string('categorie');
            $table->string('statut')->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('exigence_normes');
    }
};
