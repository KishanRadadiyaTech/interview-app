<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->role->slug === 'admin';
    }

    public function isReviewer(): bool
    {
        return $this->role->slug === 'reviewer';
    }

    public function isCandidate(): bool
    {
        return $this->role->slug === 'candidate';
    }

    public function createdInterviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'created_by');
    }

    public function interviews(): BelongsToMany
    {
        return $this->belongsToMany(Interview::class)
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

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }
}
