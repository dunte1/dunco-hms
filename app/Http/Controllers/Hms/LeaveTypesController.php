<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaveTypesController extends Controller
{
    /**
     * Display a listing of leave types.
     */
    public function index(): View
    {
        $leaveTypes = LeaveType::orderBy('name')->paginate(15);
        
        return view('hms.hr.leave-types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new leave type.
     */
    public function create(): View
    {
        return view('hms.hr.leave-types.create');
    }

    /**
     * Store a newly created leave type.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'description' => 'nullable|string',
            'default_days' => 'required|integer|min:0',
            'carry_forward' => 'boolean',
            'requires_approval' => 'boolean',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        LeaveType::create($request->all());

        return redirect()->route('hms.hr.leave-types.index')
            ->with('success', 'Leave type created successfully.');
    }

    /**
     * Display the specified leave type.
     */
    public function show(LeaveType $leaveType): View
    {
        $leaveType->load('leaveRequests');
        
        return view('hms.hr.leave-types.show', compact('leaveType'));
    }

    /**
     * Show the form for editing the specified leave type.
     */
    public function edit(LeaveType $leaveType): View
    {
        return view('hms.hr.leave-types.edit', compact('leaveType'));
    }

    /**
     * Update the specified leave type.
     */
    public function update(Request $request, LeaveType $leaveType): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'description' => 'nullable|string',
            'default_days' => 'required|integer|min:0',
            'carry_forward' => 'boolean',
            'requires_approval' => 'boolean',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $leaveType->update($request->all());

        return redirect()->route('hms.hr.leave-types.index')
            ->with('success', 'Leave type updated successfully.');
    }

    /**
     * Remove the specified leave type.
     */
    public function destroy(LeaveType $leaveType): RedirectResponse
    {
        if ($leaveType->leaveRequests()->count() > 0) {
            return redirect()->route('hms.hr.leave-types.index')
                ->with('error', 'Cannot delete leave type with associated leave requests.');
        }

        $leaveType->delete();

        return redirect()->route('hms.hr.leave-types.index')
            ->with('success', 'Leave type deleted successfully.');
    }
}
