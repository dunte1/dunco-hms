<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\BloodDonation;
use App\Models\BloodDonor;
use App\Models\BloodScreeningResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodDonationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'donor_id' => 'required|exists:blood_donors,id',
            'blood_group_id' => 'required|exists:blood_groups,id',
            'volume_ml' => 'required|integer|min:100|max:1000',
            'donation_date' => 'required|date',
            'donation_type' => 'required|in:whole_blood,plasma,platelets,apheresis',
            'hemoglobin_g_dl' => 'nullable|numeric|min:10|max:20',
            'blood_pressure_sys' => 'nullable|integer|min:80|max:200',
            'blood_pressure_dia' => 'nullable|integer|min:40|max:130',
            'pulse_rate' => 'nullable|integer|min:50|max:150',
            'weight_kg' => 'nullable|numeric|min:30|max:200',
            'hiv_test' => 'required|in:negative,positive,inconclusive',
            'hepatitis_b_test' => 'required|in:negative,positive,inconclusive',
            'hepatitis_c_test' => 'required|in:negative,positive,inconclusive',
            'syphilis_test' => 'required|in:negative,positive,inconclusive',
            'malaria_test' => 'required|in:negative,positive,inconclusive',
            'blood_group_confirmation' => 'nullable|string',
            'screening_notes' => 'nullable|string',
        ]);

        $isEligible = !in_array($data['hiv_test'], ['positive', 'inconclusive'])
            && !in_array($data['hepatitis_b_test'], ['positive', 'inconclusive'])
            && !in_array($data['hepatitis_c_test'], ['positive', 'inconclusive'])
            && !in_array($data['syphilis_test'], ['positive', 'inconclusive'])
            && !in_array($data['malaria_test'], ['positive', 'inconclusive']);

        $donation = BloodDonation::create([
            'donor_id' => $data['donor_id'],
            'blood_group_id' => $data['blood_group_id'],
            'volume_ml' => $data['volume_ml'],
            'donation_date' => $data['donation_date'],
            'donation_type' => $data['donation_type'],
            'hemoglobin_g_dl' => $data['hemoglobin_g_dl'] ?? null,
            'blood_pressure_sys' => $data['blood_pressure_sys'] ?? null,
            'blood_pressure_dia' => $data['blood_pressure_dia'] ?? null,
            'pulse_rate' => $data['pulse_rate'] ?? null,
            'weight_kg' => $data['weight_kg'] ?? null,
            'status' => $isEligible ? 'completed' : 'ineligible',
            'collected_by' => Auth::id(),
        ]);

        BloodScreeningResult::create([
            'donation_id' => $donation->id,
            'hiv_test' => $data['hiv_test'],
            'hepatitis_b_test' => $data['hepatitis_b_test'],
            'hepatitis_c_test' => $data['hepatitis_c_test'],
            'syphilis_test' => $data['syphilis_test'],
            'malaria_test' => $data['malaria_test'],
            'blood_group_confirmation' => $data['blood_group_confirmation'] ?? null,
            'screened_by' => Auth::id(),
            'screened_at' => now(),
            'is_eligible' => $isEligible,
            'notes' => $data['screening_notes'] ?? null,
        ]);

        if ($isEligible) {
            BloodDonor::where('id', $data['donor_id'])->update(['last_donation_date' => $data['donation_date']]);

            // Auto-create a blood unit from eligible donation
            try {
                $inventory = \App\Models\BloodInventory::where('blood_group_id', $data['blood_group_id'])->first()
                    ?? \App\Models\BloodInventory::whereNull('blood_group_id')->first();

                \App\Models\BloodUnit::create([
                    'blood_inventory_id' => $inventory?->id,
                    'donation_id' => $donation->id,
                    'unit_number' => \App\Models\BloodUnit::generateUnitNumber(),
                    'blood_group_id' => $data['blood_group_id'],
                    'volume_ml' => $data['volume_ml'],
                    'expiry_date' => now()->addDays(35)->toDateString(),
                    'status' => 'available',
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Blood unit auto-create failed', [
                    'donation_id' => $donation->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('hms.bloodbank.index')->with('status', 'Blood donation recorded');
    }
}
