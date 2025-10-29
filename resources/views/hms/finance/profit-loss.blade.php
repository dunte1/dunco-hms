<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profit & Loss Statement') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('hms.finance.reports') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                    <i class="fa fa-arrow-left mr-2"></i>
                    All Reports
                </a>
                <a href="{{ route('hms.finance.profit-loss.pdf') }}?from_date={{ $fromDate }}&to_date={{ $toDate }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                    <i class="fa fa-download mr-2"></i>
                    Download PDF
                </a>
                <button onclick="window.print()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                    <i class="fa fa-print mr-2"></i>
                    Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Date Range Filter -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg print:hidden">
                <div class="p-6">
                    <form method="GET" class="space-y-4">
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                                <input type="date" name="from_date" value="{{ $fromDate }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                                <input type="date" name="to_date" value="{{ $toDate }}" 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition flex items-center">
                                <i class="fa fa-sync mr-2"></i>
                                Generate Report
                            </button>
                        </div>
                        
                        @if(request()->has('from_date') || request()->has('to_date'))
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fa fa-check-circle text-green-600 dark:text-green-400 mr-3"></i>
                                <div>
                                    <p class="text-sm font-semibold text-green-800 dark:text-green-200">Report Generated Successfully</p>
                                    <p class="text-xs text-green-700 dark:text-green-300 mt-1">
                                        Showing data from <strong>{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</strong> 
                                        to <strong>{{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- P&L Statement -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">DUNCOHMS Hospital</h1>
                        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mt-2">Profit & Loss Statement</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Period: {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}
                        </p>
                    </div>

                    <!-- Revenue Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-4 py-2">
                            REVENUE
                        </h3>
                        <table class="w-full mt-2">
                            <tbody>
                                @if($revenue['patient_services'] > 0)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">Patient Services</td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($revenue['patient_services'], 2) }}
                                    </td>
                                </tr>
                                @endif
                                @if($revenue['pharmacy_sales'] > 0)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">Pharmacy Sales</td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($revenue['pharmacy_sales'], 2) }}
                                    </td>
                                </tr>
                                @endif
                                @if($revenue['lab_tests'] > 0)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">Laboratory Tests</td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($revenue['lab_tests'], 2) }}
                                    </td>
                                </tr>
                                @endif
                                @if($revenue['radiology'] > 0)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">Radiology Services</td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($revenue['radiology'], 2) }}
                                    </td>
                                </tr>
                                @endif
                                @if($revenue['consultation_fees'] > 0)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">Consultation Fees</td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($revenue['consultation_fees'], 2) }}
                                    </td>
                                </tr>
                                @endif
                                @if($revenue['other'] > 0)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">Other Income</td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($revenue['other'], 2) }}
                                    </td>
                                </tr>
                                @endif
                                <tr class="border-t-2 border-gray-900 dark:border-gray-100 font-bold">
                                    <td class="py-3 px-4 text-gray-900 dark:text-white">TOTAL REVENUE</td>
                                    <td class="py-3 px-4 text-right text-green-600 text-lg">
                                        KES {{ number_format($totalRevenue, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Expenses Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-4 py-2">
                            EXPENSES
                        </h3>
                        <table class="w-full mt-2">
                            <tbody>
                                @forelse($expenses as $expense)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">
                                        {{ $expense->category->name ?? 'N/A' }}
                                    </td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($expense->total, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="py-2 px-4 text-center text-gray-500">No expenses</td>
                                </tr>
                                @endforelse
                                <tr class="border-t-2 border-gray-900 dark:border-gray-100 font-bold">
                                    <td class="py-3 px-4 text-gray-900 dark:text-white">TOTAL EXPENSES</td>
                                    <td class="py-3 px-4 text-right text-red-600 text-lg">
                                        KES {{ number_format($totalExpenses, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Net Profit Section -->
                    <div class="border-t-4 border-gray-900 dark:border-gray-100 pt-4">
                        <table class="w-full">
                            <tbody>
                                <tr class="font-bold text-xl">
                                    <td class="py-3 px-4 text-gray-900 dark:text-white">
                                        NET PROFIT {{ $grossProfit < 0 ? '(LOSS)' : '' }}
                                    </td>
                                    <td class="py-3 px-4 text-right {{ $grossProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        KES {{ number_format($grossProfit, 2) }}
                                    </td>
                                </tr>
                                <tr class="text-sm">
                                    <td class="py-2 px-4 text-gray-600 dark:text-gray-400">Profit Margin</td>
                                    <td class="py-2 px-4 text-right {{ $profitMargin >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ number_format($profitMargin, 2) }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer -->
                    <div class="mt-12 pt-6 border-t border-gray-300 dark:border-gray-600 text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .print\\:hidden {
                display: none !important;
            }
            body {
                background: white;
                color: black;
            }
        }
    </style>
</x-app-layout>

