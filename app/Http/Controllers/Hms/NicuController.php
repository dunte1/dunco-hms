<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Newborn;
use App\Models\NicuAdmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NicuController extends Controller
{
    public function store(Request $request, Newborn $newborn): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'admission_date' => 'required|date',
            'reason' => 'required|string',
            'admission_weight_grams' => 'required|integer|min:1',
        ]);

        $data['newborn_id'] = $newborn->id;
        $data['admitted_by'] = auth()->id();
        $data['status'] = 'active';

        NicuAdmission::create($data);

        $newborn->update(['status' => 'nicu']);

        return redirect()->route('hms.neonatal.newborns.index')
            ->with('status', 'NICU admission recorded.');
    }

    public function discharge(Request $request, NicuAdmission $admission): RedirectResponse
    {
        $data = $request->validate([
            'discharge_date' => 'required|date',
            'discharge_weight_grams' => 'required|integer|min:1',
            'discharge_destination' => 'nullable|string|max:255',
        ]);

        $admission->update(array_merge($data, ['status' => 'discharged']));

        $newborn = $admission->newborn;
        $newborn->update(['status' => 'discharged']);

        return redirect()->route('hms.neonatal.newborns.index')
            ->with('status', 'NICU discharge recorded.');
    }
}
