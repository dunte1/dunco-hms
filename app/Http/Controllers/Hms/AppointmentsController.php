<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentsController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::with(['patient','doctor'])->latest('scheduled_at')->paginate(10);
        
        $todayCount = Appointment::whereDate('scheduled_at', today())->count();
        $pendingCount = Appointment::where('status', 'pending')->orWhere('status', 'scheduled')->count();
        $confirmedCount = Appointment::where('status', 'confirmed')->count();
        $cancelledCount = Appointment::where('status', 'cancelled')->count();
        
        return view('hms.appointments.index', compact('appointments', 'todayCount', 'pendingCount', 'confirmedCount', 'cancelledCount'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id','first_name','last_name','patient_no','phone']);
        $doctors = Doctor::with('department')->orderBy('first_name')->get();
        $departments = \App\Models\DoctorDepartment::orderBy('name')->get();
        
        return view('hms.appointments.create', compact('patients','doctors','departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'scheduled_at' => 'required|date|after:now',
            'appointment_type' => 'nullable|in:consultation,follow_up,emergency,checkup',
            'status' => 'nullable|in:scheduled,confirmed,cancelled,completed',
            'note' => 'nullable|string',
            'patient_name' => 'nullable|string',
            'patient_phone' => 'nullable|string',
        ]);
        
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'scheduled';
        }
        
        // Only include fields that exist in the table
        $appointmentData = [
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'scheduled_at' => $data['scheduled_at'],
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
        ];
        
        Appointment::create($appointmentData);
        return redirect()->route('hms.appointments.index')->with('success', 'Appointment scheduled successfully!');
    }
}


