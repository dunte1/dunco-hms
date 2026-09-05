<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\AmbulanceCall;
use App\Models\EmergencyAdmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmbulanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ambulance::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vehicle_number', 'like', "%{$search}%")
                  ->orWhere('driver_name', 'like', "%{$search}%")
                  ->orWhere('driver_phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('vehicle_type', $request->type);
        }
        
        // Filter by availability
        if ($request->filled('status')) {
            $query->where('is_available', $request->status === 'available');
        }
        
        $ambulances = $query->orderBy('vehicle_number')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Ambulance::count(),
            'available' => Ambulance::where('is_available', true)->count(),
            'in_use' => Ambulance::where('is_available', false)->count(),
            'basic' => Ambulance::where('vehicle_type', 'basic')->count(),
            'advanced' => Ambulance::where('vehicle_type', 'advanced')->count(),
            'critical_care' => Ambulance::where('vehicle_type', 'critical_care')->count(),
        ];
        
        return view('hms.ambulance.index', compact('ambulances', 'stats'));
    }

    public function calls(Request $request): View
    {
        $query = AmbulanceCall::with('ambulance');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('call_number', 'like', "%{$search}%")
                  ->orWhere('caller_name', 'like', "%{$search}%")
                  ->orWhere('caller_phone', 'like', "%{$search}%")
                  ->orWhere('pickup_address', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $calls = $query->latest('call_time')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => AmbulanceCall::count(),
            'pending' => AmbulanceCall::where('status', 'pending')->count(),
            'dispatched' => AmbulanceCall::where('status', 'dispatched')->count(),
            'completed' => AmbulanceCall::where('status', 'completed')->count(),
            'today' => AmbulanceCall::whereDate('call_time', today())->count(),
        ];
        
        return view('hms.ambulance.calls', compact('calls', 'stats'));
    }

    public function emergency(Request $request): View
    {
        $query = EmergencyAdmission::with(['patient', 'ambulance']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                  ->orWhere('patient_name', 'like', "%{$search}%")
                  ->orWhere('patient_phone', 'like', "%{$search}%")
                  ->orWhere('chief_complaint', 'like', "%{$search}%");
            });
        }
        
        // Filter by triage level
        if ($request->filled('triage')) {
            $query->where('triage_level', $request->triage);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $emergencies = $query->latest('admission_time')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => EmergencyAdmission::count(),
            'critical' => EmergencyAdmission::where('triage_level', 'critical')->count(),
            'urgent' => EmergencyAdmission::where('triage_level', 'urgent')->count(),
            'active' => EmergencyAdmission::where('status', 'active')->count(),
            'today' => EmergencyAdmission::whereDate('admission_time', today())->count(),
        ];
        
        return view('hms.ambulance.emergency', compact('emergencies', 'stats'));
    }

    public function createAmbulance(): View
    {
        return view('hms.ambulance.create-ambulance');
    }

    public function storeAmbulance(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_number' => 'required|string|unique:ambulances,vehicle_number',
            'driver_name' => 'required|string',
            'driver_phone' => 'required|string',
            'vehicle_type' => 'required|in:basic,advanced,critical_care',
            'equipment_list' => 'nullable|string',
        ]);

        Ambulance::create($data);
        return redirect()->route('hms.ambulance.index')->with('status', 'Ambulance added');
    }

    public function createCall(): View
    {
        $ambulances = Ambulance::where('is_available', true)->get(['id', 'vehicle_number', 'driver_name']);
        return view('hms.ambulance.create-call', compact('ambulances'));
    }

    public function storeCall(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'caller_name' => 'required|string',
            'caller_phone' => 'required|string',
            'pickup_address' => 'required|string',
            'destination_address' => 'required|string',
            'patient_condition' => 'required|string',
            'call_time' => 'required|date',
        ]);

        $data['call_number'] = 'AMB-' . date('Y') . '-' . str_pad(AmbulanceCall::count() + 1, 6, '0', STR_PAD_LEFT);

        AmbulanceCall::create($data);
        return redirect()->route('hms.ambulance.calls')->with('status', 'Ambulance call recorded');
    }

    public function createEmergency(): View
    {
        $ambulances = Ambulance::where('is_available', true)->get(['id', 'vehicle_number']);
        $patients = \App\Models\Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.ambulance.create-emergency', compact('ambulances', 'patients'));
    }

    public function storeEmergency(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'patient_name' => 'required|string',
            'patient_phone' => 'nullable|string',
            'ambulance_id' => 'nullable|exists:ambulances,id',
            'admission_time' => 'required|date',
            'triage_level' => 'required|in:critical,urgent,semi_urgent,non_urgent',
            'chief_complaint' => 'required|string',
            'vital_signs' => 'nullable|string',
            'initial_assessment' => 'nullable|string',
        ]);

        $data['admission_number'] = 'EMR-' . date('Y') . '-' . str_pad(EmergencyAdmission::count() + 1, 6, '0', STR_PAD_LEFT);

        EmergencyAdmission::create($data);
        return redirect()->route('hms.ambulance.emergency')->with('status', 'Emergency admission recorded');
    }

    public function showAmbulance(Ambulance $ambulance): View
    {
        return view('hms.ambulance.show-ambulance', compact('ambulance'));
    }

    public function editAmbulance(Ambulance $ambulance): View
    {
        return view('hms.ambulance.edit-ambulance', compact('ambulance'));
    }

    public function updateAmbulance(Request $request, Ambulance $ambulance): RedirectResponse
    {
        $data = $request->validate([
            'vehicle_number' => 'required|string|unique:ambulances,vehicle_number,' . $ambulance->id,
            'driver_name' => 'required|string',
            'driver_phone' => 'required|string',
            'vehicle_type' => 'required|in:basic,advanced,critical_care',
            'equipment_list' => 'nullable|string',
        ]);

        $ambulance->update($data);
        return redirect()->route('hms.ambulance.index')->with('status', 'Ambulance updated');
    }

    public function destroyAmbulance(Ambulance $ambulance): RedirectResponse
    {
        $ambulance->delete();
        return redirect()->route('hms.ambulance.index')->with('status', 'Ambulance deleted');
    }

    public function showCall(AmbulanceCall $call): View
    {
        $call->load('ambulance');
        return view('hms.ambulance.show-call', compact('call'));
    }

    public function editCall(AmbulanceCall $call): View
    {
        $ambulances = Ambulance::where('is_available', true)->get(['id', 'vehicle_number', 'driver_name']);
        return view('hms.ambulance.edit-call', compact('call', 'ambulances'));
    }

    public function updateCall(Request $request, AmbulanceCall $call): RedirectResponse
    {
        $data = $request->validate([
            'ambulance_id' => 'required|exists:ambulances,id',
            'caller_name' => 'required|string',
            'caller_phone' => 'required|string',
            'pickup_address' => 'required|string',
            'destination_address' => 'required|string',
            'patient_condition' => 'required|string',
            'call_time' => 'required|date',
        ]);

        $call->update($data);
        return redirect()->route('hms.ambulance.calls')->with('status', 'Ambulance call updated');
    }

    public function destroyCall(AmbulanceCall $call): RedirectResponse
    {
        $call->delete();
        return redirect()->route('hms.ambulance.calls')->with('status', 'Ambulance call deleted');
    }

    public function showEmergency(EmergencyAdmission $emergency): View
    {
        $emergency->load(['patient', 'ambulance']);
        return view('hms.ambulance.show-emergency', compact('emergency'));
    }

    public function editEmergency(EmergencyAdmission $emergency): View
    {
        $ambulances = Ambulance::where('is_available', true)->get(['id', 'vehicle_number']);
        $patients = \App\Models\Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        return view('hms.ambulance.edit-emergency', compact('emergency', 'ambulances', 'patients'));
    }

    public function updateEmergency(Request $request, EmergencyAdmission $emergency): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'patient_name' => 'required|string',
            'patient_phone' => 'nullable|string',
            'ambulance_id' => 'nullable|exists:ambulances,id',
            'admission_time' => 'required|date',
            'triage_level' => 'required|in:critical,urgent,semi_urgent,non_urgent',
            'chief_complaint' => 'required|string',
            'vital_signs' => 'nullable|string',
            'initial_assessment' => 'nullable|string',
        ]);

        $emergency->update($data);
        return redirect()->route('hms.ambulance.emergency')->with('status', 'Emergency admission updated');
    }

    public function destroyEmergency(EmergencyAdmission $emergency): RedirectResponse
    {
        $emergency->delete();
        return redirect()->route('hms.ambulance.emergency')->with('status', 'Emergency admission deleted');
    }
}
