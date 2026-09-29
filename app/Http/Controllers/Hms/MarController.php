<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\MedicationAdministration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarController extends Controller
{
    public function index(Request $request, IpdAdmission $ipd): View
    {
        $medications = MedicationAdministration::where('ipd_admission_id', $ipd->id)
            ->with(['medicine', 'administrator', 'prescription'])
            ->latest('administered_at')
            ->paginate(15);

        return view('hms.ipd.mar', compact('ipd', 'medications'));
    }

    public function store(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $data = $request->validate([
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'prescription_item_id' => 'nullable|exists:prescription_items,id',
            'medicine_id' => 'required|exists:medicines,id',
            'dose' => 'nullable|string',
            'route' => 'required|in:oral,iv,im,sc,topical',
            'administered_at' => 'required|date',
            'status' => 'required|in:scheduled,administered,skipped,refused',
            'notes' => 'nullable|string',
        ]);

        $data['ipd_admission_id'] = $ipd->id;
        $data['patient_id'] = $ipd->patient_id;
        $data['administered_by'] = auth()->id();

        MedicationAdministration::create($data);

        return back()->with('success', 'Medication administration recorded successfully!');
    }
}
