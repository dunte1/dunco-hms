<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Finance & Accounts Dashboard') }}
            </h2>
            <a href="{{ route('hms.finance.reports') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                View All Reports
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Key Financial Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-sm rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">Total Income</div>
                            <div class="text-3xl font-bold mt-2">{{ number_format($stats['total_income'], 0) }}</div>
                            <div class="text-xs opacity-75 mt-1">KES</div>
                        </div>
                        <svg class="w-16 h-16 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-4 text-xs opacity-90">
                        Month: KES {{ number_format($stats['month_income'], 0) }}
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 overflow-hidden shadow-sm rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">Total Expenses</div>
                            <div class="text-3xl font-bold mt-2">{{ number_format($stats['total_expenses'], 0) }}</div>
                            <div class="text-xs opacity-75 mt-1">KES</div>
                        </div>
                        <svg class="w-16 h-16 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-4 text-xs opacity-90">
                        Month: KES {{ number_format($stats['month_expenses'], 0) }}
                    </div>
                </div>

                <div class="bg-gradient-to-br from-{{ $stats['net_profit'] >= 0 ? 'blue' : 'orange' }}-500 to-{{ $stats['net_profit'] >= 0 ? 'blue' : 'orange' }}-600 overflow-hidden shadow-sm rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">Net Profit</div>
                            <div class="text-3xl font-bold mt-2">{{ number_format(abs($stats['net_profit']), 0) }}</div>
                            <div class="text-xs opacity-75 mt-1">KES {{ $stats['net_profit'] < 0 ? '(Loss)' : '' }}</div>
                        </div>
                        <svg class="w-16 h-16 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-4 text-xs opacity-90">
                        Month: KES {{ number_format(abs($stats['month_profit']), 0) }}
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow-sm rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm opacity-90">Today's Activity</div>
                            <div class="text-3xl font-bold mt-2">{{ number_format(abs($stats['today_profit']), 0) }}</div>
                            <div class="text-xs opacity-75 mt-1">KES {{ $stats['today_profit'] < 0 ? '(Loss)' : 'Profit' }}</div>
                        </div>
                        <svg class="w-16 h-16 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mt-4 text-xs opacity-90">
                        Income: {{ number_format($stats['today_income'], 0) }} | Expenses: {{ number_format($stats['today_expenses'], 0) }}
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('hms.finance.income.create') }}" 
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Record Income</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Add new income entry</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.finance.expenses.create') }}" 
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Record Expense</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Add new expense</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.finance.accounts.create') }}" 
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">New Account</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Add to chart of accounts</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('hms.finance.reports') }}" 
                   class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white">View Reports</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Financial analytics</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Top Income Categories (This Month) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Income Sources (This Month)</h3>
                        
                        <div class="space-y-3">
                            @forelse($topIncomeCategories as $category)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1">
                                        <div class="w-2 h-10 bg-green-500 rounded mr-3"></div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ ucfirst(str_replace('_', ' ', $category->income_category)) }}
                                            </p>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                                                <div class="bg-green-500 h-2 rounded-full" 
                                                     style="width: {{ $topIncomeCategories->sum('total') > 0 ? ($category->total / $topIncomeCategories->sum('total')) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <p class="text-sm font-bold text-green-600">KES {{ number_format($category->total, 0) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No income data for this month</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Expense Categories (This Month) -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Expense Categories (This Month)</h3>
                        
                        <div class="space-y-3">
                            @forelse($topExpenseCategories as $category)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1">
                                        <div class="w-2 h-10 bg-red-500 rounded mr-3"></div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $category->category->name ?? 'N/A' }}
                                            </p>
                                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-1">
                                                <div class="bg-red-500 h-2 rounded-full" 
                                                     style="width: {{ $topExpenseCategories->sum('total') > 0 ? ($category->total / $topExpenseCategories->sum('total')) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <p class="text-sm font-bold text-red-600">KES {{ number_format($category->total, 0) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No expense data for this month</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Income -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Income</h3>
                            <a href="{{ route('hms.finance.income.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($recentIncome as $income)
                                <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $income->income_number }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $income->income_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $income->account->account_name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-green-600">+KES {{ number_format($income->amount, 0) }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $income->income_category)) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No recent income</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Expenses</h3>
                            <a href="{{ route('hms.finance.expenses.index') }}" class="text-sm text-blue-600 hover:underline">View All</a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($recentExpenses as $expense)
                                <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $expense->expense_number }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $expense->expense_date->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-500">{{ $expense->category->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-red-600">-KES {{ number_format($expense->amount, 0) }}</p>
                                        <span class="text-xs px-2 py-1 rounded-full 
                                            @if($expense->status == 'paid') bg-green-100 text-green-800
                                            @elseif($expense->status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($expense->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No recent expenses</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Reports Quick Links -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Reports</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('hms.finance.profit-loss') }}" 
                           class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-colors text-center">
                            <svg class="w-8 h-8 mx-auto text-blue-600 dark:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Profit & Loss</p>
                        </a>

                        <a href="{{ route('hms.finance.balance-sheet') }}" 
                           class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-green-500 dark:hover:border-green-400 transition-colors text-center">
                            <svg class="w-8 h-8 mx-auto text-green-600 dark:text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Balance Sheet</p>
                        </a>

                        <a href="{{ route('hms.finance.cash-flow') }}" 
                           class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-500 dark:hover:border-purple-400 transition-colors text-center">
                            <svg class="w-8 h-8 mx-auto text-purple-600 dark:text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Cash Flow</p>
                        </a>

                        <a href="{{ route('hms.finance.trial-balance') }}" 
                           class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-orange-500 dark:hover:border-orange-400 transition-colors text-center">
                            <svg class="w-8 h-8 mx-auto text-orange-600 dark:text-orange-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Trial Balance</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

