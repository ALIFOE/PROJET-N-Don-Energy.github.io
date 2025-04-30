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
    public function up()
    {
        // Migration désactivée car remplacée par une version plus récente
        // Schema::create('formations', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nom');
        //     $table->text('description')->nullable();
        //     $table->string('duree')->nullable();
        //     $table->string('niveau')->nullable();
        //     $table->decimal('prix', 8, 2)->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('formations');
    }
};
