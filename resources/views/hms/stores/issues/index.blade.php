<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Store Issues</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Stock issued from stores</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Total</p><p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Pending</p><p class="text-2xl font-bold">{{ $stats['pending'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Issued</p><p class="text-2xl font-bold">{{ $stats['issued'] ?? 0 }}</p></div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"><p class="text-sm text-gray-500">Returned</p><p class="text-2xl font-bold">{{ $stats['returned'] ?? 0 }}</p></div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Issue #</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Store</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($issues ?? []) as $issue)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $issue->issue_number ?? $issue->id }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $issue->store->name ?? '—' }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $issue->status }}</span></td>
                                    <td class="px-4 py-3 text-sm">{{ optional($issue->created_at)->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No stock issues yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($issues ?? null, 'links'))
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $issues->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
