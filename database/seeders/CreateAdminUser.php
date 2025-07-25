<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Seeder
{
    public function run()
    {
        User::create([            'name' => 'Administrateur',
            'email' => 'admin@CREFER.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
        ]);
    }
}
