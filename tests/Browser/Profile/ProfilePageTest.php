<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows profile settings for an authenticated user', function (): void {
    $user = User::factory()->create();

    visit('/login')
        ->fill('Email', $user->email)
        ->fill('Password', 'password')
        ->press('Log in')
        ->assertPathIs('/dashboard');

    visit('/profile')
        ->assertPathIs('/profile')
        ->assertSee('Profile Settings')
        ->assertValue('Name', $user->name)
        ->assertValue('Email', $user->email);
});

it('updates profile name and email from browser', function (): void {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old-email@example.com',
    ]);

    visit('/login')
        ->fill('Email', $user->email)
        ->fill('Password', 'password')
        ->press('Log in')
        ->assertPathIs('/dashboard');

    visit('/profile')
        ->fill('Name', 'Updated Name')
        ->fill('Email', 'updated-email@example.com')
        ->press('Save')
        ->assertPathIs('/profile')
        ->assertSee('Saved.');
});
