<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.nursing-care-plans.index') }}" class="hover:text-cyan-600">Nursing Care Plans</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit {{ $carePlan->care_plan_number ?? $carePlan->id }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-edit text-cyan-600 mr-3"></i>Edit Nursing Care Plan
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 p-4">
                    <h3 class="text-lg font-bold text-white">Care Plan Information</h3>
                </div>

                <form method="POST" action="{{ route('hms.nursing-care-plans.update', $carePlan) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Patient</label>
                            <select name="patient_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" @selected(old('patient_id', $carePlan->patient_id) == $patient->id)>
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Assigned Nurse</label>
                            <select name="assigned_nurse_id" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Nurse</option>
                                @foreach($nurses as $nurse)
                                    <option value="{{ $nurse->id }}" @selected(old('assigned_nurse_id', $carePlan->assigned_nurse_id) == $nurse->id)>
                                        {{ $nurse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-2">Nursing Diagnosis *</label>
                            <textarea name="nursing_diagnosis" rows="3" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('nursing_diagnosis', $carePlan->nursing_diagnosis) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-2">Goal</label>
                            <textarea name="goal" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('goal', $carePlan->goal) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-2">Interventions</label>
                            <textarea name="interventions" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('interventions', $carePlan->interventions) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Expected outcome</label>
                            <textarea name="expected_outcome" rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('expected_outcome', $carePlan->expected_outcome) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Actual outcome</label>
                            <textarea name="actual_outcome" rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('actual_outcome', $carePlan->actual_outcome) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Evaluation</label>
                            <textarea name="evaluation" rows="2" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('evaluation', $carePlan->evaluation) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Status *</label>
                            <select name="status" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach(['active','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" @selected(old('status', $carePlan->status) === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">Update Care Plan</button>
                        <a href="{{ route('hms.nursing-care-plans.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
