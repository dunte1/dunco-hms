<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabSpecimen;
use App\Models\LabRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LabSpecimenController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lab_request_id' => 'required|exists:lab_requests,id',
            'specimen_type' => 'required|in:blood,urine,stool,sputum,swab,tissue,csf,other',
        ]);

        $labRequest = LabRequest::findOrFail($data['lab_request_id']);

        $specimen = LabSpecimen::create([
            'specimen_number' => LabSpecimen::generateSpecimenNumber(),
            'lab_request_id' => $labRequest->id,
            'patient_id' => $labRequest->patient_id,
            'specimen_type' => $data['specimen_type'],
            'status' => 'collected',
            'collected_by' => $request->user()->id,
            'collected_at' => now(),
        ]);

        return redirect()->route('hms.laboratory.requests.show', $labRequest)
            ->with('status', "Specimen {$specimen->specimen_number} created successfully");
    }

    public function receive(Request $request, LabSpecimen $specimen): RedirectResponse
    {
        if ($specimen->status !== 'collected') {
            return back()->withErrors(['status' => 'Only collected specimens can be received.']);
        }

        $specimen->receive($request->user());

        return redirect()->route('hms.laboratory.requests.show', $specimen->lab_request_id)
            ->with('status', "Specimen {$specimen->specimen_number} received successfully");
    }

    public function reject(Request $request, LabSpecimen $specimen): RedirectResponse
    {
        if ($specimen->status !== 'collected' && $specimen->status !== 'received') {
            return back()->withErrors(['status' => 'Only collected or received specimens can be rejected.']);
        }

        $data = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $specimen->reject($request->user(), $data['rejection_reason']);

        return redirect()->route('hms.laboratory.requests.show', $specimen->lab_request_id)
            ->with('status', "Specimen {$specimen->specimen_number} rejected");
    }
}
