@extends('layouts.app')

@section('title', 'Edit Role - ' . $role->name)

@section('content')
<div class="container-fluid py-6">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Role</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Update role name and permissions</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                <i class="fa fa-arrow-left mr-2"></i> Back to Roles
            </a>
        </div>
    </div>

    <!-- Edit Role Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            <form id="editRoleForm" method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role Name</label>
                    <input type="text" name="name" value="{{ old('name', $role->name) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
                    <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-200 mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Auto-Update:</strong> When you save changes here, ALL users with the "{{ $role->name }}" role will automatically receive the updated permissions. 
                            No need to update users individually!
                        </p>
                    </div>
                    <div class="max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($permissions as $category => $categoryPermissions)
                                <div class="border rounded-lg p-3">
                                    <h6 class="font-semibold text-gray-900 dark:text-white mb-2">{{ ucfirst($category) }}</h6>
                                    <div class="space-y-2">
                                        @foreach($categoryPermissions as $permission)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                                       {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
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
                    @error('permissions')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.roles.index') }}" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fa fa-save mr-2"></i> Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Role Info Card -->
    <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Role Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Users with this role</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $role->users->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Permissions</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $role->permissions->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Created</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $role->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Update role form
document.getElementById('editRoleForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route('admin.roles.update', $role) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Role updated successfully!\n\n' + (data.message || 'All users with this role will automatically receive the updated permissions.'));
            window.location.href = '{{ route('admin.roles.index') }}';
        } else {
            alert('Error: ' + (data.message || 'Failed to update role'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating role');
    });
});
</script>
@endpush
@endsection

