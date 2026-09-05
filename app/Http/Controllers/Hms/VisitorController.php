<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function index(): View
    {
        $visitors = VisitorLog::with('patient')
            ->latest('check_in_time')
            ->paginate(20);

        // Statistics
        $todayVisitors = VisitorLog::whereDate('check_in_time', today())->count();
        $checkedIn = VisitorLog::whereNull('check_out_time')->count();
        $checkedOut = VisitorLog::whereNotNull('check_out_time')
            ->whereDate('check_out_time', today())
            ->count();

        return view('hms.visitors.index', compact('visitors', 'todayVisitors', 'checkedIn', 'checkedOut'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        return view('hms.visitors.create', compact('patients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:20',
            'visitor_email' => 'nullable|email|max:255',
            'visitor_id_number' => 'nullable|string|max:50',
            'visitor_type' => 'required|in:family,friend,representative,delivery,other',
            'patient_id' => 'nullable|exists:patients,id',
            'patient_name' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Generate badge number
        $todayCount = VisitorLog::whereDate('check_in_time', today())->count();
        $data['badge_number'] = 'VIS-' . date('Ymd') . '-' . str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
        $data['check_in_time'] = now();
        $data['status'] = 'checked_in';

        // If patient_id is provided, fetch patient name
        if (!empty($data['patient_id'])) {
            $patient = Patient::find($data['patient_id']);
            $data['patient_name'] = $patient->first_name . ' ' . $patient->last_name;
        }

        VisitorLog::create($data);

        return redirect()->route('hms.visitors.index')
            ->with('success', "Visitor checked in successfully. Badge: {$data['badge_number']}");
    }

    public function show(VisitorLog $visitor): View
    {
        $visitor->load('patient');
        return view('hms.visitors.show', compact('visitor'));
    }

    public function edit(VisitorLog $visitor): View
    {
        $visitor->load('patient');
        $patients = Patient::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'patient_no']);
        return view('hms.visitors.edit', compact('visitor', 'patients'));
    }

    public function update(Request $request, VisitorLog $visitor): RedirectResponse
    {
        $data = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:20',
            'visitor_email' => 'nullable|email|max:255',
            'visitor_id_number' => 'nullable|string|max:50',
            'visitor_type' => 'required|in:family,friend,representative,delivery,other',
            'patient_id' => 'nullable|exists:patients,id',
            'patient_name' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if (!empty($data['patient_id'])) {
            $patient = Patient::find($data['patient_id']);
            $data['patient_name'] = $patient->first_name . ' ' . $patient->last_name;
        }

        $visitor->update($data);
        return redirect()->route('hms.visitors.index')->with('success', 'Visitor log updated');
    }

    public function checkOut(Request $request, VisitorLog $visitor): RedirectResponse
    {
        if ($visitor->check_out_time) {
            return redirect()->route('hms.visitors.index')
                ->with('error', 'Visitor already checked out');
        }

        $visitor->update([
            'check_out_time' => now(),
            'status' => 'checked_out',
        ]);

        return redirect()->route('hms.visitors.index')
            ->with('success', 'Visitor checked out successfully');
    }

    public function printBadge(VisitorLog $visitor): View
    {
        return view('hms.visitors.badge', compact('visitor'));
    }

    public function analytics(): View
    {
        // Daily visitor statistics
        $dailyStats = VisitorLog::selectRaw('DATE(check_in_time) as date, COUNT(*) as count')
            ->whereBetween('check_in_time', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Visitor type statistics
        $typeStats = VisitorLog::selectRaw('visitor_type, COUNT(*) as count')
            ->groupBy('visitor_type')
            ->get()
            ->pluck('count', 'visitor_type');

        // Department statistics
        $departmentStats = VisitorLog::selectRaw('department, COUNT(*) as count')
            ->whereNotNull('department')
            ->groupBy('department')
            ->get()
            ->pluck('count', 'department');

        // Average visit duration
        $averageDuration = VisitorLog::whereNotNull('check_out_time')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, check_in_time, check_out_time)) as avg_minutes')
            ->value('avg_minutes');

        return view('hms.visitors.analytics', compact(
            'dailyStats',
            'typeStats',
            'departmentStats',
            'averageDuration'
        ));
    }

    public function destroy(VisitorLog $visitor): RedirectResponse
    {
        $visitor->delete();

        return redirect()->route('hms.visitors.index')
            ->with('success', 'Visitor log deleted');
    }
}

