<x-app-layout>
    <div class="p-6">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Edit Service</h1>
            <form method="POST" action="{{ route('hms.pricing.services.update', $service) }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 space-y-4">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service Name *</label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code</label>
                        <input type="text" name="code" value="{{ old('code', $service->code) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                        <select name="category" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                            @foreach(['consultation','lab','radiology','pharmacy','procedure','bed','meal','other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $service->category) == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Price (KES) *</label>
                        <input type="number" name="default_price" value="{{ old('default_price', $service->default_price) }}" step="0.01" min="0" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    </div>
                    <div class="flex items-center gap-4 pt-6">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_taxable" value="1" {{ old('is_taxable', $service->is_taxable) ? 'checked' : '' }} class="rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Taxable</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">{{ old('description', $service->description) }}</textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('hms.pricing.services.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Update Service</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
