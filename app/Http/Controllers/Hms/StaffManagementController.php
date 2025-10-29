<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Receptionist;
use App\Models\Pharmacist;
use App\Models\LabTechnician;
use App\Models\Accountant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffManagementController extends Controller
{
    // Receptionists
    public function receptionists(Request $request): View
    {
        $query = Receptionist::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('receptionist_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by shift
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }
        
        $receptionists = $query->orderBy('first_name')->paginate(15)->withQueryString();
        
        // Statistics
        $stats = [
            'total' => Receptionist::count(),
            'day_shift' => Receptionist::where('shift', 'day')->count(),
            'night_shift' => Receptionist::where('shift', 'night')->count(),
            'added_this_month' => Receptionist::whereMonth('created_at', now()->month)->count(),
        ];
        
        return view('hms.staff.receptionists', compact('receptionists', 'stats'));
    }

    public function createReceptionist(): View
    {
        return view('hms.staff.create-receptionist');
    }

    public function storeReceptionist(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:receptionists,email',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'shift' => 'required|in:day,night,rotating',
            'notes' => 'nullable|string',
        ]);

        $data['receptionist_id'] = 'REC-' . date('Y') . '-' . str_pad(Receptionist::count() + 1, 4, '0', STR_PAD_LEFT);

        Receptionist::create($data);
        return redirect()->route('hms.staff.receptionists')->with('success', 'Receptionist registered successfully!');
    }
    
    public function showReceptionist(Receptionist $receptionist): View
    {
        return view('hms.staff.show-receptionist', compact('receptionist'));
    }
    
    public function editReceptionist(Receptionist $receptionist): View
    {
        return view('hms.staff.edit-receptionist', compact('receptionist'));
    }
    
    public function updateReceptionist(Request $request, Receptionist $receptionist): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:receptionists,email,' . $receptionist->id,
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'shift' => 'required|in:day,night,rotating',
            'notes' => 'nullable|string',
        ]);

        $receptionist->update($data);
        return redirect()->route('hms.staff.receptionists.show', $receptionist)->with('success', 'Receptionist information updated successfully!');
    }
    
    public function destroyReceptionist(Receptionist $receptionist): RedirectResponse
    {
        $receptionist->delete();
        return redirect()->route('hms.staff.receptionists')->with('success', 'Receptionist deleted successfully!');
    }

    // Pharmacists
    public function pharmacists(): View
    {
        $pharmacists = Pharmacist::orderBy('first_name')->paginate(10);
        return view('hms.staff.pharmacists', compact('pharmacists'));
    }

    public function createPharmacist(): View
    {
        return view('hms.staff.create-pharmacist');
    }

    public function storePharmacist(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:pharmacists,email',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'qualification' => 'required|string',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'shift' => 'required|in:day,night,rotating',
            'notes' => 'nullable|string',
        ]);

        $data['pharmacist_id'] = 'PHAR-' . date('Y') . '-' . str_pad(Pharmacist::count() + 1, 4, '0', STR_PAD_LEFT);

        Pharmacist::create($data);
        return redirect()->route('hms.staff.pharmacists')->with('status', 'Pharmacist registered');
    }

    // Lab Technicians
    public function labTechnicians(): View
    {
        $technicians = LabTechnician::orderBy('first_name')->paginate(10);
        return view('hms.staff.lab-technicians', compact('technicians'));
    }

    public function createLabTechnician(): View
    {
        return view('hms.staff.create-lab-technician');
    }

    public function storeLabTechnician(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:lab_technicians,email',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'qualification' => 'required|string',
            'specialization' => 'nullable|string',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'shift' => 'required|in:day,night,rotating',
            'notes' => 'nullable|string',
        ]);

        $data['technician_id'] = 'LAB-' . date('Y') . '-' . str_pad(LabTechnician::count() + 1, 4, '0', STR_PAD_LEFT);

        LabTechnician::create($data);
        return redirect()->route('hms.staff.lab-technicians')->with('status', 'Lab technician registered');
    }

    // Accountants
    public function accountants(): View
    {
        $accountants = Accountant::orderBy('first_name')->paginate(10);
        return view('hms.staff.accountants', compact('accountants'));
    }

    public function createAccountant(): View
    {
        return view('hms.staff.create-accountant');
    }

    public function storeAccountant(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:accountants,email',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'qualification' => 'required|string',
            'certification' => 'nullable|string',
            'license_number' => 'nullable|string',
            'license_expiry' => 'nullable|date',
            'joining_date' => 'required|date',
            'salary' => 'nullable|numeric|min:0',
            'department' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['accountant_id'] = 'ACC-' . date('Y') . '-' . str_pad(Accountant::count() + 1, 4, '0', STR_PAD_LEFT);

        Accountant::create($data);
        return redirect()->route('hms.staff.accountants')->with('status', 'Accountant registered');
    }
}
