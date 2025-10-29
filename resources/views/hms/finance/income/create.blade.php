<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Record Income') }}
            </h2>
            <a href="{{ route('hms.finance.income.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Income
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('hms.finance.income.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Income Details</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Income Number *</label>
                                <input type="text" name="income_number" value="{{ old('income_number', $incomeNumber) }}" required readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Income Date *</label>
                                <input type="date" name="income_date" value="{{ old('income_date', date('Y-m-d')) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('income_date') border-red-500 @enderror">
                                @error('income_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account *</label>
                                <select name="account_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('account_id') border-red-500 @enderror">
                                    <option value="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_code }} - {{ $account->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('account_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Income Category *</label>
                                <select name="income_category" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('income_category') border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    <option value="patient_services" {{ old('income_category') == 'patient_services' ? 'selected' : '' }}>Patient Services</option>
                                    <option value="pharmacy_sales" {{ old('income_category') == 'pharmacy_sales' ? 'selected' : '' }}>Pharmacy Sales</option>
                                    <option value="lab_tests" {{ old('income_category') == 'lab_tests' ? 'selected' : '' }}>Lab Tests</option>
                                    <option value="radiology" {{ old('income_category') == 'radiology' ? 'selected' : '' }}>Radiology</option>
                                    <option value="consultation_fees" {{ old('income_category') == 'consultation_fees' ? 'selected' : '' }}>Consultation Fees</option>
                                    <option value="admission_fees" {{ old('income_category') == 'admission_fees' ? 'selected' : '' }}>Admission Fees</option>
                                    <option value="surgery_fees" {{ old('income_category') == 'surgery_fees' ? 'selected' : '' }}>Surgery Fees</option>
                                    <option value="ambulance_services" {{ old('income_category') == 'ambulance_services' ? 'selected' : '' }}>Ambulance Services</option>
                                    <option value="other" {{ old('income_category') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('income_category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (KES) *</label>
                                <input type="number" name="amount" value="{{ old('amount') }}" required min="0" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('amount') border-red-500 @enderror">
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method *</label>
                                <select name="payment_method" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('payment_method') border-red-500 @enderror">
                                    <option value="">Select Method</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="m_pesa" {{ old('payment_method') == 'm_pesa' ? 'selected' : '' }}>M-Pesa</option>
                                    <option value="insurance" {{ old('payment_method') == 'insurance' ? 'selected' : '' }}>Insurance</option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Optional Fields -->
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Information (Optional)</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient</label>
                                <select name="patient_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">None</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->patient_no }} - {{ $patient->first_name }} {{ $patient->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Source</label>
                                <input type="text" name="source" value="{{ old('source') }}"
                                       placeholder="e.g., Ward A, OPD, Emergency"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference Number</label>
                                <input type="text" name="reference_number" value="{{ old('reference_number') }}"
                                       placeholder="e.g., Receipt No, Invoice No"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="notes" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Recurring Income -->
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recurring Income</h3>
                            </div>

                            <div class="md:col-span-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600" id="recurringCheckbox">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">This is recurring income</span>
                                </label>
                            </div>

                            <div id="recurringFields" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequency</label>
                                <select name="recurring_frequency"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">Select Frequency</option>
                                    <option value="daily" {{ old('recurring_frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ old('recurring_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ old('recurring_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="yearly" {{ old('recurring_frequency') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('hms.finance.income.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                Record Income
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('recurringCheckbox').addEventListener('change', function() {
            document.getElementById('recurringFields').style.display = this.checked ? 'block' : 'none';
        });
        
        // Show on page load if checked
        if (document.getElementById('recurringCheckbox').checked) {
            document.getElementById('recurringFields').style.display = 'block';
        }
    </script>
</x-app-layout>

