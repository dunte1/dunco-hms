<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.pharmacy.prescriptions.index') }}" class="hover:text-green-600">Prescriptions</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <a href="{{ route('hms.pharmacy.prescriptions.show', $prescription) }}" class="hover:text-green-600">Prescription #{{ $prescription->id }}</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-edit text-green-600 mr-3"></i>
                            Edit Prescription
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Patient: {{ $prescription->patient->full_name ?? 'N/A' }} | 
                            Doctor: Dr. {{ $prescription->doctor ? $prescription->doctor->first_name . ' ' . $prescription->doctor->last_name : 'N/A' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('hms.pharmacy.prescriptions.show', $prescription) }}" 
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-eye mr-2"></i> View
                        </a>
                        <a href="{{ route('hms.pharmacy.prescriptions.index') }}" 
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                            <i class="fa fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
                
                <form method="POST" action="{{ route('hms.pharmacy.prescriptions.update', $prescription) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Patient & Doctor Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-user text-green-600 mr-2"></i>
                            Patient & Doctor Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient *</label>
                                <select name="patient_id" id="patient_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id', $prescription->patient_id) == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->full_name }} ({{ $patient->patient_no }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Doctor *</label>
                                <select name="doctor_id" id="doctor_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id', $prescription->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                            @if($doctor->department)
                                                - {{ $doctor->department->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-stethoscope text-red-600 mr-2"></i>
                            Medical Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="diagnosis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diagnosis</label>
                                <input type="text" name="diagnosis" id="diagnosis" value="{{ old('diagnosis', $prescription->diagnosis) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"
                                    placeholder="Enter primary diagnosis">
                                @error('diagnosis')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="symptoms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Symptoms</label>
                                <input type="text" name="symptoms" id="symptoms" value="{{ old('symptoms', $prescription->symptoms) }}"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"
                                    placeholder="Enter patient symptoms">
                                @error('symptoms')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Prescription Date -->
                    <div>
                        <label for="prescription_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prescription Date *</label>
                        <input type="datetime-local" name="prescription_date" id="prescription_date" 
                               value="{{ old('prescription_date', $prescription->prescription_date->format('Y-m-d\TH:i')) }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                        @error('prescription_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prescribed Medications -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-pills text-blue-600 mr-2"></i>
                            Prescribed Medications
                        </h3>
                        
                        <div id="medicines-container">
                            @foreach($prescription->items as $index => $item)
                                <div class="medicine-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Medicine {{ $index + 1 }}</h4>
                                        @if($index > 0)
                                            <button type="button" onclick="removeMedicine(this)" class="text-red-600 hover:text-red-800">
                                                <i class="fa fa-trash"></i> Remove
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medicine *</label>
                                            <select name="medicines[{{ $index }}][medicine_id]" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                                <option value="">Select Medicine</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}" {{ old('medicines.'.$index.'.medicine_id', $item->medicine_id) == $medicine->id ? 'selected' : '' }}>
                                                        {{ $medicine->name }} @if($medicine->strength) - {{ $medicine->strength }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage *</label>
                                            <input type="text" name="medicines[{{ $index }}][dosage]" value="{{ old('medicines.'.$index.'.dosage', $item->dosage) }}" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"
                                                placeholder="e.g., 1 tablet">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequency *</label>
                                            <select name="medicines[{{ $index }}][frequency]" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                                <option value="">Select Frequency</option>
                                                <option value="Once daily" {{ old('medicines.'.$index.'.frequency', $item->frequency) == 'Once daily' ? 'selected' : '' }}>Once daily</option>
                                                <option value="Twice daily" {{ old('medicines.'.$index.'.frequency', $item->frequency) == 'Twice daily' ? 'selected' : '' }}>Twice daily</option>
                                                <option value="Three times daily" {{ old('medicines.'.$index.'.frequency', $item->frequency) == 'Three times daily' ? 'selected' : '' }}>Three times daily</option>
                                                <option value="Four times daily" {{ old('medicines.'.$index.'.frequency', $item->frequency) == 'Four times daily' ? 'selected' : '' }}>Four times daily</option>
                                                <option value="Every 6 hours" {{ old('medicines.'.$index.'.frequency', $item->frequency) == 'Every 6 hours' ? 'selected' : '' }}>Every 6 hours</option>
                                                <option value="Every 8 hours" {{ old('medicines.'.$index.'.frequency', $item->frequency) == 'Every 8 hours' ? 'selected' : '' }}>Every 8 hours</option>
                                                <option value="As needed" {{ old('medicines.'.$index.'.frequency', $item->frequency) == 'As needed' ? 'selected' : '' }}>As needed</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Days) *</label>
                                            <input type="number" name="medicines[{{ $index }}][duration_days]" value="{{ old('medicines.'.$index.'.duration_days', $item->duration_days) }}" min="1" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                                            <input type="number" name="medicines[{{ $index }}][quantity]" value="{{ old('medicines.'.$index.'.quantity', $item->quantity) }}" min="1" required
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instructions</label>
                                            <textarea name="medicines[{{ $index }}][instructions]" rows="2"
                                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"
                                                placeholder="Special instructions...">{{ old('medicines.'.$index.'.instructions', $item->instructions) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" onclick="addMedicine()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add Another Medicine
                        </button>
                    </div>

                    <!-- Additional Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="4"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"
                            placeholder="Enter any additional notes or instructions...">{{ old('notes', $prescription->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prescription Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                            <option value="active" {{ old('status', $prescription->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ old('status', $prescription->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $prescription->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.pharmacy.prescriptions.show', $prescription) }}" 
                           class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            <i class="fa fa-save mr-2"></i> Update Prescription
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let medicineIndex = {{ $prescription->items->count() }};

        function addMedicine() {
            const container = document.getElementById('medicines-container');
            const medicineHtml = `
                <div class="medicine-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Medicine ${medicineIndex + 1}</h4>
                        <button type="button" onclick="removeMedicine(this)" class="text-red-600 hover:text-red-800">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medicine *</label>
                            <select name="medicines[${medicineIndex}][medicine_id]" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                <option value="">Select Medicine</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }} @if($medicine->strength) - {{ $medicine->strength }} @endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage *</label>
                            <input type="text" name="medicines[${medicineIndex}][dosage]" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., 1 tablet">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequency *</label>
                            <select name="medicines[${medicineIndex}][frequency]" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                <option value="">Select Frequency</option>
                                <option value="Once daily">Once daily</option>
                                <option value="Twice daily">Twice daily</option>
                                <option value="Three times daily">Three times daily</option>
                                <option value="Four times daily">Four times daily</option>
                                <option value="Every 6 hours">Every 6 hours</option>
                                <option value="Every 8 hours">Every 8 hours</option>
                                <option value="As needed">As needed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (Days) *</label>
                            <input type="number" name="medicines[${medicineIndex}][duration_days]" min="1" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity *</label>
                            <input type="number" name="medicines[${medicineIndex}][quantity]" min="1" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instructions</label>
                            <textarea name="medicines[${medicineIndex}][instructions]" rows="2"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"
                                placeholder="Special instructions..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', medicineHtml);
            medicineIndex++;
        }

        function removeMedicine(button) {
            button.closest('.medicine-item').remove();
        }
    </script>
</x-app-layout>