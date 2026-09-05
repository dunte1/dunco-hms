<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Vaccine;
use App\Models\VaccinationRecord;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VaccinationController extends Controller
{
    public function index(): View
    {
        $vaccines = Vaccine::orderBy('name')->paginate(20);
        $recentRecords = VaccinationRecord::with(['patient', 'vaccine'])->orderByDesc('administered_at')->paginate(20);
        return view('hms.vaccination.index', compact('vaccines', 'recentRecords'));
    }

    public function storeVaccine(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'manufacturer' => 'nullable|string',
            'dose_count' => 'nullable|integer|min:1',
            'stock_quantity' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'batch_number' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
        ]);
        Vaccine::create($data);
        return back()->with('status', 'Vaccine added');
    }

    public function administer(): View
    {
        $patients = Patient::orderBy('first_name')->pluck(function ($p) { return $p->first_name . ' ' . $p->last_name; }, 'id');
        $vaccines = Vaccine::where('stock_quantity', '>', 0)->orderBy('name')->pluck('name', 'id');
        return view('hms.vaccination.administer', compact('patients', 'vaccines'));
    }

    public function storeAdministration(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'vaccine_id' => 'required|exists:vaccines,id',
            'dose_number' => 'required|integer|min:1',
            'site' => 'nullable|string',
            'batch_number' => 'nullable|string',
            'reaction_notes' => 'nullable|string',
            'next_dose_date' => 'nullable|date',
        ]);
        $data['administered_by'] = auth()->id();
        $data['administered_at'] = now();
        VaccinationRecord::create($data);

        $vaccine = Vaccine::find($data['vaccine_id']);
        if ($vaccine && $vaccine->stock_quantity > 0) {
            $vaccine->decrement('stock_quantity');
        }

        return redirect()->route('hms.vaccination.index')->with('status', 'Vaccination recorded');
    }
}
