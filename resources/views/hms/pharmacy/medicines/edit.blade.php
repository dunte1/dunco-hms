<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.pharmacy.medicines.index') }}" class="hover:text-green-600">Medicines</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <a href="{{ route('hms.pharmacy.medicines.show', $medicine) }}" class="hover:text-green-600">{{ $medicine->name }}</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-edit text-green-600 mr-3"></i>
                            Edit Medicine
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update medicine information and stock details</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.pharmacy.medicines.show', $medicine) }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('hms.pharmacy.medicines.index') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.pharmacy.medicines.update', $medicine) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-info-circle text-green-600 mr-2"></i>
                            Basic Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medicine Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $medicine->name) }}" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="generic_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Generic Name</label>
                                <input type="text" name="generic_name" id="generic_name" value="{{ old('generic_name', $medicine->generic_name) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                @error('generic_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category *</label>
                                <select name="category_id" id="category_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $medicine->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="manufacturer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manufacturer</label>
                                <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $medicine->manufacturer) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                @error('manufacturer')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dosage Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-pills text-blue-600 mr-2"></i>
                            Dosage Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="dosage_form" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage Form *</label>
                                <select name="dosage_form" id="dosage_form" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Form</option>
                                    <option value="tablet" {{ old('dosage_form', $medicine->dosage_form) == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="syrup" {{ old('dosage_form', $medicine->dosage_form) == 'syrup' ? 'selected' : '' }}>Syrup</option>
                                    <option value="injection" {{ old('dosage_form', $medicine->dosage_form) == 'injection' ? 'selected' : '' }}>Injection</option>
                                    <option value="capsule" {{ old('dosage_form', $medicine->dosage_form) == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                    <option value="cream" {{ old('dosage_form', $medicine->dosage_form) == 'cream' ? 'selected' : '' }}>Cream</option>
                                    <option value="drops" {{ old('dosage_form', $medicine->dosage_form) == 'drops' ? 'selected' : '' }}>Drops</option>
                                    <option value="ointment" {{ old('dosage_form', $medicine->dosage_form) == 'ointment' ? 'selected' : '' }}>Ointment</option>
                                    <option value="powder" {{ old('dosage_form', $medicine->dosage_form) == 'powder' ? 'selected' : '' }}>Powder</option>
                                </select>
                                @error('dosage_form')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="strength" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Strength</label>
                                <input type="text" name="strength" id="strength" value="{{ old('strength', $medicine->strength) }}" placeholder="e.g., 500mg"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                @error('strength')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <input type="number" name="unit_price" id="unit_price" value="{{ old('unit_price', $medicine->unit_price) }}" step="0.01" min="0" required
                                        class="mt-1 block w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                </div>
                                @error('unit_price')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Stock Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-warehouse text-purple-600 mr-2"></i>
                            Stock Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Stock *</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $medicine->stock_quantity) }}" min="0" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                @error('stock_quantity')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="minimum_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minimum Stock *</label>
                                <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', $medicine->minimum_stock) }}" min="0" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                @error('minimum_stock')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $medicine->expiry_date?->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                @error('expiry_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"
                            placeholder="Enter medicine description, usage instructions, or any additional notes...">{{ old('description', $medicine->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.pharmacy.medicines.show', $medicine) }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <i class="fa fa-save mr-2"></i> Update Medicine
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stock Status Alert -->
            @if($medicine->stock_quantity <= $medicine->minimum_stock)
                <div class="mt-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fa fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Low Stock Alert</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                Current stock ({{ $medicine->stock_quantity }}) is at or below minimum stock level ({{ $medicine->minimum_stock }}).
                                Consider restocking soon.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>