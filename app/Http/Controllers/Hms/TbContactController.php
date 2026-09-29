<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\TbCase;
use App\Models\TbContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TbContactController extends Controller
{
    public function store(Request $request, TbCase $case): RedirectResponse
    {
        $data = $request->validate([
            'contact_name' => 'required|string',
            'contact_phone' => 'nullable|string',
            'contact_relationship' => 'required|string',
        ]);

        $data['tb_case_id'] = $case->id;

        TbContact::create($data);

        return back()->with('status', 'Contact added for tracing');
    }

    public function screen(Request $request, TbContact $contact): RedirectResponse
    {
        $data = $request->validate([
            'screening_result' => 'required|in:positive,negative',
            'hts_encounter_id' => 'nullable|exists:hts_encounters,id',
        ]);

        $contact->update([
            'screened' => true,
            'screening_result' => $data['screening_result'],
            'screened_date' => now()->toDateString(),
            'hts_encounter_id' => $data['hts_encounter_id'] ?? null,
        ]);

        return back()->with('status', 'Contact screened');
    }
}
