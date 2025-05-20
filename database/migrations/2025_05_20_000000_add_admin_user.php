<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'alifoebaudoin228@gmail.com',
            'password' => Hash::make('azerty1234'),
            'role' => 'admin',
        ]);
    }

    public function down(): void
    {
        User::where('email', 'alifoebaudoin228@gmail.com')->delete();
    }
};
