<?php

use App\Models\User;

it('shows the relationships page for authenticated users', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($user)
        ->get('/relationships')
        ->assertSuccessful()
        ->assertSee('Database Relationships Explorer')
        ->assertSee('Self-Referencing / Recursive / Tree')
        ->assertSee('Polymorphic Many-to-Many: Tags')
        ->assertSee('Ternary Relationship: Skill Assessments')
        ->assertSee('ARC (Exclusive Arc): Feedback')
        ->assertSee('Self-Referencing: Candidate Referrals')
        ->assertSee('CTE: Pipeline Funnel');
});

it('requires authentication for relationships page', function () {
    $this->get('/relationships')
        ->assertRedirect('/login');
});
