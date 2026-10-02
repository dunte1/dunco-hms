<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fa fa-ambulance text-red-600 mr-2"></i> Emergency Assessment — Admission #{{ $admission->id }}
                </h1>
                <a href="{{ route('hms.ambulance.emergency', $admission) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg text-sm">Back</a>
            </div>

            @if(session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Trauma --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 lg:col-span-2">
                    <h2 class="font-semibold mb-3"><i class="fa fa-band-aid text-red-500 mr-2"></i>Trauma Assessment</h2>
                    <form method="POST" action="{{ route('emergency.trauma.store', $admission) }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $admission->patient_id }}">
                        <div class="grid grid-cols-2 gap-3">
                            <div><label class="text-sm font-medium">Mechanism *</label>
                                <input type="text" name="mechanism_of_injury" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-sm font-medium">Injury type *</label>
                                <select name="injury_type" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                                    <option value="blunt">Blunt</option>
                                    <option value="penetrating">Penetrating</option>
                                    <option value="burn">Burn</option>
                                    <option value="misc">Misc</option>
                                </select></div>
                        </div>
                        <div><label class="text-sm font-medium">Head/Face/Neck</label>
                            <input type="text" name="head_face_neck" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><label class="text-sm font-medium">Chest</label>
                                <input type="text" name="chest" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-sm font-medium">Abdomen</label>
                                <input type="text" name="abdomen" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-sm font-medium">Pelvis</label>
                                <input type="text" name="pelvis" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-sm font-medium">Extremities</label>
                                <input type="text" name="extremities" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-sm font-medium">Spinal</label>
                                <input type="text" name="spinal" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-sm font-medium">GCS (3-15)</label>
                                <input type="number" min="3" max="15" name="gcs_total" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-sm font-medium">Pupils left</label>
                                <input type="text" name="pupils_left" placeholder="e.g. 3mm reactive" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-sm font-medium">Pupils right</label>
                                <input type="text" name="pupils_right" placeholder="e.g. 3mm reactive" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700"></div>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <div><label class="text-xs">Systolic BP</label><input type="number" name="vital_signs_snapshot[systolic_bp]" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Diastolic</label><input type="number" name="vital_signs_snapshot[diastolic_bp]" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">HR</label><input type="number" name="vital_signs_snapshot[heart_rate]" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">SpO2</label><input type="number" name="vital_signs_snapshot[spo2]" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Trauma score (0-24) *</label>
                            <input type="number" min="0" max="24" name="trauma_score" required class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                        </div>
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Save Trauma Assessment</button>
                    </form>
                </div>

                <div class="space-y-6">
                    {{-- Resuscitation --}}
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                        <h2 class="font-semibold mb-3"><i class="fa fa-heartbeat text-red-500 mr-2"></i>Resuscitation (ABCDE)</h2>
                        <form method="POST" action="{{ route('emergency.resuscitation.store', $admission) }}" class="space-y-2">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $admission->patient_id }}">
                            <div><label class="text-xs">Presenting complaint *</label>
                                <input type="text" name="presenting_complaint" required class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Initial assessment *</label>
                                <textarea name="initial_assessment" rows="2" required class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></textarea></div>
                            <div><label class="text-xs">Airway *</label>
                                <input type="text" name="airway_status" required placeholder="patent / obstructed" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Breathing *</label>
                                <input type="text" name="breathing_status" required class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Circulation *</label>
                                <input type="text" name="circulation_status" required class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Disability / Neuro *</label>
                                <input type="text" name="disability_neurological" required class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Exposure *</label>
                                <input type="text" name="exposure" required placeholder="e.g. fully undressed, temp 36C" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Interventions *</label>
                                <textarea name="interventions" rows="2" required class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></textarea></div>
                            <div><label class="text-xs">Outcome *</label>
                                <select name="outcome" required class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700">
                                    <option value="survived">Survived</option>
                                    <option value="died">Died</option>
                                    <option value="in_transit">In transit</option>
                                </select></div>
                            <button class="w-full px-3 py-2 bg-red-600 text-white rounded-lg text-sm">Save Resuscitation</button>
                        </form>
                    </div>

                    {{-- Disposition --}}
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5">
                        <h2 class="font-semibold mb-3"><i class="fa fa-sign-out-alt text-blue-600 mr-2"></i>Disposition</h2>
                        <form method="POST" action="{{ route('emergency.disposition.store', $admission) }}" class="space-y-2">
                            @csrf
                            <div><label class="text-xs">Disposition type *</label>
                                <select name="disposition_type" required class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700">
                                    <option value="discharged">Discharged</option>
                                    <option value="admitted">Admitted</option>
                                    <option value="theatre">To theatre</option>
                                    <option value="icu">To ICU</option>
                                    <option value="referred">Referred</option>
                                    <option value="transferred">Transferred</option>
                                    <option value="deceased">Deceased</option>
                                </select></div>
                            <div><label class="text-xs">Destination ward</label>
                                <input type="text" name="destination_ward" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <div><label class="text-xs">Notes</label>
                                <textarea name="discharge_notes" rows="2" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></textarea></div>
                            <div><label class="text-xs">Discharged at *</label>
                                <input type="datetime-local" name="discharged_at" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700"></div>
                            <button class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg text-sm">Save Disposition</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
