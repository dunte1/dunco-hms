<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Ward;
use App\Models\EmployeeDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WardController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ward::with(['department', 'nurseInCharge']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ward_type')) {
            $query->where('ward_type', $request->ward_type);
        }

        if ($request->filled('is_active') !== null) {
            $query->where('is_active', $request->is_active);
        }

        $wards = $query->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total' => Ward::count(),
            'active' => Ward::where('is_active', true)->count(),
            'total_beds' => \App\Models\Bed::count(),
            'occupied_beds' => \App\Models\Bed::where('is_available', false)->count(),
        ];

        return view('hms.wards.index', compact('wards', 'stats'));
    }

    public function create(): View
    {
        $departments = EmployeeDepartment::orderBy('name')->get(['id', 'name']);
        return view('hms.wards.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:wards,name',
            'code' => 'required|string|max:20|unique:wards,code',
            'description' => 'nullable|string',
            'ward_type' => 'required|in:general,surgical,paediatric,maternity,icu,hdu,observation',
            'capacity' => 'required|integer|min:0',
            'department_id' => 'nullable|exists:employee_departments,id',
            'nurse_in_charge_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        Ward::create($data);

        return redirect()->route('hms.wards.index')->with('success', 'Ward created successfully!');
    }

    public function show(Ward $ward): View
    {
        $ward->load(['department', 'nurseInCharge', 'beds']);
        return view('hms.wards.show', compact('ward'));
    }

    public function edit(Ward $ward): View
    {
        $departments = EmployeeDepartment::orderBy('name')->get(['id', 'name']);
        return view('hms.wards.edit', compact('ward', 'departments'));
    }

    public function update(Request $request, Ward $ward): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:wards,name,' . $ward->id,
            'code' => 'required|string|max:20|unique:wards,code,' . $ward->id,
            'description' => 'nullable|string',
            'ward_type' => 'required|in:general,surgical,paediatric,maternity,icu,hdu,observation',
            'capacity' => 'required|integer|min:0',
            'department_id' => 'nullable|exists:employee_departments,id',
            'nurse_in_charge_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $ward->update($data);

        return redirect()->route('hms.wards.show', $ward)->with('success', 'Ward updated successfully!');
    }

    public function destroy(Ward $ward): RedirectResponse
    {
        if ($ward->beds()->count() > 0) {
            return back()->with('error', 'Cannot delete ward with existing beds. Remove or reassign beds first.');
        }

        $ward->delete();
        return redirect()->route('hms.wards.index')->with('success', 'Ward deleted successfully!');
    }
}
