<div id="inviteModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="document.getElementById('inviteModal').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        {{ __('Invite Candidates') }}
                    </h3>
                    <div class="mt-4">
                        <form action="{{ route('interviews.invite', $interview) }}" method="POST" id="inviteForm">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="candidate_emails" class="block text-sm font-medium text-gray-700">
                                        {{ __('Candidate Email Addresses') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <textarea name="candidate_emails" id="candidate_emails" rows="5" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md" 
                                            placeholder="Enter email addresses, one per line" required></textarea>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('Enter one email address per line. Candidates will receive an email invitation.') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700">
                                        {{ __('Custom Message (Optional)') }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea name="message" id="message" rows="3" 
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                            placeholder="Add a personal message to the invitation"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button type="submit" form="inviteForm" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    {{ __('Send Invitations') }}
                </button>
                <button type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm"
                        @click="document.getElementById('inviteModal').classList.add('hidden')">
                    {{ __('Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
