<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Cash Flow Statement') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('hms.finance.reports') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                    All Reports
                </a>
                <button onclick="window.print()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
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
                            Generate
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cash Flow Statement -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\SystemSetting::get('hospital_name', config('app.name')) }}</h1>
                        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mt-2">Cash Flow Statement</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            Period: {{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}
                        </p>
                    </div>

                    <!-- Summary Section -->
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                            <p class="text-sm text-green-800 dark:text-green-200">Cash Inflows</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-300">
                                KES {{ number_format($cashInflows, 2) }}
                            </p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg">
                            <p class="text-sm text-red-800 dark:text-red-200">Cash Outflows</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-300">
                                KES {{ number_format($cashOutflows, 2) }}
                            </p>
                        </div>
                        <div class="bg-{{ $netCashFlow >= 0 ? 'blue' : 'orange' }}-100 dark:bg-{{ $netCashFlow >= 0 ? 'blue' : 'orange' }}-900 p-4 rounded-lg">
                            <p class="text-sm text-{{ $netCashFlow >= 0 ? 'blue' : 'orange' }}-800 dark:text-{{ $netCashFlow >= 0 ? 'blue' : 'orange' }}-200">Net Cash Flow</p>
                            <p class="text-2xl font-bold text-{{ $netCashFlow >= 0 ? 'blue' : 'orange' }}-600 dark:text-{{ $netCashFlow >= 0 ? 'blue' : 'orange' }}-300">
                                KES {{ number_format($netCashFlow, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Operating Activities -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-4 py-2">
                            CASH FROM OPERATING ACTIVITIES
                        </h3>
                        <table class="w-full mt-2">
                            <tbody>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">Cash Receipts from Customers</td>
                                    <td class="py-2 px-4 text-right text-green-600 font-semibold">
                                        {{ number_format($cashInflows, 2) }}
                                    </td>
                                </tr>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">Cash Payments to Suppliers/Employees</td>
                                    <td class="py-2 px-4 text-right text-red-600 font-semibold">
                                        ({{ number_format($cashOutflows, 2) }})
                                    </td>
                                </tr>
                                <tr class="border-t-2 border-gray-900 dark:border-gray-100 font-bold">
                                    <td class="py-3 px-4 text-gray-900 dark:text-white">Net Cash from Operating Activities</td>
                                    <td class="py-3 px-4 text-right text-{{ $netCashFlow >= 0 ? 'green' : 'red' }}-600 text-lg">
                                        {{ number_format($netCashFlow, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cash Inflows by Method -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white bg-green-100 dark:bg-green-900 px-4 py-2">
                            CASH INFLOWS BY PAYMENT METHOD
                        </h3>
                        <table class="w-full mt-2">
                            <tbody>
                                @forelse($inflowsByMethod as $method)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">
                                        {{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}
                                    </td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($method->total, 2) }}
                                    </td>
                                    <td class="py-2 px-4 text-right text-sm text-gray-600 dark:text-gray-400">
                                        {{ $cashInflows > 0 ? number_format(($method->total / $cashInflows) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-2 px-4 text-center text-gray-500">No cash inflows</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Cash Outflows by Method -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white bg-red-100 dark:bg-red-900 px-4 py-2">
                            CASH OUTFLOWS BY PAYMENT METHOD
                        </h3>
                        <table class="w-full mt-2">
                            <tbody>
                                @forelse($outflowsByMethod as $method)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">
                                        {{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}
                                    </td>
                                    <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                        KES {{ number_format($method->total, 2) }}
                                    </td>
                                    <td class="py-2 px-4 text-right text-sm text-gray-600 dark:text-gray-400">
                                        {{ $cashOutflows > 0 ? number_format(($method->total / $cashOutflows) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-2 px-4 text-center text-gray-500">No cash outflows</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Net Increase/Decrease in Cash -->
                    <div class="border-t-4 border-gray-900 dark:border-gray-100 pt-4">
                        <table class="w-full">
                            <tbody>
                                <tr class="font-bold text-xl">
                                    <td class="py-3 px-4 text-gray-900 dark:text-white">
                                        NET {{ $netCashFlow >= 0 ? 'INCREASE' : 'DECREASE' }} IN CASH
                                    </td>
                                    <td class="py-3 px-4 text-right text-{{ $netCashFlow >= 0 ? 'green' : 'red' }}-600">
                                        KES {{ number_format(abs($netCashFlow), 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cash Flow Ratio -->
                    <div class="mt-6 p-4 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-blue-800 dark:text-blue-200">Operating Cash Flow Ratio:</p>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Measures ability to cover current liabilities with cash from operations</p>
                            </div>
                            <div>
                                <span class="text-2xl font-bold text-blue-600 dark:text-blue-300">
                                    {{ $cashOutflows > 0 ? number_format(($cashInflows / $cashOutflows), 2) : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-12 pt-6 border-t border-gray-300 dark:border-gray-600 text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
                        <p class="mt-1 text-xs">Note: This statement includes only cash-based transactions (Cash, M-Pesa, Card)</p>
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

