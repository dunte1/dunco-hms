<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa-solid fa-layer-group text-indigo-600 mr-3"></i> Nurse Departments
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create, edit, and remove nursing departments</p>
                </div>
                <button onclick="document.getElementById('addDeptModal').classList.remove('hidden')"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                    <i class="fa fa-plus mr-2"></i> Add Department
                </button>
            </div>

            @if(session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($departments ?? [] as $dept)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                                <i class="fa-solid fa-building text-indigo-600 text-xl"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $dept->name }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $dept->nurses_count ?? 0 }} nurses assigned</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ \Illuminate\Support\Str::limit($dept->description ?? 'No description', 100) }}</p>
                        <div class="flex gap-2">
                            <a href="{{ route('hms.nurses.departments.edit', $dept) }}"
                               class="flex-1 px-3 py-2 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-center rounded-lg text-sm font-medium hover:bg-blue-200 transition">
                                <i class="fa-solid fa-edit mr-1"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('hms.nurses.departments.destroy', $dept) }}" class="flex-1"
                                  onsubmit="return confirm('Delete this nurse department?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-full px-3 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-center rounded-lg text-sm font-medium hover:bg-red-200 transition">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </button>
                            </form>
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

    <!-- Add Modal -->
    <div id="addDeptModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Nurse Department</h3>
                <button onclick="document.getElementById('addDeptModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('hms.nurses.departments.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                    <input name="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                    @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('addDeptModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
