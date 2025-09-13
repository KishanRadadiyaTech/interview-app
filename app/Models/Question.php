<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'interview_id',
        'question_text',
        'type',
        'options',
        'order',
        'time_limit',
    ];

    protected $casts = [
        'options' => 'array',
        'order' => 'integer',
        'time_limit' => 'integer',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function getOptionsAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }
        return $value;
    }

    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = is_array($value) ? json_encode($value) : $value;
    }
}
