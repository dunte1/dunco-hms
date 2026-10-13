<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="min-w-0">
                    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-2 flex-wrap">
                        <a href="{{ route('analytics.bi-dashboard') }}" class="hover:text-blue-600">Analytics</a>
                        <i class="fa fa-chevron-right text-[10px]"></i>
                        <span class="text-gray-700 dark:text-gray-300">Report #{{ $analytic->id }}</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-chart-bar text-purple-600 mr-2 sm:mr-3"></i>
                        {{ $analytic->metric_name ?: 'Analytics Report' }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ ucwords(str_replace('_', ' ', $analytic->metric_type ?? 'metric')) }}
                        · {{ $analytic->date_from?->format('M d, Y') ?? 'N/A' }} → {{ $analytic->date_to?->format('M d, Y') ?? 'N/A' }}
                        · {{ ucfirst($analytic->granularity ?? 'daily') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('analytics.bi-dashboard') }}"
                       class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                    <a href="{{ route('analytics.export', $analytic) }}"
                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">
                        <i class="fa fa-file-pdf mr-2"></i> Download PDF
                    </a>
                    <button type="button" onclick="window.print()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium no-print">
                        <i class="fa fa-print mr-2"></i> Print
                    </button>
                </div>
            </div>

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 text-sm text-red-800 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Summary cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Data points</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ count($dataPoints) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Predictions</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ count($predictions) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Created</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $analytic->created_at?->format('M d, Y H:i') ?? '—' }}</p>
                </div>
            </div>

            <!-- Data points -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Data Points</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">#</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Period</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Value</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($dataPoints as $i => $point)
                                <tr>
                                    <td class="px-5 py-3 text-sm text-gray-500">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-900 dark:text-white">{{ $point['date'] ?? '—' }}</td>
                                    <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ is_numeric($point['value'] ?? null) ? number_format((float) $point['value'], 2) : ($point['value'] ?? '—') }}
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $point['type'] ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-500">
                                        No data points recorded for this report.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Predictions + Insights -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-brain text-purple-600 mr-2"></i> Predictions
                        </h2>
                    </div>
                    <div class="p-5">
                        @if(count($predictions))
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Predicted</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Confidence</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($predictions as $p)
                                            <tr>
                                                <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">{{ $p['period'] ?? '—' }}</td>
                                                <td class="px-3 py-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ is_numeric($p['predicted_value'] ?? null) ? number_format((float) $p['predicted_value'], 2) : ($p['predicted_value'] ?? '—') }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300">{{ $p['confidence'] ?? '—' }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No predictions available for this report.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-lightbulb text-amber-500 mr-2"></i> Insights
                        </h2>
                    </div>
                    <div class="p-5">
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                            {{ $analytic->insights ?? 'No insights generated for this report.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
