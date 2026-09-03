<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-calendar-check text-blue-600 mr-3"></i>
                        Appointment Details
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Appointment #{{ $appointment->id }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('hms.appointments.edit', $appointment) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('hms.appointments.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Appointment Info Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="text-center mb-6">
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-blue-100 text-blue-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                ];
                                $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColor }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                            <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">
                                {{ $appointment->patient->first_name ?? '' }} {{ $appointment->patient->last_name ?? '' }}
                            </h2>
                            <p class="text-blue-600 dark:text-blue-400 font-medium">{{ $appointment->patient->patient_no ?? '' }}</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-user-md text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Doctor</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    Dr. {{ $appointment->doctor->first_name ?? '' }} {{ $appointment->doctor->last_name ?? '' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-building text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Department</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $appointment->doctor->department->name ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-clock text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Scheduled</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $appointment->scheduled_at->format('M d, Y h:i A') }}
                                </span>
                            </div>

                            @if($appointment->appointment_type)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fa fa-tag text-blue-600 mr-3"></i>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Type</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Details & Notes -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fa fa-notes-medical text-blue-600 mr-2"></i> Appointment Notes
                        </h3>

                        @if($appointment->note)
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <p class="text-gray-700 dark:text-gray-300">{{ $appointment->note }}</p>
                            </div>
                        @else
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg text-center">
                                <p class="text-gray-500 dark:text-gray-400">No notes for this appointment</p>
                            </div>
                        @endif

                        <!-- Patient Info -->
                        <div class="mt-6">
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <i class="fa fa-user text-blue-600 mr-2"></i> Patient Information
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-xs text-gray-500 uppercase">Name</span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->patient->first_name ?? '' }} {{ $appointment->patient->last_name ?? '' }}
                                    </p>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-xs text-gray-500 uppercase">Phone</span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->patient->phone ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-xs text-gray-500 uppercase">Patient No</span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->patient->patient_no ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-xs text-gray-500 uppercase">Gender</span>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ ucfirst($appointment->patient->gender ?? 'N/A') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div class="mt-8 border-t pt-6">
                            <form action="{{ route('hms.appointments.destroy', $appointment) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">
                                    <i class="fa fa-trash mr-2"></i> Delete Appointment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
