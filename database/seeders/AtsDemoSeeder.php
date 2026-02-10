<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class AtsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@atslab.test'],
            ['name' => 'ATS Admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        $company = Company::firstOrCreate(
            ['slug' => 'acme-inc'],
            ['name' => 'Acme Inc', 'website' => 'https://example.com']
        );

        $position = Position::firstOrCreate(
            ['company_id' => $company->id, 'title' => 'Laravel Engineer'],
            [
                'department' => 'Engineering',
                'employment_type' => 'full_time',
                'location' => 'Remote',
                'description' => 'Build ATS workflows with Laravel and Postgres.',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ]
        );

        $candidate = Candidate::firstOrCreate(
            ['email' => 'candidate@demo.test'],
            ['full_name' => 'Taylor Candidate', 'phone' => '+1-555-1000']
        );

        Application::firstOrCreate(
            ['position_id' => $position->id, 'candidate_id' => $candidate->id],
            [
                'submitted_by' => $admin->id,
                'current_stage' => 'screening',
                'source' => 'linkedin',
                'status' => 'active',
                'applied_at' => now()->subDay(),
            ]
        );
    }
}
