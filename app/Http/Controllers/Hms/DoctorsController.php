<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Doctor::with('department');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('qualification', 'like', "%{$search}%");
            });
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('doctor_department_id', $request->department);
        }
        
        $doctors = $query->latest()->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Doctor::count(),
            'departments' => DoctorDepartment::count(),
            'average_experience' => round(Doctor::avg('years_experience') ?? 0, 1),
            'added_this_month' => Doctor::whereMonth('created_at', now()->month)->count(),
        ];
        
        $departments = DoctorDepartment::orderBy('name')->get();
        
        return view('hms.doctors.index', compact('doctors', 'stats', 'departments'));
    }

    public function create(): View
    {
        $departments = DoctorDepartment::orderBy('name')->pluck('name','id');
        return view('hms.doctors.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'doctor_department_id' => 'nullable|exists:doctor_departments,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'years_experience' => 'nullable|integer|min:0|max:70',
        ]);
        
        Doctor::create($data);
        return redirect()->route('hms.doctors.index')->with('success', 'Doctor registered successfully!');
    }
    
    public function show(Doctor $doctor): View
    {
        $doctor->load('department');
        return view('hms.doctors.show', compact('doctor'));
    }
    
    public function edit(Doctor $doctor): View
    {
        $departments = DoctorDepartment::orderBy('name')->pluck('name','id');
        return view('hms.doctors.edit', compact('doctor', 'departments'));
    }
    
    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'doctor_department_id' => 'nullable|exists:doctor_departments,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'years_experience' => 'nullable|integer|min:0|max:70',
        ]);
        
        $doctor->update($data);
        return redirect()->route('hms.doctors.show', $doctor)->with('success', 'Doctor information updated successfully!');
    }
    
    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->delete();
        return redirect()->route('hms.doctors.index')->with('success', 'Doctor deleted successfully!');
    }
}


