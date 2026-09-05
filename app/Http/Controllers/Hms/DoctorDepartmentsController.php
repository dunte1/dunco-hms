<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\DoctorDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorDepartmentsController extends Controller
{
    public function index(): View
    {
        $departments = DoctorDepartment::orderBy('name')->paginate(10);
        return view('hms.doctors.departments.index', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:doctor_departments,name',
            'description' => 'nullable|string'
        ]);
        DoctorDepartment::create($data);
        return back()->with('status', 'Department created');
    }

    public function show(DoctorDepartment $department): View
    {
        return view('hms.doctors.departments.show', compact('department'));
    }

    public function edit(DoctorDepartment $department): View
    {
        return view('hms.doctors.departments.edit', compact('department'));
    }

    public function update(Request $request, DoctorDepartment $department): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:doctor_departments,name,' . $department->id,
            'description' => 'nullable|string'
        ]);
        $department->update($data);
        return back()->with('status', 'Department updated');
    }

    public function destroy(DoctorDepartment $department): RedirectResponse
    {
        $department->delete();
        return back()->with('status', 'Department deleted');
    }
}


