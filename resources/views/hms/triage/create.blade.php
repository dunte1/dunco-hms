<x-app-layout>
    <div class="py-4 md:py-6 hms-form-page">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <nav class="text-sm text-gray-500 dark:text-gray-400 mb-1 flex items-center gap-2" aria-label="Breadcrumb">
                        <a href="{{ route('hms.triage.index') }}" class="hover:text-red-600">Triage</a>
                        <i class="fa fa-chevron-right text-[10px]"></i>
                        <span class="text-gray-700 dark:text-gray-300">New Assessment</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-clipboard-list text-red-600 mr-2 sm:mr-3"></i>
                        New Triage Assessment
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Record priority, vitals, and clinical history. Patient can be found by registration number from the reception receipt.
                    </p>
                </div>
                <a href="{{ route('hms.triage.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium shrink-0">
                    <i class="fa fa-arrow-left mr-2"></i> Back to Triage
                </a>
            </div>

            <form method="POST" action="{{ route('hms.triage.store') }}" class="space-y-5">
                @csrf

                {{-- Patient & OPD Visit --}}
                <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                            <i class="fa fa-user-injured"></i>
                        </span>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Patient &amp; Visit</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Search by registration #, name, phone, or National ID</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
                            <div>
                                <x-searchable-select
                                    name="patient_id"
                                    label="Patient"
                                    endpoint="{{ route('api.patients.search') }}"
                                    :selected="old('patient_id')"
                                    placeholder="Type name, PAT-00001, phone, or ID..."
                                    :required="true"
                                />
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    OPD Visit <span class="text-gray-400 font-normal">(optional)</span>
                                </label>
                                <input type="text" id="opd-search"
                                       placeholder="Filter by visit #, name, or registration #..."
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 mb-2 min-h-[42px] text-sm">
                                <select name="opd_visit_id" id="opd-visit-select"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 min-h-[42px] text-sm">
                                    <option value="">{{ ($opdVisits ?? collect())->isEmpty() ? 'No open OPD visits — register at reception first' : 'Select open visit (' . ($opdVisits ?? collect())->count() . ')' }}</option>
                                    @foreach(($opdVisits ?? []) as $visit)
                                        <option value="{{ $visit->id }}"
                                                data-search="{{ strtolower(trim(($visit->patient->first_name ?? '') . ' ' . ($visit->patient->last_name ?? '') . ' ' . ($visit->patient->patient_no ?? '') . ' ' . $visit->id . ' ' . $visit->status)) }}"
                                                {{ old('opd_visit_id') == $visit->id ? 'selected' : '' }}>
                                            #{{ $visit->id }} · {{ $visit->patient->first_name ?? '' }} {{ $visit->patient->last_name ?? '' }}
                                            ({{ $visit->patient->patient_no ?? '' }}) · {{ $visit->status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('opd_visit_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1.5 text-xs text-gray-500">Open visits from last 7 days. Auto-created when a patient is registered at reception.</p>
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
                                            : ('Select open visit (' + options.length + ')');
                                    }
                                });
                            })();
                        </script>
                    </div>
                </section>

                {{-- Priority --}}
                <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center flex-shrink-0">
                            <i class="fa fa-exclamation-triangle"></i>
                        </span>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Priority Level <span class="text-red-500">*</span></h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Select clinical urgency</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority_level" value="emergency" {{ old('priority_level') == 'emergency' ? 'checked' : '' }} class="peer sr-only" required>
                                <div class="h-full p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/30 transition text-center hover:border-red-300">
                                    <i class="fa fa-exclamation-triangle text-2xl text-red-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Emergency</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Immediate</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority_level" value="urgent" {{ old('priority_level') == 'urgent' ? 'checked' : '' }} class="peer sr-only">
                                <div class="h-full p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-900/30 transition text-center hover:border-orange-300">
                                    <i class="fa fa-bolt text-2xl text-orange-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Urgent</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">&lt; 15 min</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority_level" value="semi_urgent" {{ old('priority_level') == 'semi_urgent' ? 'checked' : '' }} class="peer sr-only">
                                <div class="h-full p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/30 transition text-center hover:border-yellow-300">
                                    <i class="fa fa-clock text-2xl text-yellow-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Semi-Urgent</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">&lt; 30 min</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority_level" value="non_urgent" {{ old('priority_level') == 'non_urgent' ? 'checked' : '' }} class="peer sr-only">
                                <div class="h-full p-4 rounded-lg border-2 border-gray-200 dark:border-gray-600 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/30 transition text-center hover:border-green-300">
                                    <i class="fa fa-check-circle text-2xl text-green-500 mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Non-Urgent</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Standard</p>
                                </div>
                            </label>
                        </div>
                        @error('priority_level')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                {{-- Vitals --}}
                <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                            <i class="fa fa-heartbeat"></i>
                        </span>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Vitals</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Optional clinical measurements</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Temperature (°C)</label>
                                <input type="number" name="temperature" step="0.1" min="30" max="45" value="{{ old('temperature') }}" placeholder="36.5"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pulse (bpm)</label>
                                <input type="number" name="pulse_rate" min="30" max="250" value="{{ old('pulse_rate') }}" placeholder="72"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Systolic BP</label>
                                <input type="number" name="systolic_bp" min="60" max="300" value="{{ old('systolic_bp') }}" placeholder="120"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diastolic BP</label>
                                <input type="number" name="diastolic_bp" min="30" max="200" value="{{ old('diastolic_bp') }}" placeholder="80"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Respiratory Rate</label>
                                <input type="number" name="respiratory_rate" min="8" max="60" value="{{ old('respiratory_rate') }}" placeholder="16"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Oxygen Sat. (%)</label>
                                <input type="number" name="oxygen_saturation" step="0.1" min="50" max="100" value="{{ old('oxygen_saturation') }}" placeholder="98"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Glucose (mg/dL)</label>
                                <input type="number" name="blood_glucose" step="0.1" min="20" max="600" value="{{ old('blood_glucose') }}" placeholder="100"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weight (kg)</label>
                                <input type="number" name="weight_kg" step="0.1" min="0.5" max="300" value="{{ old('weight_kg') }}" placeholder="70"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Height (cm)</label>
                                <input type="number" name="height_cm" step="0.1" min="20" max="250" value="{{ old('height_cm') }}" placeholder="170"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Clinical History --}}
                <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                            <i class="fa fa-notes-medical"></i>
                        </span>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Clinical History</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Allergies, disability, and alcohol use</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Allergies
                            </label>
                            <textarea name="allergies" rows="2"
                                      placeholder="e.g. Penicillin, peanuts, latex... or None"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">{{ old('allergies') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Disability / Special Needs
                                </label>
                                <label class="flex items-center gap-2 px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 cursor-pointer min-h-[42px]">
                                    <input type="checkbox" name="disability" value="1" {{ old('disability') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500 w-4 h-4">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Has disability / special needs</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Alcohol Use
                                </label>
                                <select name="alcohol_use" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                                    @foreach(['unknown' => 'Unknown / Not asked', 'never' => 'Never', 'occasionally' => 'Occasionally', 'regularly' => 'Regularly', 'heavy' => 'Heavy'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('alcohol_use', 'unknown') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Disability notes</label>
                                <input type="text" name="disability_notes" value="{{ old('disability_notes') }}"
                                       placeholder="e.g. wheelchair user, hearing impairment..."
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alcohol notes</label>
                                <input type="text" name="alcohol_notes" value="{{ old('alcohol_notes') }}"
                                       placeholder="e.g. drinks daily, recently stopped..."
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 min-h-[42px] text-sm">
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Complaint & Notes --}}
                <section class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center flex-shrink-0">
                            <i class="fa fa-comment-medical"></i>
                        </span>
                        <div>
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Complaint &amp; Notes</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Presenting complaint and observations</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Chief Complaint <span class="text-red-500">*</span>
                            </label>
                            <textarea name="chief_complaint" rows="3" required
                                      placeholder="Describe the patient's main complaint..."
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">{{ old('chief_complaint') }}</textarea>
                            @error('chief_complaint')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Triage Notes
                            </label>
                            <textarea name="triage_notes" rows="4"
                                      placeholder="Additional observations, symptoms, or notes..."
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm">{{ old('triage_notes') }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pb-4">
                    <a href="{{ route('hms.triage.index') }}"
                       class="order-2 sm:order-1 w-full sm:w-auto px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg text-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="order-1 sm:order-2 w-full sm:w-auto px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-center">
                        <i class="fa fa-save mr-2"></i> Save Triage
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
