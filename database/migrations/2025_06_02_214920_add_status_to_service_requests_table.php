<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    public function up()
    {
        if (Schema::hasTable('demande_services')) {
            Schema::table('demande_services', function (Blueprint $table) {
                if (!Schema::hasColumn('demande_services', 'status')) {
                    $table->string('status')->default('pending');
                }
            });
        }
    }public function down()
    {
        Schema::table('demande_services', function (Blueprint $table) {
            if (Schema::hasColumn('demande_services', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
