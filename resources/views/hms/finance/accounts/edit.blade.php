<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Account') }}
            </h2>
            <a href="{{ route('hms.finance.accounts.show', $account) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Details
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('hms.finance.accounts.update', $account) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Details</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Code</label>
                                <input type="text" value="{{ $account->account_code }}" disabled
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Name *</label>
                                <input type="text" name="account_name" value="{{ old('account_name', $account->account_name) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('account_name') border-red-500 @enderror">
                                @error('account_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Type *</label>
                                <select name="account_type" required {{ $account->is_system_account ? 'disabled' : '' }}
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('account_type') border-red-500 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="asset" {{ old('account_type', $account->account_type) == 'asset' ? 'selected' : '' }}>Asset</option>
                                    <option value="liability" {{ old('account_type', $account->account_type) == 'liability' ? 'selected' : '' }}>Liability</option>
                                    <option value="equity" {{ old('account_type', $account->account_type) == 'equity' ? 'selected' : '' }}>Equity</option>
                                    <option value="revenue" {{ old('account_type', $account->account_type) == 'revenue' ? 'selected' : '' }}>Revenue</option>
                                    <option value="expense" {{ old('account_type', $account->account_type) == 'expense' ? 'selected' : '' }}>Expense</option>
                                </select>
                                @if($account->is_system_account)
                                    <input type="hidden" name="account_type" value="{{ $account->account_type }}">
                                    <p class="mt-1 text-xs text-gray-500">System account type cannot be changed</p>
                                @endif
                                @error('account_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                                <select name="account_category" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('account_category') border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    <option value="current_assets" {{ old('account_category', $account->account_category) == 'current_assets' ? 'selected' : '' }}>Current Assets</option>
                                    <option value="fixed_assets" {{ old('account_category', $account->account_category) == 'fixed_assets' ? 'selected' : '' }}>Fixed Assets</option>
                                    <option value="current_liabilities" {{ old('account_category', $account->account_category) == 'current_liabilities' ? 'selected' : '' }}>Current Liabilities</option>
                                    <option value="long_term_liabilities" {{ old('account_category', $account->account_category) == 'long_term_liabilities' ? 'selected' : '' }}>Long-term Liabilities</option>
                                    <option value="owners_equity" {{ old('account_category', $account->account_category) == 'owners_equity' ? 'selected' : '' }}>Owner's Equity</option>
                                    <option value="operating_revenue" {{ old('account_category', $account->account_category) == 'operating_revenue' ? 'selected' : '' }}>Operating Revenue</option>
                                    <option value="other_income" {{ old('account_category', $account->account_category) == 'other_income' ? 'selected' : '' }}>Other Income</option>
                                    <option value="operating_expenses" {{ old('account_category', $account->account_category) == 'operating_expenses' ? 'selected' : '' }}>Operating Expenses</option>
                                    <option value="other_expenses" {{ old('account_category', $account->account_category) == 'other_expenses' ? 'selected' : '' }}>Other Expenses</option>
                                </select>
                                @error('account_category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Parent Account</label>
                                <select name="parent_account_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">None (Top Level)</option>
                                    @foreach($parentAccounts as $parent)
                                        @if($parent->id != $account->id)
                                        <option value="{{ $parent->id }}" {{ old('parent_account_id', $account->parent_account_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->account_code }} - {{ $parent->account_name }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Balance Type *</label>
                                <select name="balance_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('balance_type') border-red-500 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="debit" {{ old('balance_type', $account->balance_type) == 'debit' ? 'selected' : '' }}>Debit</option>
                                    <option value="credit" {{ old('balance_type', $account->balance_type) == 'credit' ? 'selected' : '' }}>Credit</option>
                                </select>
                                @error('balance_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening Balance</label>
                                <input type="number" name="opening_balance" value="{{ old('opening_balance', $account->opening_balance) }}" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('opening_balance') border-red-500 @enderror">
                                @error('opening_balance')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Currency</label>
                                <input type="text" name="currency" value="{{ old('currency', $account->currency ?? 'KES') }}" maxlength="3"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('description', $account->description) }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="notes" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('notes', $account->notes) }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <div class="space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $account->is_active) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                    </label>
                                    
                                    @if(!$account->is_system_account)
                                    <label class="inline-flex items-center ml-6">
                                        <input type="checkbox" name="allow_manual_entry" value="1" {{ old('allow_manual_entry', $account->allow_manual_entry) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Allow Manual Entry</span>
                                    </label>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('hms.finance.accounts.show', $account) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                Update Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

