<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Requests for Quotation</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Procurement RFQs and supplier quotations</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Total</p><p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Draft</p><p class="text-2xl font-bold">{{ $stats['draft'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Sent</p><p class="text-2xl font-bold">{{ $stats['sent'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Closed</p><p class="text-2xl font-bold">{{ $stats['closed'] ?? 0 }}</p></div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RFQ #</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issued</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($rfqs ?? []) as $rfq)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $rfq->rfq_number ?? $rfq->id }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $rfq->title }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">{{ $rfq->status }}</span></td>
                                    <td class="px-4 py-3 text-sm">{{ optional($rfq->issued_date)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No RFQs yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($rfqs ?? null, 'links'))
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $rfqs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
