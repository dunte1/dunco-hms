<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeDepartmentsController extends Controller
{
    public function index(): View
    {
        $departments = EmployeeDepartment::orderBy('name')->paginate(10);
        return view('hms.hr.departments.index', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:employee_departments,name',
            'description' => 'nullable|string'
        ]);
        EmployeeDepartment::create($data);
        return back()->with('success', 'Department created successfully!');
    }

    public function update(Request $request, EmployeeDepartment $department): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:employee_departments,name,' . $department->id,
            'description' => 'nullable|string'
        ]);
        
        $department->update($data);
        return back()->with('success', 'Department updated successfully!');
    }

    public function destroy(EmployeeDepartment $department): RedirectResponse
    {
        // Check if department has employees
        if ($department->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete department with employees assigned. Please reassign employees first.');
        }
        
        $department->delete();
        return back()->with('success', 'Department deleted successfully!');
    }

    public function show(EmployeeDepartment $department): View
    {
        return view('hms.hr.departments.show', compact('department'));
    }

    public function create(): View
    {
        return view('hms.hr.departments.create');
    }
}

