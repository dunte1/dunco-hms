<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <a href="{{ route('hms.nurses.index') }}" class="hover:text-blue-600">Nurses</a>
                        <i class="fa fa-chevron-right text-xs"></i>
                        <span>Nurse Profile</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-user-nurse text-pink-600 mr-3"></i>{{ $nurse->first_name }} {{ $nurse->last_name }}</h1>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('hms.nurses.edit', $nurse) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"><i class="fa fa-edit mr-1"></i> Edit</a>
                    <a href="{{ route('hms.nurses.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"><i class="fa fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Nurse Profile</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Full Name:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $nurse->first_name }} {{ $nurse->last_name }}</span></div>
                            <div><span class="text-gray-500">Phone:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $nurse->phone ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Email:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $nurse->email ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Qualification:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $nurse->qualification ?? 'N/A' }}</span></div>
                            <div><span class="text-gray-500">Specialization:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $nurse->specialization ?? 'N/A' }}</span></div>
                            @if($nurse->nurseDepartment)
                                <div><span class="text-gray-500">Department:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $nurse->nurseDepartment->name }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('hms.nurses.duty-roster') }}" class="block w-full px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-center rounded-lg text-sm"><i class="fa fa-calendar mr-2"></i>Duty Roster</a>
                            <a href="{{ route('hms.nurses.assign-wards') }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg text-sm"><i class="fa fa-bed mr-2"></i>Assign Wards</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
