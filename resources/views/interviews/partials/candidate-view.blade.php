@if($interview->pivot?->status === 'invited')
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('You have been invited to an interview') }}</h3>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('This interview contains') }} {{ $interview->questions->count() }} {{ __('questions.') }}
            @if($interview->time_limit)
                {{ __('You will have') }} {{ $interview->time_limit }} {{ __('minutes to complete it.') }}
            @endif
        </p>
        <div class="mt-6">
            <form action="{{ route('interviews.start', $interview) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    {{ __('Start Interview') }}
                </button>
            </form>
        </div>
    </div>
@elseif($interview->pivot?->status === 'in_progress' || $interview->pivot?->status === 'completed')
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Your Interview') }}</h3>
        
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Interview Progress') }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('Complete all questions to finish the interview.') }}
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('Questions Completed') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @php
                                $submissionsCount = $interview->submissions->where('user_id', auth()->id())->count();
                                $totalQuestions = $interview->questions->count();
                                $progress = $totalQuestions > 0 ? round(($submissionsCount / $totalQuestions) * 100) : 0;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ $submissionsCount }} {{ __('of') }} {{ $totalQuestions }} {{ __('questions completed') }} ({{ $progress }}%)
                            </div>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ __('Time Spent') }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ gmdate('H:i:s', $interview->submissions->where('user_id', auth()->id())->sum('time_taken') ?? 0) }}
                        </dd>
                    </div>
                    @if($interview->pivot?->submitted_at)
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Submitted At') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $interview->pivot->submitted_at->format('F j, Y, g:i a') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <h4 class="text-md font-medium text-gray-900 mb-4">{{ __('Questions') }}</h4>
        <div class="space-y-6">
            @foreach($interview->questions as $index => $question)
                @php
                    $submission = $interview->submissions
                        ->where('user_id', auth()->id())
                        ->where('question_id', $question->id)
                        ->first();
                @endphp
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="text-sm font-medium text-gray-900">
                                    {{ __('Question') }} #{{ $index + 1 }}
                                    @if($submission)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                            {{ __('Completed') }}
                                        </span>
                                    @endif
                                </h5>
                                <p class="mt-1 text-sm text-gray-600">{{ $question->question_text }}</p>
                                
                                @if($question->options && is_array($question->options) && count($question->options) > 0)
                                    <div class="mt-3">
                                        <p class="text-xs font-medium text-gray-500">{{ __('Options') }}:</p>
                                        <ul class="mt-1 text-sm text-gray-700 list-disc list-inside">
                                            @foreach($question->options as $option)
                                                <li>{{ $option }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                            </span>
                        </div>
                        
                        @if($submission)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Your Answer') }}
                                </h6>
                                <div class="mt-2">
                                    @if($question->type === 'video' && $submission->video_path)
                                        <video controls class="w-full max-w-md rounded-md">
                                            <source src="{{ Storage::url($submission->video_path) }}" type="video/mp4">
                                            {{ __('Your browser does not support the video tag.') }}
                                        </video>
                                    @elseif($question->type === 'file' && $submission->file_path)
                                        <a href="{{ route('submissions.download', $submission) }}" 
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            {{ __('Download File') }}
                                        </a>
                                    @else
                                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $submission->answer_text }}</p>
                                    @endif
                                </div>
                                
                                @if($submission->time_taken)
                                    <p class="mt-2 text-xs text-gray-500">
                                        {{ __('Time taken:') }} {{ gmdate('i:s', $submission->time_taken) }} {{ __('minutes') }}
                                    </p>
                                @endif
                                
                                @if($submission->reviews->isNotEmpty())
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h6 class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Review') }}
                                        </h6>
                                        @foreach($submission->reviews as $review)
                                            <div class="mt-2 p-3 bg-gray-50 rounded-md">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-gray-600 text-sm font-medium">
                                                                {{ substr($review->reviewer->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm font-medium text-gray-700">
                                                                {{ $review->reviewer->name }}
                                                            </p>
                                                            <div class="flex items-center">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= $review->score)
                                                                        <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                        </svg>
                                                                    @else
                                                                        <svg class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                        </svg>
                                                                    @endif
                                                                @endfor
                                                                <span class="ml-2 text-sm text-gray-500">{{ $review->score }}/5</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $review->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                @if($review->comments)
                                                    <p class="mt-2 text-sm text-gray-700">
                                                        {{ $review->comments }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('interviews.answer', ['interview' => $interview, 'question' => $question]) }}"
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ $interview->pivot->status === 'completed' ? 'View' : 'Answer' }} Question
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($interview->pivot?->status === 'in_progress' && $interview->submissions->where('user_id', auth()->id())->count() === $interview->questions->count())
            <div class="mt-8 pt-6 border-t border-gray-200 text-right">
                <form action="{{ route('interviews.complete', $interview) }}" method="POST" onsubmit="return confirm('Are you sure you want to submit your interview? You will not be able to make changes after submission.');">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ __('Submit Interview') }}
                    </button>
                </form>
            </div>
        @endif
    </div>
@endif
