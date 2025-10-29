@extends('admin.layouts.app')

@section('title', 'Trial Balance')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-balance-scale text-green-600 mr-3"></i>
                        Trial Balance
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Financial statement showing account balances</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="window.print()" 
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition flex items-center">
                        <i class="fa fa-print mr-2"></i>
                        Print Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Report Header -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Trial Balance Report</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">As of {{ now()->format('F d, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Generated on</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ now()->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trial Balance Table -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Account Code
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Account Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Account Type
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Debit Balance
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Credit Balance
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($balances as $balance)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $balance['account']->account_code }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $balance['account']->account_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $balance['account']->account_type === 'asset' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 
                                       ($balance['account']->account_type === 'liability' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : 
                                        ($balance['account']->account_type === 'equity' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : 
                                         ($balance['account']->account_type === 'revenue' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 
                                          'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100'))) }}">
                                    {{ ucfirst($balance['account']->account_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                @if($balance['debit'] > 0)
                                    ${{ number_format($balance['debit'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                @if($balance['credit'] > 0)
                                    ${{ number_format($balance['credit'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                TOTAL
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900 dark:text-white">
                                ${{ number_format($totalDebit, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900 dark:text-white">
                                ${{ number_format($totalCredit, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Balance Verification -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-minus text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Debit</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($totalDebit, 2) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-plus text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Credit</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($totalCredit, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Status -->
        <div class="mt-6">
            @if(abs($totalDebit - $totalCredit) < 0.01)
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                            Trial Balance is Balanced
                        </h3>
                        <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                            Total debits equal total credits. The accounting equation is in balance.
                        </p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            Trial Balance is Out of Balance
                        </h3>
                        <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                            Total debits (${{ number_format($totalDebit, 2) }}) do not equal total credits (${{ number_format($totalCredit, 2) }}). 
                            Difference: ${{ number_format(abs($totalDebit - $totalCredit), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Summary Statistics -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-building text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Accounts</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $balances->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-chart-line text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Assets</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($balances->where('account.account_type', 'asset')->sum('debit'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-chart-bar text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Liabilities</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($balances->where('account.account_type', 'liability')->sum('credit'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fa fa-balance-scale text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Equity</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            ${{ number_format($balances->where('account.account_type', 'equity')->sum('credit'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12px;
    }
    
    .print-break {
        page-break-before: always;
    }
    
    .shadow-sm {
        box-shadow: none !important;
    }
    
    .border {
        border: 1px solid #000 !important;
    }
}
</style>
@endsection
