<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Record Stock Movement') }}
            </h2>
            <a href="{{ route('hms.inventory.stock-movements.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Movements
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('hms.inventory.stock-movements.store') }}" id="stockMovementForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Movement Information</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Movement Number *</label>
                                <input type="text" name="movement_number" value="{{ old('movement_number', $movementNumber) }}" required readonly
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 bg-gray-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Movement Date *</label>
                                <input type="date" name="movement_date" value="{{ old('movement_date', date('Y-m-d')) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('movement_date') border-red-500 @enderror">
                                @error('movement_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medicine *</label>
                                <select name="medicine_id" required id="medicineSelect"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('medicine_id') border-red-500 @enderror">
                                    <option value="">Select Medicine</option>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->id }}" data-stock="{{ $medicine->stock_quantity }}" {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}>
                                            {{ $medicine->name }} (Stock: {{ $medicine->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('medicine_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p id="currentStock" class="mt-1 text-sm text-gray-600 dark:text-gray-400"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Movement Type *</label>
                                <select name="movement_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('movement_type') border-red-500 @enderror">
                                    <option value="">Select Type</option>
                                    <option value="purchase" {{ old('movement_type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                    <option value="sale" {{ old('movement_type') == 'sale' ? 'selected' : '' }}>Sale</option>
                                    <option value="adjustment" {{ old('movement_type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                                    <option value="transfer" {{ old('movement_type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                    <option value="return" {{ old('movement_type') == 'return' ? 'selected' : '' }}>Return</option>
                                    <option value="damage" {{ old('movement_type') == 'damage' ? 'selected' : '' }}>Damage</option>
                                    <option value="expiry" {{ old('movement_type') == 'expiry' ? 'selected' : '' }}>Expiry</option>
                                </select>
                                @error('movement_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Direction *</label>
                                <select name="direction" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('direction') border-red-500 @enderror">
                                    <option value="">Select Direction</option>
                                    <option value="in" {{ old('direction') == 'in' ? 'selected' : '' }}>Stock IN</option>
                                    <option value="out" {{ old('direction') == 'out' ? 'selected' : '' }}>Stock OUT</option>
                                </select>
                                @error('direction')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                                <input type="number" name="quantity" value="{{ old('quantity') }}" required min="1"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 @error('quantity') border-red-500 @enderror">
                                @error('quantity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Cost (KES)</label>
                                <input type="number" name="unit_cost" value="{{ old('unit_cost') }}" min="0" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <!-- Optional Fields -->
                            <div class="md:col-span-2 mt-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Additional Information (Optional)</h3>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purchase Order</label>
                                <select name="purchase_order_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">None</option>
                                    @foreach($purchaseOrders as $po)
                                        <option value="{{ $po->id }}" {{ old('purchase_order_id') == $po->id ? 'selected' : '' }}>
                                            {{ $po->po_number }} - {{ $po->supplier->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batch Number</label>
                                <input type="text" name="batch_number" value="{{ old('batch_number') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiry Date</label>
                                <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" min="{{ date('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Location</label>
                                <input type="text" name="from_location" value="{{ old('from_location') }}"
                                       placeholder="e.g., Main Store"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">To Location</label>
                                <input type="text" name="to_location" value="{{ old('to_location') }}"
                                       placeholder="e.g., Pharmacy"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason</label>
                                <textarea name="reason" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('reason') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="notes" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('hms.inventory.stock-movements.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                                Record Movement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('medicineSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
            const stockDisplay = document.getElementById('currentStock');
            
            if (stock) {
                stockDisplay.textContent = `Current stock: ${stock} units`;
            } else {
                stockDisplay.textContent = '';
            }
        });
    </script>
</x-app-layout>

