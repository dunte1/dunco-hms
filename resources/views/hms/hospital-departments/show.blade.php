<x-app-layout>
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <a href="{{ route('hms.hospital-departments.index') }}" class="hover:text-indigo-600">Hospital Departments</a>
                    <i class="fa fa-chevron-right text-xs"></i>
                    <span>{{ $department->name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><i class="fa fa-building text-indigo-600 mr-3"></i>{{ $department->name }}</h1>
                    <div class="flex gap-2">
                        <a href="{{ route('hms.hospital-departments.edit', $department) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Edit</a>
                        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Roles Page</a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Code</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $department->code ?: '—' }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-xl font-bold {{ $department->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $department->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                    <p class="text-sm text-gray-500">Roles Assigned</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $department->roles->count() }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Description</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ $department->description ?: 'No description provided.' }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Roles in this Department</h2>
                @if($department->roles->isEmpty())
                    <p class="text-gray-500">No roles assigned yet. Assign roles from the Roles & Permissions page.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($department->roles as $role)
                            <a href="{{ route('admin.roles.edit', $role) }}"
                               class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 text-sm hover:bg-indigo-200">
                                {{ $role->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
