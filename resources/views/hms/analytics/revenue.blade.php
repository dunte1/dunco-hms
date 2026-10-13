<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-2">
                        <a href="{{ route('analytics.bi-dashboard') }}" class="hover:text-blue-600">Analytics</a>
                        <i class="fa fa-chevron-right text-[10px]"></i>
                        <span class="text-gray-700 dark:text-gray-300">Revenue</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-chart-line text-green-600 mr-2 sm:mr-3"></i>
                        Revenue Analytics
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Revenue totals, averages, and growth outlook
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('analytics.bi-dashboard') }}"
                       class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium">
                        <i class="fa fa-arrow-left mr-2"></i> BI Dashboard
                    </a>
                    <a href="{{ route('analytics.bi-dashboard.pdf') }}"
                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium">
                        <i class="fa fa-file-pdf mr-2"></i> PDF
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format((float)($revenue['total_revenue'] ?? 0), 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Daily Average</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format((float)($revenue['daily_average'] ?? 0), 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Growth Rate</p>
                    <p class="text-3xl font-bold {{ ($revenue['growth_rate'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                        {{ number_format((float)($revenue['growth_rate'] ?? 0), 1) }}%
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-brain text-purple-600 mr-2"></i> Revenue Predictions
                    </h2>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-xs text-green-700 dark:text-green-300 uppercase">Next Month</p>
                        <p class="text-2xl font-bold text-green-800 dark:text-green-200 mt-1">${{ number_format((float)($predictions['next_month'] ?? 0), 2) }}</p>
                    </div>
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-xs text-blue-700 dark:text-blue-300 uppercase">Next Quarter</p>
                        <p class="text-2xl font-bold text-blue-800 dark:text-blue-200 mt-1">${{ number_format((float)($predictions['next_quarter'] ?? 0), 2) }}</p>
                    </div>
                    <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <p class="text-xs text-purple-700 dark:text-purple-300 uppercase">Confidence</p>
                        <p class="text-2xl font-bold text-purple-800 dark:text-purple-200 mt-1">{{ (int)($predictions['confidence'] ?? 0) }}%</p>
                    </div>
                </div>
            </div>

            @if(!empty($insights))
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-200 mb-1">Insights</p>
                    <p class="text-sm text-amber-800 dark:text-amber-300">{{ $insights }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
