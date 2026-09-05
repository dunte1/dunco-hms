<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.telemedicine.index') }}" class="hover:text-blue-600">Telemedicine</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit Session</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-edit text-teal-600 mr-3"></i>Edit Telemedicine Session</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 h-2"></div>
                <form method="POST" action="{{ route('hms.telemedicine.update', $session) }}" class="p-6 space-y-6">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient <span class="text-red-500">*</span></label>
                            <select name="patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach($patients as $id => $name)
                                    <option value="{{ $id }}" {{ $session->patient_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor <span class="text-red-500">*</span></label>
                            <select name="doctor_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach($doctors as $id => $name)
                                    <option value="{{ $id }}" {{ $session->doctor_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scheduled Date <span class="text-red-500">*</span></label>
                            <input type="date" name="scheduled_date" value="{{ old('scheduled_date', $session->scheduled_date?->format('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scheduled Time <span class="text-red-500">*</span></label>
                            <input type="time" name="scheduled_time" value="{{ old('scheduled_time', $session->scheduled_time) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meeting Link</label>
                            <input type="url" name="meeting_link" value="{{ old('meeting_link', $session->meeting_link) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="https://zoom.us/j/...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Platform</label>
                            <select name="platform" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="zoom" {{ old('platform', $session->platform) == 'zoom' ? 'selected' : '' }}>Zoom</option>
                                <option value="teams" {{ old('platform', $session->platform) == 'teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                <option value="google_meet" {{ old('platform', $session->platform) == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                <option value="custom" {{ old('platform', $session->platform) == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Consultation</label>
                            <textarea name="reason" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('reason', $session->reason) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                            <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $session->notes) }}</textarea>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg"><i class="fa fa-save mr-2"></i> Update</button>
                        <a href="{{ route('hms.telemedicine.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg"><i class="fa fa-times mr-2"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
