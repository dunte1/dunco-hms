<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\InpatientTransfer;
use App\Models\Bed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function store(Request $request, IpdAdmission $ipd): RedirectResponse
    {
        $data = $request->validate([
            'from_ward_id' => 'required|exists:wards,id',
            'to_ward_id' => 'required|exists:wards,id|different:from_ward_id',
            'from_bed_id' => 'nullable|exists:beds,id',
            'to_bed_id' => 'nullable|exists:beds,id',
            'reason' => 'nullable|string',
            'transferred_at' => 'required|date',
        ]);

        $data['ipd_admission_id'] = $ipd->id;
        $data['patient_id'] = $ipd->patient_id;
        $data['transferred_by'] = auth()->id();

        // Release old bed
        if ($ipd->bed_id) {
            Bed::where('id', $ipd->bed_id)->update(['is_available' => true]);
        }

        // Update admission to new ward/bed
        $ipd->update([
            'ward_id' => $data['to_ward_id'],
            'bed_id' => $data['to_bed_id'] ?? $ipd->bed_id,
            'status' => 'admitted',
        ]);

        // Occupy new bed
        if (!empty($data['to_bed_id'])) {
            Bed::where('id', $data['to_bed_id'])->update(['is_available' => false]);
        }

        InpatientTransfer::create($data);

        return back()->with('success', 'Patient transferred successfully!');
    }
}
