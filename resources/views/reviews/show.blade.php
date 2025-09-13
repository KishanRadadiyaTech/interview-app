@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold">Review Submission</h2>
                        <p class="text-gray-600">Reviewing submission by {{ $submission->user->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        @if($submission->is_complete)
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                In Progress
                            </span>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $submission->interview->title }}</h3>
                    <p class="text-gray-600">{{ $submission->interview->description }}</p>
                    <div class="mt-2 text-sm text-gray-500">
                        Submitted {{ $submission->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="space-y-6">
                    @foreach($submission->responses as $response)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Question {{ $loop->iteration }}: {{ $response->question->text }}</h4>
                            
                            @if($response->question->type === 'text' || $response->question->type === 'code')
                                <div class="bg-gray-50 p-3 rounded-md mt-2">
                                    <pre class="whitespace-pre-wrap">{{ $response->answer_text }}</pre>
                                </div>
                            @elseif($response->question->type === 'video')
                                <div class="mt-2">
                                    <video controls class="w-full max-w-2xl rounded-lg">
                                        <source src="{{ Storage::url($response->answer_video) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif

                            @if($response->reviews->isNotEmpty())
                                <div class="mt-4 border-t pt-4">
                                    <h5 class="font-medium text-gray-700 mb-2">Your Previous Review</h5>
                                    @foreach($response->reviews as $review)
                                        @if($review->reviewer_id === Auth::id())
                                            <div class="bg-blue-50 p-3 rounded-md">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex items-center">
                                                        <span class="font-medium">Rating:</span>
                                                        <div class="flex ml-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                                    fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                                @if($review->comments)
                                                    <div class="mt-2 text-gray-700">
                                                        {{ $review->comments }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            @if(!$submission->is_complete)
                                <form action="{{ route('reviews.store', $submission) }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="response_id" value="{{ $response->id }}">
                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Rating <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex items-center" id="rating-{{ $response->id }}">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" 
                                                    class="rating-star text-gray-300 hover:text-yellow-400 focus:outline-none" 
                                                    data-rating="{{ $i }}" 
                                                    data-response="{{ $response->id }}">
                                                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </button>
                                                <input type="hidden" name="rating" id="rating-value-{{ $response->id }}" value="0">
                                            @endfor
                                        </div>
                                        @error('rating')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-1">
                                            Comments
                                        </label>
                                        <textarea name="comments" id="comments" rows="3" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Submit Review
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if(!$submission->is_complete && $submission->responses->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <form action="{{ route('submissions.complete', $submission) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Mark as Complete
                            </button>
                        </form>
                    </div>
                @endif

                <div class="mt-8">
                    <a href="{{ route('reviews.index') }}" class="text-blue-600 hover:text-blue-800">
                        &larr; Back to Submissions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle star rating selection
        document.querySelectorAll('.rating-star').forEach(star => {
            star.addEventListener('click', function() {
                const responseId = this.dataset.response;
                const rating = parseInt(this.dataset.rating);
                const container = document.getElementById(`rating-${responseId}`);
                
                // Update the hidden input value
                document.getElementById(`rating-value-${responseId}`).value = rating;
                
                // Update star display
                container.querySelectorAll('.rating-star').forEach((s, index) => {
                    const starIcon = s.querySelector('svg');
                    if (index < rating) {
                        starIcon.classList.remove('text-gray-300');
                        starIcon.classList.add('text-yellow-400');
                    } else {
                        starIcon.classList.remove('text-yellow-400');
                        starIcon.classList.add('text-gray-300');
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
