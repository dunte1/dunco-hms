<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\FireEquipmentRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FireEquipmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'equipment_type' => 'required|in:extinguisher,alarm,hose,blanket,station',
            'location' => 'required|string|max:255',
            'serial_number' => 'nullable|string',
            'last_inspection_date' => 'nullable|date',
            'next_inspection_date' => 'nullable|date|after_or_equal:last_inspection_date',
            'status' => 'required|in:good,needs_maintenance,defective,expired',
            'notes' => 'nullable|string',
        ]);

        FireEquipmentRecord::create($data);

        return back()->with('status', 'Fire equipment record created.');
    }

    public function index(Request $request): View
    {
        $query = FireEquipmentRecord::query();

        if ($request->filled('equipment_type')) {
            $query->where('equipment_type', $request->equipment_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $equipment = $query->latest()->paginate(20);

        return view('hms.ohs.fire-equipment.index', compact('equipment'));
    }
}
