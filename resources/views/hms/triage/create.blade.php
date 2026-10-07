<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb & Header -->
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.triage.index') }}" class="hover:text-red-600">Triage</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>New Triage</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fa fa-plus text-red-600 mr-3"></i>New Triage Assessment
                </h1>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                <form method="POST" action="{{ route('hms.triage.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Patient & Visit Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-searchable-select
                                name="patient_id"
                                label="Patient (search name, registration #, phone, or National ID)"
                                endpoint="{{ route('api.patients.search') }}"
                                :selected="old('patient_id')"
                                placeholder="Type patient name or registration number..."
                                :required="true"
                            />
                            @error('patient_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                OPD Visit <span class="text-gray-400">(optional)</span>
                            </label>
                            <input type="text" id="opd-search" placeholder="Filter visit #, patient name, or registration #..."
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 mb-2 min-h-[42px]">
                            <select name="opd_visit_id" id="opd-visit-select" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 min-h-[42px]">
                                <option value="">{{ ($opdVisits ?? collect())->isEmpty() ? 'No open OPD visits found — register patient at reception first' : 'Select OPD visit (' . ($opdVisits ?? collect())->count() . ' open)' }}</option>
                                @foreach(($opdVisits ?? []) as $visit)
                                    <option value="{{ $visit->id }}"
                                            data-search="{{ strtolower(($visit->patient->first_name ?? '') . ' ' . ($visit->patient->last_name ?? '') . ' ' . ($visit->patient->patient_no ?? '') . ' ' . $visit->id . ' ' . $visit->status) }}"
                                            {{ old('opd_visit_id') == $visit->id ? 'selected' : '' }}>
                                        #{{ $visit->id }} · {{ $visit->patient->first_name ?? '' }} {{ $visit->patient->last_name ?? '' }}
                                        ({{ $visit->patient->patient_no ?? '' }}) · {{ $visit->status }} · {{ optional($visit->visit_time ?? $visit->created_at)->format('H:i') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('opd_visit_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Open visits from the last 7 days (registered, triaged, in progress, lab/pharmacy pending). Auto-created at reception when a patient is registered.</p>
                        </div>
                    </div>

                    <script>
                        (function () {
                            var search = document.getElementById('opd-search');
                            var select = document.getElementById('opd-visit-select');
                            if (!search || !select) return;
                            search.addEventListener('input', function () {
                                var q = (this.value || '').toLowerCase().trim();
                                var options = select.querySelectorAll('option[data-search]');
                                var visible = 0;
                                options.forEach(function (opt) {
                                    var match = !q || (opt.getAttribute('data-search') || '').indexOf(q) !== -1;
                                    opt.hidden = !match;
                                    if (match) visible++;
                                });
                                var empty = select.querySelector('option:not([data-search])');
                                if (empty) {
                                    empty.textContent = q
                                        ? ('No matches (' + visible + ' shown)')
                                        : ('Select OPD visit (' + options.length + ' open)');
                                }
                            });
                        })();
                    </script>

                    <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm px-4 py-3 rounded-lg">
                        <i class="fa fa-info-circle me-1"></i>
                        Search by <strong>registration number</strong> (e.g. PAT-00001), <strong>full name</strong>, <strong>phone</strong>, or <strong>National ID</strong> — same data as the reception receipt.
                    </div>

                    <!-- Priority Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Priority Level <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority_level" value="emergency" {{ old('priority_level') == 'emergency' ? 'checked' : '' }} class="peer sr-only" required>
                                <div class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/30 transition text-center hover:border-red-300">
                                    <i class="fa fa-exclamation-triangle text-2xl text-red-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Emergency</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Immediate</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority_level" value="urgent" {{ old('priority_level') == 'urgent' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-900/30 transition text-center hover:border-orange-300">
                                    <i class="fa fa-bolt text-2xl text-orange-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Urgent</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">&lt; 15 min</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority_level" value="semi_urgent" {{ old('priority_level') == 'semi_urgent' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/30 transition text-center hover:border-yellow-300">
                                    <i class="fa fa-clock text-2xl text-yellow-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Semi-Urgent</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">&lt; 30 min</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority_level" value="non_urgent" {{ old('priority_level') == 'non_urgent' ? 'checked' : '' }} class="peer sr-only">
                                <div class="p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/30 transition text-center hover:border-green-300">
                                    <i class="fa fa-check-circle text-2xl text-green-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Non-Urgent</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Standard</p>
                                </div>
                            </label>
                        </div>
                        @error('priority_level')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vitals Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fa fa-heartbeat text-red-600 mr-2"></i>Vitals
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Temperature (°C)</label>
                                <input type="number" name="temperature" step="0.1" min="30" max="45"
                                    value="{{ old('temperature') }}"
                                    placeholder="36.5"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pulse Rate (bpm)</label>
                                <input type="number" name="pulse_rate" min="30" max="250"
                                    value="{{ old('pulse_rate') }}"
                                    placeholder="72"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Systolic BP (mmHg)</label>
                                <input type="number" name="systolic_bp" min="60" max="300"
                                    value="{{ old('systolic_bp') }}"
                                    placeholder="120"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Diastolic BP (mmHg)</label>
                                <input type="number" name="diastolic_bp" min="30" max="200"
                                    value="{{ old('diastolic_bp') }}"
                                    placeholder="80"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Respiratory Rate (/min)</label>
                                <input type="number" name="respiratory_rate" min="8" max="60"
                                    value="{{ old('respiratory_rate') }}"
                                    placeholder="16"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Oxygen Saturation (%)</label>
                                <input type="number" name="oxygen_saturation" step="0.1" min="50" max="100"
                                    value="{{ old('oxygen_saturation') }}"
                                    placeholder="98"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Blood Glucose (mg/dL)</label>
                                <input type="number" name="blood_glucose" step="0.1" min="20" max="600"
                                    value="{{ old('blood_glucose') }}"
                                    placeholder="100"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Weight (kg)</label>
                                <input type="number" name="weight_kg" step="0.1" min="0.5" max="300"
                                    value="{{ old('weight_kg') }}"
                                    placeholder="70"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Height (cm)</label>
                                <input type="number" name="height_cm" step="0.1" min="20" max="250"
                                    value="{{ old('height_cm') }}"
                                    placeholder="170"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                            </div>
                        </div>
                    </div>

                    <!-- Chief Complaint -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Chief Complaint <span class="text-red-500">*</span>
                        </label>
                        <textarea name="chief_complaint" rows="3" required
                            placeholder="Describe the patient's main complaint..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">{{ old('chief_complaint') }}</textarea>
                        @error('chief_complaint')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Clinical History: Allergies, Disability, Alcohol -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fa fa-exclamation-triangle text-amber-500 me-1"></i>Allergies
                            </label>
                            <textarea name="allergies" rows="2"
                                placeholder="e.g. Penicillin, peanuts, latex, dust... or None"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">{{ old('allergies') }}</textarea>
                            @error('allergies')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Disability / Special Needs
                            </label>
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 cursor-pointer min-h-[42px]">
                                <input type="checkbox" name="disability" value="1" {{ old('disability') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Patient has disability / special needs</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alcohol Use
                            </label>
                            <select name="alcohol_use" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 min-h-[42px]">
                                @foreach(['unknown' => 'Unknown / Not asked', 'never' => 'Never', 'occasionally' => 'Occasionally', 'regularly' => 'Regularly', 'heavy' => 'Heavy'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('alcohol_use', 'unknown') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <input type="text" name="disability_notes" value="{{ old('disability_notes') }}"
                                placeholder="Disability details (optional) e.g. wheelchair user, hearing impairment..."
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 mb-2">
                            <input type="text" name="alcohol_notes" value="{{ old('alcohol_notes') }}"
                                placeholder="Alcohol notes (optional) e.g. drinks daily, recently stopped..."
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">
                        </div>
                    </div>

                    <!-- Triage Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Triage Notes
                        </label>
                        <textarea name="triage_notes" rows="4"
                            placeholder="Additional observations, symptoms, or notes..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500">{{ old('triage_notes') }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                            <i class="fa fa-save mr-2"></i> Save Triage
                        </button>
                        <a href="{{ route('hms.triage.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
