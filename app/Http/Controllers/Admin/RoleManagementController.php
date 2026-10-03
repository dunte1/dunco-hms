<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HospitalDepartment;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class RoleManagementController extends Controller
{
    private function permissionGroups()
    {
        return Permission::all()->groupBy(function ($permission) {
            if (empty($permission->name)) {
                return 'other';
            }
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
    }

    public function index(): View
    {
        $departments = HospitalDepartment::orderBy('name')->get();
        $roles = Role::with(['permissions', 'users'])
            ->orderBy('department_id')
            ->orderBy('name')
            ->get();

        $rolesByDepartment = $roles->groupBy(function ($role) use ($departments) {
            $department = $departments->firstWhere('id', $role->department_id);
            return $department ? $department->name : 'Unassigned';
        });

        $orderedGroups = collect();
        foreach ($departments as $department) {
            if ($rolesByDepartment->has($department->name)) {
                $orderedGroups->put($department->name, $rolesByDepartment->get($department->name));
            }
        }
        if ($rolesByDepartment->has('Unassigned')) {
            $orderedGroups->put('Unassigned', $rolesByDepartment->get('Unassigned'));
        }

        $permissions = $this->permissionGroups();

        return view('admin.roles.index', compact('roles', 'permissions', 'departments', 'orderedGroups'));
    }

    public function create(): View
    {
        $permissions = $this->permissionGroups();
        $departments = HospitalDepartment::orderBy('name')->get();

        return view('admin.roles.create', compact('permissions', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'department_id' => 'nullable|exists:hospital_departments,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->forceFill(['department_id' => $data['department_id'] ?? null])->save();

        $permissionIds = $data['permissions'] ?? [];
        if (!empty($permissionIds)) {
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'data' => $role->fresh()->load('permissions')
            ], 201);
        }

        return redirect()->route('admin.roles.index')
            ->with('status', 'Role created successfully');
    }

    public function show($role): JsonResponse
    {
        $role = Role::findOrFail(is_numeric($role) ? $role : $role->getKey());
        $role->load(['permissions', 'hospitalDepartment']);

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function edit($role): View
    {
        $role = Role::findOrFail(is_numeric($role) ? $role : $role->getKey());
        $role->load('permissions');
        $permissions = $this->permissionGroups();
        $departments = HospitalDepartment::orderBy('name')->get();

        return view('admin.roles.edit', compact('role', 'permissions', 'departments'));
    }

    public function update(Request $request, $role)
    {
        $role = Role::findOrFail(is_numeric($role) ? $role : $role->getKey());

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->getKey(),
            'department_id' => 'nullable|exists:hospital_departments,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->forceFill([
            'name' => $data['name'],
            'department_id' => $data['department_id'] ?? null,
        ])->save();

        $permissionIds = $data['permissions'] ?? [];
        if (!empty($permissionIds)) {
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully. All users with this role will automatically receive the updated permissions.',
                'data' => $role->fresh()->load('permissions')
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('status', 'Role updated successfully');
    }

    public function destroy(Request $request, $role)
    {
        $role = Role::findOrFail(is_numeric($role) ? $role : $role->getKey());

        if ($role->name === 'Super Admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete Super Admin role'
                ], 403);
            }

            return back()->with('error', 'Cannot delete Super Admin role');
        }

        $role->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ]);
        }

        return redirect()->route('admin.roles.index')
            ->with('status', 'Role deleted successfully');
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
        $permissions = $this->permissionGroups();

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }
}
