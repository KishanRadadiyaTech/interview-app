@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                    {{ __('Edit Interview') }}: {{ $interview->title }}
                </h2>
                
                <form action="{{ route('interviews.update', $interview) }}" method="POST" id="interviewForm">
                    @csrf
                    @method('PUT')
                        
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                {{ __('Interview Title') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('title', $interview->title) }}" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                {{ __('Description') }}
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $interview->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">
                                    {{ __('Status') }} <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    @foreach(['draft', 'published', 'completed'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $interview->status) === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="starts_at" class="block text-sm font-medium text-gray-700">
                                    {{ __('Start Date & Time') }}
                                </label>
                                <input type="datetime-local" name="starts_at" id="starts_at"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('starts_at', $interview->starts_at ? $interview->starts_at->format('Y-m-d\TH:i') : '') }}">
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
                                    value="{{ old('ends_at', $interview->ends_at ? $interview->ends_at->format('Y-m-d\TH:i') : '') }}">
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
                                @foreach(old('questions', $interview->questions) as $index => $question)
                                    <div class="question-card bg-gray-50 p-4 rounded-lg border border-gray-200" data-index="{{ $index }}">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-sm font-medium text-gray-900 question-number">Question #<span>{{ $loop->iteration }}</span></h4>
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
                                            <textarea name="questions[{{ $index }}][text]" class="question-text w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="2" required>{{ old('questions.' . $index . '.text', is_array($question) ? $question['text'] : $question->question_text) }}</textarea>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ __('Question Type') }} <span class="text-red-500">*</span>
                                                </label>
                                                <select name="questions[{{ $index }}][type]" class="question-type w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                                    @foreach(['text', 'video', 'multiple_choice', 'code'] as $type)
                                                        <option value="{{ $type }}" {{ old('questions.' . $index . '.type', (is_array($question) ? $question['type'] : $question->type)) === $type ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="time-limit-container">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ __('Time Limit (seconds)') }}
                                                </label>
                                                <input type="number" name="questions[{{ $index }}][time_limit]" min="30" step="30" 
                                                    class="time-limit w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    value="{{ old('questions.' . $index . '.time_limit', is_array($question) ? ($question['time_limit'] ?? '') : $question->time_limit) }}">
                                            </div>
                                        </div>
                                        
                                        @php
                                            $options = '';
                                            if (is_array($question) && !empty($question['options'])) {
                                                $options = is_array($question['options']) ? implode("\n", $question['options']) : $question['options'];
                                            } elseif (isset($question->options) && is_array($question->options)) {
                                                $options = implode("\n", $question->options);
                                            }
                                            $isMultipleChoice = (is_array($question) ? $question['type'] : $question->type) === 'multiple_choice';
                                        @endphp
                                        
                                        <div class="options-container {{ $isMultipleChoice ? '' : 'hidden' }}">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ __('Options (one per line)') }} <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="questions[{{ $index }}][options]" class="options-textarea w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3" {{ $isMultipleChoice ? 'required' : '' }}>{{ $options }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            @error('questions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('interviews.show', $interview) }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Update Interview') }}
                            </button>
                        </div>
                    </form>
                </div>
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
                <textarea name="questions[{{ '__index__' }}][text]" class="question-text w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="2" required></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('Question Type') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="questions[{{ '__index__' }}][type]" class="question-type w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
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
                    <input type="number" name="questions[{{ '__index__' }}][time_limit]" min="30" step="30" 
                        class="time-limit w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="options-container hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    {{ __('Options (one per line)') }} <span class="text-red-500">*</span>
                </label>
                <textarea name="questions[{{ '__index__' }}][options]" class="options-textarea w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3"></textarea>
            </div>
        </div>
    </template>

@section('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('questions-container');
            const addButton = document.getElementById('addQuestion');
            const template = document.getElementById('question-template');
            let questionCount = {{ count(old('questions', $interview->questions)) }};
            let nextIndex = questionCount;

            // Add event listeners to existing questions
            document.querySelectorAll('.question-card').forEach(questionCard => {
                const typeSelect = questionCard.querySelector('.question-type');
                const optionsContainer = questionCard.querySelector('.options-container');
                const removeButton = questionCard.querySelector('.remove-question');
                const timeLimitInput = questionCard.querySelector('.time-limit');
                
                setupQuestionCard(questionCard);
                
                // Initialize options container visibility
                toggleOptionsContainer(typeSelect, optionsContainer);
                
                // Handle time limit input
                if (timeLimitInput) {
                    timeLimitInput.addEventListener('change', function() {
                        if (this.value < 30 && this.value !== '') {
                            this.value = 30;
                        }
                    });
                }
                
                // Handle remove button
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        if (confirm('Are you sure you want to remove this question?')) {
                            questionCard.remove();
                            updateQuestionNumbers();
                        }
                    });
                }
            });
            
            // Add a new question
            function addQuestion() {
                const questionHTML = template.innerHTML.replace(/\{\{index\}\}/g, nextIndex);
                const questionElement = document.createElement('div');
                questionElement.innerHTML = questionHTML;
                
                const questionCard = questionElement.firstElementChild;
                questionCard.dataset.index = nextIndex;
                
                // Set up event listeners for the new question
                const typeSelect = questionCard.querySelector('.question-type');
                const optionsContainer = questionCard.querySelector('.options-container');
                const removeButton = questionCard.querySelector('.remove-question');
                const timeLimitInput = questionCard.querySelector('.time-limit');
                
                setupQuestionCard(questionCard);
                
                // Add to container
                container.appendChild(questionCard);
                
                // Update counters
                nextIndex++;
                questionCount++;
                
                // Update question numbers
                updateQuestionNumbers();
                
                // Focus the new question textarea
                questionCard.querySelector('.question-text').focus();
            }
            
            // Set up a question card with event listeners
            function setupQuestionCard(questionCard) {
                const typeSelect = questionCard.querySelector('.question-type');
                const optionsContainer = questionCard.querySelector('.options-container');
                const optionsTextarea = questionCard.querySelector('.options-textarea');
                
                // Type change handler
                typeSelect.addEventListener('change', function() {
                    const isMultipleChoice = this.value === 'multiple_choice';
                    optionsContainer.classList.toggle('hidden', !isMultipleChoice);
                    
                    // Toggle required attribute on options textarea
                    if (isMultipleChoice) {
                        optionsTextarea.setAttribute('required', 'required');
                    } else {
                        optionsTextarea.removeAttribute('required');
                    }
                });
                
                // Time limit input handler
                const timeLimitInput = questionCard.querySelector('.time-limit');
                if (timeLimitInput) {
                    timeLimitInput.addEventListener('change', function() {
                        if (this.value < 30 && this.value !== '') {
                            this.value = 30;
                        }
                    });
                }
                
                // Remove button handler
                const removeButton = questionCard.querySelector('.remove-question');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        if (confirm('Are you sure you want to remove this question?')) {
                            questionCard.remove();
                            questionCount--;
                            updateQuestionNumbers();
                        }
                    });
                }
            }
            
            // Toggle options container based on question type
            function toggleOptionsContainer(select, container) {
                const isMultipleChoice = select.value === 'multiple_choice';
                container.classList.toggle('hidden', !isMultipleChoice);
                
                const optionsTextarea = container.querySelector('.options-textarea');
                if (isMultipleChoice) {
                    optionsTextarea.setAttribute('required', 'required');
                } else {
                    optionsTextarea.removeAttribute('required');
                }
            }
            
            // Update question numbers
            function updateQuestionNumbers() {
                const questions = container.querySelectorAll('.question-card');
                questions.forEach((question, index) => {
                    const numberSpan = question.querySelector('.question-number span');
                    if (numberSpan) {
                        numberSpan.textContent = index + 1;
                    }
                    
                    // Update the name attributes to maintain order
                    const inputs = question.querySelectorAll('[name^="questions["]');
                    inputs.forEach(input => {
                        const name = input.getAttribute('name');
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        input.setAttribute('name', newName);
                    });
                });
            }
            
            // Add question button click handler
            addButton.addEventListener('click', addQuestion);
            
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
                    
                    // Additional validation can be added here
                    return true;
                });
            }
        });
    </script>
@endsection
