<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BloodUnit;
use App\Models\Transfusion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BloodBankApiController extends ApiController
{
    public function storeUnit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'blood_inventory_id' => 'required|exists:blood_inventory,id',
            'donation_id' => 'nullable|exists:blood_donations,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'volume_ml' => 'required|integer|min:100|max:1000',
            'expiry_date' => 'required|date|after:today',
        ]);

        $validated['unit_number'] = BloodUnit::generateUnitNumber();
        $validated['status'] = 'available';

        $unit = BloodUnit::create($validated);

        return $this->created($unit, 'Blood unit created successfully');
    }

    public function storeTransfusion(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'blood_issue_id' => 'required|exists:blood_issues,id',
            'patient_id' => 'required|exists:patients,id',
            'blood_unit_id' => 'required|exists:blood_units,id',
        ]);

        $validated['started_at'] = now();
        $validated['performed_by'] = $request->user()->id;
        $validated['status'] = 'in_progress';

        $transfusion = Transfusion::create($validated);

        BloodUnit::where('id', $validated['blood_unit_id'])->update(['status' => 'issued']);

        return $this->created($transfusion->load(['patient', 'bloodUnit']), 'Transfusion started successfully');
    }
}
