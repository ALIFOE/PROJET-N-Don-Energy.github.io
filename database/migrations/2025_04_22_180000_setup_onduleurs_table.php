<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('onduleurs');
        
        Schema::create('onduleurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('marque');
            $table->string('modele');
            $table->string('numero_serie')->unique();
            $table->decimal('puissance_nominale', 10, 2)->nullable();
            $table->foreignId('installation_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('est_connecte')->default(false);
            $table->boolean('connectable')->default(true);
            $table->date('date_installation')->nullable();
            $table->date('dernier_entretien')->nullable();
            $table->date('prochain_entretien')->nullable();
            $table->string('statut')->default('actif');
            $table->integer('duree_recherche')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        Schema::dropIfExists('onduleurs');
    }
};
