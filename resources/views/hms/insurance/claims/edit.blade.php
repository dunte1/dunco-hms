<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.insurance.claims.index') }}" class="hover:text-blue-600">Insurance Claims</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <a href="{{ route('hms.insurance.claims.show', $claim) }}" class="hover:text-blue-600">{{ $claim->claim_number }}</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-edit text-blue-600 mr-3"></i>
                            Edit Insurance Claim
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Claim #{{ $claim->claim_number }} | 
                            Patient: {{ $claim->patient->full_name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.insurance.claims.show', $claim) }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('hms.insurance.claims.index') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.insurance.claims.update', $claim) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Claim Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-file-medical text-blue-600 mr-2"></i>
                            Claim Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="claim_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Claim Number *</label>
                                <input type="text" name="claim_number" id="claim_number" value="{{ old('claim_number', $claim->claim_number) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                @error('claim_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="insurance_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Insurance Reference</label>
                                <input type="text" name="insurance_reference" id="insurance_reference" value="{{ old('insurance_reference', $claim->insurance_reference) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                @error('insurance_reference')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="diagnosis_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diagnosis Code</label>
                                <input type="text" name="diagnosis_code" id="diagnosis_code" value="{{ old('diagnosis_code', $claim->diagnosis_code) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., ICD-10 code">
                                @error('diagnosis_code')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="claim_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Claim Date *</label>
                                <input type="date" name="claim_date" id="claim_date" value="{{ old('claim_date', $claim->claim_date->format('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                @error('claim_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="service_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Date *</label>
                                <input type="date" name="service_date" id="service_date" value="{{ old('service_date', $claim->service_date->format('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                @error('service_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending" {{ old('status', $claim->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="submitted" {{ old('status', $claim->status) == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="under_review" {{ old('status', $claim->status) == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="approved" {{ old('status', $claim->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $claim->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="paid" {{ old('status', $claim->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Patient & Provider Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-user text-green-600 mr-2"></i>
                            Patient & Provider Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient *</label>
                                <select name="patient_id" id="patient_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id', $claim->patient_id) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->full_name }} ({{ $patient->patient_no }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="patient_insurance_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient Insurance *</label>
                                <select name="patient_insurance_id" id="patient_insurance_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Insurance</option>
                                    @foreach($patientInsurances as $insurance)
                                        <option value="{{ $insurance->id }}" {{ old('patient_insurance_id', $claim->patient_insurance_id) == $insurance->id ? 'selected' : '' }}>
                                            {{ $insurance->patient->full_name }} - {{ $insurance->insuranceProvider->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_insurance_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-stethoscope text-purple-600 mr-2"></i>
                            Treatment Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="treatment_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Treatment Description</label>
                                <textarea name="treatment_description" id="treatment_description" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Describe the treatment or service provided...">{{ old('treatment_description', $claim->treatment_description) }}</textarea>
                                @error('treatment_description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-dollar-sign text-yellow-600 mr-2"></i>
                            Financial Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="claimed_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Claimed Amount *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="claimed_amount" id="claimed_amount" value="{{ old('claimed_amount', $claim->claimed_amount) }}" step="0.01" min="0" required
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('claimed_amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="approved_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Approved Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="approved_amount" id="approved_amount" value="{{ old('approved_amount', $claim->approved_amount) }}" step="0.01" min="0"
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('approved_amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="paid_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paid Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $claim->paid_amount) }}" step="0.01" min="0"
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('paid_amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="deductible_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deductible Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="deductible_amount" id="deductible_amount" value="{{ old('deductible_amount', $claim->deductible_amount) }}" step="0.01" min="0"
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('deductible_amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="copay_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Copay Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="copay_amount" id="copay_amount" value="{{ old('copay_amount', $claim->copay_amount) }}" step="0.01" min="0"
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                @error('copay_amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-sticky-note text-indigo-600 mr-2"></i>
                            Additional Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter any additional notes...">{{ old('notes', $claim->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rejection Reason</label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter rejection reason if applicable...">{{ old('rejection_reason', $claim->rejection_reason) }}</textarea>
                                @error('rejection_reason')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.insurance.claims.show', $claim) }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fa fa-save mr-2"></i> Update Claim
                        </button>
                    </div>
                </form>
            </div>

            <!-- Claim Information -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fa fa-info-circle text-blue-600 dark:text-blue-400 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Claim Information</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                            This claim was created on {{ $claim->created_at->format('M d, Y') }} and last updated on {{ $claim->updated_at->format('M d, Y') }}.
                            Claim ID: #{{ $claim->id }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
