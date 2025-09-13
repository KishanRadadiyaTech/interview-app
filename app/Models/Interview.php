<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'created_by',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function candidates(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'interview_user')
            ->withPivot([
                'status', 
                'invited_at',
                'started_at', 
                'submitted_at',
                'completed_at'
            ])
            ->withTimestamps();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
