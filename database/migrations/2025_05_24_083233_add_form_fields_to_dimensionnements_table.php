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
        Schema::table('dimensionnements', function (Blueprint $table) {
            $table->float('surface_toiture')->nullable();
            $table->string('orientation')->nullable();
            $table->string('type_installation')->nullable();
            $table->json('equipements')->nullable();
            $table->json('objectifs')->nullable();
            $table->float('facture_annuelle')->nullable();
            $table->string('fournisseur')->nullable();
            $table->integer('nb_personnes')->nullable();
            $table->float('budget')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dimensionnements', function (Blueprint $table) {
            $table->dropColumn([
                'surface_toiture',
                'orientation',
                'type_installation',
                'equipements',
                'objectifs',
                'facture_annuelle',
                'fournisseur',
                'nb_personnes',
                'budget'
            ]);
        });
    }
};
