<?php

use App\Models\Company;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a recruiter to create a position and see it in the list', function (): void {
    $this->seed(RoleSeeder::class);

    $recruiter = User::factory()->create();
    $recruiter->assignRole('recruiter');

    $company = Company::query()->create([
        'name' => 'Acme Labs',
        'slug' => 'acme-labs',
    ]);

    visit('/login')
        ->fill('Email', $recruiter->email)
        ->fill('Password', 'password')
        ->press('Log in')
        ->assertPathIs('/dashboard');

    visit('/positions')
        ->assertPathIs('/positions')
        ->assertSee('Positions')
        ->assertSee('No positions yet.')
        ->select('Company', (string) $company->id)
        ->fill('Title', 'Senior Laravel Engineer')
        ->fill('Department', 'Engineering')
        ->select('Employment Type', 'full_time')
        ->select('Status', 'published')
        ->fill('Location', 'Remote')
        ->fill('Description', 'Own the ATS platform roadmap.')
        ->click('#create-position-submit')
        ->assertPathIs('/positions')
        ->assertSee('Position created.')
        ->assertSee('Senior Laravel Engineer')
        ->assertSee('Acme Labs');
});
