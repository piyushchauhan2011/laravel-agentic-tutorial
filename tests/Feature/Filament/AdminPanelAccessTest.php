<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(RoleSeeder::class);
});

it('redirects guests to filament admin login', function (): void {
    $this->get('/admin')->assertRedirect('/admin/login');
});

it('allows admins to access the filament panel', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)->get('/admin')->assertSuccessful();
});

it('allows recruiters to access the filament panel', function (): void {
    $recruiter = User::factory()->create();
    $recruiter->assignRole('recruiter');

    $this->actingAs($recruiter)->get('/admin')->assertSuccessful();
});

it('forbids viewers from accessing the filament panel', function (): void {
    $viewer = User::factory()->create();
    $viewer->assignRole('viewer');

    $this->actingAs($viewer)->get('/admin')->assertForbidden();
});
