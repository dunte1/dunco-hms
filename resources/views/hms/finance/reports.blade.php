<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Financial Reports & Analytics') }}
            </h2>
            <a href="{{ route('hms.finance.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Finance Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Date Range Filter -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="GET" class="flex items-end gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                            <input type="date" name="from_date" value="{{ $fromDate }}" 
                                   class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                            <input type="date" name="to_date" value="{{ $toDate }}" 
                                   class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                            Generate Report
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('hms.finance.profit-loss') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow-sm text-center">
                    <h3 class="text-lg font-semibold">Profit & Loss</h3>
                    <p class="text-sm mt-1">Income Statement</p>
                </a>
                <a href="{{ route('hms.finance.balance-sheet') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow-sm text-center">
                    <h3 class="text-lg font-semibold">Balance Sheet</h3>
                    <p class="text-sm mt-1">Financial Position</p>
                </a>
                <a href="{{ route('hms.finance.cash-flow') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg shadow-sm text-center">
                    <h3 class="text-lg font-semibold">Cash Flow</h3>
                    <p class="text-sm mt-1">Cash Statement</p>
                </a>
                <a href="{{ route('hms.finance.income.reports') }}" 
                   class="bg-orange-600 hover:bg-orange-700 text-white p-6 rounded-lg shadow-sm text-center">
                    <h3 class="text-lg font-semibold">Income Reports</h3>
                    <p class="text-sm mt-1">Revenue Analysis</p>
                </a>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Income</div>
                    <div class="text-2xl font-bold text-green-600">KES {{ number_format($stats['total_income'], 2) }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Expenses</div>
                    <div class="text-2xl font-bold text-red-600">KES {{ number_format($stats['total_expenses'], 2) }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Net Profit</div>
                    <div class="text-2xl font-bold {{ $stats['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        KES {{ number_format($stats['net_profit'], 2) }}
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Profit Margin</div>
                    <div class="text-2xl font-bold {{ $stats['profit_margin'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($stats['profit_margin'], 1) }}%
                    </div>
                </div>
            </div>

            <!-- Income by Category -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Income by Category</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Count</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Amount</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">% of Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($incomeByCategory as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ ucfirst(str_replace('_', ' ', $item->income_category)) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                            {{ $item->count }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right font-bold text-green-600">
                                            KES {{ number_format($item->total_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                            {{ $stats['total_income'] > 0 ? number_format(($item->total_amount / $stats['total_income']) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No income in this period
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Expenses by Category -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Expenses by Category</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Count</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Amount</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">% of Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($expensesByCategory as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $item->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                            {{ $item->count }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right font-bold text-red-600">
                                            KES {{ number_format($item->total_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                            {{ $stats['total_expenses'] > 0 ? number_format(($item->total_amount / $stats['total_expenses']) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No expenses in this period
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Monthly Trend (Last 12 Months) -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">12-Month Trend</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Month</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Income</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Expenses</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Profit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($monthlyTrend as $month)
                                    @php
                                        $profit = $month['income'] - $month['expenses'];
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                            {{ $month['month'] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-green-600">
                                            KES {{ number_format($month['income'], 0) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-red-600">
                                            KES {{ number_format($month['expenses'], 0) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            KES {{ number_format($profit, 0) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

