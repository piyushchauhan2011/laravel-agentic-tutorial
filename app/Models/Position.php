<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'department',
        'employment_type',
        'location',
        'description',
        'status',
        'published_at',
        'closing_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'closing_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function skillAssessments(): HasMany
    {
        return $this->hasMany(SkillAssessment::class);
    }
}
