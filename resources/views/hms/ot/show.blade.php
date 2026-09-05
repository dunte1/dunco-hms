<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.ot.index') }}" class="hover:text-blue-600">Operation Theatre</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $schedule->schedule_number }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fa fa-clock text-purple-600 mr-3"></i>
                            {{ $schedule->schedule_number }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $schedule->procedure_name }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('hms.ot.edit', $schedule) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition">
                            <i class="fa fa-edit mr-2"></i> Edit
                        </a>
                        @if($schedule->status === 'scheduled')
                            <form action="{{ route('hms.ot.time-in', $schedule) }}" method="POST">
                                @csrf
                                <button class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-md transition">
                                    <i class="fa fa-play mr-2"></i> Start Surgery
                                </button>
                            </form>
                        @endif
                        @if($schedule->status === 'in_progress')
                            <form action="{{ route('hms.ot.time-out', $schedule) }}" method="POST">
                                @csrf
                                <button class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition">
                                    <i class="fa fa-stop mr-2"></i> Complete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center"><i class="fa fa-check-circle mr-2"></i>{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Status & Priority -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $schedule->status_badge }}">{{ ucfirst(str_replace('_', ' ', $schedule->status)) }}</span>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $schedule->risk_badge }}">{{ ucfirst($schedule->risk_level) }} Risk</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Type:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($schedule->procedure_type) }}</span></div>
                            <div><span class="text-gray-500">Anesthesia:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($schedule->anesthesia_type) }}</span></div>
                            <div><span class="text-gray-500">Scheduled:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $schedule->scheduled_date->format('M d, Y') }} at {{ $schedule->scheduled_start }}</span></div>
                            <div><span class="text-gray-500">Duration:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $schedule->scheduled_end ?? 'TBD' }}</span></div>
                            @if($schedule->actual_start)
                                <div><span class="text-gray-500">Started:</span> <span class="font-medium text-green-600">{{ $schedule->actual_start->format('M d, Y H:i') }}</span></div>
                            @endif
                            @if($schedule->actual_end)
                                <div><span class="text-gray-500">Ended:</span> <span class="font-medium text-blue-600">{{ $schedule->actual_end->format('M d, Y H:i') }}</span></div>
                            @endif
                            <div><span class="text-gray-500">Consent:</span> <span class="font-medium {{ $schedule->consent_signed ? 'text-green-600' : 'text-red-600' }}">{{ $schedule->consent_signed ? 'Signed' : 'Not Signed' }}</span></div>
                            @if($schedule->estimated_cost)
                                <div><span class="text-gray-500">Est. Cost:</span> <span class="font-medium text-gray-900 dark:text-white">${{ number_format($schedule->estimated_cost, 2) }}</span></div>
                            @endif
                        </div>
                    </div>

                    <!-- Procedure Description -->
                    @if($schedule->procedure_description)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-file-medical text-purple-600 mr-2"></i>Procedure Description</h3>
                            <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $schedule->procedure_description }}</p>
                        </div>
                    @endif

                    <!-- Notes -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-sticky-note text-purple-600 mr-2"></i>Clinical Notes</h3>
                        <div class="space-y-3">
                            @if($schedule->pre_op_notes)
                                <div><span class="text-xs font-semibold text-gray-500 uppercase">Pre-Op Notes</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $schedule->pre_op_notes }}</p></div>
                            @endif
                            @if($schedule->intra_op_notes)
                                <div><span class="text-xs font-semibold text-gray-500 uppercase">Intra-Op Notes</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $schedule->intra_op_notes }}</p></div>
                            @endif
                            @if($schedule->post_op_notes)
                                <div><span class="text-xs font-semibold text-gray-500 uppercase">Post-Op Notes</span><p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $schedule->post_op_notes }}</p></div>
                            @endif
                            @if($schedule->complications)
                                <div><span class="text-xs font-semibold text-red-500 uppercase">Complications</span><p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ $schedule->complications }}</p></div>
                            @endif
                            @if(!$schedule->pre_op_notes && !$schedule->intra_op_notes && !$schedule->post_op_notes && !$schedule->complications)
                                <p class="text-sm text-gray-500 italic">No notes recorded yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    @if(count($timeline) > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"><i class="fa fa-stream text-purple-600 mr-2"></i>Surgery Timeline</h3>
                            <div class="space-y-4">
                                @foreach($timeline as $event)
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-3 h-3 bg-purple-500 rounded-full mt-2"></div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event['label'] }}</span>
                                                <span class="text-xs text-gray-500">{{ $event['time'] }}</span>
                                            </div>
                                            @if($event['notes'])
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $event['notes'] }}</p>
                                            @endif
                                            <span class="text-xs text-gray-400">by {{ $event['recorded_by'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Patient Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-user text-purple-600 mr-2"></i>Patient</h3>
                        <div class="text-sm space-y-2">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $schedule->patient->first_name }} {{ $schedule->patient->last_name }}</p>
                            <p class="text-gray-600 dark:text-gray-400">ID: {{ $schedule->patient->patient_id ?? $schedule->patient->id }}</p>
                            @if($schedule->patient->phone)
                                <p class="text-gray-600 dark:text-gray-400"><i class="fa fa-phone mr-1"></i>{{ $schedule->patient->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Medical Team -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-user-md text-purple-600 mr-2"></i>Medical Team</h3>
                        <div class="space-y-3 text-sm">
                            <div><span class="text-gray-500 text-xs uppercase">Surgeon</span><p class="font-medium text-gray-900 dark:text-white">Dr. {{ $schedule->surgeon->first_name }} {{ $schedule->surgeon->last_name }}</p></div>
                            @if($schedule->anesthetist)
                                <div><span class="text-gray-500 text-xs uppercase">Anesthetist</span><p class="font-medium text-gray-900 dark:text-white">Dr. {{ $schedule->anesthetist->first_name }} {{ $schedule->anesthetist->last_name }}</p></div>
                            @endif
                            @if($schedule->assistantDoctor)
                                <div><span class="text-gray-500 text-xs uppercase">Assistant</span><p class="font-medium text-gray-900 dark:text-white">Dr. {{ $schedule->assistantDoctor->first_name }} {{ $schedule->assistantDoctor->last_name }}</p></div>
                            @endif
                            @if($schedule->nurse)
                                <div><span class="text-gray-500 text-xs uppercase">Nurse</span><p class="font-medium text-gray-900 dark:text-white">{{ $schedule->nurse->first_name }} {{ $schedule->nurse->last_name }}</p></div>
                            @endif
                        </div>
                    </div>

                    <!-- Room Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3"><i class="fa fa-door-open text-purple-600 mr-2"></i>OT Room</h3>
                        <div class="text-sm space-y-2">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $schedule->otRoom->name }}</p>
                            <p class="text-gray-600 dark:text-gray-400">Type: {{ ucfirst($schedule->otRoom->type) }}</p>
                            @if($schedule->otRoom->floor)
                                <p class="text-gray-600 dark:text-gray-400">Floor: {{ $schedule->otRoom->floor }}</p>
                            @endif
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->otRoom->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($schedule->otRoom->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
