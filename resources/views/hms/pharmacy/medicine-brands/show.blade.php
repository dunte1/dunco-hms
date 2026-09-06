<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.pharmacy.medicine-brands.index') }}" class="hover:text-blue-600">Medicine Brands</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Details</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-tag text-cyan-600 mr-3"></i>Brand Details</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-2"></div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><h3 class="text-sm font-medium text-gray-500">Name</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $brand->name }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Medicines Count</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $brand->medicines_count ?? 0 }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Manufacturer</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $brand->manufacturer ?? 'N/A' }}</p></div>
                        <div><h3 class="text-sm font-medium text-gray-500">Country</h3><p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $brand->country ?? 'N/A' }}</p></div>
                    </div>
                    @if($brand->description)
                    <div><h3 class="text-sm font-medium text-gray-500">Description</h3><p class="mt-1 text-gray-900 dark:text-white">{{ $brand->description }}</p></div>
                    @endif
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.pharmacy.medicine-brands.edit', $brand) }}" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium"><i class="fa fa-edit mr-2"></i> Edit</a>
                        <a href="{{ route('hms.pharmacy.medicine-brands.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
