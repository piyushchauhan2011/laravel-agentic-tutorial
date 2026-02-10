<?php

it('renders the login form in the browser', function (): void {
    visit('/login')
        ->assertPathIs('/login')
        ->assertSee('Sign in')
        ->assertSee('Forgot your password?')
        ->assertSee('Log in')
        ->assertValue('Email', '')
        ->fill('Email', 'candidate@example.com')
        ->assertValue('Email', 'candidate@example.com');
});

it('navigates to password reset request page', function (): void {
    visit('/login')
        ->click('Forgot your password?')
        ->assertPathIs('/forgot-password')
        ->assertSee('Email Password Reset Link');
});
