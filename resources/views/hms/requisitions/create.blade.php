<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.requisitions.index') }}" class="hover:text-blue-600">Requisitions</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>New Requisition</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-plus text-purple-600 mr-3"></i>Create Requisition</h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                <form method="POST" action="{{ route('hms.requisitions.store') }}" class="p-6 space-y-6" id="requisitionForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Requesting Store <span class="text-red-500">*</span></label>
                            <select name="requesting_store_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('requesting_store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }} ({{ $store->code }})</option>
                                @endforeach
                            </select>
                            @error('requesting_store_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason <span class="text-red-500">*</span></label>
                            <input type="text" name="reason" value="{{ old('reason') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500" placeholder="Reason for requisition...">
                            @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                        <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Items -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Items <span class="text-red-500">*</span></label>
                            <button type="button" onclick="addItem()" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg"><i class="fa fa-plus mr-1"></i> Add Item</button>
                        </div>
                        <div id="itemsContainer" class="space-y-3">
                            <div class="item-row flex gap-3 items-end p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Medicine *</label>
                                    <select name="items[0][medicine_id]" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                        <option value="">Select Medicine</option>
                                        @foreach($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-32">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Quantity *</label>
                                    <input type="number" name="items[0][quantity_requested]" required min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm" placeholder="Qty">
                                </div>
                                <button type="button" onclick="removeItem(this)" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        @error('items') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i> Submit Requisition</button>
                        <a href="{{ route('hms.requisitions.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg"><i class="fa fa-times mr-2"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = 1;
        function addItem() {
            const container = document.getElementById('itemsContainer');
            const medicines = @json($medicines);
            let options = '<option value="">Select Medicine</option>';
            medicines.forEach(m => {
                options += `<option value="${m.id}">${m.name}</option>`;
            });
            const row = document.createElement('div');
            row.className = 'item-row flex gap-3 items-end p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg';
            row.innerHTML = `
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Medicine *</label>
                    <select name="items[${itemIndex}][medicine_id]" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">${options}</select>
                </div>
                <div class="w-32">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Quantity *</label>
                    <input type="number" name="items[${itemIndex}][quantity_requested]" required min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm" placeholder="Qty">
                </div>
                <button type="button" onclick="removeItem(this)" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm"><i class="fa fa-trash"></i></button>
            `;
            container.appendChild(row);
            itemIndex++;
        }
        function removeItem(btn) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                btn.closest('.item-row').remove();
            }
        }
    </script>
</x-app-layout>
