<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\FireInspectionRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FireInspectionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'inspection_date' => 'required|date',
            'inspector_name' => 'required|string|max:255',
            'equipment_checked' => 'required|integer|min:0',
            'equipment_passed' => 'required|integer|min:0|lte:equipment_checked',
            'deficiencies_found' => 'nullable|string',
            'corrective_actions' => 'nullable|string',
            'status' => 'required|in:passed,failed,conditional',
            'next_inspection_date' => 'nullable|date|after:inspection_date',
        ]);

        FireInspectionRecord::create($data);

        return back()->with('status', 'Fire inspection record created.');
    }
}
