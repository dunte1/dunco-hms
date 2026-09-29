<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MortuaryRecord;
use App\Models\Postmortem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostmortemController extends Controller
{
    public function store(Request $request, MortuaryRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'cause_of_death' => 'required|string',
            'manner_of_death' => 'required|in:natural,accident,suicide,homicide,undetermined',
            'performed_by' => 'nullable|exists:doctors,id',
            'requested_date' => 'required|date',
        ]);

        $data['mortuary_record_id'] = $record->id;
        $data['status'] = 'requested';

        Postmortem::create($data);

        return back()->with('status', 'Postmortem requested');
    }

    public function complete(Request $request, Postmortem $postmortem): RedirectResponse
    {
        $data = $request->validate([
            'findings' => 'required|string',
        ]);

        $postmortem->update([
            'findings' => $data['findings'],
            'completed_date' => now()->toDateString(),
            'status' => 'completed',
        ]);

        return back()->with('status', 'Postmortem completed');
    }
}
