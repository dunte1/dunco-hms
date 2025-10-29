<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Account') }}
            </h2>
            <a href="{{ route('hms.finance.accounts.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Accounts
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('hms.finance.accounts.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Details</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Code *</label>
                                <input type="text" name="account_code" value="{{ old('account_code') }}" required
                                       placeholder="e.g., 1000"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('account_code') border-red-500 @enderror">
                                @error('account_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Name *</label>
                                <input type="text" name="account_name" value="{{ old('account_name') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('account_name') border-red-500 @enderror">
                                @error('account_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Type *</label>
                                <select name="account_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('account_type') border-red-500 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="asset" {{ old('account_type') == 'asset' ? 'selected' : '' }}>Asset</option>
                                    <option value="liability" {{ old('account_type') == 'liability' ? 'selected' : '' }}>Liability</option>
                                    <option value="equity" {{ old('account_type') == 'equity' ? 'selected' : '' }}>Equity</option>
                                    <option value="revenue" {{ old('account_type') == 'revenue' ? 'selected' : '' }}>Revenue</option>
                                    <option value="expense" {{ old('account_type') == 'expense' ? 'selected' : '' }}>Expense</option>
                                </select>
                                @error('account_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Category</label>
                                <select name="account_category"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">None</option>
                                    <optgroup label="Assets">
                                        <option value="current_asset">Current Asset</option>
                                        <option value="fixed_asset">Fixed Asset</option>
                                    </optgroup>
                                    <optgroup label="Liabilities">
                                        <option value="current_liability">Current Liability</option>
                                        <option value="long_term_liability">Long-term Liability</option>
                                    </optgroup>
                                    <optgroup label="Equity">
                                        <option value="equity">Equity</option>
                                    </optgroup>
                                    <optgroup label="Revenue">
                                        <option value="operating_revenue">Operating Revenue</option>
                                        <option value="other_revenue">Other Revenue</option>
                                    </optgroup>
                                    <optgroup label="Expenses">
                                        <option value="operating_expense">Operating Expense</option>
                                        <option value="other_expense">Other Expense</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Parent Account</label>
                                <select name="parent_account_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">None (Top-level account)</option>
                                    @foreach($parentAccounts as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_account_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->account_code }} - {{ $parent->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Balance Type *</label>
                                <select name="balance_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('balance_type') border-red-500 @enderror">
                                    <option value="debit" {{ old('balance_type', 'debit') == 'debit' ? 'selected' : '' }}>Debit</option>
                                    <option value="credit" {{ old('balance_type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                </select>
                                @error('balance_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Normal balance side for this account</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening Balance</label>
                                <input type="number" name="opening_balance" value="{{ old('opening_balance', 0) }}" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                                <input type="text" name="currency" value="{{ old('currency', 'KES') }}" maxlength="3"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="notes" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Options -->
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Options</h3>
                            </div>

                            <div class="md:col-span-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="allow_manual_entry" value="1" {{ old('allow_manual_entry', true) ? 'checked' : '' }}
                                           class="rounded border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow manual journal entries</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('hms.finance.accounts.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

