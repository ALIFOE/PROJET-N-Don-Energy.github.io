<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('admin can access admin routes', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'email' => 'admin@test.com'
    ]);

    $response = $this->actingAs($admin)
        ->get('/admin/dashboard');

    $response->assertStatus(200);
});

test('non-admin cannot access admin routes', function () {
    $user = User::factory()->create([
        'role' => 'client',
        'email' => 'client@test.com'
    ]);

    $response = $this->actingAs($user)
        ->get('/admin/dashboard');

    $response->assertRedirect('dashboard');
    $response->assertSessionHas('error', 'Accès non autorisé. Cette section est réservée aux administrateurs.');
});

test('guest cannot access admin routes', function () {
    $response = $this->get(route('admin.formations.inscriptions.index'));

    $response->assertRedirect('login');
});
