<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UsersManagementController extends Controller
{
    /**
     * Display a listing of users and employees.
     */
    public function index(): View
    {
        // Get all users with their employee relationships
        $users = User::with(['roles', 'employee'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all employees (including those without user accounts)
        // Show all employees regardless of status to show complete staff list
        $employees = Employee::with(['user', 'department'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Merge and combine: Users with employees + Employees without users
        $allStaff = collect();
        
        // Add all users (they may or may not have employee records)
        foreach ($users as $user) {
            $employee = $user->employee;
            $allStaff->push([
                'type' => 'user',
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles,
                'employee' => $employee,
                'employee_id' => $employee ? $employee->employee_id : null,
                'department' => $employee && $employee->department ? $employee->department->name : null,
                'position' => $employee ? $employee->position : null,
                'status' => $user->status ?? 'pending', // Use actual status field
                'created_at' => $user->created_at,
                'user_id' => $user->id,
                'is_employee' => $employee ? true : false,
            ]);
        }
        
        // Add employees that don't have user accounts
        foreach ($employees as $employee) {
            if (!$employee->user_id) {
                $allStaff->push([
                    'type' => 'employee',
                    'id' => $employee->id,
                    'name' => $employee->full_name,
                    'email' => $employee->email ?? 'N/A',
                    'roles' => collect([]),
                    'employee' => $employee,
                    'employee_id' => $employee->employee_id,
                    'department' => $employee->department ? $employee->department->name : 'N/A',
                    'position' => $employee->position,
                    'status' => $employee->status,
                    'created_at' => $employee->created_at,
                    'user_id' => null,
                    'employee_record_id' => $employee->id,
                    'is_employee' => true,
                ]);
            }
        }
        
        // Sort by created_at descending
        $allStaff = $allStaff->sortByDesc('created_at')->values();
        
        // Paginate manually
        $currentPage = request()->get('page', 1);
        $perPage = 15;
        $currentItems = $allStaff->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedStaff = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allStaff->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('hms.system.users.index', [
            'users' => $paginatedStaff,
            'total_users' => $users->count(),
            'total_employees' => $employees->count(),
            'total_staff' => $allStaff->count(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('hms.system.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string',
            'status' => 'nullable|in:active,pending,inactive',
            'auto_verify_email' => 'nullable|boolean',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status' => $validated['status'] ?? 'pending',
        ];

        // If auto-verify is checked, verify email and activate
        if ($request->has('auto_verify_email') && $request->auto_verify_email) {
            $userData['email_verified_at'] = now();
            if ($userData['status'] === 'pending') {
                $userData['status'] = 'active';
                $userData['approved_at'] = now();
                $userData['approved_by'] = auth()->id();
            }
        }

        $user = User::create($userData);

        if ($validated['role']) {
            $user->assignRole($validated['role']);
        }

        return redirect()->route('hms.system.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $user->load(['roles', 'approver']);
        return view('hms.system.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $user->load(['roles', 'approver']);
        return view('hms.system.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string',
            'status' => 'nullable|in:active,pending,inactive',
            'status_notes' => 'nullable|string|max:1000',
            'auto_verify_email' => 'nullable|boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Handle password update
        if ($validated['password']) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        // Handle status update (manual admin approval)
        if (isset($validated['status'])) {
            $updateData['status'] = $validated['status'];
            $updateData['status_notes'] = $validated['status_notes'] ?? null;
            
            // Track who approved (if activating) and when
            if ($validated['status'] === 'active' && $user->status !== 'active') {
                $updateData['approved_at'] = now();
                $updateData['approved_by'] = auth()->id();
            }
        }

        // Handle email verification
        if ($request->has('auto_verify_email') && $request->auto_verify_email && !$user->email_verified_at) {
            $updateData['email_verified_at'] = now();
            // Auto-activate if status is pending
            if (!isset($updateData['status']) && $user->status === 'pending') {
                $updateData['status'] = 'active';
                if (!$user->approved_at) {
                    $updateData['approved_at'] = now();
                    $updateData['approved_by'] = auth()->id();
                }
            }
        }

        $user->update($updateData);

        if ($validated['role']) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->route('hms.system.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deletion of the current user
        if ($user->id === auth()->id()) {
            return redirect()->route('hms.system.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('hms.system.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Show permissions management page for a user
     */
    public function permissions(User $user): View
    {
        $user->load(['roles', 'permissions']);
        
        // Get all roles
        $allRoles = Role::orderBy('name')->get();
        
        // Get all permissions grouped by category
        $allPermissions = Permission::all()->groupBy(function ($permission) {
            if (empty($permission->name)) {
                return 'other';
            }
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        // Get user's current permissions (via roles + direct)
        $userPermissions = $user->getAllPermissions()->pluck('id')->toArray();
        
        // Get permissions via roles
        $permissionsViaRoles = $user->getPermissionsViaRoles()->pluck('id')->toArray();
        
        // Get direct permissions
        $directPermissions = $user->getDirectPermissions()->pluck('id')->toArray();
        
        return view('hms.system.users.permissions', compact(
            'user', 
            'allRoles', 
            'allPermissions', 
            'userPermissions',
            'permissionsViaRoles',
            'directPermissions'
        ));
    }

    /**
     * Update user roles
     */
    public function updateRoles(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roleIds = $validated['roles'] ?? [];
        $roles = Role::whereIn('id', $roleIds)->get();
        
        // Sync roles (this automatically updates permissions)
        $user->syncRoles($roles);
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('hms.system.users.permissions', $user)
            ->with('success', 'Roles updated successfully! Permissions will be automatically updated based on role permissions.');
    }

    /**
     * Update user direct permissions
     */
    public function updatePermissions(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionIds = $validated['permissions'] ?? [];
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        
        // Sync direct permissions
        $user->syncPermissions($permissions);
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('hms.system.users.permissions', $user)
            ->with('success', 'Direct permissions updated successfully!');
    }
}
