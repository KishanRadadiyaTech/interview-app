<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InterviewController extends Controller
{
    /**
     * Create a new controller instance.
     */
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Apply auth middleware to all methods except 'show' and 'index'
        $this->middleware('auth');
        
        // Use route model binding with explicit key name
        $this->middleware(function ($request, $next) {
            if ($interview = $request->route('interview')) {
                $interview = Interview::findOrFail($interview);
                $request->route()->setParameter('interview', $interview);
            }
            return $next($request);
        })->except(['index', 'create', 'store']);
        
        // Authorize resource controller
        $this->authorizeResource(Interview::class, 'interview');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isReviewer()) {
            $interviews = Interview::with('questions')
                ->when($user->isReviewer(), function($query) use ($user) {
                    return $query->where('created_by', $user->id);
                })
                ->latest()
                ->paginate(10);
        } else {
            $interviews = $user->interviews()
                ->with('questions')
                ->wherePivot('status', '!=', 'invited')
                ->paginate(10);
        }

        return view('interviews.index', compact('interviews'));
    }

    public function create()
    {
        return view('interviews.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:text,video,multiple_choice,code',
            'questions.*.options' => 'nullable|required_if:questions.*.type,multiple_choice|array',
            'questions.*.time_limit' => 'nullable|integer|min:30',
        ]);

        return DB::transaction(function () use ($validated) {
            $interview = Interview::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status' => 'draft',
                'created_by' => Auth::id(),
                'starts_at' => $validated['starts_at'] ?? null,
                'ends_at' => $validated['ends_at'] ?? null,
            ]);

            foreach ($validated['questions'] as $index => $questionData) {
                $interview->questions()->create([
                    'question_text' => $questionData['text'],
                    'type' => $questionData['type'],
                    'options' => $questionData['options'] ?? null,
                    'time_limit' => $questionData['time_limit'] ?? null,
                    'order' => $index + 1,
                ]);
            }

            return redirect()->route('interviews.show', $interview)
                ->with('success', 'Interview created successfully.');
        });
    }

    public function show(Interview $interview)
    {
        $interview->load(['questions', 'creator', 'candidates']);
        
        // Check if user is authorized to view this interview
        $this->authorize('view', $interview);
        
        if (Auth::user()->isCandidate()) {
            $userInterview = $interview->candidates()->find(Auth::id());
            if (!$userInterview) {
                abort(403, 'You are not invited to this interview.');
            }
            
            return view('interviews.take', compact('interview', 'userInterview'));
        }

        return view('interviews.show', compact('interview'));
    }

    public function edit(Interview $interview)
    {
        $this->authorize('update', $interview);
        $interview->load('questions');
        return view('interviews.edit', compact('interview'));
    }

    public function update(Request $request, Interview $interview)
    {
        $this->authorize('update', $interview);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published,completed',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $interview->update($validated);

        return redirect()->route('interviews.show', $interview)
            ->with('success', 'Interview updated successfully.');
    }

    public function destroy(Interview $interview)
    {
        $this->authorize('delete', $interview);
        
        $interview->delete();
        
        return redirect()->route('interviews.index')
            ->with('success', 'Interview deleted successfully.');
    }

    public function invite(Interview $interview)
    {
        $this->authorize('update', $interview);
        
        // Get all candidates who haven't been invited yet
        $candidates = \App\Models\User::where('role_id', \App\Models\Role::where('slug', 'candidate')->first()->id)
            ->whereNotIn('id', $interview->candidates->pluck('id'))
            ->get();
            
        // Also include already invited candidates so they can be seen in the list
        $invitedCandidates = $interview->candidates;
        $candidates = $candidates->merge($invitedCandidates)->unique('id');

        return view('interviews.invite', compact('interview', 'candidates'));
    }
    
    public function inviteCandidates(Request $request, Interview $interview)
    {
        $this->authorize('update', $interview);
        
        $validated = $request->validate([
            'candidates' => 'required|array',
            'candidates.*' => 'exists:users,id,role_id,' . \App\Models\Role::where('slug', 'candidate')->first()->id,
        ]);

        $interview->candidates()->syncWithoutDetaching($validated['candidates']);
        
        // Send invitation emails to newly invited candidates
        $newCandidates = \App\Models\User::whereIn('id', $validated['candidates'])->get();
        foreach ($newCandidates as $candidate) {
            // TODO: Uncomment this when you have email setup
            // \Illuminate\Support\Facades\Mail::to($candidate->email)
            //     ->send(new \App\Mail\InterviewInvitation($interview, $candidate));
        }

        return redirect()->route('interviews.show', $interview)
            ->with('success', 'Candidates invited successfully.');
    }

    public function start(Interview $interview)
    {
        $user = Auth::user();
        
        if (!$interview->candidates->contains($user)) {
            abort(403, 'You are not invited to this interview.');
        }

        $interview->candidates()->updateExistingPivot($user->id, [
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return view('interviews.take', compact('interview'));
    }

    public function submitAnswer(Request $request, Interview $interview, Question $question)
    {
        $user = Auth::user();
        
        if (!$interview->candidates->contains($user)) {
            abort(403, 'You are not invited to this interview.');
        }

        $validated = $request->validate([
            'answer_text' => 'required_without:video_path|string|nullable',
            'video_path' => 'required_without:answer_text|string|nullable',
            'time_taken' => 'required|integer|min:0',
        ]);

        $submission = $interview->submissions()->updateOrCreate(
            [
                'user_id' => $user->id,
                'question_id' => $question->id,
            ],
            array_merge($validated, [
                'interview_id' => $interview->id,
            ])
        );

        return response()->json([
            'success' => true,
            'submission' => $submission,
        ]);
    }
}
