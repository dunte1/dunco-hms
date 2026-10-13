<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-2">
                        <a href="{{ route('hms.dashboard.kpis.index') }}" class="hover:text-indigo-600">KPIs</a>
                        <i class="fa fa-chevron-right text-[10px]"></i>
                        <span class="text-gray-700 dark:text-gray-300">{{ $kpi->name }}</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ $kpi->name }} Trend</h1>
                </div>
                <a href="{{ route('hms.dashboard.kpis.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium">
                    <i class="fa fa-arrow-left mr-2"></i> Back
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Snapshots</h2>
                    <p class="text-xs text-gray-500">Target: {{ $kpi->target_value !== null ? number_format((float)$kpi->target_value, 2).' '.$kpi->unit : '—' }}</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actual</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Target</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($snapshots as $s)
                                <tr>
                                    <td class="px-5 py-4 text-sm text-gray-900 dark:text-white">{{ $s->snapshot_date?->format('M d, Y') }}</td>
                                    <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ number_format((float)$s->actual_value, 2) }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $s->target_value !== null ? number_format((float)$s->target_value, 2) : '—' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $s->status === 'met' ? 'bg-green-100 text-green-800' : ($s->status === 'not_met' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700') }}">
                                            {{ str_replace('_', ' ', $s->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $s->notes ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-500">No snapshots recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
