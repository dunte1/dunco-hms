<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.ot.index') }}" class="hover:text-blue-600">Operation Theatre</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Edit {{ $schedule->schedule_number }}</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-edit text-purple-600 mr-3"></i>
                    Edit OT Schedule
                </h1>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
                <form method="POST" action="{{ route('hms.ot.update', $schedule) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Patient & Procedure -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Patient & Procedure Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient <span class="text-red-500">*</span></label>
                                <select name="patient_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    @foreach($patients as $id => $name)
                                        <option value="{{ $id }}" {{ $schedule->patient_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Procedure Name <span class="text-red-500">*</span></label>
                                <input type="text" name="procedure_name" value="{{ old('procedure_name', $schedule->procedure_name) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Procedure Description</label>
                                <textarea name="procedure_description" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">{{ old('procedure_description', $schedule->procedure_description) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Procedure Type <span class="text-red-500">*</span></label>
                                <select name="procedure_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    <option value="elective" {{ old('procedure_type', $schedule->procedure_type) == 'elective' ? 'selected' : '' }}>Elective</option>
                                    <option value="emergency" {{ old('procedure_type', $schedule->procedure_type) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                    <option value="urgent" {{ old('procedure_type', $schedule->procedure_type) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Risk Level <span class="text-red-500">*</span></label>
                                <select name="risk_level" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    <option value="low" {{ old('risk_level', $schedule->risk_level) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('risk_level', $schedule->risk_level) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('risk_level', $schedule->risk_level) == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('risk_level', $schedule->risk_level) == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Medical Team -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Medical Team</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Surgeon <span class="text-red-500">*</span></label>
                                <select name="surgeon_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    @foreach($surgeons as $id => $name)
                                        <option value="{{ $id }}" {{ $schedule->surgeon_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Anesthetist</label>
                                <select name="anesthetist_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    <option value="">Select Anesthetist</option>
                                    @foreach($anesthetists as $id => $name)
                                        <option value="{{ $id }}" {{ $schedule->anesthetist_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Anesthesia Type <span class="text-red-500">*</span></label>
                                <select name="anesthesia_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    <option value="general" {{ old('anesthesia_type', $schedule->anesthesia_type) == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="regional" {{ old('anesthesia_type', $schedule->anesthesia_type) == 'regional' ? 'selected' : '' }}>Regional</option>
                                    <option value="local" {{ old('anesthesia_type', $schedule->anesthesia_type) == 'local' ? 'selected' : '' }}>Local</option>
                                    <option value="spinal" {{ old('anesthesia_type', $schedule->anesthesia_type) == 'spinal' ? 'selected' : '' }}>Spinal</option>
                                    <option value="epidural" {{ old('anesthesia_type', $schedule->anesthesia_type) == 'epidural' ? 'selected' : '' }}>Epidural</option>
                                    <option value="none" {{ old('anesthesia_type', $schedule->anesthesia_type) == 'none' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assistant Doctor</label>
                                <select name="assistant_doctor_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    <option value="">Select Assistant</option>
                                    @foreach($surgeons as $id => $name)
                                        <option value="{{ $id }}" {{ $schedule->assistant_doctor_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nurse</label>
                                <select name="nurse_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    <option value="">Select Nurse</option>
                                    @foreach($nurses as $id => $name)
                                        <option value="{{ $id }}" {{ $schedule->nurse_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Room & Schedule -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Room & Schedule</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">OT Room <span class="text-red-500">*</span></label>
                                <select name="ot_room_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                                    @foreach($rooms as $id => $name)
                                        <option value="{{ $id }}" {{ $schedule->ot_room_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scheduled Date <span class="text-red-500">*</span></label>
                                <input type="date" name="scheduled_date" value="{{ old('scheduled_date', $schedule->scheduled_date->format('Y-m-d')) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Time <span class="text-red-500">*</span></label>
                                <input type="time" name="scheduled_start" value="{{ old('scheduled_start', $schedule->scheduled_start) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Time</label>
                                <input type="time" name="scheduled_end" value="{{ old('scheduled_end', $schedule->scheduled_end) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Estimated Cost ($)</label>
                                <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $schedule->estimated_cost) }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Actual Cost ($)</label>
                                <input type="number" name="actual_cost" value="{{ old('actual_cost', $schedule->actual_cost) }}" step="0.01" min="0" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="consent_signed" value="1" {{ old('consent_signed', $schedule->consent_signed) ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Consent Form Signed</label>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Clinical Notes</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pre-Op Notes</label>
                                <textarea name="pre_op_notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">{{ old('pre_op_notes', $schedule->pre_op_notes) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Intra-Op Notes</label>
                                <textarea name="intra_op_notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">{{ old('intra_op_notes', $schedule->intra_op_notes) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Post-Op Notes</label>
                                <textarea name="post_op_notes" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">{{ old('post_op_notes', $schedule->post_op_notes) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Complications</label>
                                <textarea name="complications" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-purple-500">{{ old('complications', $schedule->complications) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-save mr-2"></i> Update Schedule
                        </button>
                        <a href="{{ route('hms.ot.show', $schedule) }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
