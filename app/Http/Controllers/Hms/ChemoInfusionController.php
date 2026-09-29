<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ChemoCycle;
use App\Models\ChemoInfusion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChemoInfusionController extends Controller
{
    public function store(Request $request, ChemoCycle $cycle): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'start_time' => 'required|date',
            'drug_name' => 'required|string|max:255',
            'dose' => 'required|string|max:100',
            'volume_ml' => 'nullable|numeric|min:0',
            'rate' => 'nullable|numeric|min:0',
            'site' => 'required|in:arm,hand,port,other',
            'nurse_id' => 'required|exists:users,id',
            'complications' => 'nullable|string',
        ]);

        $data['chemo_cycle_id'] = $cycle->id;
        $data['status'] = 'running';

        ChemoInfusion::create($data);

        return redirect()->route('hms.oncology.plans.index')
            ->with('status', 'Infusion recorded.');
    }

    public function complete(Request $request, ChemoInfusion $infusion): RedirectResponse
    {
        $data = $request->validate([
            'end_time' => 'required|date',
            'complications' => 'nullable|string',
        ]);

        $infusion->update([
            'status' => 'completed',
            'end_time' => $data['end_time'],
            'complications' => $data['complications'] ?? null,
        ]);

        return redirect()->route('hms.oncology.plans.index')
            ->with('status', 'Infusion completed.');
    }
}
