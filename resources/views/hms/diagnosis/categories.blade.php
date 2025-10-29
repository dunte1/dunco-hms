<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-clipboard-list text-indigo-600 mr-3"></i>
                        Diagnosis Categories
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage diagnosis classification categories</p>
                </div>
                <a href="{{ route('hms.diagnosis.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa fa-plus mr-2"></i> Add Category
                </a>
            </div>

            <!-- Success Message -->
            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($categories as $category)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2"></div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                        {{ $category->name }}
                                    </h3>
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400 font-mono">
                                        {{ $category->code }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fa fa-clipboard-list text-xl"></i>
                                </div>
                            </div>

                            @if($category->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                    {{ $category->description }}
                                </p>
                            @endif

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fa fa-users mr-2 text-gray-400"></i>
                                    <span>{{ $category->patient_diagnoses_count }} diagnoses</span>
                                </div>
                                <div class="flex gap-2">
                                    <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                        <i class="fa fa-clipboard-list text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No diagnosis categories found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Add a new category to get started</p>
                        <a href="{{ route('hms.diagnosis.categories.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add First Category
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
                <div class="mt-6">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

