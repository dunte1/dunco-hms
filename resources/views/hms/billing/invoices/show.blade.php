<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Actions -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-file-invoice text-emerald-600 mr-3"></i>
                        Invoice {{ $invoice->invoice_number }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Created on {{ $invoice->invoice_date->format('M d, Y') }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.billing.invoices.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                    <a href="{{ route('hms.billing.invoices.thermal-receipt', $invoice) }}" target="_blank" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition" title="Print Thermal Receipt">
                        <i class="fa fa-receipt mr-2"></i> Receipt
                    </a>
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        <i class="fa fa-print mr-2"></i> Print
                    </button>
                    <a href="{{ route('hms.billing.invoices.pdf', $invoice) }}" target="_blank" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        <i class="fa fa-file-pdf mr-2"></i> PDF
                    </a>
                    <button onclick="emailInvoice()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                        <i class="fa fa-envelope mr-2"></i> Email
                    </button>
                </div>
            </div>

            <!-- Invoice Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <!-- Status Bar -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-4 flex justify-between items-center">
                    <div class="text-white">
                        <h2 class="text-2xl font-bold">{{ $invoice->invoice_number }}</h2>
                        <p class="text-sm opacity-90">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-bold {{ $invoice->status == 'paid' ? 'bg-green-500' : ($invoice->status == 'partial' ? 'bg-yellow-500' : 'bg-red-500') }} text-white">
                        {{ strtoupper($invoice->status) }}
                    </span>
                </div>

                <!-- Invoice Details -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Bill To -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Bill To:</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="font-bold text-gray-900 dark:text-white text-lg">{{ $invoice->patient->full_name }}</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ $invoice->patient->email }}</p>
                                <p class="text-gray-600 dark:text-gray-400">{{ $invoice->patient->phone }}</p>
                                @if($invoice->patient->address)
                                    <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $invoice->patient->address }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Invoice Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Invoice Info:</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Invoice Date:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Due Date:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $invoice->due_date->format('M d, Y') }}</span>
                                </div>
                                @if($invoice->doctor)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Doctor:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">Dr. {{ $invoice->doctor->full_name }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto mb-8">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Description</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Qty</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Unit Price</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($invoice->items as $index => $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->description }}</div>
                                        @if($item->item_type)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Type: {{ ucfirst($item->item_type) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900 dark:text-white">${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/2">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 space-y-3">
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Subtotal:</span>
                                    <span class="font-semibold">${{ number_format($invoice->subtotal, 2) }}</span>
                                </div>
                                @if($invoice->tax_amount > 0)
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Tax:</span>
                                    <span class="font-semibold">${{ number_format($invoice->tax_amount, 2) }}</span>
                                </div>
                                @endif
                                @if($invoice->discount_amount > 0)
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Discount:</span>
                                    <span class="font-semibold text-red-600">-${{ number_format($invoice->discount_amount, 2) }}</span>
                                </div>
                                @endif
                                <div class="border-t border-gray-300 dark:border-gray-600 pt-3"></div>
                                <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                    <span>Total:</span>
                                    <span>${{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                                @if($invoice->paid_amount > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Paid:</span>
                                    <span class="font-semibold">-${{ number_format($invoice->paid_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-xl font-bold text-emerald-600">
                                    <span>Balance Due:</span>
                                    <span>${{ number_format($invoice->balance_amount, 2) }}</span>
                                </div>
                                @endif
                            </div>

                            @if($invoice->balance_amount > 0)
                            <button onclick="recordPayment()" class="mt-4 w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
                                <i class="fa fa-credit-card mr-2"></i> Record Payment
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Payment History -->
                    @if($invoice->payments->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Payment History</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-100 dark:bg-gray-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Reference</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($invoice->payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $payment->payment_reference ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">${{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($invoice->notes)
                    <div class="mt-8 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 rounded">
                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-2">Notes:</h4>
                        <p class="text-sm text-blue-800 dark:text-blue-300">{{ $invoice->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function emailInvoice() {
            if (confirm('Send invoice to patient email?')) {
                fetch('{{ route("hms.billing.invoices.email", $invoice) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => {
                    alert('Failed to send email');
                });
            }
        }

        function recordPayment() {
            window.location.href = '{{ route("hms.billing.payments.create", ["invoice" => $invoice->id]) }}';
        }
    </script>
</x-app-layout>


