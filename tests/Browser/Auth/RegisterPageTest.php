<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the registration form in the browser', function (): void {
    visit('/register')
        ->assertPathIs('/register')
        ->assertSee('Create account')
        ->assertSee('Already registered?')
        ->assertSee('Register');
});

it('registers a new user and redirects to dashboard', function (): void {
    visit('/register')
        ->fill('Name', 'Browser User')
        ->fill('Email', 'browser-user@example.com')
        ->fill('Password', 'password')
        ->fill('Confirm password', 'password')
        ->press('Register')
        ->assertPathIs('/dashboard')
        ->assertSee('Recruiting Operations Dashboard');
});
