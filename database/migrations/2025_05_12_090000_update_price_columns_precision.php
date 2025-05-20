<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('prix', 12, 2)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_price', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->float('prix')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->float('total_price')->change();
        });
    }
};
