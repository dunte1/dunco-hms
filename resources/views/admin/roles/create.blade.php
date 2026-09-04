@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="container-fluid py-6">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                <i class="fa-solid fa-shield-halved text-indigo-600 mr-3"></i> Create New Role
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Define a role and assign permissions</p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Role Details</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="e.g., Doctor, Nurse, Admin">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Brief description of this role">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border p-6 mb-6" x-data="{ selectAll: false }">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Permissions</h2>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" x-model="selectAll" @change="
                            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = selectAll)"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">Select All</span>
                    </label>
                </div>

                @php
                    $permissionGroups = [
                        'Users' => ['view-users', 'create-users', 'edit-users', 'delete-users'],
                        'Patients' => ['view-patients', 'create-patients', 'edit-patients', 'delete-patients'],
                        'Appointments' => ['view-appointments', 'create-appointments', 'edit-appointments', 'delete-appointments'],
                        'Billing' => ['view-billing', 'create-invoices', 'process-payments'],
                        'Pharmacy' => ['view-pharmacy', 'manage-medicines', 'dispense-medicines'],
                        'Laboratory' => ['view-lab', 'create-lab-requests', 'manage-lab-results'],
                        'HR' => ['view-employees', 'manage-attendance', 'manage-payroll', 'manage-leaves'],
                        'Reports' => ['view-reports', 'export-reports', 'create-reports'],
                        'Settings' => ['manage-settings', 'manage-backups', 'view-audit-logs'],
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($permissionGroups as $group => $permissions)
                        <div class="border dark:border-gray-700 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-3">{{ $group }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($permissions as $permission)
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission }}"
                                            {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}
                                            class="permission-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ ucwords(str_replace('-', ' ', $permission)) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fa-solid fa-plus mr-2"></i> Create Role
                </button>
                <a href="{{ route('admin.roles.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
