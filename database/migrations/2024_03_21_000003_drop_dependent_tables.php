<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Supprimer d'abord les tables qui dépendent de installations
        Schema::dropIfExists('maintenance_tasks');
        Schema::dropIfExists('production_data');
        Schema::dropIfExists('installations');
    }

    public function down()
    {
        // Cette méthode est vide car nous ne voulons pas recréer les tables en cas de rollback
    }
}; 