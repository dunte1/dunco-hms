<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fa fa-bed text-blue-600 mr-3"></i>
                    Real-Time Bed Occupancy Map
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">IoT-powered live bed status monitoring</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Beds</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total_beds'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-bed text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Occupied</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['occupied'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-user-check text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Warning Alerts</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['warning_alerts'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-exclamation-triangle text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Critical Alerts</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['critical_alerts'] }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-bell text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Status Legend</h3>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Available</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Occupied - Normal</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Warning Alert</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Critical Alert</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-300 rounded mr-2"></div>
                        <span class="text-sm text-gray-700 dark:text-gray-300">No Sensor</span>
                    </div>
                </div>
            </div>

            @if($beds->count() > 0)
                <!-- Bed Map by Ward -->
                @foreach($bedsByWard as $ward => $wardBeds)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                            <h3 class="text-lg font-bold text-white">
                                <i class="fa fa-hospital mr-2"></i> {{ $ward ?: 'General Ward' }}
                            </h3>
                            <p class="text-sm text-white opacity-90">{{ $wardBeds->count() }} beds</p>
                        </div>
                        
                        <div class="p-6">
                            <!-- Group by room -->
                            @php
                                $roomBeds = $wardBeds->groupBy('room');
                            @endphp
                            
                            @foreach($roomBeds as $room => $roomBedsList)
                                <div class="mb-6 last:mb-0">
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">
                                        {{ $room ?: 'Room ' . $loop->iteration }}
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                        @foreach($roomBedsList as $bed)
                                            @php
                                                $sensor = $bed->sensor;
                                                $assignment = $bed->bedAssignments->first();
                                                
                                                $bgColor = 'bg-gray-300';
                                                $statusText = 'No Sensor';
                                                
                                                if ($sensor) {
                                                    if ($sensor->alert_level === 'critical') {
                                                        $bgColor = 'bg-red-500';
                                                        $statusText = 'Critical';
                                                    } elseif ($sensor->alert_level === 'warning') {
                                                        $bgColor = 'bg-yellow-500';
                                                        $statusText = 'Warning';
                                                    } elseif ($sensor->is_occupied) {
                                                        $bgColor = 'bg-blue-500';
                                                        $statusText = 'Occupied';
                                                    } else {
                                                        $bgColor = 'bg-green-500';
                                                        $statusText = 'Available';
                                                    }
                                                }
                                            @endphp
                                            
                                            <div class="relative group cursor-pointer" x-data="{ showDetails: false }" @click="showDetails = !showDetails">
                                                <!-- Bed Card -->
                                                <div class="{{ $bgColor }} rounded-lg p-4 text-white shadow-md hover:shadow-lg transition duration-200">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <i class="fa fa-bed text-2xl"></i>
                                                        @if($sensor && $sensor->alert_level !== 'normal')
                                                            <i class="fa fa-bell animate-pulse text-xl"></i>
                                                        @endif
                                                    </div>
                                                    <div class="text-lg font-bold">{{ $bed->bed_number }}</div>
                                                    <div class="text-xs opacity-90">{{ $statusText }}</div>
                                                </div>
                                                
                                                <!-- Tooltip/Details -->
                                                <div x-show="showDetails" 
                                                     x-transition
                                                     @click.away="showDetails = false"
                                                     class="absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-64 bg-white dark:bg-gray-700 rounded-lg shadow-xl border border-gray-200 dark:border-gray-600 p-4">
                                                    <div class="text-sm">
                                                        <div class="font-bold text-gray-900 dark:text-white mb-2">
                                                            Bed {{ $bed->bed_number }}
                                                        </div>
                                                        <div class="space-y-1 text-gray-700 dark:text-gray-300">
                                                            <div><span class="font-semibold">Type:</span> {{ $bed->bedType->name ?? 'N/A' }}</div>
                                                            <div><span class="font-semibold">Ward:</span> {{ $bed->ward ?? 'N/A' }}</div>
                                                            <div><span class="font-semibold">Room:</span> {{ $bed->room ?? 'N/A' }}</div>
                                                            <div><span class="font-semibold">Status:</span> {{ $statusText }}</div>
                                                            
                                                            @if($assignment && $assignment->patient)
                                                                <div class="pt-2 border-t border-gray-200 dark:border-gray-600 mt-2">
                                                                    <div class="font-semibold text-blue-600 dark:text-blue-400">Patient Info:</div>
                                                                    <div>{{ $assignment->patient->full_name }}</div>
                                                                    <div class="text-xs">{{ $assignment->patient->patient_no }}</div>
                                                                </div>
                                                            @endif
                                                            
                                                            @if($sensor)
                                                                <div class="pt-2 border-t border-gray-200 dark:border-gray-600 mt-2">
                                                                    <div class="font-semibold">Sensor ID:</div>
                                                                    <div class="text-xs">{{ $sensor->sensor_id }}</div>
                                                                    @if($sensor->alerts)
                                                                        <div class="mt-1 text-red-600 dark:text-red-400 text-xs">
                                                                            <i class="fa fa-exclamation-circle mr-1"></i>{{ $sensor->alerts }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12 text-center">
                    <i class="fa fa-bed text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Beds Available</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Add beds to start monitoring occupancy</p>
                    <a href="{{ route('hms.beds.create') }}" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fa fa-plus mr-2"></i> Add Bed
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto-refresh every 30 seconds
        setInterval(() => {
            window.location.reload();
        }, 30000);
    </script>
</x-app-layout>

