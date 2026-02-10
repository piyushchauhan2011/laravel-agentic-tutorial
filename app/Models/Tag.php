<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    public function candidates(): MorphToMany
    {
        return $this->morphedByMany(Candidate::class, 'taggable');
    }

    public function positions(): MorphToMany
    {
        return $this->morphedByMany(Position::class, 'taggable');
    }

    public function companies(): MorphToMany
    {
        return $this->morphedByMany(Company::class, 'taggable');
    }
}
