<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ImagingReportVersion;
use App\Models\RadiologyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ImagingReportController extends Controller
{
    public function store(Request $request, RadiologyRequest $radiologyRequest): RedirectResponse
    {
        $validated = $request->validate([
            'findings' => 'required|string',
            'impression' => 'required|string',
        ]);

        $lastVersion = ImagingReportVersion::where('radiology_request_id', $radiologyRequest->id)
            ->max('version_number');

        ImagingReportVersion::create([
            'radiology_request_id' => $radiologyRequest->id,
            'version_number' => ($lastVersion ?? 0) + 1,
            'findings' => $validated['findings'],
            'impression' => $validated['impression'],
            'created_by' => auth()->id(),
            'status' => 'draft',
            'created_at' => now(),
        ]);

        return redirect()->route('hms.radiology.requests.show', $radiologyRequest)
            ->with('success', 'Report version created as draft.');
    }

    public function approve(Request $request, RadiologyRequest $radiologyRequest): RedirectResponse
    {
        $validated = $request->validate([
            'version_number' => 'required|integer',
        ]);

        $reportVersion = ImagingReportVersion::where('radiology_request_id', $radiologyRequest->id)
            ->where('version_number', $validated['version_number'])
            ->where('status', 'draft')
            ->firstOrFail();

        // Demote any previously final report to 'amended'
        ImagingReportVersion::where('radiology_request_id', $radiologyRequest->id)
            ->where('status', 'final')
            ->update(['status' => 'amended']);

        $reportVersion->update(['status' => 'final']);

        $radiologyRequest->update([
            'status' => 'completed',
            'findings' => $reportVersion->findings,
            'impression' => $reportVersion->impression,
        ]);

        return redirect()->route('hms.radiology.requests.show', $radiologyRequest)
            ->with('success', 'Report approved and finalized.');
    }

    public function amend(Request $request, RadiologyRequest $radiologyRequest): RedirectResponse
    {
        $validated = $request->validate([
            'findings' => 'required|string',
            'impression' => 'required|string',
        ]);

        $lastVersion = ImagingReportVersion::where('radiology_request_id', $radiologyRequest->id)
            ->max('version_number');

        // Mark any previously final report as amended
        ImagingReportVersion::where('radiology_request_id', $radiologyRequest->id)
            ->where('status', 'final')
            ->update(['status' => 'amended']);

        ImagingReportVersion::create([
            'radiology_request_id' => $radiologyRequest->id,
            'version_number' => ($lastVersion ?? 0) + 1,
            'findings' => $validated['findings'],
            'impression' => $validated['impression'],
            'created_by' => auth()->id(),
            'status' => 'draft',
            'created_at' => now(),
        ]);

        return redirect()->route('hms.radiology.requests.show', $radiologyRequest)
            ->with('success', 'Amended report version created as draft.');
    }
}
