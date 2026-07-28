<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\EmployeeShift;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShiftsController extends Controller
{
    /**
     * Display a listing of shifts.
     */
    public function index(): View
    {
        $shifts = Shift::orderBy('start_time')->paginate(15);
        
        return view('hms.hr.shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new shift.
     */
    public function create(): View
    {
        return view('hms.hr.shifts.create');
    }

    /**
     * Store a newly created shift.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Shift::create($validated);

        return redirect()->route('hms.hr.shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    /**
     * Display the specified shift.
     */
    public function show(Shift $shift): View
    {
        $shift->load('employeeShifts.employee');
        
        return view('hms.hr.shifts.show', compact('shift'));
    }

    /**
     * Show the form for editing the specified shift.
     */
    public function edit(Shift $shift): View
    {
        return view('hms.hr.shifts.edit', compact('shift'));
    }

    /**
     * Update the specified shift.
     */
    public function update(Request $request, Shift $shift): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $shift->update($validated);

        return redirect()->route('hms.hr.shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    /**
     * Remove the specified shift.
     */
    public function destroy(Shift $shift): RedirectResponse
    {
        if ($shift->employeeShifts()->count() > 0) {
            return redirect()->route('hms.hr.shifts.index')
                ->with('error', 'Cannot delete shift with assigned employees.');
        }

        $shift->delete();

        return redirect()->route('hms.hr.shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }

    /**
     * Display roster for a shift.
     */
    public function roster(Shift $shift, Request $request): View
    {
        $query = EmployeeShift::with('employee')->where('shift_id', $shift->id);
        
        if ($request->has('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->where(function($q) use ($request) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '<=', $request->date_to);
            });
        }
        
        $employeeShifts = $query->latest()->paginate(20);
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        return view('hms.hr.shifts.roster', compact('shift', 'employeeShifts', 'employees'));
    }

    /**
     * Assign shift to employees.
     */
    public function assignShift(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        foreach ($validated['employee_ids'] as $employeeId) {
            // Check for overlapping shifts
            $existing = EmployeeShift::where('employee_id', $employeeId)
                ->where('is_active', true)
                ->where(function($q) use ($validated) {
                    $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date'] ?? '9999-12-31'])
                      ->orWhere(function($q2) use ($validated) {
                          $q2->where('start_date', '<=', $validated['start_date'])
                             ->where(function($q3) use ($validated) {
                                 $q3->whereNull('end_date')
                                    ->orWhere('end_date', '>=', $validated['start_date']);
                             });
                      });
                })
                ->first();
            
            if ($existing) {
                // End the existing shift before the new one starts
                $existing->update([
                    'end_date' => date('Y-m-d', strtotime($validated['start_date'] . ' -1 day')),
                    'is_active' => false,
                ]);
            }
            
            EmployeeShift::create([
                'employee_id' => $employeeId,
                'shift_id' => $validated['shift_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'is_active' => true,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Shift assigned successfully.');
    }
}
