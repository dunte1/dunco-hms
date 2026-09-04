<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa-solid fa-phone-volume text-orange-600 mr-3"></i> Create Emergency Call
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Log a new emergency call request</p>
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

            <form method="POST" action="{{ route('hms.ambulance.store-emergency') }}" class="max-w-2xl">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Call Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Caller Name *</label>
                            <input type="text" name="caller_name" value="{{ old('caller_name') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Caller Phone *</label>
                            <input type="text" name="caller_phone" value="{{ old('caller_phone') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Emergency Type *</label>
                            <select name="emergency_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                                <option value="">Select type</option>
                                <option value="cardiac" {{ old('emergency_type') == 'cardiac' ? 'selected' : '' }}>Cardiac Emergency</option>
                                <option value="trauma" {{ old('emergency_type') == 'trauma' ? 'selected' : '' }}>Trauma/Accident</option>
                                <option value="respiratory" {{ old('emergency_type') == 'respiratory' ? 'selected' : '' }}>Respiratory Emergency</option>
                                <option value="obstetric" {{ old('emergency_type') == 'obstetric' ? 'selected' : '' }}>Obstetric Emergency</option>
                                <option value="other" {{ old('emergency_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pickup Location *</label>
                            <input type="text" name="pickup_location" value="{{ old('pickup_location') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Full address for pickup">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority *</label>
                            <select name="priority" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500">
                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical (Red)</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High (Orange)</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium (Yellow)</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low (Green)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient Details</label>
                            <textarea name="patient_details" rows="3"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Describe patient condition">{{ old('patient_details') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa-solid fa-phone mr-2"></i> Log Emergency Call
                    </button>
                    <a href="{{ route('hms.ambulance.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
