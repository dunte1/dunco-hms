<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-hospital-user text-purple-600 mr-3"></i>
                        New IPD Admission
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Admit a new patient to In-Patient Department</p>
                </div>
                <a href="{{ route('hms.ipd.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa fa-arrow-left mr-2"></i> Back to List
                </a>
            </div>

            <!-- Form -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                <form action="{{ route('hms.ipd.store') }}" method="POST">
                    @csrf

                    <!-- Patient Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-user-injured text-purple-600 mr-2"></i> Patient Information
                        </h2>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Select Patient <span class="text-red-500">*</span>
                                </label>
                                <select name="patient_id" id="patient_id" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500 @error('patient_id') border-red-500 @enderror">
                                    <option value="">-- Select Patient --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->patient_no }} - {{ $patient->first_name }} {{ $patient->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-stethoscope text-purple-600 mr-2"></i> Medical Information
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="doctor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Assigned Doctor
                                </label>
                                <select name="doctor_id" id="doctor_id"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500 @error('doctor_id') border-red-500 @enderror">
                                    <option value="">-- Select Doctor --</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="admission_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Admission Date <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="admission_date" id="admission_date" 
                                    value="{{ old('admission_date', now()->format('Y-m-d\TH:i')) }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500 @error('admission_date') border-red-500 @enderror">
                                @error('admission_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="diagnosis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Diagnosis / Chief Complaint
                                </label>
                                <textarea name="diagnosis" id="diagnosis" rows="3"
                                    placeholder="Enter preliminary diagnosis or chief complaint..."
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500 @error('diagnosis') border-red-500 @enderror">{{ old('diagnosis') }}</textarea>
                                @error('diagnosis')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="treatment_plan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Treatment Plan
                                </label>
                                <textarea name="treatment_plan" id="treatment_plan" rows="3"
                                    placeholder="Enter initial treatment plan..."
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500 @error('treatment_plan') border-red-500 @enderror">{{ old('treatment_plan') }}</textarea>
                                @error('treatment_plan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bed Assignment -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-bed text-purple-600 mr-2"></i> Bed Assignment
                        </h2>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="bed_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Assign Bed
                                </label>
                                <select name="bed_id" id="bed_id"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500 focus:border-purple-500 @error('bed_id') border-red-500 @enderror">
                                    <option value="">-- Select Bed (Optional) --</option>
                                    @foreach($beds as $bed)
                                        <option value="{{ $bed->id }}" {{ old('bed_id') == $bed->id ? 'selected' : '' }}>
                                            {{ $bed->ward_name }} - Bed {{ $bed->bed_number }} 
                                            @if($bed->bedType)
                                                ({{ $bed->bedType->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('bed_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if($beds->isEmpty())
                                    <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
                                        <i class="fa fa-exclamation-triangle mr-1"></i> No beds available
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.ipd.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                            <i class="fa fa-save mr-2"></i> Admit Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
