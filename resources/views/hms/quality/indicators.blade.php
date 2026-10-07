<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Quality Indicators</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Clinical quality KPIs and indicators</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indicator</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actual</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse(($indicators ?? collect()) as $row)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium">{{ $row->name ?? $row->indicator ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $row->target ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $row->actual ?? $row->value ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $row->period ?? optional($row->created_at)->format('M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No quality indicators recorded</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
