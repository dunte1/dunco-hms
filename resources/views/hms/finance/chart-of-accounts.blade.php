@extends('admin.layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-sitemap text-indigo-600 mr-3"></i>
                        Chart of Accounts
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Hierarchical view of all accounts in the system</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="window.print()" 
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition flex items-center">
                        <i class="fa fa-print mr-2"></i>
                        Print Chart
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fa fa-building text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Assets</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $accounts->where('account_type', 'asset')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                <i class="fa fa-chart-bar text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Liabilities</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $accounts->where('account_type', 'liability')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fa fa-balance-scale text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Equity</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $accounts->where('account_type', 'equity')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa fa-chart-line text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $accounts->where('account_type', 'revenue')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <i class="fa fa-chart-pie text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Expenses</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $accounts->where('account_type', 'expense')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart of Accounts -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Account Hierarchy</h3>
            </div>
            
            <div class="p-6">
                @foreach($accounts as $parentAccount)
                <div class="mb-8">
                    <!-- Parent Account -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg mb-3">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                    {{ $parentAccount->account_type === 'asset' ? 'bg-blue-500' : 
                                       ($parentAccount->account_type === 'liability' ? 'bg-red-500' : 
                                        ($parentAccount->account_type === 'equity' ? 'bg-purple-500' : 
                                         ($parentAccount->account_type === 'revenue' ? 'bg-green-500' : 
                                          'bg-yellow-500'))) }}">
                                    <i class="fa fa-folder text-white text-sm"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $parentAccount->account_code }} - {{ $parentAccount->account_name }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ ucfirst($parentAccount->account_type) }} Account
                                    @if($parentAccount->description)
                                        • {{ $parentAccount->description }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                ${{ number_format($parentAccount->current_balance, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $parentAccount->childAccounts->count() }} sub-accounts
                            </p>
                        </div>
                    </div>
                    
                    <!-- Child Accounts -->
                    @if($parentAccount->childAccounts->count() > 0)
                    <div class="ml-8 space-y-2">
                        @foreach($parentAccount->childAccounts as $childAccount)
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 rounded flex items-center justify-center
                                        {{ $childAccount->account_type === 'asset' ? 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-100' : 
                                           ($childAccount->account_type === 'liability' ? 'bg-red-100 text-red-600 dark:bg-red-800 dark:text-red-100' : 
                                            ($childAccount->account_type === 'equity' ? 'bg-purple-100 text-purple-600 dark:bg-purple-800 dark:text-purple-100' : 
                                             ($childAccount->account_type === 'revenue' ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-100' : 
                                              'bg-yellow-100 text-yellow-600 dark:bg-yellow-800 dark:text-yellow-100'))) }}">
                                        <i class="fa fa-file text-xs"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $childAccount->account_code }} - {{ $childAccount->account_name }}
                                    </p>
                                    @if($childAccount->description)
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $childAccount->description }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${{ number_format($childAccount->current_balance, 2) }}
                                </p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $childAccount->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 
                                       'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100' }}">
                                    {{ $childAccount->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="ml-8 p-3 text-center text-gray-500 dark:text-gray-400 text-sm">
                        No sub-accounts
                    </div>
                    @endif
                </div>
                @endforeach
                
                @if($accounts->count() === 0)
                <div class="text-center py-12">
                    <i class="fa fa-sitemap text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-lg font-medium text-gray-700 dark:text-gray-300">No accounts found</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Create accounts to see them in the chart</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Account Type Legend -->
        <div class="mt-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Account Type Legend</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 bg-blue-500 rounded"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Assets</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Liabilities</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 bg-purple-500 rounded"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Equity</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Revenue</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Expenses</span>
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
    
    .bg-gray-50 {
        background-color: #f9fafb !important;
    }
}
</style>
@endsection
