<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'interview_id',
        'offer_id',
        'rating',
        'comments',
        'author_id',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the subject of this feedback (interview or offer) - ARC pattern.
     */
    public function getSubjectAttribute(): Interview|Offer|null
    {
        return $this->interview ?? $this->offer;
    }

    /**
     * Get the type of subject for this feedback.
     */
    public function getSubjectTypeAttribute(): ?string
    {
        if ($this->interview_id) {
            return 'interview';
        }

        if ($this->offer_id) {
            return 'offer';
        }

        return null;
    }
}
