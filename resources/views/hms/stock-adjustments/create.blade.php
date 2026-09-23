<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.stock-adjustments.index') }}" class="hover:text-blue-600">Stock Adjustments</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>New Adjustment</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-plus text-orange-600 mr-3"></i>Create Stock Adjustment</h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2"></div>
                <form method="POST" action="{{ route('hms.stock-adjustments.store') }}" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Store <span class="text-red-500">*</span></label>
                            <select name="store_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }} ({{ $store->code }})</option>
                                @endforeach
                            </select>
                            @error('store_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medicine <span class="text-red-500">*</span></label>
                            <select name="medicine_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500">
                                <option value="">Select Medicine</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}" {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}>{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                            @error('medicine_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity Adjustment <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity_adjustment" value="{{ old('quantity_adjustment') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500" placeholder="+10 or -5">
                            <p class="text-xs text-gray-500 mt-1">Positive to add, negative to deduct</p>
                            @error('quantity_adjustment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adjustment Type <span class="text-red-500">*</span></label>
                            <select name="adjustment_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500">
                                <option value="">Select Type</option>
                                <option value="addition" {{ old('adjustment_type') === 'addition' ? 'selected' : '' }}>Addition</option>
                                <option value="deduction" {{ old('adjustment_type') === 'deduction' ? 'selected' : '' }}>Deduction</option>
                                <option value="correction" {{ old('adjustment_type') === 'correction' ? 'selected' : '' }}>Correction</option>
                                <option value="damage" {{ old('adjustment_type') === 'damage' ? 'selected' : '' }}>Damage</option>
                                <option value="expiry" {{ old('adjustment_type') === 'expiry' ? 'selected' : '' }}>Expiry</option>
                            </select>
                            @error('adjustment_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason <span class="text-red-500">*</span></label>
                        <textarea name="reason" rows="3" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500" placeholder="Explain the reason for this adjustment...">{{ old('reason') }}</textarea>
                        @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fa fa-exclamation-triangle text-amber-600 mt-0.5 mr-3"></i>
                            <div class="text-sm text-amber-800 dark:text-amber-200">
                                <p class="font-semibold mb-1">Approval Required</p>
                                <p>This adjustment will be submitted for approval. An authorized user must approve it before stock levels are modified.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i> Submit Adjustment</button>
                        <a href="{{ route('hms.stock-adjustments.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg"><i class="fa fa-times mr-2"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
