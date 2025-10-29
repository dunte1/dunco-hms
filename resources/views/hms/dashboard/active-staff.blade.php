<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">👥 Active Staff Today</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center">
                        <i class="fa fa-print mr-2"></i> Print
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            <!-- Attendance Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Staff</p>
                            <p class="text-3xl font-bold mt-2">{{ $attendanceStats['total_staff'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Present Today</p>
                            <p class="text-3xl font-bold mt-2">{{ $attendanceStats['present_today'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-check-circle text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">On Leave</p>
                            <p class="text-3xl font-bold mt-2">{{ $attendanceStats['on_leave'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-calendar-days text-3xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Absent</p>
                            <p class="text-3xl font-bold mt-2">{{ $attendanceStats['absent'] }}</p>
                        </div>
                        <div class="p-4 bg-white bg-opacity-30 rounded-lg">
                            <i class="fa fa-times-circle text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff by Role -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
                <!-- Doctors -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fa fa-user-md text-blue-600 dark:text-blue-400"></i>
                        </div>
                        Doctors ({{ $staffByRole['doctors']->count() }})
                    </h2>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($staffByRole['doctors'] as $doctor)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-200 dark:bg-blue-800 rounded-full flex items-center justify-center text-blue-800 dark:text-blue-200 font-bold text-sm">
                                        {{ substr($doctor->name, 0, 1) }}
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $doctor->name }}</span>
                                </div>
                                @if($doctor->attendance->first())
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        $doctor->attendance->first()->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                        ($doctor->attendance->first()->status === 'leave' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') 
                                    }}">
                                        {{ ucfirst($doctor->attendance->first()->status) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                                        No record
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No doctors registered</p>
                        @endforelse
                    </div>
                </div>

                <!-- Nurses -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fa fa-user-nurse text-green-600 dark:text-green-400"></i>
                        </div>
                        Nurses ({{ $staffByRole['nurses']->count() }})
                    </h2>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($staffByRole['nurses'] as $nurse)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-200 dark:bg-green-800 rounded-full flex items-center justify-center text-green-800 dark:text-green-200 font-bold text-sm">
                                        {{ substr($nurse->name, 0, 1) }}
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $nurse->name }}</span>
                                </div>
                                @if($nurse->attendance->first())
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        $nurse->attendance->first()->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                        ($nurse->attendance->first()->status === 'leave' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') 
                                    }}">
                                        {{ ucfirst($nurse->attendance->first()->status) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                                        No record
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No nurses registered</p>
                        @endforelse
                    </div>
                </div>

                <!-- Receptionists -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fa fa-user-tie text-purple-600 dark:text-purple-400"></i>
                        </div>
                        Receptionists ({{ $staffByRole['receptionists']->count() }})
                    </h2>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($staffByRole['receptionists'] as $receptionist)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-200 dark:bg-purple-800 rounded-full flex items-center justify-center text-purple-800 dark:text-purple-200 font-bold text-sm">
                                        {{ substr($receptionist->name, 0, 1) }}
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $receptionist->name }}</span>
                                </div>
                                @if($receptionist->attendance->first())
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        $receptionist->attendance->first()->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                        ($receptionist->attendance->first()->status === 'leave' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') 
                                    }}">
                                        {{ ucfirst($receptionist->attendance->first()->status) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                                        No record
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No receptionists registered</p>
                        @endforelse
                    </div>
                </div>

                <!-- Lab Technicians -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fa fa-flask text-orange-600 dark:text-orange-400"></i>
                        </div>
                        Lab Technicians ({{ $staffByRole['lab_technicians']->count() }})
                    </h2>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($staffByRole['lab_technicians'] as $tech)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-orange-200 dark:bg-orange-800 rounded-full flex items-center justify-center text-orange-800 dark:text-orange-200 font-bold text-sm">
                                        {{ substr($tech->name, 0, 1) }}
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $tech->name }}</span>
                                </div>
                                @if($tech->attendance->first())
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        $tech->attendance->first()->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                        ($tech->attendance->first()->status === 'leave' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') 
                                    }}">
                                        {{ ucfirst($tech->attendance->first()->status) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                                        No record
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No lab technicians registered</p>
                        @endforelse
                    </div>
                </div>

                <!-- Pharmacists -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-3">
                            <i class="fa fa-pills text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        Pharmacists ({{ $staffByRole['pharmacists']->count() }})
                    </h2>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @forelse($staffByRole['pharmacists'] as $pharmacist)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-indigo-200 dark:bg-indigo-800 rounded-full flex items-center justify-center text-indigo-800 dark:text-indigo-200 font-bold text-sm">
                                        {{ substr($pharmacist->name, 0, 1) }}
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $pharmacist->name }}</span>
                                </div>
                                @if($pharmacist->attendance->first())
                                    <span class="px-2 py-1 text-xs rounded-full {{ 
                                        $pharmacist->attendance->first()->status === 'present' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                        ($pharmacist->attendance->first()->status === 'leave' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') 
                                    }}">
                                        {{ ucfirst($pharmacist->attendance->first()->status) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-600 dark:bg-gray-600 dark:text-gray-300">
                                        No record
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No pharmacists registered</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Check-ins -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fa fa-clock text-blue-600 mr-2"></i>
                    Recent Check-ins
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Staff Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check-in Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentCheckIns as $checkIn)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-800 dark:text-blue-200 font-bold">
                                                {{ substr($checkIn->user->name ?? 'N/A', 0, 1) }}
                                            </div>
                                            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $checkIn->user->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                        <i class="fa fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($checkIn->check_in)->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            {{ ucfirst($checkIn->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fa fa-clipboard-check text-4xl mb-2"></i>
                                        <p>No check-ins recorded today</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

