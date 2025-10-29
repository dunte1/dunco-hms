<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.medical-history.index') }}" class="hover:text-indigo-600">Medical History</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $patient->full_name }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-file-medical text-indigo-600 mr-3"></i>
                    Medical History - {{ $patient->full_name }}
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Complete medical records and history</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Patient Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-6">
                        <div class="text-center mb-6">
                            <div class="inline-flex h-24 w-24 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full items-center justify-center text-white font-bold text-4xl mb-3">
                                {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $patient->full_name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ $patient->patient_no }}</p>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <i class="fa fa-birthday-cake text-indigo-600 w-5"></i>
                                <span>{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }} ({{ \Carbon\Carbon::parse($patient->date_of_birth)->age }}y)</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <i class="fa fa-venus-mars text-indigo-600 w-5"></i>
                                <span>{{ ucfirst($patient->gender) }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <i class="fa fa-phone text-indigo-600 w-5"></i>
                                <span>{{ $patient->phone }}</span>
                            </div>
                            @if($patient->email)
                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <i class="fa fa-envelope text-indigo-600 w-5"></i>
                                <span>{{ $patient->email }}</span>
                            </div>
                            @endif
                        </div>

                        <button onclick="document.getElementById('addHistoryModal').classList.remove('hidden')" 
                            class="mt-6 w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add Record
                        </button>
                    </div>
                </div>

                <!-- Medical History Records -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                            Medical History Records ({{ $patient->medicalHistories->count() }})
                        </h3>

                        @if($patient->medicalHistories->count() > 0)
                            <div class="space-y-4">
                                @foreach($patient->medicalHistories as $history)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $history->condition }}
                                                    @if($history->is_chronic)
                                                        <span class="ml-2 px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Chronic</span>
                                                    @endif
                                                </h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    <i class="fa fa-calendar mr-1"></i>
                                                    Recorded: {{ \Carbon\Carbon::parse($history->recorded_date)->format('M d, Y') }}
                                                    @if($history->diagnosis_date)
                                                        | Diagnosed: {{ \Carbon\Carbon::parse($history->diagnosis_date)->format('M d, Y') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        @if($history->treatment)
                                            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                    <i class="fa fa-pills mr-1 text-blue-600"></i> Treatment
                                                </p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $history->treatment }}</p>
                                            </div>
                                        @endif

                                        @if($history->notes)
                                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                                                    <i class="fa fa-sticky-note mr-1 text-gray-600"></i> Notes
                                                </p>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $history->notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fa fa-file-medical text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">No medical history records</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Click "Add Record" to create the first record</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add History Modal -->
    <div id="addHistoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">Add Medical History Record</h3>
                <button onclick="document.getElementById('addHistoryModal').classList.add('hidden')" class="text-white hover:text-gray-200">
                    <i class="fa fa-times text-2xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('hms.medical-history.store') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <input type="hidden" name="recorded_date" value="{{ date('Y-m-d') }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Condition / Diagnosis <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="condition" required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="e.g., Hypertension, Diabetes Type 2, Asthma">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Diagnosis Date
                    </label>
                    <input type="date" name="diagnosis_date"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Treatment
                    </label>
                    <textarea name="treatment" rows="3"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Medications, procedures, or ongoing treatment..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Additional Notes
                    </label>
                    <textarea name="notes" rows="2"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Any additional observations or details..."></textarea>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_chronic" value="1"
                        class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700">
                    <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        This is a chronic condition
                    </label>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                        <i class="fa fa-save mr-2"></i> Save Record
                    </button>
                    <button type="button" onclick="document.getElementById('addHistoryModal').classList.add('hidden')" 
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

