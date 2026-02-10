<?php

namespace Tests\Feature\Web;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PipelineMetricsWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_recruiter_can_fetch_pipeline_metrics_from_web_endpoint(): void
    {
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('recruiter');

        $company = Company::create(['name' => 'Acme', 'slug' => 'acme']);
        $position = Position::create([
            'company_id' => $company->id,
            'title' => 'Laravel Engineer',
            'employment_type' => 'full_time',
            'status' => 'published',
        ]);

        $candidateOne = Candidate::create(['full_name' => 'A', 'email' => 'a@example.test']);
        $candidateTwo = Candidate::create(['full_name' => 'B', 'email' => 'b@example.test']);

        Application::create([
            'position_id' => $position->id,
            'candidate_id' => $candidateOne->id,
            'current_stage' => 'screening',
            'status' => 'active',
        ]);
        Application::create([
            'position_id' => $position->id,
            'candidate_id' => $candidateTwo->id,
            'current_stage' => 'interview',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->getJson('/metrics/pipeline')
            ->assertOk()
            ->assertJsonStructure(['data' => ['funnel']]);
    }
}
