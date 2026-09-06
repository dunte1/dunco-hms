<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.hr.leave-requests.index') }}" class="hover:text-blue-600">Leave Requests</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>Details</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-calendar-alt text-green-600 mr-3"></i>Leave Request Details</h1>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2"></div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $leaveRequest->employee->first_name ?? 'N/A' }} {{ $leaveRequest->employee->last_name ?? '' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Leave Type</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $leaveRequest->leave_type ?? $leaveRequest->leaveType->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Start Date</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">End Date</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-{{ $leaveRequest->status === 'approved' ? 'green' : ($leaveRequest->status === 'rejected' ? 'red' : 'yellow') }}-100 text-{{ $leaveRequest->status === 'approved' ? 'green' : ($leaveRequest->status === 'rejected' ? 'red' : 'yellow') }}-800">{{ ucfirst($leaveRequest->status) }}</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Days</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-white">{{ $leaveRequest->total_days ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Reason</h3>
                        <p class="mt-1 text-gray-900 dark:text-white">{{ $leaveRequest->reason ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('hms.hr.leave-requests.edit', $leaveRequest) }}" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium"><i class="fa fa-edit mr-2"></i> Edit</a>
                        <a href="{{ route('hms.hr.leave-requests.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
