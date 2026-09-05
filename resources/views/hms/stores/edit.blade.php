<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.stores.index') }}" class="hover:text-blue-600">Stores</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit {{ $store->name }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-indigo-600 mr-3"></i>Edit Store</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                <form method="POST" action="{{ route('hms.stores.update', $store) }}" class="p-6 space-y-6">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Store Name *</label><input type="text" name="name" value="{{ old('name', $store->name) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Store Code *</label><input type="text" name="code" value="{{ old('code', $store->code) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type *</label>
                            <select name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach(['pharmacy' => 'Pharmacy', 'main' => 'Main Store', 'satellite' => 'Satellite', 'warehouse' => 'Warehouse', 'emergency' => 'Emergency', 'ward' => 'Ward'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('type', $store->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                            <select name="status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="active" {{ old('status', $store->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $store->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Manager</label>
                            <select name="manager_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Manager</option>
                                @foreach($managers as $id => $name)
                                    <option value="{{ $id }}" {{ old('manager_id', $store->manager_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label><input type="text" name="phone" value="{{ old('phone', $store->phone) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label><input type="text" name="address" value="{{ old('address', $store->address) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    </div>
                    <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label><textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $store->description) }}</textarea></div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_main" value="1" {{ old('is_main', $store->is_main) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Main Store</label>
                    </div>
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.stores.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg"><i class="fa fa-times mr-2"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
