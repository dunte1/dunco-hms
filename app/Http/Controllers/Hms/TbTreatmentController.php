<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\TbCase;
use App\Models\TbTreatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TbTreatmentController extends Controller
{
    public function store(Request $request, TbCase $case): RedirectResponse
    {
        $data = $request->validate([
            'regimen' => 'required|in:2RHZE,4RHZE,2RHZ,4RH',
            'phase' => 'required|in:intensive,continuation',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'weight_kg' => 'nullable|numeric|min:0',
            'drugs_given' => 'nullable|array',
            'prescribed_by' => 'required|exists:doctors,id',
        ]);

        $data['tb_case_id'] = $case->id;
        $data['status'] = 'active';

        TbTreatment::create($data);

        $case->update([
            'treatment_start_date' => $data['start_date'],
            'status' => 'active',
        ]);

        return back()->with('status', 'TB treatment started');
    }

    public function complete(TbTreatment $treatment): RedirectResponse
    {
        $treatment->update([
            'status' => 'completed',
            'end_date' => now()->toDateString(),
        ]);

        $case = $treatment->tbCase;
        $allCompleted = $case->treatments()->where('status', '!=', 'completed')->count() === 0;

        if ($allCompleted) {
            $case->update([
                'status' => 'treatment_completed',
                'treatment_end_date' => now()->toDateString(),
            ]);
        }

        return back()->with('status', 'TB treatment marked as completed');
    }
}
