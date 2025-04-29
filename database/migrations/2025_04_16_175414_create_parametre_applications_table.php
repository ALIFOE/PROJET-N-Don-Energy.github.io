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
        Schema::create('parametre_applications', function (Blueprint $table) {
            $table->id();
            $table->string('cle');
            $table->text('valeur');
            $table->text('description')->nullable();
            $table->string('type')->default('texte');
            $table->string('groupe')->default('general');
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
        Schema::dropIfExists('parametre_applications');
    }
};
