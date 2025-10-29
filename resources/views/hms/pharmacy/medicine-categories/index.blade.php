<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-tags text-emerald-600 mr-3"></i>
                    Medicine Categories
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage pharmaceutical categories</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Categories</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total_categories'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-layer-group text-2xl"></i>
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
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Medicine Categories</h3>
                    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="px-3 py-1 bg-white bg-opacity-30 hover:bg-opacity-50 rounded-lg text-sm">
                        <i class="fa fa-plus mr-1"></i> Add
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($categories as $category)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</h4>
                                    <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 rounded-full text-xs font-semibold">
                                        {{ $category->medicines_count }} items
                                    </span>
                                </div>
                                @if($category->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $category->description }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fa fa-tags text-4xl mb-2"></i>
                                <p>No categories yet</p>
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
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Add Medicine Category</h3>
                <form method="POST" action="{{ route('hms.pharmacy.medicine-categories.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category Name *</label>
                            <input type="text" name="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add Category
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

