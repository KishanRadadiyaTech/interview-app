@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                    {{ __('Interviews') }}
                </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @can('create', App\Models\Interview::class)
                        <div class="mb-6 flex justify-end">
                            <a href="{{ route('interviews.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Create New Interview') }}
                            </a>
                        </div>
                    @endcan

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                            <p class="font-bold">Success!</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($interviews->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Title') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Questions') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Created') }}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">{{ __('Actions') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($interviews as $interview)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('interviews.show', $interview) }}" class="text-blue-600 hover:text-blue-900">
                                                        {{ $interview->title }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($interview->description, 50) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'draft' => 'bg-yellow-100 text-yellow-800',
                                                        'published' => 'bg-blue-100 text-blue-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                    ][$interview->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors }}">
                                                    {{ ucfirst($interview->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $interview->questions_count ?? 0 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $interview->created_at->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex space-x-2 justify-end">
                                                    @if (auth()->user()->isCandidate() && $interview->pivot?->status === 'invited')
                                                        <form action="{{ route('interviews.start', $interview) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                                {{ __('Start') }}
                                                            </button>
                                                        </form>
                                                    @elseif (auth()->user()->isCandidate() && $interview->pivot?->status === 'in_progress')
                                                        <a href="{{ route('interviews.show', $interview) }}" class="text-blue-600 hover:text-blue-900">
                                                            {{ __('Continue') }}
                                                        </a>
                                                    @elseif (auth()->user()->isCandidate() && $interview->pivot?->status === 'completed')
                                                        <span class="text-green-600">
                                                            {{ __('Completed') }}
                                                        </span>
                                                    @elseif (auth()->user()->isAdmin() || (auth()->user()->isReviewer() && $interview->created_by === auth()->id()))
                                                        <a href="{{ route('interviews.show', $interview) }}" class="text-blue-600 hover:text-blue-900">
                                                            {{ __('View') }}
                                                        </a>
                                                        <a href="{{ route('interviews.edit', $interview) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form action="{{ route('interviews.destroy', $interview) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this interview?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $interviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No interviews</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @can('create', App\Models\Interview::class)
                                    Get started by creating a new interview.
                                @else
                                    You haven't been invited to any interviews yet.
                                @endcan
                            </p>
                            @can('create', App\Models\Interview::class)
                                <div class="mt-6">
                                    <a href="{{ route('interviews.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        New Interview
                                    </a>
                                </div>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection
