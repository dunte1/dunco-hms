<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-gauge-high text-indigo-600 mr-2 sm:mr-3"></i>
                        Key Performance Indicators
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Track hospital KPIs, targets, and latest snapshots
                    </p>
                </div>
            </div>

            @if(session('status'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total KPIs</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Active</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Snapshots</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $stats['snapshots'] }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">KPI Definitions</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Code</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Target</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Unit</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Latest</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($kpis as $kpi)
                                @php $latest = $kpi->snapshots->first(); @endphp
                                <tr>
                                    <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $kpi->name }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300 font-mono">{{ $kpi->code }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $kpi->target_value !== null ? number_format((float)$kpi->target_value, 2) : '—' }}
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $kpi->unit ?: '—' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $latest?->actual_value !== null ? number_format((float)$latest->actual_value, 2) : '—' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($latest)
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $latest->status === 'met' ? 'bg-green-100 text-green-800' : ($latest->status === 'not_met' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700') }}">
                                                {{ str_replace('_', ' ', $latest->status) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">No data</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-500">
                                        No KPI definitions yet. Seed or create KPIs to start tracking performance.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
