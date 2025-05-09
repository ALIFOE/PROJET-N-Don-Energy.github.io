<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('notification_preferences')->nullable();
            $table->string('theme_preference')->default('system');
            $table->string('language')->default('fr');
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notification_preferences',
                'theme_preference',
                'language',
                'two_factor_enabled',
                'two_factor_secret'
            ]);
        });
    }
};