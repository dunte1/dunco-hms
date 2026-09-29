<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CalibrationRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalibrationController extends Controller
{
    public function index(): View
    {
        $calibrations = CalibrationRecord::with('equipment')
            ->orderByDesc('calibration_date')
            ->paginate(20);

        return view('hms.maintenance.calibrations.index', compact('calibrations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'equipment_id' => 'required|exists:medical_equipment,id',
            'calibration_date' => 'required|date',
            'next_due_date' => 'required|date|after:calibration_date',
            'result' => 'required|in:pass,fail,conditional',
            'certificate_number' => 'nullable|string',
            'performed_by_vendor' => 'boolean',
            'vendor_name' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        CalibrationRecord::create($data);

        return back()->with('status', 'Calibration record saved');
    }
}
