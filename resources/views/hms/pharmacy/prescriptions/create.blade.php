<x-app-layout>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-prescription text-green-600 mr-3"></i>
                    Create New Prescription
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Write a prescription for a patient</p>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
<form action="{{ route('hms.pharmacy.prescriptions.store') }}" method="POST" id="prescriptionForm">
@csrf
<input type="hidden" name="opd_visit_id" id="opd_visit_id" value="{{ request('opd_visit_id') }}">

{{-- Weight-based dosing helper --}}
<div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
    <label class="block text-sm font-medium mb-2">Patient weight (kg) — optional dosing helper</label>
    <div class="flex gap-2 items-end">
        <input type="number" step="0.1" min="0.5" id="patient_weight_kg" placeholder="e.g. 70"
               class="w-32 border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
        <div id="dose_preview" class="text-sm text-gray-700 dark:text-gray-300 pb-2"></div>
    </div>
    <p class="text-xs text-gray-500 mt-1">When you enter a dose like <code>10 mg/kg</code> in a medicine dosage field, this helper shows the calculated total for the weight above. Always confirm clinically.</p>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const weightInput = document.getElementById('patient_weight_kg');
    const preview = document.getElementById('dose_preview');
    function recalc() {
        const w = parseFloat(weightInput.value);
        if (!w || w <= 0) { preview.textContent = ''; return; }
        const doses = document.querySelectorAll('input[name*="[dosage]"]');
        const parts = [];
        doses.forEach(function (el) {
            const m = (el.value || '').match(/([0-9.]+)\s*(mg|mcg|ml|g)\s*\/\s*kg/i);
            if (m) {
                const perKg = parseFloat(m[1]);
                const unit = m[2];
                const total = (perKg * w);
                parts.push(unit + ': ' + perKg + ' x ' + w + ' kg = ' + total.toFixed(2) + ' ' + unit);
            }
        });
        preview.innerHTML = parts.length ? ('Calculated: ' + parts.join(' | ')) : '';
    }
    if (weightInput) {
        weightInput.addEventListener('input', recalc);
        document.addEventListener('input', function (e) {
            if (e.target && e.target.name && e.target.name.indexOf('[dosage]') !== -1) recalc();
        });
    }
});
</script>

                    <!-- Patient & Doctor Info -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-user text-green-600 mr-2"></i> Patient & Doctor Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Patient <span class="text-red-500">*</span>
                                </label>
                                <select name="patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select patient...</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" @selected(request('patient_id') == $patient->id)>{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Prescribing Doctor <span class="text-red-500">*</span>
                                </label>
                                <select name="doctor_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select doctor...</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Prescription Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="prescription_date" value="{{ date('Y-m-d') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosis & Symptoms -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-stethoscope text-green-600 mr-2"></i> Medical Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Symptoms</label>
                                <textarea name="symptoms" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Diagnosis</label>
                                <textarea name="diagnosis" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Medicines -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center border-b pb-2">
                            <i class="fa fa-pills text-green-600 mr-2"></i> Medications
                        </h3>
                        <div id="medicinesContainer" class="space-y-4"></div>
                        <button type="button" onclick="addMedicine()" class="mt-4 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Add Medicine
                        </button>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Notes</label>
                        <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.pharmacy.prescriptions.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                            <i class="fa fa-save mr-2"></i> Create Prescription
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let medicineIndex = 0;
        const medicines = @json($medicines);

        function addMedicine() {
            const container = document.getElementById('medicinesContainer');
            const div = document.createElement('div');
            div.className = 'p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-gray-200 dark:border-gray-600';
            div.innerHTML = `
                <div class="flex items-start justify-between mb-3">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Medicine #${medicineIndex + 1}</h4>
                    <button type="button" onclick="this.closest('.bg-gray-50, .bg-gray-700').remove()" class="text-red-600 hover:text-red-800">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medicine *</label>
                        <select name="medicines[${medicineIndex}][medicine_id]" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select medicine...</option>
                            ${medicines.map(m => `<option value="${m.id}">${m.name} ${m.strength ? '- ' + m.strength : ''}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dosage *</label>
                        <input type="text" name="medicines[${medicineIndex}][dosage]" required placeholder="e.g., 1 tablet" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Frequency *</label>
                        <select name="medicines[${medicineIndex}][frequency]" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Select...</option>
                            <option value="Once daily">Once daily</option>
                            <option value="Twice daily">Twice daily</option>
                            <option value="Three times daily">Three times daily</option>
                            <option value="Four times daily">Four times daily</option>
                            <option value="Every 6 hours">Every 6 hours</option>
                            <option value="As needed">As needed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (days) *</label>
                        <input type="number" name="medicines[${medicineIndex}][duration_days]" required min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity *</label>
                        <input type="number" name="medicines[${medicineIndex}][quantity]" required min="1" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Instructions</label>
                        <input type="text" name="medicines[${medicineIndex}][instructions]" placeholder="e.g., Take after meals" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            `;
            container.appendChild(div);
            medicineIndex++;
        }

        // Add first medicine on page load
        addMedicine();
    </script>
</x-app-layout>
