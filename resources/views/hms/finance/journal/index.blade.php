<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Journal Entries</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Accounting journal</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($entries ?? []) as $entry)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ optional($entry->date)->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $entry->description }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-800">{{ $entry->status }}</span></td>
                                    <td class="px-4 py-3 text-sm">{{ $entry->fiscalPeriod->name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No journal entries yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($entries ?? null, 'links'))
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">{{ $entries->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
