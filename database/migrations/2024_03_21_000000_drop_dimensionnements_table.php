<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('dimensionnements');
    }

    public function down()
    {
        // Cette méthode est vide car nous ne voulons pas recréer la table en cas de rollback
    }
}; 