<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.ambulance.emergency') }}" class="hover:text-red-600">Emergency Admissions</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit Emergency</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-red-600 mr-3"></i>Edit Emergency Admission</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
                <form method="POST" action="{{ route('hms.ambulance.update-emergency', $emergency) }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient Name <span class="text-red-500">*</span></label>
                            <input type="text" name="patient_name" required value="{{ old('patient_name', $emergency->patient_name) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('patient_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                            <input type="tel" name="patient_phone" value="{{ old('patient_phone', $emergency->patient_phone) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('patient_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Triage Level <span class="text-red-500">*</span></label>
                            <select name="triage_level" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="critical" {{ old('triage_level', $emergency->triage_level) === 'critical' ? 'selected' : '' }}>Critical</option>
                                <option value="urgent" {{ old('triage_level', $emergency->triage_level) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                <option value="semi_urgent" {{ old('triage_level', $emergency->triage_level) === 'semi_urgent' ? 'selected' : '' }}>Semi Urgent</option>
                                <option value="non_urgent" {{ old('triage_level', $emergency->triage_level) === 'non_urgent' ? 'selected' : '' }}>Non Urgent</option>
                            </select>
                            @error('triage_level')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="active" {{ old('status', $emergency->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="stabilized" {{ old('status', $emergency->status) === 'stabilized' ? 'selected' : '' }}>Stabilized</option>
                                <option value="discharged" {{ old('status', $emergency->status) === 'discharged' ? 'selected' : '' }}>Discharged</option>
                            </select>
                            @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Chief Complaint <span class="text-red-500">*</span></label>
                            <textarea name="chief_complaint" rows="3" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('chief_complaint', $emergency->chief_complaint) }}</textarea>
                            @error('chief_complaint')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.ambulance.emergency') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
