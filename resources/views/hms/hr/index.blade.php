@extends('admin.layouts.app')

@section('content')
    <div class="mb-4">
        @include('admin.partials.stats', ['stats' => $stats])
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Recent Attendance -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Today's Attendance</h3>
                @if($recentAttendance->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentAttendance as $attendance)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $attendance->employee->full_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $attendance->check_in ? $attendance->check_in->format('H:i') : 'Not checked in' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($attendance->status === 'present') bg-green-100 text-green-800
                                        @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                        @elseif($attendance->status === 'absent') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No attendance records for today</p>
                @endif
            </div>
        </div>

        <!-- Pending Leave Requests -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pending Leave Requests</h3>
                @if($pendingLeaves->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingLeaves as $leave)
                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $leave->employee->full_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $leave->leave_type }}</p>
                                    <p class="text-xs text-gray-500">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No pending leave requests</p>
                @endif
            </div>
        </div>

        <!-- Upcoming Birthdays -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Upcoming Birthdays</h3>
                @if($upcomingBirthdays->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingBirthdays as $employee)
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $employee->full_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $employee->position }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-blue-600">{{ $employee->date_of_birth->format('M d') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No upcoming birthdays</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('hms.hr.employees.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-900">Manage Employees</p>
                        <p class="text-xs text-blue-700">Add, edit, or view employees</p>
                    </div>
                </a>

                <a href="{{ route('hms.hr.attendance.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-900">Attendance</p>
                        <p class="text-xs text-green-700">Mark and view attendance</p>
                    </div>
                </a>

                <a href="{{ route('hms.hr.leave-requests.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-900">Leave Requests</p>
                        <p class="text-xs text-yellow-700">Manage leave applications</p>
                    </div>
                </a>

                <a href="{{ route('hms.hr.payrolls.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-900">Payroll</p>
                        <p class="text-xs text-purple-700">Manage employee payroll</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection


