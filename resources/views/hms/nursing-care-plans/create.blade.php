<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.nursing-care-plans.index') }}" class="hover:text-cyan-600">Nursing Care Plans</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Create</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-plus-circle text-cyan-600 mr-3"></i>New Nursing Care Plan
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create a new nursing care plan for a patient</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 p-4">
                    <h3 class="text-lg font-bold text-white">Care Plan Information</h3>
                </div>

                <form method="POST" action="{{ route('hms.nursing-care-plans.store') }}" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient *</label>
                            <select name="patient_id" required
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-500">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->patient_id ?? $patient->id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">IPD Admission</label>
                            <select name="ipd_admission_id"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-500">
                                <option value="">None</option>
                                @isset($ipdAdmissions)
                                    @foreach($ipdAdmissions as $admission)
                                        <option value="{{ $admission->id }}" {{ old('ipd_admission_id') == $admission->id ? 'selected' : '' }}>
                                            {{ $admission->admission_number }} - {{ $admission->patient->first_name ?? '' }} {{ $admission->patient->last_name ?? '' }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('ipd_admission_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assigned Nurse</label>
                            <select name="assigned_nurse_id"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-500">
                                <option value="">Select Nurse</option>
                                @foreach($nurses as $nurse)
                                    <option value="{{ $nurse->id }}" {{ old('assigned_nurse_id') == $nurse->id ? 'selected' : '' }}>
                                        {{ $nurse->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_nurse_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nursing Diagnosis *</label>
                            <textarea name="nursing_diagnosis" rows="3" required
                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-500">{{ old('nursing_diagnosis') }}</textarea>
                            @error('nursing_diagnosis') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Goal</label>
                            <textarea name="goal" rows="3"
                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-500">{{ old('goal') }}</textarea>
                            @error('goal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Interventions</label>
                            <textarea name="interventions" rows="4"
                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-500">{{ old('interventions') }}</textarea>
                            @error('interventions') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expected Outcome</label>
                            <textarea name="expected_outcome" rows="3"
                                      class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-500">{{ old('expected_outcome') }}</textarea>
                            @error('expected_outcome') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-lg transition">
                            <i class="fa fa-save mr-2"></i> Create Care Plan
                        </button>
                        <a href="{{ route('hms.nursing-care-plans.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
