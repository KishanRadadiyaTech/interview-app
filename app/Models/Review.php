<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'submission_id',
        'reviewer_id',
        'score',
        'comments',
        'evaluation_criteria',
    ];

    protected $casts = [
        'score' => 'integer',
        'evaluation_criteria' => 'array',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function getEvaluationCriteriaAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }
        return $value;
    }

    public function setEvaluationCriteriaAttribute($value)
    {
        $this->attributes['evaluation_criteria'] = is_array($value) ? json_encode($value) : $value;
    }
}
