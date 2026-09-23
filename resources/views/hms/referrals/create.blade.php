<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-exchange-alt text-indigo-600 mr-3"></i>
                        New Referral
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create a new patient referral</p>
                </div>
                <a href="{{ route('hms.referrals.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa fa-arrow-left mr-2"></i> Back
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.referrals.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                <i class="fa fa-user-injured text-indigo-600 mr-2"></i> Patient Information
                            </h3>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Patient <span class="text-red-500">*</span>
                            </label>
                            <select name="patient_id" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }} ({{ $patient->patient_no }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                OPD Visit
                            </label>
                            <select name="opd_visit_id"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Select OPD Visit (Optional)</option>
                                @foreach($opdVisits ?? [] as $visit)
                                    <option value="{{ $visit->id }}" {{ old('opd_visit_id') == $visit->id ? 'selected' : '' }}>
                                        {{ $visit->visit_number }} - {{ $visit->created_at->format('M d, Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('opd_visit_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                IPD Admission
                            </label>
                            <select name="ipd_admission_id"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Select IPD Admission (Optional)</option>
                                @foreach($ipdAdmissions ?? [] as $admission)
                                    <option value="{{ $admission->id }}" {{ old('ipd_admission_id') == $admission->id ? 'selected' : '' }}>
                                        {{ $admission->admission_number }} - {{ $admission->created_at->format('M d, Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ipd_admission_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Referral Type <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-6 mt-2">
                                <label class="flex items-center">
                                    <input type="radio" name="referral_type" value="in" {{ old('referral_type', 'in') === 'in' ? 'checked' : '' }}
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">In Referral</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="referral_type" value="out" {{ old('referral_type') === 'out' ? 'checked' : '' }}
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Out Referral</span>
                                </label>
                            </div>
                            @error('referral_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Urgency <span class="text-red-500">*</span>
                            </label>
                            <select name="urgency" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Select Urgency</option>
                                <option value="emergency" {{ old('urgency') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="urgent" {{ old('urgency') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                <option value="routine" {{ old('urgency') === 'routine' ? 'selected' : '' }}>Routine</option>
                            </select>
                            @error('urgency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Facility Information -->
                        <div class="md:col-span-2 mt-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                <i class="fa fa-hospital text-indigo-600 mr-2"></i> Facility Information
                            </h3>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Referring Facility <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="referring_facility" value="{{ old('referring_facility') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Enter referring facility name">
                            @error('referring_facility')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Receiving Facility <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="receiving_facility" value="{{ old('receiving_facility') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Enter receiving facility name">
                            @error('receiving_facility')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Referring Doctor
                            </label>
                            <select name="referring_doctor_id"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Select Referring Doctor (Optional)</option>
                                @foreach($doctors ?? [] as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('referring_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('referring_doctor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Receiving Doctor
                            </label>
                            <select name="receiving_doctor_id"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="">Select Receiving Doctor (Optional)</option>
                                @foreach($doctors ?? [] as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('receiving_doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('receiving_doctor_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Clinical Information -->
                        <div class="md:col-span-2 mt-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                <i class="fa fa-notes-medical text-indigo-600 mr-2"></i> Clinical Information
                            </h3>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Reason for Referral <span class="text-red-500">*</span>
                            </label>
                            <textarea name="reason" rows="3" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Enter reason for referral">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Clinical Summary
                            </label>
                            <textarea name="clinical_summary" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="Enter clinical summary including diagnosis, history, and current condition">{{ old('clinical_summary') }}</textarea>
                            @error('clinical_summary')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Investigations Done
                            </label>
                            <textarea name="investigations_done" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                placeholder="List any investigations or tests already performed">{{ old('investigations_done') }}</textarea>
                            @error('investigations_done')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex items-center justify-end gap-4">
                        <a href="{{ route('hms.referrals.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium">
                            <i class="fa fa-paper-plane mr-2"></i> Create Referral
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
