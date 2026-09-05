<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MedicalEquipment;
use App\Models\MaintenanceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentMaintenanceController extends Controller
{
    public function index(): View
    {
        $equipment = MedicalEquipment::orderBy('name')->paginate(20);
        $upcomingMaintenance = MedicalEquipment::where('next_maintenance', '<=', now()->addDays(7))
            ->where('status', '!=', 'retired')
            ->orderBy('next_maintenance')
            ->get();
        $stats = [
            'total' => MedicalEquipment::count(),
            'operational' => MedicalEquipment::where('status', 'operational')->count(),
            'maintenance' => MedicalEquipment::where('status', 'maintenance')->count(),
            'out_of_service' => MedicalEquipment::where('status', 'out_of_service')->count(),
        ];
        return view('hms.equipment.index', compact('equipment', 'upcomingMaintenance', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'required|in:diagnostic,therapeutic,surgical,life_support,laboratory,other',
            'department' => 'nullable|string',
            'model_number' => 'nullable|string',
            'serial_number' => 'required|string|unique:medical_equipment,serial_number',
            'manufacturer' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'location' => 'nullable|string',
            'current_value' => 'nullable|numeric|min:0',
        ]);
        $data['status'] = 'operational';
        MedicalEquipment::create($data);
        return back()->with('status', 'Equipment registered');
    }

    public function show(MedicalEquipment $equipment): View
    {
        $equipment->load('maintenanceLogs.performedByUser');
        return view('hms.equipment.show', compact('equipment'));
    }

    public function logMaintenance(Request $request, MedicalEquipment $equipment): RedirectResponse
    {
        $data = $request->validate([
            'maintenance_type' => 'required|in:preventive,corrective,calibration,emergency',
            'description' => 'nullable|string',
            'parts_replaced' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'next_action' => 'nullable|string',
            'next_due_date' => 'nullable|date',
        ]);
        $data['equipment_id'] = $equipment->id;
        $data['performed_by'] = auth()->id();
        $data['performed_at'] = now();
        $data['status'] = 'completed';
        MaintenanceLog::create($data);

        $equipment->update([
            'last_maintenance' => now(),
            'next_maintenance' => $data['next_due_date'] ?? null,
            'status' => 'operational',
        ]);

        return back()->with('status', 'Maintenance logged');
    }

    public function updateStatus(Request $request, MedicalEquipment $equipment): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:operational,maintenance,out_of_service,retired',
        ]);
        $equipment->update($data);
        return back()->with('status', 'Equipment status updated');
    }
}
