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

        $recruiter = User::firstOrCreate(
            ['email' => 'recruiter@atslab.test'],
            ['name' => 'ATS Recruiter', 'password' => bcrypt('password')]
        );
        $recruiter->assignRole('recruiter');

        $acme = Company::firstOrCreate(
            ['slug' => 'acme-inc'],
            ['name' => 'Acme Inc', 'website' => 'https://example.com']
        );

        $globex = Company::firstOrCreate(
            ['slug' => 'globex-corp'],
            ['name' => 'Globex Corp', 'website' => 'https://globex.example']
        );

        $positionLaravel = Position::firstOrCreate(
            ['company_id' => $acme->id, 'title' => 'Laravel Engineer'],
            [
                'department' => 'Engineering',
                'employment_type' => 'full_time',
                'location' => 'Remote',
                'description' => 'Build ATS workflows with Laravel and Postgres.',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ]
        );

        $positionFrontend = Position::firstOrCreate(
            ['company_id' => $globex->id, 'title' => 'Frontend Engineer'],
            [
                'department' => 'Product Engineering',
                'employment_type' => 'full_time',
                'location' => 'Austin, TX',
                'description' => 'Build recruiter and hiring manager interfaces.',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ]
        );

        $demoCandidates = [
            ['name' => 'Taylor Candidate', 'email' => 'candidate@demo.test', 'stage' => 'screening', 'status' => 'active', 'source' => 'linkedin', 'position' => $positionLaravel],
            ['name' => 'Jordan Brooks', 'email' => 'jordan@demo.test', 'stage' => 'applied', 'status' => 'active', 'source' => 'careers_site', 'position' => $positionLaravel],
            ['name' => 'Casey Morgan', 'email' => 'casey@demo.test', 'stage' => 'interview', 'status' => 'active', 'source' => 'referral', 'position' => $positionLaravel],
            ['name' => 'Robin Patel', 'email' => 'robin@demo.test', 'stage' => 'offer', 'status' => 'active', 'source' => 'linkedin', 'position' => $positionFrontend],
            ['name' => 'Drew Garcia', 'email' => 'drew@demo.test', 'stage' => 'hired', 'status' => 'hired', 'source' => 'referral', 'position' => $positionFrontend],
            ['name' => 'Avery Kim', 'email' => 'avery@demo.test', 'stage' => 'rejected', 'status' => 'rejected', 'source' => 'agency', 'position' => $positionFrontend],
            ['name' => 'Sam Nguyen', 'email' => 'sam@demo.test', 'stage' => 'screening', 'status' => 'active', 'source' => 'linkedin', 'position' => $positionFrontend],
            ['name' => 'Riley Chen', 'email' => 'riley@demo.test', 'stage' => 'interview', 'status' => 'active', 'source' => 'careers_site', 'position' => $positionLaravel],
        ];

        foreach ($demoCandidates as $index => $data) {
            $candidate = Candidate::firstOrCreate(
                ['email' => $data['email']],
                [
                    'full_name' => $data['name'],
                    'phone' => '+1-555-10'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'current_company' => fake()->company(),
                    'score' => fake()->numberBetween(62, 96),
                ]
            );

            Application::firstOrCreate(
                ['position_id' => $data['position']->id, 'candidate_id' => $candidate->id],
                [
                    'submitted_by' => $recruiter->id,
                    'current_stage' => $data['stage'],
                    'source' => $data['source'],
                    'status' => $data['status'],
                    'applied_at' => now()->subDays($index + 1),
                ]
            );
        }
    }
}
