<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add New Supplier') }}
            </h2>
            <a href="{{ route('hms.inventory.suppliers.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Suppliers
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('hms.inventory.suppliers.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier Code *</label>
                                <input type="text" name="supplier_code" value="{{ old('supplier_code') }}" required
                                       placeholder="e.g., SUP-001"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('supplier_code') border-red-500 @enderror">
                                @error('supplier_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Company Name</label>
                                <input type="text" name="company_name" value="{{ old('company_name') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Person</label>
                                <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Supplier Type *</label>
                                <select name="supplier_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('supplier_type') border-red-500 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="medicines" {{ old('supplier_type') == 'medicines' ? 'selected' : '' }}>Medicines</option>
                                    <option value="equipment" {{ old('supplier_type') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                    <option value="consumables" {{ old('supplier_type') == 'consumables' ? 'selected' : '' }}>Consumables</option>
                                    <option value="food" {{ old('supplier_type') == 'food' ? 'selected' : '' }}>Food</option>
                                    <option value="general" {{ old('supplier_type') == 'general' ? 'selected' : '' }}>General</option>
                                </select>
                                @error('supplier_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Terms *</label>
                                <select name="payment_terms" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('payment_terms') border-red-500 @enderror">
                                    <option value="">Select Payment Terms</option>
                                    <option value="cash" {{ old('payment_terms') == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="credit_7" {{ old('payment_terms') == 'credit_7' ? 'selected' : '' }}>Credit 7 Days</option>
                                    <option value="credit_15" {{ old('payment_terms') == 'credit_15' ? 'selected' : '' }}>Credit 15 Days</option>
                                    <option value="credit_30" {{ old('payment_terms') == 'credit_30' ? 'selected' : '' }}>Credit 30 Days</option>
                                    <option value="credit_60" {{ old('payment_terms') == 'credit_60' ? 'selected' : '' }}>Credit 60 Days</option>
                                    <option value="credit_90" {{ old('payment_terms') == 'credit_90' ? 'selected' : '' }}>Credit 90 Days</option>
                                </select>
                                @error('payment_terms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Information -->
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone *</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile</label>
                                <input type="text" name="mobile" value="{{ old('mobile') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax Number</label>
                                <input type="text" name="tax_number" value="{{ old('tax_number') }}"
                                       placeholder="e.g., VAT/PIN Number"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <!-- Address Information -->
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Address Information</h3>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address *</label>
                                <textarea name="address" rows="3" required
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                <input type="text" name="city" value="{{ old('city') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State/Province</label>
                                <input type="text" name="state" value="{{ old('state') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label>
                                <input type="text" name="country" value="{{ old('country', 'Kenya') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal Code</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <!-- Financial Information -->
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Information</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Credit Limit (KES)</label>
                                <input type="number" name="credit_limit" value="{{ old('credit_limit', 0) }}" min="0" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="notes" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Bank Details -->
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Bank Details (Optional)</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Number</label>
                                <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bank Branch</label>
                                <input type="text" name="bank_branch" value="{{ old('bank_branch') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('hms.inventory.suppliers.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                Create Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

