<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.pharmacy.medicine-brands.index') }}" class="hover:text-blue-600">Medicine Brands</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-cyan-600 mr-3"></i>Edit Brand</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.pharmacy.medicine-brands.update', $brand) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Brand Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $brand->name) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Manufacturer</label>
                        <input type="text" name="manufacturer" value="{{ old('manufacturer', $brand->manufacturer) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Country</label>
                        <input type="text" name="country" value="{{ old('country', $brand->country) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $brand->description) }}</textarea>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.pharmacy.medicine-brands.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
