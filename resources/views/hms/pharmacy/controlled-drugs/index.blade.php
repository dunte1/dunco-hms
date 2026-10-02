<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-prescription-bottle-alt text-purple-600 mr-3"></i> Controlled Drug Register
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Audited register of controlled medicine transactions with running balance</p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-left">Medicine</th>
                                <th class="px-4 py-3 text-left">Batch</th>
                                <th class="px-4 py-3 text-left">Type</th>
                                <th class="px-4 py-3 text-left">Qty</th>
                                <th class="px-4 py-3 text-left">Balance</th>
                                <th class="px-4 py-3 text-left">Ref</th>
                                <th class="px-4 py-3 text-left">By</th>
                                <th class="px-4 py-3 text-left">Witness</th>
                                <th class="px-4 py-3 text-left">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($registers as $reg)
                                <tr>
                                    <td class="px-4 py-3">{{ optional($reg->created_at)->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3">{{ optional($reg->medicine)->name ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ optional($reg->batch)->batch_number ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $reg->transaction_type }}</td>
                                    <td class="px-4 py-3">{{ $reg->quantity }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $reg->balance_after }}</td>
                                    <td class="px-4 py-3">{{ $reg->reference_type }} #{{ $reg->reference_id }}</td>
                                    <td class="px-4 py-3">{{ $reg->performed_by }}</td>
                                    <td class="px-4 py-3">{{ $reg->witnessed_by ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $reg->notes }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-8 text-center text-gray-500">No controlled drug transactions recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
