<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows database learning page for recruiters', function (): void {
    $this->seed(RoleSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('recruiter');

    $this->actingAs($user)
        ->get('/learn/database')
        ->assertSuccessful()
        ->assertSee('Database Relationships')
        ->assertSee('CTE Query Patterns');
});
