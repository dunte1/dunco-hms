<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.advance-payments.index') }}" class="hover:text-emerald-600">Advance Payments</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>New Advance Payment</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-plus-circle text-emerald-600 mr-3"></i>Record Advance Payment</h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.advance-payments.store') }}" method="POST" id="advancePaymentForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="payment_id" id="advance-payment-id" value="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient <span class="text-red-500">*</span></label>
                            <select name="patient_id" id="advancePatientSelect" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" data-phone="{{ $patient->phone }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }} ({{ $patient->patient_no }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                <input type="number" name="amount" id="advanceAmount" step="0.01" min="0.01" required value="{{ old('amount') }}"
                                    class="w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            @error('amount')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method <span class="text-red-500">*</span></label>
                            <select name="payment_method" id="advancePaymentMethod" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Method</option>
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="mpesa" {{ old('payment_method') === 'mpesa' ? 'selected' : '' }}>M-Pesa</option>
                                <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="insurance" {{ old('payment_method') === 'insurance' ? 'selected' : '' }}>Insurance</option>
                            </select>
                            @error('payment_method')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Date <span class="text-red-500">*</span></label>
                            <input type="date" name="payment_date" required value="{{ old('payment_date', date('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('payment_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                            <textarea name="notes" rows="3"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes') }}</textarea>
                            @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <div id="advance-mpesa-action" style="display:none;" class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-emerald-800 dark:text-emerald-300">Deposit will be paid instantly by the patient to their M-Pesa.</p>
                                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">The patient will receive a push prompt on their phone.</p>
                                    </div>
                                    <button type="button" id="startAdvanceMpesaPayment" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium">
                                        <i class="fa fa-mobile-alt mr-2"></i> Pay with M-Pesa
                                    </button>
                                </div>
                                <span id="advance-mpesa-status" style="display:none;" class="text-emerald-700 dark:text-emerald-300 font-semibold text-sm mt-3 block">
                                    <i class="fa fa-check-circle"></i> Payment confirmed
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Record Payment</button>
                        <a href="{{ route('hms.advance-payments.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@include('hms.partials.mpesa-payment-modal')

<script>
document.addEventListener('DOMContentLoaded', function () {
    var methodSelect = document.getElementById('advancePaymentMethod');
    var mpesaAction = document.getElementById('advance-mpesa-action');

    methodSelect.addEventListener('change', function () {
        mpesaAction.style.display = (this.value === 'mpesa') ? 'block' : 'none';
    });

    document.getElementById('startAdvanceMpesaPayment').addEventListener('click', function () {
        var patient = document.getElementById('advancePatientSelect').selectedOptions[0];
        var amount = parseFloat(document.getElementById('advanceAmount').value) || 0;

        if (!patient || !patient.value) { alert('Select a patient first.'); return; }
        if (amount <= 0) { alert('Enter a deposit amount first.'); return; }

        openMpesaModal({
            patientId: patient.value,
            phone: patient.dataset.phone || '',
            feeType: 'deposit',
            feeLabel: 'Deposit',
            itemName: 'Patient Deposit',
            amount: amount,
            sourceType: 'advance_payment',
            onSuccess: function (config, resp) {
                document.getElementById('advance-payment-id').value = config.paymentId;
                document.getElementById('advance-mpesa-status').style.display = 'block';
            }
        });
    });

    document.getElementById('advancePaymentForm').addEventListener('submit', function (e) {
        if (methodSelect.value === 'mpesa' && !document.getElementById('advance-payment-id').value) {
            e.preventDefault();
            alert('Complete the M-Pesa payment for the deposit before recording the advance payment.');
        }
    });

    if (methodSelect.value === 'mpesa') { mpesaAction.style.display = 'block'; }
});
</script>
</x-app-layout>
