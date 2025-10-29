<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-calendar-plus text-blue-600 mr-3"></i>
                    Schedule New Appointment
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Book an appointment for a patient with a doctor</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <form action="{{ route('hms.appointments.store') }}" method="POST" id="appointmentForm">
                    @csrf

                    <!-- Patient Information -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-user text-blue-600 mr-2"></i> Patient Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select Patient <span class="text-red-500">*</span>
                                </label>
                                <select name="patient_id" id="patientSelect" required onchange="updatePatientInfo()"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('patient_id') border-red-500 @enderror">
                                    <option value="">Choose a patient...</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" 
                                            data-name="{{ $patient->first_name }} {{ $patient->last_name }}"
                                            data-phone="{{ $patient->phone }}"
                                            data-patient-no="{{ $patient->patient_no }}"
                                            {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->first_name }} {{ $patient->last_name }} - {{ $patient->patient_no }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="patientInfoCard" class="md:col-span-2 hidden p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300" id="selectedPatientName">-</h4>
                                        <div class="mt-1 text-xs text-blue-700 dark:text-blue-400">
                                            <span id="selectedPatientNo"></span> | <span id="selectedPatientPhone"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor & Appointment Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-user-md text-blue-600 mr-2"></i> Doctor & Appointment Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Department
                                </label>
                                <select id="departmentFilter" onchange="filterDoctors()"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select Doctor <span class="text-red-500">*</span>
                                </label>
                                <select name="doctor_id" id="doctorSelect" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('doctor_id') border-red-500 @enderror">
                                    <option value="">Choose a doctor...</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" 
                                            data-department="{{ $doctor->doctor_department_id }}"
                                            data-dept-name="{{ $doctor->department->name ?? 'General' }}"
                                            {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} - {{ $doctor->department->name ?? 'General' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Appointment Type
                                </label>
                                <select name="appointment_type"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('appointment_type') border-red-500 @enderror">
                                    <option value="">Select type...</option>
                                    <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>
                                        General Consultation
                                    </option>
                                    <option value="follow_up" {{ old('appointment_type') == 'follow_up' ? 'selected' : '' }}>
                                        Follow-up Visit
                                    </option>
                                    <option value="checkup" {{ old('appointment_type') == 'checkup' ? 'selected' : '' }}>
                                        Regular Checkup
                                    </option>
                                    <option value="emergency" {{ old('appointment_type') == 'emergency' ? 'selected' : '' }}>
                                        Emergency
                                    </option>
                                </select>
                                @error('appointment_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status
                                </label>
                                <select name="status"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                    <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Time -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-clock text-blue-600 mr-2"></i> Schedule Date & Time
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Appointment Date & Time <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="scheduled_at" 
                                    value="{{ old('scheduled_at', now()->addDay()->format('Y-m-d\TH:i')) }}" 
                                    min="{{ now()->format('Y-m-d\TH:i') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('scheduled_at') border-red-500 @enderror">
                                @error('scheduled_at')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fa fa-magic mr-1"></i> Quick Time Slots
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" onclick="setTime('09:00')" 
                                        class="px-3 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-800 dark:text-blue-200 rounded-lg text-xs font-medium transition">
                                        <i class="fa fa-sun mr-1"></i> 9:00 AM
                                    </button>
                                    <button type="button" onclick="setTime('11:00')" 
                                        class="px-3 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-800 dark:text-blue-200 rounded-lg text-xs font-medium transition">
                                        <i class="fa fa-sun mr-1"></i> 11:00 AM
                                    </button>
                                    <button type="button" onclick="setTime('14:00')" 
                                        class="px-3 py-2 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900 dark:hover:bg-orange-800 text-orange-800 dark:text-orange-200 rounded-lg text-xs font-medium transition">
                                        <i class="fa fa-cloud-sun mr-1"></i> 2:00 PM
                                    </button>
                                    <button type="button" onclick="setTime('16:00')" 
                                        class="px-3 py-2 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900 dark:hover:bg-orange-800 text-orange-800 dark:text-orange-200 rounded-lg text-xs font-medium transition">
                                        <i class="fa fa-cloud-sun mr-1"></i> 4:00 PM
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-sticky-note text-blue-600 mr-2"></i> Additional Information
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes / Reason for Visit
                            </label>
                            <textarea name="note" rows="4" placeholder="Enter any special instructions, symptoms, or reason for visit..."
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('note') border-red-500 @enderror">{{ old('note') }}</textarea>
                            @error('note')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Information Box -->
                    <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <div class="flex items-start">
                            <i class="fa fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3"></i>
                            <div class="text-sm text-yellow-700 dark:text-yellow-300">
                                <p class="font-semibold mb-1">Important Notes:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Patients are advised to arrive 15 minutes before the scheduled time</li>
                                    <li>Please bring all relevant medical records and previous prescriptions</li>
                                    <li>Emergency appointments will be prioritized</li>
                                    <li>A confirmation SMS/Email will be sent to the patient</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.appointments.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <i class="fa fa-calendar-check mr-2"></i> Schedule Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updatePatientInfo() {
            const select = document.getElementById('patientSelect');
            const option = select.options[select.selectedIndex];
            const card = document.getElementById('patientInfoCard');
            
            if (option.value) {
                document.getElementById('selectedPatientName').textContent = option.dataset.name;
                document.getElementById('selectedPatientNo').textContent = option.dataset.patientNo;
                document.getElementById('selectedPatientPhone').textContent = option.dataset.phone;
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        }

        function filterDoctors() {
            const deptFilter = document.getElementById('departmentFilter').value;
            const doctorSelect = document.getElementById('doctorSelect');
            const options = doctorSelect.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                    return;
                }
                
                if (!deptFilter || option.dataset.department === deptFilter) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Reset selection if current is hidden
            if (doctorSelect.selectedIndex > 0 && options[doctorSelect.selectedIndex].style.display === 'none') {
                doctorSelect.selectedIndex = 0;
            }
        }

        function setTime(time) {
            const dateInput = document.querySelector('input[name="scheduled_at"]');
            const currentValue = dateInput.value;
            const date = currentValue ? currentValue.split('T')[0] : new Date().toISOString().split('T')[0];
            dateInput.value = `${date}T${time}`;
        }
    </script>
</x-app-layout>
