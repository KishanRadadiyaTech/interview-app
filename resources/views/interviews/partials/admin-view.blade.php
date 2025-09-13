<div class="mt-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-900">{{ __('Questions') }}</h3>
        <span class="text-sm text-gray-500">{{ $interview->questions->count() }} questions</span>
    </div>
    
    <div class="space-y-4">
        @forelse($interview->questions as $index => $question)
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between items-start">
                    <h4 class="text-sm font-medium text-gray-900">
                        {{ __('Question') }} #{{ $index + 1 }}
                    </h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                    </span>
                </div>
                <p class="mt-2 text-sm text-gray-700">{{ $question->question_text }}</p>
                
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
                
                @if($question->time_limit)
                    <div class="mt-2">
                        <p class="text-xs text-gray-500">
                            {{ __('Time Limit') }}: {{ $question->time_limit }} {{ __('seconds') }}
                        </p>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-gray-500">{{ __('No questions have been added to this interview yet.') }}</p>
        @endforelse
    </div>

    @if($interview->candidates->count() > 0)
        <div class="mt-12">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Candidates') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Email') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Progress') }}
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">{{ __('Actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($interview->candidates as $candidate)
                            @php
                                $submissionsCount = $interview->submissions->where('user_id', $candidate->id)->count();
                                $progress = $interview->questions->count() > 0 
                                    ? round(($submissionsCount / $interview->questions->count()) * 100) 
                                    : 0;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $candidate->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $candidate->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $candidate->pivot->status;
                                        $statusColors = [
                                            'invited' => 'bg-yellow-100 text-yellow-800',
                                            'in_progress' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                        ][$status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors }}">
                                        {{ str_replace('_', ' ', ucfirst($status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $progress }}% {{ __('completed') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('reviews.interview', ['interview' => $interview->id, 'user' => $candidate->id]) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">{{ __('View Submissions') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="mt-8 text-center py-8 border-t border-gray-200">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No candidates invited yet') }}</h3>
            <p class="mt-1 text-sm text-gray-500">
                {{ __('Get started by inviting candidates to this interview.') }}
            </p>
            <div class="mt-6">
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        @click="document.getElementById('inviteModal').classList.remove('hidden')">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                    </svg>
                    {{ __('Invite Candidates') }}
                </button>
            </div>
        </div>
    @endif
</div>
