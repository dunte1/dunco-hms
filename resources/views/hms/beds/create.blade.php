<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.beds.index') }}" class="hover:text-blue-600">Bed Management</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Add New Bed</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-plus text-blue-600 mr-3"></i>
                    Add New Bed
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Register a new hospital bed</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.beds.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Bed Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bed Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Bed Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="bed_number" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., B-101, ICU-05">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Ward/Room Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="ward_name" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., General Ward A, ICU-1, Private Room 205">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Bed Type <span class="text-red-500">*</span>
                                </label>
                                <select name="bed_type_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Bed Type</option>
                                    @foreach($bedTypes as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Don't see the bed type you need? 
                                    <a href="{{ route('hms.bed-types.index') }}" class="text-blue-600 hover:underline">Manage Bed Types</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Guide -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            <i class="fa fa-info-circle text-blue-600 mr-2"></i> Quick Guide
                        </h4>
                        <ul class="text-xs text-gray-700 dark:text-gray-300 space-y-1 ml-6 list-disc">
                            <li>Use a unique bed number for easy identification</li>
                            <li>Group beds by ward or floor for better organization</li>
                            <li>Select the appropriate bed type to set daily charges automatically</li>
                            <li>Once created, the bed will be available for patient assignments</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-save mr-2"></i> Add Bed
                        </button>
                        <a href="{{ route('hms.beds.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
