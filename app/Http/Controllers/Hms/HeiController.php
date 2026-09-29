<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\HeiRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HeiController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'newborn_id' => 'required|exists:patients,id',
            'mother_patient_id' => 'required|exists:patients,id',
            'mother_art_number' => 'nullable|string',
            'birth_date' => 'required|date',
            'prophylaxis_given' => 'boolean',
            'cotrimoxazole_start' => 'nullable|date',
        ]);

        $data['final_status'] = 'pending';
        $data['status'] = 'active';

        HeiRecord::create($data);

        return redirect()->back()->with('success', 'HEI record created successfully!');
    }

    public function recordPcr(Request $request, HeiRecord $hei): RedirectResponse
    {
        $data = $request->validate([
            'pcr_round' => 'required|in:1,2,6',
            'pcr_result' => 'required|in:positive,negative,pending',
            'pcr_date' => 'required|date',
        ]);

        $field = "pcr_{$data['pcr_round']}_result";
        $dateField = "pcr_{$data['pcr_round']}_date";

        $hei->update([
            $field => $data['pcr_result'],
            $dateField => $data['pcr_date'],
        ]);

        $this->updateFinalStatus($hei);

        return redirect()->back()->with('success', "PCR round {$data['pcr_round']} result recorded successfully!");
    }

    private function updateFinalStatus(HeiRecord $hei): void
    {
        $results = [$hei->pcr_1_result, $hei->pcr_2_result, $hei->pcr_6_result];
        $validResults = array_filter($results);

        if (empty($validResults)) {
            return;
        }

        if (in_array('positive', $validResults)) {
            $hei->update(['final_status' => 'exposed_infected']);
        } elseif (in_array('pending', $validResults)) {
            $hei->update(['final_status' => 'pending']);
        } else {
            $hei->update(['final_status' => 'exposed_uninfected']);
        }
    }
}
