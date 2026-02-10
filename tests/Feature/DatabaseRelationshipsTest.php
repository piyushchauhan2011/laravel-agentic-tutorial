<?php

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

it('creates a self-referencing department tree', function () {
    $root = Department::create(['name' => 'Engineering', 'level' => 0]);
    $child = Department::create(['name' => 'Backend', 'parent_id' => $root->id, 'level' => 1]);

    expect($child->parent->id)->toBe($root->id);
    expect($root->children)->toHaveCount(1);
    expect($root->children->first()->name)->toBe('Backend');
});

it('uses recursive CTE to get descendants', function () {
    $root = Department::create(['name' => 'Engineering', 'level' => 0]);
    $child = Department::create(['name' => 'Backend', 'parent_id' => $root->id, 'level' => 1]);
    $grandchild = Department::create(['name' => 'API Team', 'parent_id' => $child->id, 'level' => 2]);

    $descendants = $root->getDescendants();

    expect($descendants)->toHaveCount(2);
});

it('supports polymorphic tagging', function () {
    $tag = Tag::create(['name' => 'Remote', 'slug' => 'remote', 'type' => 'general']);
    $company = Company::create(['name' => 'TagCo', 'slug' => 'tagco']);
    $candidate = Candidate::create(['full_name' => 'Jane Doe', 'email' => 'jane@example.com']);

    $company->tags()->attach($tag);
    $candidate->tags()->attach($tag);

    expect($tag->companies)->toHaveCount(1);
    expect($tag->candidates)->toHaveCount(1);
    expect($company->tags->first()->name)->toBe('Remote');
});

it('creates ternary skill assessment', function () {
    $company = Company::create(['name' => 'SkillCo', 'slug' => 'skillco']);
    $position = Position::create(['title' => 'Developer', 'company_id' => $company->id, 'status' => 'open']);
    $candidate = Candidate::create(['full_name' => 'John Doe', 'email' => 'john@example.com']);
    $skill = Skill::create(['name' => 'PHP', 'category' => 'technical']);

    $assessment = SkillAssessment::create([
        'candidate_id' => $candidate->id,
        'position_id' => $position->id,
        'skill_id' => $skill->id,
        'rating' => 8,
    ]);

    expect($assessment->candidate->full_name)->toBe('John Doe');
    expect($assessment->position->title)->toBe('Developer');
    expect($assessment->skill->name)->toBe('PHP');
    expect($assessment->rating)->toBe(8);
});

it('creates ARC feedback for interview or offer', function () {
    $company = Company::create(['name' => 'ArcCo', 'slug' => 'arcco']);
    $position = Position::create(['title' => 'Dev', 'company_id' => $company->id, 'status' => 'open']);
    $candidate = Candidate::create(['full_name' => 'Jane', 'email' => 'jane-arc@test.com']);
    $application = \App\Models\Application::create([
        'position_id' => $position->id,
        'candidate_id' => $candidate->id,
        'current_stage' => 'applied',
        'status' => 'active',
    ]);
    $interview = Interview::create([
        'application_id' => $application->id,
        'scheduled_for' => now()->addDays(1),
        'interviewer_name' => 'Bob',
        'interviewer_email' => 'bob@test.com',
        'status' => 'scheduled',
    ]);

    $feedback = Feedback::create([
        'interview_id' => $interview->id,
        'rating' => 9,
        'comments' => 'Great candidate',
    ]);

    expect($feedback->subject_type)->toBe('interview');
    expect($feedback->interview->id)->toBe($interview->id);
    expect($feedback->offer)->toBeNull();
});

it('creates candidate referrals', function () {
    $company = Company::create(['name' => 'RefCo', 'slug' => 'refco']);
    $position = Position::create(['title' => 'Dev', 'company_id' => $company->id, 'status' => 'open']);
    $referrer = Candidate::create(['full_name' => 'Alice', 'email' => 'alice-ref@test.com']);
    $referred = Candidate::create(['full_name' => 'Bob', 'email' => 'bob-ref@test.com']);

    $referral = Referral::create([
        'candidate_id' => $referred->id,
        'referred_by_candidate_id' => $referrer->id,
        'position_id' => $position->id,
        'status' => 'pending',
    ]);

    expect($referral->candidate->full_name)->toBe('Bob');
    expect($referral->referrer->full_name)->toBe('Alice');
    expect($referrer->referralsGiven)->toHaveCount(1);
    expect($referred->referralsReceived)->toHaveCount(1);
});
