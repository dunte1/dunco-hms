<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AnaesthesiaDrugGiven;
use App\Models\AnaesthesiaRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnaesthesiaDrugController extends Controller
{
    public function store(Request $request, AnaesthesiaRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'dose' => 'required|string|max:255',
            'route' => 'required|in:iv,im,sc,inhalation,topical',
            'time_administered' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $data['anaesthesia_record_id'] = $record->id;

        AnaesthesiaDrugGiven::create($data);

        return redirect()->route('hms.ot.show', $record->ot_schedule_id)
            ->with('status', 'Drug administration recorded successfully');
    }
}
