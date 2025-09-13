<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $interview->title }}
            </h2>
            <div class="flex space-x-2">
                @can('update', $interview)
                    <a href="{{ route('interviews.edit', $interview) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Edit') }}
                    </a>
                    <a href="{{ route('interviews.invite', $interview) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ __('Invite Candidates') }}
                    </a>
                @endcan
                @if(auth()->user()->isCandidate() && $interview->pivot?->status === 'in_progress')
                    <a href="{{ route('interviews.show', $interview) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        {{ __('Continue Interview') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                            <p class="font-bold">{{ __('Success!') }}</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Interview Details') }}</h3>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Description') }}</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $interview->description ?: 'No description provided.' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-yellow-100 text-yellow-800',
                                        'published' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                    ][$interview->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors }}">
                                    {{ ucfirst($interview->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->isAdmin() || (auth()->user()->isReviewer() && $interview->created_by === auth()->id()))
                        @include('interviews.partials.admin-view', ['interview' => $interview])
                    @else
                        @include('interviews.partials.candidate-view', ['interview' => $interview])
                    @endif

                    @can('update', $interview)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Invited Candidates') }}</h3>
                        
                        @if($interview->candidates->isEmpty())
                            <p class="text-gray-500">{{ __('No candidates have been invited yet.') }}</p>
                        @else
                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    @foreach($interview->candidates as $candidate)
                                        <li>
                                            <div class="px-4 py-4 flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $candidate->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $candidate->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    @php
                                                        $statusColors = [
                                                            'invited' => 'bg-yellow-100 text-yellow-800',
                                                            'in_progress' => 'bg-blue-100 text-blue-800',
                                                            'completed' => 'bg-green-100 text-green-800',
                                                        ][$candidate->pivot->status] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors }}">
                                                        {{ ucfirst(str_replace('_', ' ', $candidate->pivot->status)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin() || (auth()->user()->isReviewer() && $interview->created_by === auth()->id()))
        @include('interviews.partials.invite-candidate-modal')
    @endif
</x-app-layout>
