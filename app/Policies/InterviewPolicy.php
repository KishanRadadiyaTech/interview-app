<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InterviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isReviewer() || $user->isCandidate();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Interview $interview): bool
    {
        // Admin can view all interviews
        if ($user->isAdmin()) {
            return true;
        }

        // Creator can view their own interviews
        if ($user->isReviewer() && $interview->created_by === $user->id) {
            return true;
        }

        // Candidate can view interviews they are invited to
        if ($user->isCandidate() && $interview->candidates->contains($user->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isReviewer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Interview $interview): bool
    {
        // Admin can update any interview
        if ($user->isAdmin()) {
            return true;
        }

        // Only the creator can update their own interviews
        return $user->isReviewer() && $interview->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Interview $interview): bool
    {
        // Admin can delete any interview
        if ($user->isAdmin()) {
            return true;
        }

        // Only the creator can delete their own interviews
        return $user->isReviewer() && $interview->created_by === $user->id;
    }

    /**
     * Determine whether the user can invite candidates to the interview.
     */
    public function inviteCandidates(User $user, Interview $interview): bool
    {
        return $this->update($user, $interview);
    }

    /**
     * Determine whether the user can start the interview.
     */
    public function start(User $user, Interview $interview): bool
    {
        // Only candidates can start interviews they are invited to
        return $user->isCandidate() && $interview->candidates->contains($user->id);
    }

    /**
     * Determine whether the user can submit answers to the interview.
     */
    public function submitAnswer(User $user, Interview $interview): bool
    {
        return $this->start($user, $interview);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Interview $interview): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Interview $interview): bool
    {
        return false;
    }
}
