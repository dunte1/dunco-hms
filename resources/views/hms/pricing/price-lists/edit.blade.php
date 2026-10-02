<x-app-layout>
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Edit Price List</h1>
            <form method="POST" action="{{ route('hms.pricing.price-lists.update', $priceList) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $priceList->name) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency</label>
                        <input type="text" name="currency" value="{{ old('currency', $priceList->currency) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Effective From</label>
                        <input type="date" name="effective_from" value="{{ old('effective_from', $priceList->effective_from?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Effective To</label>
                        <input type="date" name="effective_to" value="{{ old('effective_to', $priceList->effective_to?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_default" value="1" {{ old('is_default', $priceList->is_default) ? 'checked' : '' }} class="rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Default</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $priceList->is_active) ? 'checked' : '' }} class="rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">{{ old('description', $priceList->description) }}</textarea>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Price Items</h3>
                    <div id="items-container" class="space-y-3">
                        @forelse($priceList->items as $index => $item)
                        <div class="item-row grid grid-cols-1 md:grid-cols-3 gap-3">
                            <select name="items[{{ $index }}][service_id]" required class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                                <option value="">Select Service</option>
                                @foreach($services as $s)
                                    <option value="{{ $s->id }}" {{ $item->service_id == $s->id ? 'selected' : '' }}>{{ $s->code }} - {{ $s->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="items[{{ $index }}][price]" value="{{ $item->price }}" step="0.01" min="0" required class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                            <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">Remove</button>
                        </div>
                        @empty
                        <p class="text-gray-500 text-sm">No items yet.</p>
                        @endforelse
                    </div>
                    <button type="button" onclick="addItem()" class="mt-3 px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200">
                        <i class="fa fa-plus mr-2"></i>Add Item
                    </button>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('hms.pricing.price-lists.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Update Price List</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemIndex = {{ $priceList->items->count() }};
        function addItem() {
            const container = document.getElementById('items-container');
            let options = '<option value="">Select Service</option>';
            @foreach($services as $s)
                options += '<option value="{{ $s->id }}">{{ $s->code }} - {{ $s->name }} ({{ $s->category }})</option>';
            @endforeach
            const html = '<div class="item-row grid grid-cols-1 md:grid-cols-3 gap-3">' +
                '<select name="items[' + itemIndex + '][service_id]" required class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">' + options + '</select>' +
                '<input type="number" name="items[' + itemIndex + '][price]" step="0.01" min="0" required placeholder="Price" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">' +
                '<button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">Remove</button>' +
                '</div>';
            container.insertAdjacentHTML('beforeend', html);
            itemIndex++;
        }
    </script>
</x-app-layout>
