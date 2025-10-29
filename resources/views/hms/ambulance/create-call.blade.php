<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-phone-alt text-orange-600 mr-3"></i>
                    Record Ambulance Call
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Log a new ambulance dispatch request</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.ambulance.store-call') }}" method="POST">
                    @csrf

                    <!-- Caller Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-user text-orange-600 mr-2"></i> Caller Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Caller Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="caller_name" value="{{ old('caller_name') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500 @error('caller_name') border-red-500 @enderror">
                                @error('caller_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Caller Phone <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="caller_phone" value="{{ old('caller_phone') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500 @error('caller_phone') border-red-500 @enderror">
                                @error('caller_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-map-marker-alt text-orange-600 mr-2"></i> Location Details
                        </h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pickup Address <span class="text-red-500">*</span>
                                </label>
                                <textarea name="pickup_address" rows="2" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500 @error('pickup_address') border-red-500 @enderror">{{ old('pickup_address') }}</textarea>
                                @error('pickup_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Destination Address <span class="text-red-500">*</span>
                                </label>
                                <textarea name="destination_address" rows="2" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500 @error('destination_address') border-red-500 @enderror">{{ old('destination_address') }}</textarea>
                                @error('destination_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-ambulance text-orange-600 mr-2"></i> Emergency Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select Ambulance <span class="text-red-500">*</span>
                                </label>
                                <select name="ambulance_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500 @error('ambulance_id') border-red-500 @enderror">
                                    <option value="">Choose available ambulance...</option>
                                    @foreach($ambulances as $ambulance)
                                        <option value="{{ $ambulance->id }}" {{ old('ambulance_id') == $ambulance->id ? 'selected' : '' }}>
                                            {{ $ambulance->vehicle_number }} - {{ $ambulance->driver_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ambulance_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if($ambulances->isEmpty())
                                    <p class="mt-1 text-sm text-yellow-600">
                                        <i class="fa fa-exclamation-triangle mr-1"></i> No ambulances currently available
                                    </p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Call Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="call_time" 
                                    value="{{ old('call_time', now()->format('Y-m-d\TH:i')) }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500 @error('call_time') border-red-500 @enderror">
                                @error('call_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Patient Condition <span class="text-red-500">*</span>
                                </label>
                                <textarea name="patient_condition" rows="3" required
                                    placeholder="Describe the patient's condition, symptoms, and urgency..."
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500 @error('patient_condition') border-red-500 @enderror">{{ old('patient_condition') }}</textarea>
                                @error('patient_condition')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Quick Templates -->
                    <div class="mb-6 p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg">
                        <h4 class="text-sm font-semibold text-orange-900 dark:text-orange-300 mb-2">
                            <i class="fa fa-magic mr-1"></i> Quick Condition Templates
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setCondition('Cardiac emergency - Chest pain, difficulty breathing')" 
                                class="px-3 py-1 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-800 dark:text-red-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-heart mr-1"></i> Cardiac
                            </button>
                            <button type="button" onclick="setCondition('Trauma - Accident victim, multiple injuries')" 
                                class="px-3 py-1 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900 dark:hover:bg-orange-800 text-orange-800 dark:text-orange-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-car-crash mr-1"></i> Trauma
                            </button>
                            <button type="button" onclick="setCondition('Respiratory distress - Difficulty breathing')" 
                                class="px-3 py-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-800 dark:text-blue-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-lungs mr-1"></i> Respiratory
                            </button>
                            <button type="button" onclick="setCondition('Stroke - Facial drooping, weakness, speech difficulty')" 
                                class="px-3 py-1 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-brain mr-1"></i> Stroke
                            </button>
                            <button type="button" onclick="setCondition('Obstetric emergency - Labor/pregnancy complications')" 
                                class="px-3 py-1 bg-pink-100 hover:bg-pink-200 dark:bg-pink-900 dark:hover:bg-pink-800 text-pink-800 dark:text-pink-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-baby mr-1"></i> Obstetric
                            </button>
                            <button type="button" onclick="setCondition('General transfer - Stable patient transfer')" 
                                class="px-3 py-1 bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-800 dark:text-green-200 rounded-lg text-xs font-medium transition">
                                <i class="fa fa-exchange-alt mr-1"></i> Transfer
                            </button>
                        </div>
                    </div>

                    <!-- Emergency Notice -->
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-start">
                            <i class="fa fa-exclamation-triangle text-red-600 dark:text-red-400 mt-0.5 mr-3"></i>
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <p class="font-semibold mb-1">Emergency Protocol:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Verify exact location with caller</li>
                                    <li>Ask about any specific landmarks nearby</li>
                                    <li>Confirm patient count and conditions</li>
                                    <li>Ambulance will be dispatched immediately upon submission</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.ambulance.calls') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition">
                            <i class="fa fa-paper-plane mr-2"></i> Dispatch Ambulance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setCondition(condition) {
            document.querySelector('textarea[name="patient_condition"]').value = condition;
        }
    </script>
</x-app-layout>

