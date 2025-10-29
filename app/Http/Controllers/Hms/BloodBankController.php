<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\BloodGroup;
use App\Models\BloodDonor;
use App\Models\BloodRequest;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BloodBankController extends Controller
{
    public function index(): View
    {
        // Get blood groups with donor counts
        $bloodGroups = BloodGroup::withCount('donors')->get();
        
        // Get recent blood requests
        $recentRequests = BloodRequest::with(['patient', 'bloodGroup'])
            ->latest()
            ->limit(5)
            ->get();
        
        $stats = [
            'total_donors' => BloodDonor::count(),
            'total_requests' => BloodRequest::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'completed_requests' => BloodRequest::where('status', 'completed')->count(),
        ];
        
        return view('hms.bloodbank.index', compact('bloodGroups', 'recentRequests', 'stats'));
    }

    public function stockLevels(): View
    {
        $bloodGroups = BloodGroup::withCount('donors')->get();
        
        $stats = [
            'total_donors' => BloodDonor::count(),
            'active_donors' => BloodDonor::where('status', 'active')->count(),
            'blood_types' => BloodGroup::count(),
        ];
        
        return view('hms.bloodbank.stock-levels', compact('bloodGroups', 'stats'));
    }

    public function donors(): View
    {
        $donors = BloodDonor::with('bloodGroup')->orderBy('first_name')->paginate(10);
        return view('hms.bloodbank.donors', compact('donors'));
    }

    public function requests(): View
    {
        $requests = BloodRequest::with(['patient', 'doctor', 'bloodGroup'])
            ->latest('created_at')
            ->paginate(10);
        return view('hms.bloodbank.requests', compact('requests'));
    }

    public function createDonor(): View
    {
        $bloodGroups = BloodGroup::orderBy('name')->pluck('name', 'id');
        return view('hms.bloodbank.create-donor', compact('bloodGroups'));
    }

    public function storeDonor(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:blood_donors,email',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'address' => 'required|string',
            'medical_history' => 'nullable|string',
        ]);

        $data['donor_id'] = 'DON-' . date('Y') . '-' . str_pad(BloodDonor::count() + 1, 4, '0', STR_PAD_LEFT);

        BloodDonor::create($data);
        return redirect()->route('hms.bloodbank.donors')->with('status', 'Blood donor registered');
    }

    public function createRequest(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $doctors = Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $bloodGroups = BloodGroup::orderBy('name')->pluck('name', 'id');
        return view('hms.bloodbank.create-request', compact('patients', 'doctors', 'bloodGroups'));
    }

    public function storeRequest(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'units_required' => 'required|integer|min:1',
            'reason' => 'required|string',
        ]);

        $data['request_number'] = 'BR-' . date('Y') . '-' . str_pad(BloodRequest::count() + 1, 6, '0', STR_PAD_LEFT);

        BloodRequest::create($data);
        return redirect()->route('hms.bloodbank.requests')->with('status', 'Blood request created');
    }
}
