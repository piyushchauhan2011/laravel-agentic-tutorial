<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PositionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_recruiter_can_create_and_list_positions(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('recruiter');
        Sanctum::actingAs($user);

        $company = Company::create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $payload = [
            'company_id' => $company->id,
            'title' => 'Senior Laravel Engineer',
            'department' => 'Engineering',
            'employment_type' => 'full_time',
            'location' => 'Remote',
            'status' => 'published',
        ];

        $this->postJson('/api/v1/positions', $payload)->assertCreated();

        $this->getJson('/api/v1/positions')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Senior Laravel Engineer');
    }
}
