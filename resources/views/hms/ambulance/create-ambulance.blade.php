<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa-solid fa-truck-medical text-red-600 mr-3"></i> Register Ambulance
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add a new ambulance to the fleet</p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('hms.ambulance.store-ambulance') }}" class="max-w-2xl">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Vehicle Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vehicle Number *</label>
                            <input type="text" name="vehicle_number" value="{{ old('vehicle_number') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500"
                                placeholder="e.g., AMB-001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vehicle Type *</label>
                            <select name="vehicle_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                                <option value="">Select type</option>
                                <option value="basic" {{ old('vehicle_type') == 'basic' ? 'selected' : '' }}>Basic Life Support (BLS)</option>
                                <option value="als" {{ old('vehicle_type') == 'als' ? 'selected' : '' }}>Advanced Life Support (ALS)</option>
                                <option value="icu" {{ old('vehicle_type') == 'icu' ? 'selected' : '' }}>ICU Ambulance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Make & Model</label>
                            <input type="text" name="make_model" value="{{ old('make_model') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500"
                                placeholder="e.g., Toyota HiAce">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Base Location *</label>
                            <input type="text" name="base_location" value="{{ old('base_location') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500"
                                placeholder="e.g., Main Hospital">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Phone</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa-solid fa-plus mr-2"></i> Register Ambulance
                    </button>
                    <a href="{{ route('hms.ambulance.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
