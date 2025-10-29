<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorDepartment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DoctorChargesController extends Controller
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
                  ->orWhere('qualification', 'like', "%{$search}%");
            });
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('doctor_department_id', $request->department);
        }
        
        $doctors = $query->orderBy('first_name')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total_doctors' => Doctor::count(),
            'departments' => DoctorDepartment::count(),
            'specialists' => Doctor::whereHas('department')->count(),
        ];
        
        $departments = DoctorDepartment::orderBy('name')->get();
        
        return view('hms.doctor-charges.index', compact('doctors', 'stats', 'departments'));
    }
    
    public function edit(Doctor $doctor): View
    {
        $doctor->load('department');
        return view('hms.doctor-charges.edit', compact('doctor'));
    }
    
    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $data = $request->validate([
            'consultation_fee' => 'nullable|numeric|min:0',
            'followup_fee' => 'nullable|numeric|min:0',
            'emergency_fee' => 'nullable|numeric|min:0',
            'home_visit_fee' => 'nullable|numeric|min:0',
        ]);
        
        // For now, we'll store these in a JSON field or you can add columns to doctors table
        // This is a placeholder implementation
        $doctor->update($data);
        
        return redirect()->route('hms.doctor-charges.index')
            ->with('success', 'Doctor charges updated successfully!');
    }
}
