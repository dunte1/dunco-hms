<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Account Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('hms.finance.accounts.ledger', $account) }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                    View Ledger
                </a>
                <a href="{{ route('hms.finance.accounts.edit', $account) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                    Edit
                </a>
                <a href="{{ route('hms.finance.accounts.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Account Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Account Code</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $account->account_code }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Account Name</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $account->account_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Account Type</label>
                            <span class="mt-1 inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                @if($account->account_type == 'asset') bg-blue-100 text-blue-800
                                @elseif($account->account_type == 'liability') bg-red-100 text-red-800
                                @elseif($account->account_type == 'equity') bg-purple-100 text-purple-800
                                @elseif($account->account_type == 'revenue') bg-green-100 text-green-800
                                @else bg-orange-100 text-orange-800
                                @endif">
                                {{ ucfirst($account->account_type) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Category</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $account->account_category)) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Current Balance</label>
                            <p class="mt-1 text-2xl font-bold 
                                @if($account->balance_type == 'debit') text-blue-600 
                                @else text-green-600 
                                @endif">
                                KES {{ number_format($account->current_balance, 2) }}
                            </p>
                            <p class="text-sm text-gray-500">({{ ucfirst($account->balance_type) }} Balance)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Opening Balance</label>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">
                                KES {{ number_format($account->opening_balance, 2) }}
                            </p>
                        </div>

                        @if($account->parentAccount)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Parent Account</label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                {{ $account->parentAccount->account_code }} - {{ $account->parentAccount->account_name }}
                            </p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Currency</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $account->currency ?? 'KES' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="mt-1 inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $account->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $account->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">System Account</label>
                            <span class="mt-1 inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $account->is_system_account ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $account->is_system_account ? 'Yes' : 'No' }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Manual Entry Allowed</label>
                            <span class="mt-1 inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $account->allow_manual_entry ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $account->allow_manual_entry ? 'Yes' : 'No' }}
                            </span>
                        </div>

                        @if($account->description)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $account->description }}</p>
                        </div>
                        @endif

                        @if($account->notes)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Notes</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $account->notes }}</p>
                        </div>
                        @endif

                        <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Created</label>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $account->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Child Accounts -->
            @if($account->childAccounts->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sub-Accounts</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Balance</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($account->childAccounts as $child)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $child->account_code }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                        <a href="{{ route('hms.finance.accounts.show', $child) }}" class="text-blue-600 hover:underline">
                                            {{ $child->account_name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ ucfirst($child->account_type) }}</td>
                                    <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-white">
                                        KES {{ number_format($child->current_balance, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $child->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $child->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.finance.accounts.ledger', $account) }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md">
                            View Ledger
                        </a>
                        
                        <a href="{{ route('hms.finance.accounts.edit', $account) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                            Edit Account
                        </a>
                        
                        @if(!$account->is_system_account && $account->childAccounts->count() == 0)
                        <form action="{{ route('hms.finance.accounts.destroy', $account) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this account?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                Delete Account
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

