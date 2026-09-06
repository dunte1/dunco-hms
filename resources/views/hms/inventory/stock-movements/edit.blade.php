<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.inventory.stock-movements.index') }}" class="hover:text-blue-600">Stock Movements</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-emerald-600 mr-3"></i>Edit Stock Movement</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.inventory.stock-movements.update', $stockMovement) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medicine</label>
                            <select name="medicine_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach($medicines as $id => $name)
                                    <option value="{{ $id }}" {{ old('medicine_id', $stockMovement->medicine_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" value="{{ old('quantity', $stockMovement->quantity) }}" min="1" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit Cost</label>
                            <input type="number" name="unit_cost" value="{{ old('unit_cost', $stockMovement->unit_cost) }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $stockMovement->notes) }}</textarea>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.inventory.stock-movements.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
