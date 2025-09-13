<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invite Candidates to Interview') }}: {{ $interview->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('interviews.invite', $interview) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ __('Select Candidates to Invite') }}
                            </h3>
                            
                            @if($candidates->isEmpty())
                                <p class="text-gray-500">
                                    {{ __('No candidates available to invite.') }}
                                </p>
                            @else
                                <div class="space-y-4">
                                    @foreach($candidates as $candidate)
                                        <div class="flex items-center">
                                            <input id="candidate-{{ $candidate->id }}" 
                                                   name="candidates[]" 
                                                   type="checkbox" 
                                                   value="{{ $candidate->id }}"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                   {{ in_array($candidate->id, $interview->candidates->pluck('id')->toArray()) ? 'checked disabled' : '' }}>
                                            <label for="candidate-{{ $candidate->id }}" class="ml-2 block text-sm text-gray-700">
                                                {{ $candidate->name }} ({{ $candidate->email }})
                                                @if(in_array($candidate->id, $interview->candidates->pluck('id')->toArray()))
                                                    <span class="text-xs text-green-600 ml-2">(Already invited)</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('interviews.show', $interview) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Cancel') }}
                            </a>
                            
                            @if(!$candidates->isEmpty())
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('Send Invitations') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
