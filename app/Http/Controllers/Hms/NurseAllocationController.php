<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\NurseAllocation;
use App\Models\Ward;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NurseAllocationController extends Controller
{
    public function index(Request $request): View
    {
        $query = NurseAllocation::with(['nurseUser', 'ward', 'shift', 'allocatedBy']);

        if ($request->filled('ward_id')) {
            $query->where('ward_id', $request->ward_id);
        }
        if ($request->filled('shift_type')) {
            $query->where('shift_type', $request->shift_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('allocated_date')) {
            $query->where('allocated_date', $request->allocated_date);
        }

        $allocations = $query->latest('allocated_date')->paginate(20)->withQueryString();
        $wards = Ward::where('is_active', true)->orderBy('name')->get();

        return view('hms.nursing.allocations.index', compact('allocations', 'wards'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nurse_user_id' => 'required|exists:users,id',
            'ward_id' => 'required|exists:wards,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'allocated_date' => 'required|date',
            'shift_type' => 'required|in:day,night',
            'bed_range_start' => 'nullable|integer|min:0',
            'bed_range_end' => 'nullable|integer|gte:bed_range_start',
            'patient_count' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'active';
        $data['allocated_by'] = auth()->id();

        NurseAllocation::create($data);

        return back()->with('success', 'Nurse allocated to ward successfully!');
    }
}
