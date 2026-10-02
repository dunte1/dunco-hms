<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.triage.index') }}" class="hover:text-red-600">Triage</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit #{{ $triage->triage_number ?? $triage->id }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fa fa-edit text-red-600 mr-3"></i>Edit Triage Assessment
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                <form method="POST" action="{{ route('hms.triage.update', $triage) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Patient</label>
                            <select name="patient_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" @selected(old('patient_id', $triage->patient_id) == $patient->id)>
                                        {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->patient_no ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Priority Level *</label>
                            <select name="priority_level" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach(['emergency','urgent','semi_urgent','non_urgent'] as $p)
                                    <option value="{{ $p }}" @selected(old('priority_level', $triage->priority_level) === $p)>{{ ucfirst(str_replace('_',' ',$p)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div><label class="text-sm font-medium">Temp (°C)</label>
                            <input type="number" step="0.1" name="temperature" value="{{ old('temperature', $triage->temperature) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2"></div>
                        <div><label class="text-sm font-medium">Pulse</label>
                            <input type="number" name="pulse_rate" value="{{ old('pulse_rate', $triage->pulse_rate) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2"></div>
                        <div><label class="text-sm font-medium">Systolic BP</label>
                            <input type="number" name="systolic_bp" value="{{ old('systolic_bp', $triage->systolic_bp) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2"></div>
                        <div><label class="text-sm font-medium">Diastolic BP</label>
                            <input type="number" name="diastolic_bp" value="{{ old('diastolic_bp', $triage->diastolic_bp) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2"></div>
                        <div><label class="text-sm font-medium">Respiratory rate</label>
                            <input type="number" name="respiratory_rate" value="{{ old('respiratory_rate', $triage->respiratory_rate) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2"></div>
                        <div><label class="text-sm font-medium">SpO2 %</label>
                            <input type="number" step="0.1" name="oxygen_saturation" value="{{ old('oxygen_saturation', $triage->oxygen_saturation) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2"></div>
                        <div><label class="text-sm font-medium">Pain (0-10)</label>
                            <input type="number" min="0" max="10" name="pain_score" value="{{ old('pain_score', $triage->pain_score) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2"></div>
                        <div><label class="text-sm font-medium">GCS (3-15)</label>
                            <input type="number" min="3" max="15" name="gcs_score" value="{{ old('gcs_score', $triage->gcs_score) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Chief complaint</label>
                        <input type="text" name="chief_complaint" value="{{ old('chief_complaint', $triage->chief_complaint) }}" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Triage notes</label>
                        <textarea name="triage_notes" rows="3" class="w-full rounded-lg border dark:bg-gray-700 dark:border-gray-600 px-3 py-2">{{ old('triage_notes', $triage->triage_notes) }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Update Triage</button>
                        <a href="{{ route('hms.triage.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
