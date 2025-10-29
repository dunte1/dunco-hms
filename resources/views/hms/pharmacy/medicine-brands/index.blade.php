<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-trademark text-blue-600 mr-3"></i>
                    Medicine Brands
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage pharmaceutical brands and manufacturers</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Brands</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total_brands'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-trademark text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Medicines</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total_medicines'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-pills text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Medicine Brands</h3>
                    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="px-3 py-1 bg-white bg-opacity-30 hover:bg-opacity-50 rounded-lg text-sm">
                        <i class="fa fa-plus mr-1"></i> Add Brand
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($brands as $brand)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                                            <i class="fa fa-trademark text-blue-600 dark:text-blue-400"></i>
                                        </div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $brand->name }}</h4>
                                    </div>
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-xs font-semibold">
                                        {{ $brand->medicines_count }} items
                                    </span>
                                </div>
                                @if($brand->manufacturer)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        <i class="fa fa-industry mr-1"></i> {{ $brand->manufacturer }}
                                    </p>
                                @endif
                                @if($brand->country)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <i class="fa fa-globe mr-1"></i> {{ $brand->country }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fa fa-trademark text-4xl mb-2"></i>
                                <p>No brands yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="addModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Add Medicine Brand</h3>
                <form method="POST" action="{{ route('hms.pharmacy.medicine-brands.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Brand Name *</label>
                            <input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Manufacturer</label>
                            <input type="text" name="manufacturer" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Country</label>
                            <input type="text" name="country" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                            <textarea name="description" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add Brand
                        </button>
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

