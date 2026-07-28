<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class RoleManagementController extends Controller
{
    public function index(): View
    {
        $roles = Role::with(['permissions', 'users'])->paginate(20);
        $permissions = Permission::all()->groupBy(function ($permission) {
            if (empty($permission->name)) {
                return 'other';
            }
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function create(): View
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            if (empty($permission->name)) {
                return 'other';
            }
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $data['name']]);

        if (isset($data['permissions']) && !empty($data['permissions'])) {
            // Convert permission IDs to Permission models
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => $role
        ], 201);
    }

    public function show(Role $role): JsonResponse
    {
        $role->load('permissions');
        
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');
        $permissions = Permission::all()->groupBy(function ($permission) {
            if (empty($permission->name)) {
                return 'other';
            }
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $data['name']]);

        if (isset($data['permissions']) && !empty($data['permissions'])) {
            // Convert permission IDs to Permission models
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }
        
        // Clear permission cache so all users with this role get updated permissions immediately
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully. All users with this role will automatically receive the updated permissions.',
            'data' => $role
        ]);
    }

    public function destroy(Role $role): JsonResponse
    {
        if ($role->name === 'Super Admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete Super Admin role'
            ], 403);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    public function assignRole(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = \App\Models\User::find($data['user_id']);
        $role = Role::find($data['role_id']);

        $user->assignRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully'
        ]);
    }

    public function removeRole(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = \App\Models\User::find($data['user_id']);
        $role = Role::find($data['role_id']);

        $user->removeRole($role);

        return response()->json([
            'success' => true,
            'message' => 'Role removed successfully'
        ]);
    }

    public function getUsersWithRole(Role $role): JsonResponse
    {
        $users = $role->users()->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function getRolePermissions(Role $role): JsonResponse
    {
        $permissions = $role->permissions;
        
        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    public function getAllPermissions(): JsonResponse
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            if (empty($permission->name)) {
                return 'other';
            }
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }
}
