<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa-solid fa-layer-group text-indigo-600 mr-3"></i> Nurse Departments
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage nursing departments and assignments</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($departments ?? [] as $dept)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                                <i class="fa-solid fa-building text-indigo-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $dept->name ?? 'Department' }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $dept->nurses_count ?? 0 }} nurses assigned</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ Str::limit($dept->description ?? 'No description', 100) }}</p>
                        <div class="flex gap-2">
                            <a href="#" class="flex-1 px-3 py-2 bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-center rounded-lg text-sm font-medium hover:bg-indigo-200 transition">
                                <i class="fa-solid fa-eye mr-1"></i> View
                            </a>
                            <a href="#" class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-center rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                <i class="fa-solid fa-edit mr-1"></i> Edit
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-12 text-center">
                        <i class="fa-solid fa-layer-group text-6xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 dark:text-white">No nurse departments configured</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Set up departments to organize your nursing staff</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
