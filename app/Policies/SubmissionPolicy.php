<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubmissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isReviewer();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Submission $submission): bool
    {
        // Admin can view any submission
        if ($user->isAdmin()) {
            return true;
        }

        // Creator of the interview can view submissions
        if ($user->isReviewer() && $submission->interview->created_by === $user->id) {
            return true;
        }

        // User can view their own submissions
        if ($submission->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can review the submission.
     */
    public function review(User $user, Submission $submission): bool
    {
        // User can't review their own submission
        if ($user->id === $submission->user_id) {
            return false;
        }

        // Admin can review any submission
        if ($user->isAdmin()) {
            return true;
        }

        // Creator of the interview can review submissions
        if ($user->isReviewer() && $submission->interview->created_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can download the submission files.
     */
    public function download(User $user, Submission $submission): bool
    {
        return $this->view($user, $submission) || $this->review($user, $submission);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only candidates can create submissions
        return $user->isCandidate();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Submission $submission): bool
    {
        // Only the submission owner can update it
        return $user->id === $submission->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Submission $submission): bool
    {
        // Only admin or the submission owner can delete it
        return $user->isAdmin() || $user->id === $submission->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Submission $submission): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Submission $submission): bool
    {
        return $user->isAdmin();
    }
}
