<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    protected $fillable = [
        'user_id',
        'interview_id',
        'question_id',
        'answer_text',
        'video_path',
        'file_path',
        'time_taken',
    ];

    protected $casts = [
        'time_taken' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function hasBeenReviewed(): bool
    {
        return $this->reviews()->exists();
    }

    public function averageScore(): ?float
    {
        return $this->reviews()->avg('score');
    }
}
