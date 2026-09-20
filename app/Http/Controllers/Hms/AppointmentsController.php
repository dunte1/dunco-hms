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
        $query = Appointment::with(['patient', 'doctor.department']);

        if (request('status')) {
            $query->where('status', request('status'));
        }
        if (request('doctor_id')) {
            $query->where('doctor_id', request('doctor_id'));
        }
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('patient_no', 'like', "%{$search}%");
                });
            });
        }

        $appointments = $query->latest('scheduled_at')->paginate(15)->withQueryString();

        $todayCount = Appointment::whereDate('scheduled_at', today())->count();
        $pendingCount = Appointment::whereIn('status', ['pending', 'scheduled'])->count();
        $confirmedCount = Appointment::where('status', 'confirmed')->count();
        $cancelledCount = Appointment::where('status', 'cancelled')->count();

        return view('hms.appointments.index', compact('appointments', 'todayCount', 'pendingCount', 'confirmedCount', 'cancelledCount'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no', 'phone']);
        $doctors = Doctor::with('department')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'doctor_department_id', 'consultation_fee']);
        $departments = \App\Models\DoctorDepartment::orderBy('name')->get();
        $defaultConsultationFee = (float) \App\Models\SystemSetting::get('consultation_fee', 0);

        return view('hms.appointments.create', compact('patients', 'doctors', 'departments', 'defaultConsultationFee'));
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
            'consultation_fee' => 'nullable|numeric|min:0',
            'billing_mode' => 'required|in:sha,mpesa,cash,none',
            'payment_id' => 'nullable|integer|exists:payments,id',
        ]);

        if (!isset($data['status'])) {
            $data['status'] = 'scheduled';
        }

        $fee = (float) ($data['consultation_fee'] ?? 0);
        $mpesa = app(\App\Services\MpesaService::class);

        if ($fee > 0) {
            if ($data['billing_mode'] === 'mpesa') {
                if (empty($data['payment_id']) || !$mpesa->hasConfirmedPayment($data['patient_id'], $data['payment_id'], $fee)) {
                    return back()->withErrors(['payment_id' => 'A completed M-Pesa payment for the consultation fee is required before scheduling the appointment.'])
                        ->withInput();
                }
            } elseif ($data['billing_mode'] === 'sha') {
                $coverage = $mpesa->coverage(Patient::findOrFail($data['patient_id']), 'consultation_fee');
                if (!$coverage['covered']) {
                    return back()->withErrors(['billing_mode' => 'This patient is not covered under SHA for consultation. Please collect M-Pesa or cash payment instead.'])
                        ->withInput();
                }
            }
        } else {
            $data['billing_mode'] = 'none';
        }

        $appointment = Appointment::create([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'scheduled_at' => $data['scheduled_at'],
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
            'consultation_fee' => $fee > 0 ? $fee : null,
            'payment_id' => ($data['billing_mode'] === 'mpesa' && !empty($data['payment_id'])) ? $data['payment_id'] : null,
        ]);

        if ($data['billing_mode'] === 'mpesa') {
            $mpesa->attachSource($data['payment_id'], 'appointment', $appointment->id);
        }

        return redirect()->route('hms.appointments.index')
            ->with('success', 'Appointment scheduled successfully!');
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['patient', 'doctor.department']);

        return view('hms.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): View
    {
        $appointment->load(['patient', 'doctor.department']);
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no', 'phone']);
        $doctors = Doctor::with('department')->orderBy('first_name')->get();
        $departments = \App\Models\DoctorDepartment::orderBy('name')->get();

        return view('hms.appointments.edit', compact('appointment', 'patients', 'doctors', 'departments'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'scheduled_at' => 'required|date',
            'appointment_type' => 'nullable|in:consultation,follow_up,emergency,checkup',
            'status' => 'required|in:scheduled,confirmed,cancelled,completed',
            'note' => 'nullable|string',
        ]);

        $appointment->update([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'scheduled_at' => $data['scheduled_at'],
            'status' => $data['status'],
            'note' => $data['note'] ?? null,
        ]);

        return redirect()->route('hms.appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();
        return redirect()->route('hms.appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }
}
