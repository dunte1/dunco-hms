<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\KhisReportSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KhisReportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'report_type' => 'required|in:moh_731,moh_711,moh_710,surveillance,other',
            'reporting_period_month' => 'required|integer|min:1|max:12',
            'reporting_period_year' => 'required|integer|min:2020|max:2100',
            'facility_code' => 'nullable|string|max:50',
            'total_patients' => 'nullable|integer|min:0',
            'total_visits' => 'nullable|integer|min:0',
            'report_data' => 'required|array',
        ]);

        $data['submitted_by'] = auth()->id();
        $data['status'] = 'draft';

        KhisReportSubmission::create($data);

        return back()->with('status', 'KHIS report created as draft');
    }

    public function submit(KhisReportSubmission $report): RedirectResponse
    {
        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('status', 'KHIS report submitted');
    }
}
