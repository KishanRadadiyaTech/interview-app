<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Submission;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,reviewer');
    }

    public function index(Interview $interview = null)
    {
        $query = Submission::with(['user', 'interview', 'question', 'reviews.reviewer']);
        
        if ($interview) {
            $query->where('interview_id', $interview->id);
            
            // Check if the current user is the creator or has permission
            if ($interview->created_by !== Auth::id() && !Auth::user()->isAdmin()) {
                abort(403, 'You do not have permission to view these submissions.');
            }
        } else {
            // For non-admin users, only show submissions they can review
            if (!Auth::user()->isAdmin()) {
                $query->whereHas('interview', function($q) {
                    $q->where('created_by', Auth::id());
                });
            }
        }

        $submissions = $query->latest()->paginate(10);
        
        return view('reviews.index', compact('submissions', 'interview'));
    }

    public function show(Submission $submission)
    {
        $this->authorize('view', $submission);
        
        $submission->load(['user', 'interview', 'question', 'reviews.reviewer']);
        
        return view('reviews.show', compact('submission'));
    }

    public function storeReview(Request $request, Submission $submission)
    {
        $this->authorize('review', $submission);
        
        $validated = $request->validate([
            'score' => 'required|integer|min:1|max:10',
            'comments' => 'required|string|min:10',
            'evaluation_criteria' => 'nullable|array',
        ]);
        
        // Check if the user has already reviewed this submission
        $existingReview = $submission->reviews()->where('reviewer_id', Auth::id())->first();
        
        if ($existingReview) {
            $existingReview->update($validated);
            $message = 'Review updated successfully.';
        } else {
            $validated['reviewer_id'] = Auth::id();
            $submission->reviews()->create($validated);
            $message = 'Review submitted successfully.';
        }
        
        // Update the interview_user status if all questions are answered
        $this->checkInterviewCompletion($submission);
        
        return back()->with('success', $message);
    }
    
    protected function checkInterviewCompletion(Submission $submission)
    {
        $interview = $submission->interview;
        $user = $submission->user;
        
        $totalQuestions = $interview->questions()->count();
        $answeredQuestions = $interview->submissions()
            ->where('user_id', $user->id)
            ->whereNotNull('answer_text')
            ->orWhereNotNull('video_path')
            ->count();
            
        if ($totalQuestions > 0 && $answeredQuestions >= $totalQuestions) {
            $interview->candidates()->updateExistingPivot($user->id, [
                'status' => 'completed',
                'submitted_at' => now(),
            ]);
        }
    }
    
    public function downloadSubmission(Submission $submission)
    {
        $this->authorize('view', $submission);
        
        if ($submission->file_path && file_exists(storage_path('app/' . $submission->file_path))) {
            return response()->download(storage_path('app/' . $submission->file_path));
        }
        
        if ($submission->video_path && file_exists(storage_path('app/' . $submission->video_path))) {
            return response()->download(storage_path('app/' . $submission->video_path));
        }
        
        return back()->with('error', 'File not found.');
    }
}
