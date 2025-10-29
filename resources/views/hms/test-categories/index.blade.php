<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-layer-group text-purple-600 mr-3"></i>
                    Test Categories
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage laboratory and radiology test categories</p>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Lab Categories</p>
                            <p class="text-3xl font-bold mt-2">{{ $labCategories->count() }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-flask text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Radiology Categories</p>
                            <p class="text-3xl font-bold mt-2">{{ $radiologyCategories->count() }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-x-ray text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Lab Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center justify-between">
                            <span><i class="fa fa-flask mr-2"></i> Laboratory Categories</span>
                            <button onclick="openAddModal('lab')" class="px-3 py-1 bg-white bg-opacity-30 hover:bg-opacity-50 rounded-lg text-sm">
                                <i class="fa fa-plus mr-1"></i> Add
                            </button>
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($labCategories as $category)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:shadow-md transition">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</h4>
                                        @if($category->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-semibold">
                                            {{ $category->lab_tests_count ?? 0 }} tests
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <i class="fa fa-flask text-4xl mb-2"></i>
                                    <p>No laboratory categories yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Radiology Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                        <h3 class="text-lg font-bold text-white flex items-center justify-between">
                            <span><i class="fa fa-x-ray mr-2"></i> Radiology Categories</span>
                            <button onclick="openAddModal('radiology')" class="px-3 py-1 bg-white bg-opacity-30 hover:bg-opacity-50 rounded-lg text-sm">
                                <i class="fa fa-plus mr-1"></i> Add
                            </button>
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($radiologyCategories as $category)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:shadow-md transition">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</h4>
                                        @if($category->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-sm font-semibold">
                                            {{ $category->radiology_tests_count ?? 0 }} tests
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <i class="fa fa-x-ray text-4xl mb-2"></i>
                                    <p>No radiology categories yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    Add Test Category
                </h3>
                <form method="POST" action="{{ route('hms.test-categories.store') }}">
                    @csrf
                    <input type="hidden" name="category_type" id="categoryType" value="">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Enter category name">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea name="description" rows="3"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Optional description"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add Category
                        </button>
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddModal(type) {
            document.getElementById('categoryType').value = type;
            document.getElementById('addModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
    </script>
</x-app-layout>

