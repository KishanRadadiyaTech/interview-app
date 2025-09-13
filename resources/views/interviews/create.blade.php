@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                    {{ __('Create New Interview') }}
                </h2>
                
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        <p class="font-bold">Error!</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    <form action="{{ route('interviews.store') }}" method="POST" id="interviewForm">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                {{ __('Interview Title') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                {{ __('Description') }}
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="starts_at" class="block text-sm font-medium text-gray-700">
                                    {{ __('Start Date & Time') }}
                                </label>
                                <input type="datetime-local" name="starts_at" id="starts_at"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('starts_at') }}">
                                @error('starts_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="ends_at" class="block text-sm font-medium text-gray-700">
                                    {{ __('End Date & Time') }}
                                </label>
                                <input type="datetime-local" name="ends_at" id="ends_at"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('ends_at') }}">
                                @error('ends_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ __('Questions') }} <span class="text-red-500">*</span>
                                </h3>
                                <button type="button" id="addQuestion" 
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('Add Question') }}
                                </button>
                            </div>
                            
                            <div id="questions-container" class="mt-4 space-y-6">
                                <!-- Questions will be added here dynamically -->
                            </div>
                            
                            <!-- Test Button -->
                            <div class="mt-4">
                                <button type="button" id="testButton" class="bg-green-500 text-white px-4 py-2 rounded">
                                    Test JavaScript
                                </button>
                            </div>
                            
                            @error('questions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('interviews.index') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Save Interview') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Template (Hidden) -->
    <template id="question-template">
        <div class="question-card bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="flex justify-between items-start mb-4">
                <h4 class="text-sm font-medium text-gray-900 question-number">Question #<span></span></h4>
                <button type="button" class="text-red-600 hover:text-red-800 remove-question">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Question Text') }} <span class="text-red-500">*</span>
                </label>
                <textarea name="questions[__index__][text]" class="question-text w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="2" required></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Question Type') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="questions[__index__][type]" class="question-type w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="text">{{ __('Text Answer') }}</option>
                        <option value="video">{{ __('Video Response') }}</option>
                        <option value="multiple_choice">{{ __('Multiple Choice') }}</option>
                        <option value="code">{{ __('Code') }}</option>
                    </select>
                </div>
                <div class="time-limit-container">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Time Limit (seconds)') }}
                    </label>
                    <input type="number" name="questions[__index__][time_limit]" min="30" step="30" 
                        class="time-limit w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="options-container hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Options (one per line)') }} <span class="text-red-500">*</span>
                </label>
                <textarea name="questions[__index__][options]" class="options-textarea w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3"></textarea>
            </div>
        </div>
    </template>

    @push('scripts')
    <script>
        // Simple test to verify JavaScript is working
        console.log('Script loaded!');
        
        // Test if jQuery is available
        console.log('jQuery available:', typeof $ !== 'undefined');
        
        // Test if elements exist
        console.log('addQuestion element exists:', document.getElementById('addQuestion') !== null);
        console.log('questions-container exists:', document.getElementById('questions-container') !== null);
        console.log('question-template exists:', document.getElementById('question-template') !== null);
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded and ready');
            console.log('DOM fully loaded');
            
            // Get elements
            const addButton = document.getElementById('addQuestion');
            const container = document.getElementById('questions-container');
            const template = document.getElementById('question-template');
            
            console.log('Elements:', { addButton, container, template });
            
            if (!addButton || !container || !template) {
                console.error('Required elements not found!');
                return;
            }
            
            let questionCount = 0;
            
            // Function to add a new question
            function addQuestion() {
                console.log('Adding new question...');
                
                // Create a new question element from the template
                const questionHTML = template.innerHTML.replace(/__index__/g, questionCount);
                const questionElement = document.createElement('div');
                questionElement.innerHTML = questionHTML;
                
                const questionCard = questionElement.firstElementChild;
                
                // Add remove button functionality
                const removeButton = questionCard.querySelector('.remove-question');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        console.log('Remove button clicked');
                        questionCard.remove();
                    });
                }
                
                const typeSelect = questionCard.querySelector('.question-type');
                const optionsContainer = questionCard.querySelector('.options-container');
                
                if (typeSelect && optionsContainer) {
                    typeSelect.addEventListener('change', function() {
                        console.log('Question type changed:', this.value);
                        const isMultipleChoice = this.value === 'multiple_choice';
                        optionsContainer.classList.toggle('hidden', !isMultipleChoice);
                    });
                }
                
                // Add time limit validation
                const timeLimitInput = questionCard.querySelector('.time-limit');
                if (timeLimitInput) {
                    timeLimitInput.addEventListener('change', function() {
                        if (this.value < 30 && this.value !== '') {
                            this.value = 30;
                        }
                    });
                }
                
                // Add to container
                container.appendChild(questionCard);
                questionCount++;
                console.log('Question added. Total questions:', questionCount);
            }
            
            // Add first question by default
            addQuestion();
            
            // Add click handler for the Add Question button
            addButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Add Question button clicked');
                addQuestion();
            });
            
            // Form validation
            const form = document.getElementById('interviewForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const questions = container.querySelectorAll('.question-card');
                    if (questions.length === 0) {
                        e.preventDefault();
                        alert('Please add at least one question.');
                        return false;
                    }
                    return true;
                });
            }
            // Add test button functionality
            const testButton = document.getElementById('testButton');
            if (testButton) {
                testButton.addEventListener('click', function() {
                    alert('JavaScript is working!');
                    console.log('Test button clicked');
                });
            }
        }); // Close the DOMContentLoaded event listener
    </script>
    @endpush
    
    <!-- Test script at the bottom -->
    <script>
        console.log('Bottom script loaded');
        
        // Try to add a question directly
        function testAddQuestion() {
            const container = document.getElementById('questions-container');
            if (container) {
                const question = document.createElement('div');
                question.className = 'question-card bg-gray-100 p-4 mb-4 rounded';
                question.textContent = 'Test question ' + (container.children.length + 1);
                container.appendChild(question);
                console.log('Added test question');
                return true;
            }
            return false;
        }
        
        // Add a test button
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Bottom script DOM loaded');
            
            // Add a test button next to the Add Question button
            const addButton = document.getElementById('addQuestion');
            if (addButton) {
                const testBtn = document.createElement('button');
                testBtn.textContent = 'Test Add';
                testBtn.className = 'ml-2 bg-yellow-500 text-white px-3 py-1 rounded';
                testBtn.onclick = function() {
                    if (testAddQuestion()) {
                        alert('Test question added successfully!');
                    } else {
                        alert('Failed to add test question');
                    }
                };
                addButton.parentNode.insertBefore(testBtn, addButton.nextSibling);
            }
        });
    </script>
</div>
@endsection
