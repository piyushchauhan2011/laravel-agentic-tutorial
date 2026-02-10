<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'resume_url',
        'current_company',
        'score',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function referralsGiven(): HasMany
    {
        return $this->hasMany(Referral::class, 'referred_by_candidate_id');
    }

    public function referralsReceived(): HasMany
    {
        return $this->hasMany(Referral::class, 'candidate_id');
    }

    public function skillAssessments(): HasMany
    {
        return $this->hasMany(SkillAssessment::class);
    }
}
