@extends('admin.layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-plus-circle text-emerald-600 mr-3"></i>
                        Record Payment
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Record a new payment for an invoice</p>
                </div>
                <a href="{{ route('hms.billing.payments.index') }}" 
                   class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition flex items-center">
                    <i class="fa fa-arrow-left mr-2"></i>
                    Back to Payments
                </a>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Payment Details</h3>
            </div>
            
            <form action="{{ route('hms.billing.payments.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Invoice Selection -->
                    <div class="md:col-span-2">
                        <label for="invoice_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Invoice <span class="text-red-500">*</span>
                        </label>
                        <select name="invoice_id" id="invoice_id" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" 
                                required>
                            <option value="">Select Invoice</option>
                            @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" 
                                    data-balance="{{ $invoice->balance_amount }}"
                                    data-patient="{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}">
                                {{ $invoice->invoice_number }} - {{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }} 
                                (Balance: ${{ number_format($invoice->balance_amount, 2) }})
                            </option>
                            @endforeach
                        </select>
                        @error('invoice_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   name="amount" 
                                   id="amount"
                                   step="0.01" 
                                   min="0.01"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" 
                                   placeholder="0.00"
                                   required>
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" 
                                required>
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="insurance">Insurance</option>
                            <option value="check">Check</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="payment_date" 
                               id="payment_date"
                               value="{{ date('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" 
                               required>
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reference Number -->
                    <div>
                        <label for="payment_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reference Number
                        </label>
                        <input type="text" 
                               name="payment_reference" 
                               id="payment_reference"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" 
                               placeholder="Transaction reference or check number">
                        @error('payment_reference')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" 
                              id="notes"
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" 
                              placeholder="Additional payment notes or comments"></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Balance Information -->
                <div id="balance-info" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hidden">
                    <div class="flex items-center">
                        <i class="fa fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Invoice Balance</p>
                            <p class="text-sm text-blue-600 dark:text-blue-300" id="balance-amount">$0.00</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('hms.billing.payments.index') }}" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition flex items-center">
                        <i class="fa fa-save mr-2"></i>
                        Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const invoiceSelect = document.getElementById('invoice_id');
    const amountInput = document.getElementById('amount');
    const balanceInfo = document.getElementById('balance-info');
    const balanceAmount = document.getElementById('balance-amount');

    invoiceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const balance = parseFloat(selectedOption.dataset.balance);
            balanceAmount.textContent = '$' + balance.toFixed(2);
            balanceInfo.classList.remove('hidden');
            
            // Set max amount to balance
            amountInput.max = balance;
        } else {
            balanceInfo.classList.add('hidden');
        }
    });

    // Validate amount doesn't exceed balance
    amountInput.addEventListener('input', function() {
        const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
        if (selectedOption.value) {
            const balance = parseFloat(selectedOption.dataset.balance);
            const amount = parseFloat(this.value);
            
            if (amount > balance) {
                this.setCustomValidity('Payment amount cannot exceed invoice balance');
            } else {
                this.setCustomValidity('');
            }
        }
    });
});
</script>
@endsection