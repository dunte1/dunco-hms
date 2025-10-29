<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.case-handlers.cases') }}" class="hover:text-amber-600">Patient Cases</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Create New Case</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-folder-plus text-amber-600 mr-3"></i>
                    Create Patient Case
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Open a new case for patient care coordination</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.case-handlers.cases.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Case Number (Auto-generated) -->
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Case Number (Auto-generated)
                        </label>
                        <input type="text" value="{{ 'CASE-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}" 
                            disabled
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-gray-100 text-gray-600 dark:text-gray-400 cursor-not-allowed">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">System will auto-generate upon creation</p>
                    </div>

                    <!-- Case Information -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Case Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Patient <span class="text-red-500">*</span>
                                </label>
                                <select name="patient_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_no }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Assign to Case Handler <span class="text-red-500">*</span>
                                </label>
                                <select name="case_handler_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Select Handler</option>
                                    @foreach($caseHandlers as $handler)
                                        <option value="{{ $handler->id }}">
                                            {{ $handler->first_name }} {{ $handler->last_name }} - {{ $handler->specialization }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Case Type <span class="text-red-500">*</span>
                                </label>
                                <select name="case_type" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">Select Type</option>
                                    <option value="medical">Medical Support</option>
                                    <option value="social">Social Services</option>
                                    <option value="financial">Financial Assistance</option>
                                    <option value="legal">Legal Advocacy</option>
                                    <option value="mental_health">Mental Health</option>
                                    <option value="discharge_planning">Discharge Planning</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Priority Level <span class="text-red-500">*</span>
                                </label>
                                <select name="priority" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Opened Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="opened_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Expected Close Date
                                </label>
                                <input type="date" name="expected_close_date"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                            </div>
                        </div>
                    </div>

                    <!-- Case Description -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Case Details</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Case Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" rows="4" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Describe the patient's situation, needs, and challenges..."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Initial Notes
                                </label>
                                <textarea name="notes" rows="3"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Any additional notes, observations, or action items..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Templates -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            <i class="fa fa-bolt text-blue-600 mr-2"></i> Quick Templates
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <button type="button" onclick="fillTemplate('discharge')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Discharge Planning
                            </button>
                            <button type="button" onclick="fillTemplate('financial')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Financial Aid
                            </button>
                            <button type="button" onclick="fillTemplate('mental')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Mental Health
                            </button>
                            <button type="button" onclick="fillTemplate('legal')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Legal Support
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-save mr-2"></i> Create Case
                        </button>
                        <a href="{{ route('hms.case-handlers.cases') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fillTemplate(type) {
            const descriptions = {
                discharge: "Patient requires comprehensive discharge planning including home care arrangements, medical equipment coordination, and follow-up appointments scheduling.",
                financial: "Patient needs financial assistance for medical bills and treatment costs. Requires evaluation for insurance coverage, charity care programs, and payment plan options.",
                mental: "Patient presenting with mental health concerns requiring counseling, support group referrals, and coordination with psychiatric services.",
                legal: "Patient requires legal advocacy support including advance directives, medical power of attorney, or assistance with healthcare-related legal matters."
            };
            
            const descField = document.querySelector('textarea[name="description"]');
            if (descField && descriptions[type]) {
                descField.value = descriptions[type];
            }
        }
    </script>
</x-app-layout>

