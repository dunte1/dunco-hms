<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\HospitalDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HospitalDepartmentsController extends Controller
{
    public function index(Request $request): View
    {
        $query = HospitalDepartment::withCount('roles');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $departments = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('hms.hospital-departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('hms.hospital-departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:hospital_departments,name',
            'code' => 'nullable|string|max:20|unique:hospital_departments,code',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        HospitalDepartment::create($data);

        return redirect()->route('hms.hospital-departments.index')
            ->with('status', 'Department created successfully');
    }

    public function show(HospitalDepartment $department): View
    {
        $department->load('roles');

        return view('hms.hospital-departments.show', compact('department'));
    }

    public function edit(HospitalDepartment $department): View
    {
        return view('hms.hospital-departments.edit', compact('department'));
    }

    public function update(Request $request, HospitalDepartment $department): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:hospital_departments,name,' . $department->id,
            'code' => 'nullable|string|max:20|unique:hospital_departments,code,' . $department->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $department->update($data);

        return redirect()->route('hms.hospital-departments.index')
            ->with('status', 'Department updated successfully');
    }

    public function destroy(HospitalDepartment $department): RedirectResponse
    {
        if ($department->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete department while roles are assigned to it. Reassign roles first.');
        }

        $department->delete();

        return redirect()->route('hms.hospital-departments.index')
            ->with('status', 'Department deleted');
    }
}
