<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\EmployeeDepartment;

class DesignationsController extends Controller
{
    /**
     * Display a listing of designations.
     */
    public function index()
    {
        $designations = Designation::orderBy('name')->paginate(15);
        $departments = EmployeeDepartment::orderBy('name')->get();
        
        return view('hms.hr.designations.index', compact('designations', 'departments'));
    }

    /**
     * Store a newly created designation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
            'description' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:50',
        ]);

        Designation::create($request->all());

        return redirect()->route('hms.hr.designations.index')
            ->with('success', 'Designation created successfully.');
    }

    /**
     * Update the specified designation.
     */
    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $designation->id,
            'description' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:50',
        ]);

        $designation->update($request->all());

        return redirect()->route('hms.hr.designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    /**
     * Remove the specified designation.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()->route('hms.hr.designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
