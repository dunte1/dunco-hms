<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa fa-procedures text-red-600 mr-2"></i> IPD Final Bill — {{ $admission->admission_number }}
                </h1>
                <a href="{{ route('hms.ipd.show', $admission) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm">Back</a>
            </div>

            @if(session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <dl class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    <div><dt class="text-gray-500">Patient</dt><dd>{{ optional($admission->patient)->full_name }}</dd></div>
                    <div><dt class="text-gray-500">MRN</dt><dd>{{ optional($admission->patient)->patient_no }}</dd></div>
                    <div><dt class="text-gray-500">Admitted</dt><dd>{{ optional($admission->admission_date)->format('d M Y') }}</dd></div>
                    <div><dt class="text-gray-500">LOS</dt><dd>{{ $bill['los'] }} day(s)</dd></div>
                    <div><dt class="text-gray-500">Bed rate</dt><dd>{{ number_format($bill['bed_rate'], 2) }}/day</dd></div>
                    <div><dt class="text-gray-500">Status</dt><dd>{{ $admission->status }}</dd></div>
                    <div class="col-span-2"><dt class="text-gray-500">Existing invoice</dt>
                        <dd>{{ $existingInvoice ? $existingInvoice->invoice_number . ' (' . $existingInvoice->status . ')' : 'None yet' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold">Bill lines</h2>
                    @if(!$existingInvoice)
                        <form method="POST" action="{{ route('hms.ipd.final-bill.generate', $admission) }}">
                            @csrf
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                                <i class="fa fa-file-invoice mr-1"></i> Generate Final Bill Invoice
                            </button>
                        </form>
                    @endif
                </div>
                @if(count($bill['lines']))
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-gray-500 border-b">
                            <th class="py-2">Type</th><th>Description</th><th>Qty</th><th>Unit</th><th class="text-right">Total</th>
                        </tr></thead>
                        <tbody>
                            @foreach($bill['lines'] as $line)
                                <tr class="border-b">
                                    <td class="py-2">{{ $line['type'] }}</td>
                                    <td>{{ $line['name'] }} — {{ $line['description'] }}</td>
                                    <td>{{ $line['quantity'] }}</td>
                                    <td>{{ number_format($line['unit_price'], 2) }}</td>
                                    <td class="text-right font-semibold">{{ number_format($line['total'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="font-bold">
                                <td colspan="4" class="py-2 text-right">Grand total</td>
                                <td class="text-right">{{ number_format($bill['total'], 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 text-sm">No billable lines yet. Assign a bed type with daily charge, or create lab invoices during the stay.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
