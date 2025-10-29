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
}


