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
        // Schema::create('formations', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('nom');
        //     $table->text('description')->nullable();
        //     $table->integer('duree')->comment('en heures');
        //     $table->decimal('prix', 10, 2);
        //     $table->string('statut')->default('active');
        //     $table->timestamps();
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('formations');
    }
};
