<x-app-layout>
    <div class="py-6" x-data="categoryManager()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-tags text-indigo-600 mr-3"></i>
                    Inventory Categories
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Organize inventory items by category</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Stats Cards -->
            @isset($stats)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Total Categories</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['total_categories'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <i class="fa fa-tags text-3xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Medicines</p>
                            <p class="text-3xl font-bold mt-1">{{ $stats['total_medicines'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                            <i class="fa fa-pills text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            @endisset

            <!-- Categories List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Medicine Categories</h3>
                    <button @click="openAddModal" 
                            class="px-4 py-2 bg-white hover:bg-gray-100 text-indigo-600 rounded-lg text-sm font-semibold transition flex items-center">
                        <i class="fa fa-plus mr-2"></i> Add Category
                    </button>
                </div>
                
                <div class="p-6">
                    @if($categories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($categories as $category)
                                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 rounded-lg p-6 hover:shadow-lg transition">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="p-3 bg-indigo-500 rounded-lg">
                                            <i class="fa fa-box text-white text-2xl"></i>
                                        </div>
                                        <span class="px-3 py-1 bg-indigo-600 text-white rounded-full text-sm font-semibold">
                                            {{ $category->medicines_count }} items
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $category->name }}</h3>
                                    @if($category->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($category->description, 60) }}</p>
                                    @endif
                                    <div class="flex gap-2 mt-4">
                                        <a href="{{ route('hms.pharmacy.medicines.index') }}?category={{ $category->id }}" 
                                           class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm text-center transition">
                                            <i class="fa fa-eye mr-1"></i> View Items
                                        </a>
                                        <button @click="openEditModal({{ $category->id }}, '{{ $category->name }}', '{{ addslashes($category->description ?? '') }}')"
                                                class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button @click="deleteCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->medicines_count }})"
                                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fa fa-tags text-gray-300 dark:text-gray-600 text-6xl mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg">No categories found</p>
                            <button @click="openAddModal" 
                                    class="mt-4 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                                <i class="fa fa-plus mr-2"></i> Add Your First Category
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Add Category Modal -->
        <div x-show="showAddModal" 
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             @click.self="showAddModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4 rounded-t-xl">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fa fa-plus-circle mr-2"></i>
                        Add New Category
                    </h3>
                </div>
                <form method="POST" action="{{ route('hms.inventory.categories.store') }}">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                   placeholder="e.g., Antibiotics, Pain Relief">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea name="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                      placeholder="Brief description of the category..."></textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 rounded-b-xl flex justify-end gap-3">
                        <button type="button" @click="showAddModal = false"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                            <i class="fa fa-save mr-2"></i> Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Category Modal -->
        <div x-show="showEditModal" 
             x-cloak
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             @click.self="showEditModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 rounded-t-xl">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fa fa-edit mr-2"></i>
                        Edit Category
                    </h3>
                </div>
                <form method="POST" :action="`{{ url('hms/inventory/categories') }}/${editCategoryId}`">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" x-model="editCategoryName" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea name="description" x-model="editCategoryDescription" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 rounded-b-xl flex justify-end gap-3">
                        <button type="button" @click="showEditModal = false"
                                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <i class="fa fa-save mr-2"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function categoryManager() {
            return {
                showAddModal: false,
                showEditModal: false,
                editCategoryId: null,
                editCategoryName: '',
                editCategoryDescription: '',
                
                openAddModal() {
                    this.showAddModal = true;
                },
                
                openEditModal(id, name, description) {
                    this.editCategoryId = id;
                    this.editCategoryName = name;
                    this.editCategoryDescription = description;
                    this.showEditModal = true;
                },
                
                deleteCategory(id, name, itemCount) {
                    if (itemCount > 0) {
                        if (!confirm(`Category "${name}" has ${itemCount} medicine(s). Are you sure you want to delete it?\n\nThis may affect inventory records.`)) {
                            return;
                        }
                    } else {
                        if (!confirm(`Are you sure you want to delete category "${name}"?`)) {
                            return;
                        }
                    }
                    
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('hms/inventory/categories') }}/${id}`;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>
