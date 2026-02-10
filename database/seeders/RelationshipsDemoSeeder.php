<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Interview;
use App\Models\Offer;
use App\Models\Position;
use App\Models\Referral;
use App\Models\Skill;
use App\Models\SkillAssessment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class RelationshipsDemoSeeder extends Seeder
{
    /**
     * Seed demo data for advanced relationship patterns.
     */
    public function run(): void
    {
        $recruiter = User::where('email', 'recruiter@atslab.test')->first()
            ?? User::first();

        // -----------------------------------------------------------------
        // 1. Self-Referencing / Recursive / Tree: Departments
        // -----------------------------------------------------------------
        $engineering = Department::firstOrCreate(
            ['name' => 'Engineering'],
            ['description' => 'All engineering teams', 'level' => 0]
        );

        $backend = Department::firstOrCreate(
            ['name' => 'Backend Engineering'],
            ['description' => 'Server-side development', 'parent_id' => $engineering->id, 'level' => 1]
        );

        $frontend = Department::firstOrCreate(
            ['name' => 'Frontend Engineering'],
            ['description' => 'Client-side development', 'parent_id' => $engineering->id, 'level' => 1]
        );

        Department::firstOrCreate(
            ['name' => 'API Team'],
            ['description' => 'REST & GraphQL services', 'parent_id' => $backend->id, 'level' => 2]
        );

        Department::firstOrCreate(
            ['name' => 'Data Platform'],
            ['description' => 'ETL and data pipelines', 'parent_id' => $backend->id, 'level' => 2]
        );

        Department::firstOrCreate(
            ['name' => 'React Team'],
            ['description' => 'React and Next.js applications', 'parent_id' => $frontend->id, 'level' => 2]
        );

        $hr = Department::firstOrCreate(
            ['name' => 'Human Resources'],
            ['description' => 'People operations', 'level' => 0]
        );

        Department::firstOrCreate(
            ['name' => 'Talent Acquisition'],
            ['description' => 'Recruiting and hiring', 'parent_id' => $hr->id, 'level' => 1]
        );

        Department::firstOrCreate(
            ['name' => 'People Operations'],
            ['description' => 'Employee experience', 'parent_id' => $hr->id, 'level' => 1]
        );

        // -----------------------------------------------------------------
        // 2. Polymorphic Many-to-Many: Tags
        // -----------------------------------------------------------------
        $tagRemote = Tag::firstOrCreate(['slug' => 'remote'], ['name' => 'Remote', 'type' => 'location']);
        $tagHybrid = Tag::firstOrCreate(['slug' => 'hybrid'], ['name' => 'Hybrid', 'type' => 'location']);
        $tagSenior = Tag::firstOrCreate(['slug' => 'senior'], ['name' => 'Senior', 'type' => 'general']);
        $tagUrgent = Tag::firstOrCreate(['slug' => 'urgent'], ['name' => 'Urgent Hire', 'type' => 'general']);
        $tagPhp = Tag::firstOrCreate(['slug' => 'php'], ['name' => 'PHP', 'type' => 'skill']);
        $tagReact = Tag::firstOrCreate(['slug' => 'react'], ['name' => 'React', 'type' => 'skill']);
        $tagLaravel = Tag::firstOrCreate(['slug' => 'laravel'], ['name' => 'Laravel', 'type' => 'skill']);
        $tagStartup = Tag::firstOrCreate(['slug' => 'startup'], ['name' => 'Startup', 'type' => 'general']);

        // Attach tags to companies
        $companies = Company::all();
        foreach ($companies as $company) {
            $company->tags()->syncWithoutDetaching([$tagStartup->id]);
        }
        if ($companies->first()) {
            $companies->first()->tags()->syncWithoutDetaching([$tagRemote->id]);
        }

        // Attach tags to positions
        $positions = Position::all();
        foreach ($positions as $position) {
            $tags = [$tagSenior->id];
            if (str_contains(strtolower($position->title), 'laravel') || str_contains(strtolower($position->title), 'backend')) {
                $tags[] = $tagPhp->id;
                $tags[] = $tagLaravel->id;
                $tags[] = $tagRemote->id;
            }
            if (str_contains(strtolower($position->title), 'frontend')) {
                $tags[] = $tagReact->id;
                $tags[] = $tagHybrid->id;
                $tags[] = $tagUrgent->id;
            }
            $position->tags()->syncWithoutDetaching($tags);
        }

        // Attach tags to candidates
        $candidates = Candidate::all();
        foreach ($candidates as $index => $candidate) {
            $tags = [];
            if ($index % 2 === 0) {
                $tags[] = $tagPhp->id;
                $tags[] = $tagLaravel->id;
            } else {
                $tags[] = $tagReact->id;
            }
            if ($index < 3) {
                $tags[] = $tagSenior->id;
            }
            $candidate->tags()->syncWithoutDetaching($tags);
        }

        // -----------------------------------------------------------------
        // 3. Ternary Relationship: Skills + Skill Assessments
        // -----------------------------------------------------------------
        $skillPhp = Skill::firstOrCreate(['name' => 'PHP'], ['category' => 'technical']);
        $skillLaravel = Skill::firstOrCreate(['name' => 'Laravel'], ['category' => 'technical']);
        $skillJs = Skill::firstOrCreate(['name' => 'JavaScript'], ['category' => 'technical']);
        $skillReact = Skill::firstOrCreate(['name' => 'React'], ['category' => 'technical']);
        $skillSql = Skill::firstOrCreate(['name' => 'SQL'], ['category' => 'technical']);
        $skillComm = Skill::firstOrCreate(['name' => 'Communication'], ['category' => 'soft']);
        $skillLeader = Skill::firstOrCreate(['name' => 'Leadership'], ['category' => 'leadership']);
        $skillSystem = Skill::firstOrCreate(['name' => 'System Design'], ['category' => 'domain']);

        $allSkills = [$skillPhp, $skillLaravel, $skillJs, $skillReact, $skillSql, $skillComm, $skillLeader, $skillSystem];

        // Create assessments for each candidate-position-skill combination
        foreach (Application::with(['candidate', 'position'])->limit(6)->get() as $app) {
            $skillSubset = fake()->randomElements($allSkills, min(4, count($allSkills)));
            foreach ($skillSubset as $skill) {
                SkillAssessment::firstOrCreate(
                    [
                        'candidate_id' => $app->candidate_id,
                        'position_id' => $app->position_id,
                        'skill_id' => $skill->id,
                    ],
                    [
                        'rating' => fake()->numberBetween(3, 10),
                        'assessed_by' => $recruiter?->id,
                        'assessed_at' => now()->subDays(fake()->numberBetween(1, 14)),
                    ]
                );
            }
        }

        // -----------------------------------------------------------------
        // 4. ARC (Exclusive Arc): Feedback for Interviews & Offers
        // -----------------------------------------------------------------
        $applications = Application::all();

        // Create interviews for candidates in screening/interview stages
        foreach ($applications->whereIn('current_stage', ['screening', 'interview']) as $app) {
            $interview = Interview::firstOrCreate(
                ['application_id' => $app->id],
                [
                    'scheduled_for' => now()->addDays(fake()->numberBetween(1, 14)),
                    'interviewer_name' => fake()->name(),
                    'interviewer_email' => fake()->safeEmail(),
                    'mode' => fake()->randomElement(['video', 'phone', 'onsite']),
                    'status' => fake()->randomElement(['scheduled', 'completed']),
                ]
            );

            // ARC feedback on interview
            Feedback::firstOrCreate(
                ['interview_id' => $interview->id, 'offer_id' => null],
                [
                    'rating' => fake()->numberBetween(5, 10),
                    'comments' => fake()->randomElement([
                        'Strong technical skills, good cultural fit.',
                        'Excellent problem-solving abilities demonstrated.',
                        'Good communication, needs more experience in system design.',
                        'Very impressive candidate, highly recommended.',
                    ]),
                    'author_id' => $recruiter?->id,
                ]
            );
        }

        // Create offers for candidates in offer/hired stages
        foreach ($applications->whereIn('current_stage', ['offer', 'hired']) as $app) {
            $offer = Offer::firstOrCreate(
                ['application_id' => $app->id],
                [
                    'salary' => fake()->numberBetween(80, 180) * 1000,
                    'currency' => 'USD',
                    'status' => $app->current_stage === 'hired' ? 'accepted' : 'pending',
                    'expires_at' => now()->addDays(14),
                ]
            );

            // ARC feedback on offer (not interview)
            Feedback::firstOrCreate(
                ['interview_id' => null, 'offer_id' => $offer->id],
                [
                    'rating' => fake()->numberBetween(7, 10),
                    'comments' => fake()->randomElement([
                        'Competitive offer, expecting acceptance.',
                        'Offer matches market rate for this role.',
                        'Candidate negotiated slightly higher, approved.',
                    ]),
                    'author_id' => $recruiter?->id,
                ]
            );
        }

        // -----------------------------------------------------------------
        // 5. Self-Referencing (Candidates): Referrals
        // -----------------------------------------------------------------
        if ($candidates->count() >= 4) {
            Referral::firstOrCreate(
                ['candidate_id' => $candidates[1]->id, 'position_id' => $positions->first()->id],
                [
                    'referred_by_candidate_id' => $candidates[0]->id,
                    'status' => 'accepted',
                    'notes' => 'Former colleague, strong recommendation.',
                ]
            );

            Referral::firstOrCreate(
                ['candidate_id' => $candidates[3]->id, 'position_id' => $positions->first()->id],
                [
                    'referred_by_candidate_id' => $candidates[2]->id,
                    'status' => 'pending',
                    'notes' => 'Met at a tech conference.',
                ]
            );

            if ($positions->count() >= 2) {
                Referral::firstOrCreate(
                    ['candidate_id' => $candidates[2]->id, 'position_id' => $positions[1]->id],
                    [
                        'referred_by_candidate_id' => $candidates[0]->id,
                        'status' => 'hired',
                        'notes' => 'University classmate, excellent match for the role.',
                    ]
                );
            }
        }
    }
}
