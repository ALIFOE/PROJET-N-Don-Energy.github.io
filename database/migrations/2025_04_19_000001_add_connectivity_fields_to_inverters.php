<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inverters', function (Blueprint $table) {
            $table->string('connection_type')->default('modbus_tcp');
            $table->json('connection_config')->nullable();
            $table->timestamp('last_connected_at')->nullable();
            $table->string('connection_status')->default('disconnected');
            $table->json('last_error')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inverters', function (Blueprint $table) {
            $table->dropColumn([
                'connection_type',
                'connection_config',
                'last_connected_at',
                'connection_status',
                'last_error'
            ]);
        });
    }
};