<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BodyIdentification;
use App\Models\MortuaryRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BodyIdentificationController extends Controller
{
    public function store(Request $request, MortuaryRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'identifier_name' => 'required|string',
            'identifier_relationship' => 'nullable|string',
            'identification_method' => 'required|in:visual,photo,belongings,fingerprint,dna',
            'notes' => 'nullable|string',
        ]);

        $data['mortuary_record_id'] = $record->id;
        $data['identified_at'] = now();
        $data['identified_by'] = auth()->id();

        BodyIdentification::create($data);

        return back()->with('status', 'Body identification recorded');
    }
}
