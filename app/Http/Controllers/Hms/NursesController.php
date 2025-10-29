<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Nurse;
use App\Models\NurseDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NursesController extends Controller
{
    public function index(Request $request): View
    {
        $query = Nurse::with('department');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('nurse_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('nurse_department_id', $request->department);
        }
        
        // Filter by shift
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }
        
        $nurses = $query->orderBy('first_name')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Nurse::count(),
            'day_shift' => Nurse::where('shift', 'day')->count(),
            'night_shift' => Nurse::where('shift', 'night')->count(),
            'departments' => NurseDepartment::count(),
        ];
        
        $departments = NurseDepartment::orderBy('name')->get();
        
        return view('hms.nurses.index', compact('nurses', 'stats', 'departments'));
    }

    public function create(): View
    {
        $departments = NurseDepartment::orderBy('name')->pluck('name', 'id');
        return view('hms.nurses.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:nurses,email',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'nurse_department_id' => 'required|exists:nurse_departments,id',
            'qualification' => 'required|string',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'address' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'shift' => 'required|in:day,night,rotating',
            'notes' => 'nullable|string',
        ]);

        $data['nurse_id'] = 'NUR-' . date('Y') . '-' . str_pad(Nurse::count() + 1, 4, '0', STR_PAD_LEFT);

        Nurse::create($data);
        return redirect()->route('hms.nurses.index')->with('success', 'Nurse registered successfully!');
    }
    
    public function show(Nurse $nurse): View
    {
        $nurse->load('department');
        return view('hms.nurses.show', compact('nurse'));
    }
    
    public function edit(Nurse $nurse): View
    {
        $departments = NurseDepartment::orderBy('name')->pluck('name', 'id');
        return view('hms.nurses.edit', compact('nurse', 'departments'));
    }
    
    public function update(Request $request, Nurse $nurse): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:nurses,email,' . $nurse->id,
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'nurse_department_id' => 'required|exists:nurse_departments,id',
            'qualification' => 'required|string',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'address' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'shift' => 'required|in:day,night,rotating',
            'notes' => 'nullable|string',
        ]);

        $nurse->update($data);
        return redirect()->route('hms.nurses.show', $nurse)->with('success', 'Nurse information updated successfully!');
    }
    
    public function destroy(Nurse $nurse): RedirectResponse
    {
        $nurse->delete();
        return redirect()->route('hms.nurses.index')->with('success', 'Nurse deleted successfully!');
    }

    public function departments(): View
    {
        $departments = NurseDepartment::withCount('nurses')->orderBy('name')->paginate(10);
        return view('hms.nurses.departments', compact('departments'));
    }

    public function storeDepartment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:nurse_departments,name',
            'description' => 'nullable|string',
        ]);

        NurseDepartment::create($data);
        return redirect()->route('hms.nurses.departments')->with('status', 'Department created');
    }
    
    public function dutyRoster(Request $request): View
    {
        $query = Nurse::with('department');
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('nurse_department_id', $request->department);
        }
        
        // Filter by shift
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }
        
        $nurses = $query->orderBy('first_name')->paginate(20)->withQueryString();
        
        // Statistics
        $stats = [
            'total_nurses' => Nurse::count(),
            'day_shift' => Nurse::where('shift', 'day')->count(),
            'night_shift' => Nurse::where('shift', 'night')->count(),
            'rotating' => Nurse::where('shift', 'rotating')->count(),
        ];
        
        $departments = NurseDepartment::orderBy('name')->get();
        
        return view('hms.nurses.duty-roster', compact('nurses', 'stats', 'departments'));
    }
    
    public function assignWards(Request $request): View
    {
        $query = Nurse::with('department');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('nurse_id', 'like', "%{$search}%");
            });
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('nurse_department_id', $request->department);
        }
        
        $nurses = $query->orderBy('first_name')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total_nurses' => Nurse::count(),
            'assigned' => 0, // Placeholder - you can add actual ward assignment tracking
            'unassigned' => Nurse::count(),
            'departments' => NurseDepartment::count(),
        ];
        
        $departments = NurseDepartment::orderBy('name')->get();
        
        return view('hms.nurses.assign-wards', compact('nurses', 'stats', 'departments'));
    }
}
