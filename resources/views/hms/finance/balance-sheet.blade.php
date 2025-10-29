<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Balance Sheet') }}
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
            
            <!-- Date Filter -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg print:hidden">
                <div class="p-6">
                    <form method="GET" class="flex items-end gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">As of Date</label>
                            <input type="date" name="as_of_date" value="{{ $asOfDate }}" 
                                   class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                            Generate
                        </button>
                    </form>
                </div>
            </div>

            <!-- Balance Sheet -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">DUNCOHMS Hospital</h1>
                        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mt-2">Balance Sheet</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            As of {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- ASSETS -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white bg-blue-100 dark:bg-blue-900 px-4 py-2">
                                ASSETS
                            </h3>
                            <table class="w-full mt-2">
                                <tbody>
                                    @foreach($assets as $asset)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 text-gray-700 dark:text-gray-300">
                                            {{ $asset->account_name }}
                                        </td>
                                        <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                            {{ number_format($asset->current_balance, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="border-t-2 border-gray-900 dark:border-gray-100 font-bold">
                                        <td class="py-3 px-4 text-gray-900 dark:text-white">TOTAL ASSETS</td>
                                        <td class="py-3 px-4 text-right text-blue-600 text-lg">
                                            {{ number_format($totalAssets, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- LIABILITIES & EQUITY -->
                        <div>
                            <!-- Liabilities -->
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white bg-red-100 dark:bg-red-900 px-4 py-2">
                                LIABILITIES
                            </h3>
                            <table class="w-full mt-2">
                                <tbody>
                                    @foreach($liabilities as $liability)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 text-gray-700 dark:text-gray-300">
                                            {{ $liability->account_name }}
                                        </td>
                                        <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                            {{ number_format($liability->current_balance, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="border-t-2 border-gray-900 dark:border-gray-100 font-bold">
                                        <td class="py-3 px-4 text-gray-900 dark:text-white">TOTAL LIABILITIES</td>
                                        <td class="py-3 px-4 text-right text-red-600 text-lg">
                                            {{ number_format($totalLiabilities, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Equity -->
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white bg-purple-100 dark:bg-purple-900 px-4 py-2 mt-6">
                                EQUITY
                            </h3>
                            <table class="w-full mt-2">
                                <tbody>
                                    @foreach($equity as $equityItem)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 text-gray-700 dark:text-gray-300">
                                            {{ $equityItem->account_name }}
                                        </td>
                                        <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                            {{ number_format($equityItem->current_balance, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 text-gray-700 dark:text-gray-300">
                                            Retained Earnings
                                        </td>
                                        <td class="py-2 px-4 text-right text-gray-900 dark:text-white">
                                            {{ number_format($retainedEarnings, 2) }}
                                        </td>
                                    </tr>
                                    <tr class="border-t-2 border-gray-900 dark:border-gray-100 font-bold">
                                        <td class="py-3 px-4 text-gray-900 dark:text-white">TOTAL EQUITY</td>
                                        <td class="py-3 px-4 text-right text-purple-600 text-lg">
                                            {{ number_format($totalEquity + $retainedEarnings, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Total Liabilities + Equity -->
                            <table class="w-full mt-4">
                                <tbody>
                                    <tr class="border-t-4 border-gray-900 dark:border-gray-100 font-bold text-xl">
                                        <td class="py-3 px-4 text-gray-900 dark:text-white">
                                            TOTAL LIABILITIES + EQUITY
                                        </td>
                                        <td class="py-3 px-4 text-right text-green-600">
                                            {{ number_format($totalLiabilities + $totalEquity + $retainedEarnings, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Balance Check -->
                    <div class="mt-8 p-4 {{ abs($totalAssets - ($totalLiabilities + $totalEquity + $retainedEarnings)) < 0.01 ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold {{ abs($totalAssets - ($totalLiabilities + $totalEquity + $retainedEarnings)) < 0.01 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                    Balance Check:
                                </p>
                            </div>
                            <div>
                                @if(abs($totalAssets - ($totalLiabilities + $totalEquity + $retainedEarnings)) < 0.01)
                                    <span class="text-green-800 dark:text-green-200 font-semibold">✓ Balanced</span>
                                @else
                                    <span class="text-red-800 dark:text-red-200 font-semibold">
                                        ✗ Difference: {{ number_format(abs($totalAssets - ($totalLiabilities + $totalEquity + $retainedEarnings)), 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>
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

