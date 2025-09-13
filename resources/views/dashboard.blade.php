@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    {{ __('Dashboard') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if(auth()->user()->isAdmin() || auth()->user()->isReviewer())
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Interviews</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Interview::count() }}</p>
                            <a href="{{ route('interviews.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                                View all interviews →
                            </a>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-green-800 mb-2">Candidates</h3>
                            <p class="text-3xl font-bold text-green-600">{{ \App\Models\User::whereHas('role', function($q) { $q->where('slug', 'candidate'); })->count() }}</p>
                        </div>
                        
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-purple-800 mb-2">Submissions to Review</h3>
                            <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Review::where('reviewer_id', auth()->id())->count() }}</p>
                            <a href="{{ route('reviews.index') }}" class="text-purple-600 hover:text-purple-800 text-sm mt-2 inline-block">
                                Review submissions →
                            </a>
                        </div>
                    @else
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">My Interviews</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ auth()->user()->interviews()->count() }}</p>
                            <a href="{{ route('interviews.index') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                                View my interviews →
                            </a>
                        </div>
                        
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-green-800 mb-2">Completed</h3>
                            <p class="text-3xl font-bold text-green-600">{{ auth()->user()->interviews()->wherePivot('status', 'completed')->count() }}</p>
                        </div>
                        
                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-yellow-800 mb-2">In Progress</h3>
                            <p class="text-3xl font-bold text-yellow-600">{{ auth()->user()->interviews()->wherePivot('status', 'in_progress')->count() }}</p>
                        </div>
                    @endif
                </div>
                
                @if(auth()->user()->isAdmin() || auth()->user()->isReviewer())
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Submissions</h3>
                        <div class="bg-white shadow overflow-hidden sm:rounded-md">
                            <ul class="divide-y divide-gray-200">
                                @forelse(\App\Models\Submission::with(['user', 'interview'])->latest()->take(5)->get() as $submission)
                                    <li>
                                        <a href="{{ route('reviews.show', $submission) }}" class="block hover:bg-gray-50">
                                            <div class="px-4 py-4 sm:px-6">
                                                <div class="flex items-center justify-between">
                                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                                        {{ $submission->interview->title }}
                                                    </p>
                                                    <div class="ml-2 flex-shrink-0 flex">
                                                        @if($submission->status === 'submitted')
                                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Pending Review
                                                            </p>
                                                        @elseif($submission->status === 'reviewed')
                                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Reviewed
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:flex sm:justify-between">
                                                    <div class="sm:flex">
                                                        <p class="flex items-center text-sm text-gray-500">
                                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ $submission->user->name }}
                                                        </p>
                                                    </div>
                                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                        </svg>
                                                        <p>
                                                            Submitted {{ $submission->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="px-4 py-4 sm:px-6">
                                        <p class="text-sm text-gray-500">No submissions found.</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
