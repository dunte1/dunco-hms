<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.advance-payments.index') }}" class="hover:text-emerald-600">Advance Payments</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Payment #{{ str_pad($advancePayment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-money-bill-wave text-emerald-600 mr-3"></i>Advance Payment Details</h1>
                </div>
                <a href="{{ route('hms.advance-payments.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Payment #:</span> <span class="font-medium text-gray-900 dark:text-white">#{{ str_pad($advancePayment->id, 6, '0', STR_PAD_LEFT) }}</span></div>
                    <div><span class="text-gray-500">Patient:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $advancePayment->patient->first_name ?? '' }} {{ $advancePayment->patient->last_name ?? '' }}</span></div>
                    <div><span class="text-gray-500">Amount:</span> <span class="font-semibold text-emerald-600">${{ number_format($advancePayment->amount, 2) }}</span></div>
                    <div><span class="text-gray-500">Used:</span> <span class="font-medium text-gray-900 dark:text-white">${{ number_format($advancePayment->used_amount, 2) }}</span></div>
                    <div><span class="text-gray-500">Balance:</span> <span class="font-semibold text-blue-600">${{ number_format($advancePayment->balance_amount, 2) }}</span></div>
                    <div><span class="text-gray-500">Status:</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $advancePayment->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($advancePayment->status) }}
                        </span>
                    </div>
                    <div><span class="text-gray-500">Method:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $advancePayment->payment_method)) }}</span></div>
                    <div><span class="text-gray-500">Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $advancePayment->payment_date->format('M d, Y') }}</span></div>
                    @if($advancePayment->notes)
                        <div class="md:col-span-2"><span class="text-gray-500">Notes:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $advancePayment->notes }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
