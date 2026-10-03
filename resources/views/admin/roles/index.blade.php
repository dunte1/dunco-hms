@extends('layouts.app')

@section('title', 'Roles & Permissions Management')

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Roles & Permissions</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Roles grouped by hospital department (OPD, Triage, Maternity, etc.)</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('hms.hospital-departments.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fa fa-building mr-2"></i> Manage Departments
                </a>
                <button onclick="openCreateModal()" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fa fa-plus mr-2"></i> Create New Role
                </button>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg">{{ session('error') }}</div>
    @endif

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <i class="fa fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Roles</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $roles->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <i class="fa fa-building text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Departments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $departments->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <i class="fa fa-shield-alt text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Permissions</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \Spatie\Permission\Models\Permission::count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <i class="fa fa-user-check text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Assigned Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::whereHas('roles')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 p-4">
        <input type="text" id="searchRoles" placeholder="Search roles..."
               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
    </div>

    <!-- Roles grouped by department -->
    @forelse($orderedGroups as $departmentName => $departmentRoles)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6 department-group">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <div class="flex items-center">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg mr-3">
                    <i class="fa fa-building text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $departmentName }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $departmentRoles->count() }} role(s)</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Permissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($departmentRoles as $role)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 role-row" data-role-name="{{ strtolower($role->name) }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($role->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $role->name }}
                                        @if($role->name === 'Super Admin')
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">System</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $role->permissions->count() }} permissions
                                </span>
                                @if($role->permissions->count() > 0)
                                <button onclick="viewPermissions({{ $role->id }})" class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    <i class="fa fa-eye text-xs"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ $role->users->count() }} users
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <button onclick="viewRole({{ $role->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @if($role->name !== 'Super Admin')
                                <button onclick="deleteRole({{ $role->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
        <i class="fa fa-users text-5xl text-gray-400 mb-4"></i>
        <p class="text-lg text-gray-600 dark:text-gray-400">No roles found. Create your first role to get started.</p>
    </div>
    @endforelse
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Create New Role</h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <form id="createRoleForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role Name</label>
                <input type="text" name="name" required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                <select name="department_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Unassigned</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
                <div class="max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($permissions as $category => $categoryPermissions)
                        <div class="border rounded-lg p-3">
                            <h6 class="font-semibold text-gray-900 dark:text-white mb-2">{{ ucfirst($category) }}</h6>
                            <div class="space-y-2">
                                @foreach($categoryPermissions as $permission)
                                <label class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Role
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Role Modal -->
<div id="viewRoleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Role Details</h3>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <div id="viewRoleContent"></div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('searchRoles')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.role-row').forEach(row => {
        const roleName = row.getAttribute('data-role-name');
        row.style.display = roleName.includes(searchTerm) ? '' : 'none';
    });
    document.querySelectorAll('.department-group').forEach(group => {
        const visibleRows = group.querySelectorAll('.role-row:not([style*="display: none"])');
        group.style.display = visibleRows.length ? '' : 'none';
    });
});

function openCreateModal() {
    document.getElementById('createRoleModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createRoleModal').classList.add('hidden');
}

function closeViewModal() {
    document.getElementById('viewRoleModal').classList.add('hidden');
}

function viewRole(roleId) {
    document.getElementById('viewRoleContent').innerHTML = '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>';
    document.getElementById('viewRoleModal').classList.remove('hidden');

    fetch(`/admin/roles/${roleId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const role = data.data;
                let html = `
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Role Information</h4>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Name:</strong> ${role.name}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Department:</strong> ${role.hospital_department ? role.hospital_department.name : 'Unassigned'}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Created:</strong> ${new Date(role.created_at).toLocaleDateString()}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Permissions:</strong> ${role.permissions.length}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Permissions</h4>
                        <div class="flex flex-wrap gap-2">
                `;
                role.permissions.forEach(permission => {
                    html += `<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">${permission.name}</span>`;
                });
                html += '</div></div></div>';
                document.getElementById('viewRoleContent').innerHTML = html;
            }
        })
        .catch(error => {
            document.getElementById('viewRoleContent').innerHTML = '<div class="text-red-600">Error loading role details</div>';
        });
}

function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
        fetch(`/admin/roles/${roleId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Role deleted successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function viewPermissions(roleId) {
    viewRole(roleId);
}

document.getElementById('createRoleForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/admin/roles', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Role created successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to create role'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating role');
    });
});
</script>
@endpush
@endsection
