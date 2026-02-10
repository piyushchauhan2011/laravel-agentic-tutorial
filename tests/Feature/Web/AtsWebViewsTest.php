<?php

namespace Tests\Feature\Web;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AtsWebViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruiter_can_view_candidates_applications_and_pipeline_pages(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('recruiter');

        $this->actingAs($user)->get('/candidates')->assertOk();
        $this->actingAs($user)->get('/applications')->assertOk();
        $this->actingAs($user)->get('/pipelines')->assertOk();
    }

    public function test_recruiter_can_create_candidate_from_web_page(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('recruiter');

        $this->actingAs($user)
            ->post('/candidates', [
                'full_name' => 'Jane Candidate',
                'email' => 'jane@example.test',
                'phone' => '+12025550123',
                'score' => 78,
            ])
            ->assertRedirect('/candidates');

        $this->assertDatabaseHas('candidates', [
            'email' => 'jane@example.test',
            'full_name' => 'Jane Candidate',
        ]);
    }

    public function test_recruiter_can_create_application_from_web_page(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('recruiter');

        $company = Company::create([
            'name' => 'Acme Corp',
            'slug' => 'acme-corp',
        ]);

        $position = Position::create([
            'company_id' => $company->id,
            'title' => 'Laravel Developer',
            'employment_type' => 'full_time',
            'status' => 'published',
        ]);

        $candidate = Candidate::create([
            'full_name' => 'John Candidate',
            'email' => 'john@example.test',
        ]);

        $this->actingAs($user)
            ->post('/applications', [
                'position_id' => $position->id,
                'candidate_id' => $candidate->id,
                'current_stage' => 'applied',
                'status' => 'active',
                'source' => 'Referral',
            ])
            ->assertRedirect('/applications');

        $this->assertDatabaseHas('applications', [
            'position_id' => $position->id,
            'candidate_id' => $candidate->id,
            'current_stage' => 'applied',
            'status' => 'active',
        ]);
    }
}
