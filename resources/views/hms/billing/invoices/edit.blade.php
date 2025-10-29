<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.billing.invoices.index') }}" class="hover:text-emerald-600">Invoices</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <a href="{{ route('hms.billing.invoices.show', $invoice) }}" class="hover:text-emerald-600">{{ $invoice->invoice_number }}</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-edit text-emerald-600 mr-3"></i>
                            Edit Invoice
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Invoice #{{ $invoice->invoice_number }} | 
                            Patient: {{ $invoice->patient->full_name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.billing.invoices.show', $invoice) }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('hms.billing.invoices.index') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.billing.invoices.update', $invoice) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Invoice Header -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-file-invoice text-emerald-600 mr-2"></i>
                            Invoice Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="invoice_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Invoice Number *</label>
                                <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                @error('invoice_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="invoice_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Invoice Date *</label>
                                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                @error('invoice_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Patient & Doctor Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-user text-blue-600 mr-2"></i>
                            Patient & Doctor Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient *</label>
                                <select name="patient_id" id="patient_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id', $invoice->patient_id) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->full_name }} ({{ $patient->patient_no }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Doctor</label>
                                <select name="doctor_id" id="doctor_id"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id', $invoice->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                            @if($doctor->department)
                                                - {{ $doctor->department->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-list text-purple-600 mr-2"></i>
                            Invoice Items
                        </h3>
                        
                        <div id="items-container">
                            @foreach($invoice->items as $index => $item)
                                <div class="invoice-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Item {{ $index + 1 }}</h4>
                                        @if($index > 0)
                                            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800">
                                                <i class="fa fa-trash"></i> Remove
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description *</label>
                                            <input type="text" name="items[{{ $index }}][description]" value="{{ old('items.'.$index.'.description', $item->description) }}" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                                placeholder="Service or item description">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                                            <input type="number" name="items[{{ $index }}][quantity]" value="{{ old('items.'.$index.'.quantity', $item->quantity) }}" min="1" step="0.01" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price *</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                                </div>
                                                <input type="number" name="items[{{ $index }}][unit_price]" value="{{ old('items.'.$index.'.unit_price', $item->unit_price) }}" step="0.01" min="0" required
                                                    class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                                </div>
                                                <input type="number" name="items[{{ $index }}][total]" value="{{ old('items.'.$index.'.total', $item->total) }}" step="0.01" min="0" required
                                                    class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" onclick="addItem()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add Another Item
                        </button>
                    </div>

                    <!-- Invoice Totals -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-calculator text-green-600 mr-2"></i>
                            Invoice Totals
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <label for="subtotal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subtotal</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="subtotal" id="subtotal" value="{{ old('subtotal', $invoice->subtotal) }}" step="0.01" min="0" required
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                        readonly>
                                </div>
                                @error('subtotal')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate) }}" step="0.01" min="0" max="100"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                @error('tax_rate')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tax_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="tax_amount" id="tax_amount" value="{{ old('tax_amount', $invoice->tax_amount) }}" step="0.01" min="0"
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                        readonly>
                                </div>
                                @error('tax_amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="total_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="total_amount" id="total_amount" value="{{ old('total_amount', $invoice->total_amount) }}" step="0.01" min="0" required
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                        readonly>
                                </div>
                                @error('total_amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-credit-card text-yellow-600 mr-2"></i>
                            Payment Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                    <option value="pending" {{ old('status', $invoice->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="partial" {{ old('status', $invoice->status) == 'partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="paid_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paid Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $invoice->paid_amount) }}" step="0.01" min="0"
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                                @error('paid_amount')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="balance_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Balance Amount</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="balance_amount" id="balance_amount" value="{{ old('balance_amount', $invoice->balance_amount) }}" step="0.01" min="0"
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                        readonly>
                                </div>
                                @error('balance_amount')
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
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Enter any additional notes...">{{ old('notes', $invoice->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="terms_conditions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Terms & Conditions</label>
                                <textarea name="terms_conditions" id="terms_conditions" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                    placeholder="Enter terms and conditions...">{{ old('terms_conditions', $invoice->terms_conditions) }}</textarea>
                                @error('terms_conditions')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.billing.invoices.show', $invoice) }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                            <i class="fa fa-save mr-2"></i> Update Invoice
                        </button>
                    </div>
                </form>
            </div>

            <!-- Invoice Information -->
            <div class="mt-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fa fa-info-circle text-emerald-600 dark:text-emerald-400 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Invoice Information</h4>
                        <p class="text-sm text-emerald-700 dark:text-emerald-300 mt-1">
                            This invoice was created on {{ $invoice->created_at->format('M d, Y') }} and last updated on {{ $invoice->updated_at->format('M d, Y') }}.
                            Invoice ID: #{{ $invoice->id }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = {{ $invoice->items->count() }};

        function addItem() {
            const container = document.getElementById('items-container');
            const itemHtml = `
                <div class="invoice-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Item ${itemIndex + 1}</h4>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description *</label>
                            <input type="text" name="items[${itemIndex}][description]" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="Service or item description">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                            <input type="number" name="items[${itemIndex}][quantity]" min="1" step="0.01" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price *</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                </div>
                                <input type="number" name="items[${itemIndex}][unit_price]" step="0.01" min="0" required
                                    class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                </div>
                                <input type="number" name="items[${itemIndex}][total]" step="0.01" min="0" required
                                    class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-emerald-500 focus:border-emerald-500"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', itemHtml);
            itemIndex++;
        }

        function removeItem(button) {
            button.closest('.invoice-item').remove();
        }

        // Calculate totals when quantity or unit price changes
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('[quantity]') || e.target.name && e.target.name.includes('[unit_price]')) {
                const item = e.target.closest('.invoice-item');
                const quantity = parseFloat(item.querySelector('input[name*="[quantity]"]').value) || 0;
                const unitPrice = parseFloat(item.querySelector('input[name*="[unit_price]"]').value) || 0;
                const total = quantity * unitPrice;
                item.querySelector('input[name*="[total]"]').value = total.toFixed(2);
                
                calculateInvoiceTotals();
            }
        });

        function calculateInvoiceTotals() {
            let subtotal = 0;
            document.querySelectorAll('input[name*="[total]"]').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });
            
            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
            const taxAmount = (subtotal * taxRate) / 100;
            const totalAmount = subtotal + taxAmount;
            
            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('tax_amount').value = taxAmount.toFixed(2);
            document.getElementById('total_amount').value = totalAmount.toFixed(2);
            
            // Calculate balance
            const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
            const balanceAmount = totalAmount - paidAmount;
            document.getElementById('balance_amount').value = balanceAmount.toFixed(2);
        }

        // Calculate totals when tax rate or paid amount changes
        document.getElementById('tax_rate').addEventListener('input', calculateInvoiceTotals);
        document.getElementById('paid_amount').addEventListener('input', calculateInvoiceTotals);
    </script>
</x-app-layout>