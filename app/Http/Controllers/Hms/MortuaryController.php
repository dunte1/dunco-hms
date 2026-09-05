<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\MortuaryRecord;
use App\Models\MortuaryRelease;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MortuaryController extends Controller
{
    public function index(): View
    {
        $records = MortuaryRecord::with('deathReport')->orderByDesc('received_at')->paginate(20);
        return view('hms.mortuary.index', compact('records'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'body_id' => 'required|string|unique:mortuary_records,body_id',
            'storage_location' => 'nullable|string',
            'cause_of_death' => 'nullable|string',
            'family_contact_name' => 'nullable|string',
            'family_contact_phone' => 'nullable|string',
            'identification_method' => 'nullable|string',
        ]);
        $data['received_at'] = now();
        $data['received_by'] = auth()->id();
        $data['status'] = 'stored';
        MortuaryRecord::create($data);
        return back()->with('status', 'Body registered in mortuary');
    }

    public function show(MortuaryRecord $record): View
    {
        $record->load('release');
        return view('hms.mortuary.show', compact('record'));
    }

    public function release(Request $request, MortuaryRecord $record): RedirectResponse
    {
        $data = $request->validate([
            'released_to_name' => 'required|string',
            'released_to_relation' => 'nullable|string',
            'released_to_id_number' => 'nullable|string',
            'released_to_phone' => 'nullable|string',
            'receiving_facility' => 'nullable|string',
            'transport_method' => 'nullable|string',
        ]);
        $data['mortuary_record_id'] = $record->id;
        $data['released_at'] = now();
        $data['released_by'] = auth()->id();
        MortuaryRelease::create($data);
        $record->update(['status' => 'released']);
        return back()->with('status', 'Body released from mortuary');
    }
}
