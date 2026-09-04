<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa-solid fa-microchip text-violet-600 mr-3"></i> Register IoT Sensor
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add a new IoT sensor for bed monitoring or environmental tracking</p>
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

            <form method="POST" action="{{ route('hms.iot.store-sensor') }}" class="max-w-2xl">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sensor Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-violet-500 focus:border-violet-500"
                                placeholder="e.g., Ward A Bed Sensor 1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor Type *</label>
                            <select name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-violet-500 focus:border-violet-500">
                                <option value="">Select type</option>
                                <option value="bed_occupancy" {{ old('type') == 'bed_occupancy' ? 'selected' : '' }}>Bed Occupancy</option>
                                <option value="temperature" {{ old('type') == 'temperature' ? 'selected' : '' }}>Temperature</option>
                                <option value="humidity" {{ old('type') == 'humidity' ? 'selected' : '' }}>Humidity</option>
                                <option value="air_quality" {{ old('type') == 'air_quality' ? 'selected' : '' }}>Air Quality</option>
                                <option value="motion" {{ old('type') == 'motion' ? 'selected' : '' }}>Motion Detection</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Serial Number *</label>
                            <input type="text" name="serial_number" value="{{ old('serial_number') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-violet-500 focus:border-violet-500"
                                placeholder="e.g., IOT-2024-001">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location *</label>
                            <input type="text" name="location" value="{{ old('location') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-violet-500 focus:border-violet-500"
                                placeholder="e.g., Ward A, Room 101">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Address</label>
                            <input type="text" name="ip_address" value="{{ old('ip_address') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-violet-500 focus:border-violet-500"
                                placeholder="e.g., 192.168.1.100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                            <textarea name="notes" rows="2"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-violet-500 focus:border-violet-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa-solid fa-plus mr-2"></i> Register Sensor
                    </button>
                    <a href="{{ route('hms.iot.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
