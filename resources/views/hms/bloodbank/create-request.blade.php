<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.bloodbank.requests') }}" class="hover:text-red-600">Blood Requests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>New Request</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-file-medical-alt text-red-600 mr-3"></i>
                    Create Blood Request
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Submit a new blood request for a patient</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.bloodbank.requests.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Request Number (Auto-generated) -->
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Request Number (Auto-generated)
                        </label>
                        <input type="text" value="{{ 'BR-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT) }}" 
                            disabled
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 bg-gray-100 text-gray-600 dark:text-gray-400 cursor-not-allowed">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">System will auto-generate upon creation</p>
                    </div>

                    <!-- Request Information -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Request Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Patient <span class="text-red-500">*</span>
                                </label>
                                <select name="patient_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->first_name }} {{ $patient->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Requesting Doctor <span class="text-red-500">*</span>
                                </label>
                                <select name="doctor_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Blood Group Required <span class="text-red-500">*</span>
                                </label>
                                <select name="blood_group_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500">
                                    <option value="">Select Blood Group</option>
                                    @foreach($bloodGroups as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Units Required <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="units_required" min="1" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500"
                                    placeholder="Number of units">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Reason for Request <span class="text-red-500">*</span>
                                </label>
                                <textarea name="reason" rows="4" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500"
                                    placeholder="Provide detailed reason for blood request (e.g., surgery, anemia, trauma, etc.)"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Reason Templates -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            <i class="fa fa-bolt text-blue-600 mr-2"></i> Quick Reason Templates
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <button type="button" onclick="fillReason('surgery')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Surgery
                            </button>
                            <button type="button" onclick="fillReason('trauma')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Trauma
                            </button>
                            <button type="button" onclick="fillReason('anemia')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Anemia
                            </button>
                            <button type="button" onclick="fillReason('transfusion')" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                Blood Transfusion
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-save mr-2"></i> Submit Request
                        </button>
                        <a href="{{ route('hms.bloodbank.requests') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fillReason(type) {
            const reasons = {
                surgery: "Patient scheduled for surgery requiring blood transfusion as precautionary measure. Pre-operative blood requirement for maintaining hemoglobin levels during and after surgical procedure.",
                trauma: "Emergency blood requirement due to traumatic injury resulting in significant blood loss. Immediate transfusion required to stabilize patient condition.",
                anemia: "Patient diagnosed with severe anemia requiring blood transfusion. Low hemoglobin levels necessitate immediate blood replacement therapy.",
                transfusion: "Routine blood transfusion required for patient with chronic condition. Regular blood replacement therapy as part of ongoing treatment plan."
            };
            
            const reasonField = document.querySelector('textarea[name="reason"]');
            if (reasonField && reasons[type]) {
                reasonField.value = reasons[type];
            }
        }
    </script>
</x-app-layout>

