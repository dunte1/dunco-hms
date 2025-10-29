<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fa fa-user-tie text-blue-600 mr-3"></i>
                        Employee Details
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Complete employee information and records</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('hms.hr.employees.id-card', $employee) }}" target="_blank" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg">
                        <i class="fa fa-id-card mr-2"></i> ID Card
                    </a>
                    <a href="{{ route('hms.hr.employees.edit', $employee) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        <i class="fa fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('hms.hr.employees.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                        <i class="fa fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center" role="alert">
                    <i class="fa fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Employee Profile Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="text-center mb-6">
                            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                            </div>
                            <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ $employee->full_name }}</h2>
                            <p class="text-gray-600 dark:text-gray-400">Employee ID: {{ $employee->employee_id }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-2
                                {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : ($employee->status === 'inactive' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                <i class="fa fa-{{ $employee->status === 'active' ? 'check-circle' : ($employee->status === 'inactive' ? 'pause-circle' : 'times-circle') }} mr-1"></i>
                                {{ ucfirst($employee->status) }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-building text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Department</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $employee->department->name ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-briefcase text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Position</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $employee->position }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-calendar text-blue-600 mr-3"></i>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Hire Date</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $employee->hire_date->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Quick Actions</h3>
                            <div class="space-y-2">
                                <a href="{{ route('hms.hr.employees.id-card', $employee) }}" target="_blank" class="w-full flex items-center justify-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition">
                                    <i class="fa fa-id-card mr-2"></i> Download ID Card
                                </a>
                                <a href="{{ route('hms.hr.employees.edit', $employee) }}" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                    <i class="fa fa-edit mr-2"></i> Edit Employee
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employee Details & Records -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-user text-blue-600 mr-2"></i> Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Employee ID</label>
                                <div class="text-gray-900 dark:text-white font-mono">{{ $employee->employee_id }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Full Name</label>
                                <div class="text-gray-900 dark:text-white">{{ $employee->full_name }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Date of Birth</label>
                                <div class="text-gray-900 dark:text-white">
                                    @if($employee->date_of_birth)
                                        {{ $employee->date_of_birth->format('F d, Y') }}
                                        <span class="text-sm text-gray-500">({{ $employee->date_of_birth->age }} years old)</span>
                                    @else
                                        Not provided
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Gender</label>
                                <div class="text-gray-900 dark:text-white">{{ $employee->gender ? ucfirst($employee->gender) : 'Not specified' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Employment Type</label>
                                <div class="text-gray-900 dark:text-white">{{ ucfirst(str_replace('-', ' ', $employee->employment_type)) }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Status</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : ($employee->status === 'inactive' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-address-book text-blue-600 mr-2"></i> Contact Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Email Address</label>
                                <div class="flex items-center text-gray-900 dark:text-white">
                                    <i class="fa fa-envelope text-blue-600 mr-2"></i>
                                    {{ $employee->email ?: 'Not provided' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Phone Number</label>
                                <div class="flex items-center text-gray-900 dark:text-white">
                                    <i class="fa fa-phone text-blue-600 mr-2"></i>
                                    {{ $employee->phone ?: 'Not provided' }}
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Address</label>
                                <div class="flex items-start text-gray-900 dark:text-white">
                                    <i class="fa fa-map-marker-alt text-blue-600 mr-2 mt-1"></i>
                                    <span>{{ $employee->address ?: 'Not provided' }}</span>
                                </div>
                            </div>

                            @if($employee->emergency_contact)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Emergency Contact</label>
                                <div class="text-gray-900 dark:text-white">{{ $employee->emergency_contact }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Employment Information -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-briefcase text-blue-600 mr-2"></i> Employment Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Department</label>
                                <div class="text-gray-900 dark:text-white">{{ $employee->department->name ?? 'N/A' }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Position</label>
                                <div class="text-gray-900 dark:text-white">{{ $employee->position }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Hire Date</label>
                                <div class="text-gray-900 dark:text-white">{{ $employee->hire_date->format('F d, Y') }}</div>
                            </div>

                            @if($employee->termination_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Termination Date</label>
                                <div class="text-gray-900 dark:text-white">{{ $employee->termination_date->format('F d, Y') }}</div>
                            </div>
                            @endif

                            @if($employee->salary)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Salary</label>
                                <div class="text-gray-900 dark:text-white font-semibold">${{ number_format($employee->salary, 2) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Employee Records -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            <i class="fa fa-chart-line text-blue-600 mr-2"></i> Employee Records
                        </h3>
                        
                        <!-- Tabs -->
                        <div x-data="{ activeTab: 'attendance' }" class="mt-4">
                            <div class="flex border-b border-gray-200 dark:border-gray-700 mb-4 overflow-x-auto">
                                <button @click="activeTab = 'attendance'" 
                                        :class="activeTab === 'attendance' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="py-2 px-4 border-b-2 font-medium text-sm whitespace-nowrap">
                                    <i class="fa fa-calendar-check mr-1"></i> Attendance ({{ $attendance->count() }})
                                </button>
                                <button @click="activeTab = 'payrolls'" 
                                        :class="activeTab === 'payrolls' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="py-2 px-4 border-b-2 font-medium text-sm whitespace-nowrap">
                                    <i class="fa fa-money-bill-wave mr-1"></i> Payrolls ({{ $payrolls->count() }})
                                </button>
                                <button @click="activeTab = 'leaves'" 
                                        :class="activeTab === 'leaves' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="py-2 px-4 border-b-2 font-medium text-sm whitespace-nowrap">
                                    <i class="fa fa-calendar-times mr-1"></i> Leave Requests ({{ $leaveRequests->count() }})
                                </button>
                            </div>

                            <!-- Attendance Tab -->
                            <div x-show="activeTab === 'attendance'">
                                @if($attendance->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Check In</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Check Out</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Hours</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($attendance as $record)
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $record->attendance_date->format('M d, Y') }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('h:i A') : 'N/A' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('h:i A') : 'N/A' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $record->hours_worked ?? 'N/A' }}</td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <span class="px-2 py-1 text-xs rounded-full {{ $record->status === 'present' ? 'bg-green-100 text-green-800' : ($record->status === 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                            {{ ucfirst($record->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <i class="fa fa-calendar-times text-4xl mb-2"></i>
                                        <p>No attendance records found</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Payrolls Tab -->
                            <div x-show="activeTab === 'payrolls'">
                                @if($payrolls->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Period</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Basic Salary</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Allowances</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Deductions</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Net Salary</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($payrolls as $payroll)
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $payroll->pay_period }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${{ number_format($payroll->basic_salary ?? 0, 2) }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${{ number_format($payroll->allowances ?? 0, 2) }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${{ number_format($payroll->deductions ?? 0, 2) }}</td>
                                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($payroll->net_salary ?? 0, 2) }}</td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <span class="px-2 py-1 text-xs rounded-full {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ ucfirst($payroll->status ?? 'pending') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <i class="fa fa-money-bill-wave text-4xl mb-2"></i>
                                        <p>No payroll records found</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Leave Requests Tab -->
                            <div x-show="activeTab === 'leaves'">
                                @if($leaveRequests->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Start Date</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">End Date</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Days</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($leaveRequests as $leave)
                                                <tr>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ ucfirst($leave->leave_type ?? 'N/A') }}</td>
                                                    <td class="px-4 py-3 text-sm">
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : ($leave->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                            {{ ucfirst($leave->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <i class="fa fa-calendar-times text-4xl mb-2"></i>
                                        <p>No leave requests found</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

