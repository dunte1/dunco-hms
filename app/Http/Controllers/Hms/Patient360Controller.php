<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\LabRequest;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\RadiologyRequest;
use App\Models\Triage;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Patient 360 — presentation layer over authorized records.
 *
 * Not an authorization bypass: every section is filtered by the
 * authenticated user's permissions. Roles without a permission simply
 * do not receive that section's data.
 */
class Patient360Controller extends Controller
{
    public function show(Request $request, Patient $patient): View
    {
        $user = $request->user();
        $sections = [];

        $sections['demographics'] = [
            'allowed' => $user->can('view patients'),
            'data' => $user->can('view patients') ? $patient->only([
                'id', 'patient_no', 'first_name', 'last_name', 'gender', 'dob',
                'email', 'phone', 'address', 'national_id', 'dha_cr_id',
                'facility_id', 'created_at',
            ]) : null,
        ];

        $sections['triage'] = [
            'allowed' => $user->can('view patients') || $user->can('manage triage records') || $user->can('view triage queue'),
            'data' => null,
        ];
        if ($sections['triage']['allowed']) {
            $sections['triage']['data'] = Triage::where('patient_id', $patient->id)
                ->latest()->limit(10)->get();
        }

        $sections['prescriptions'] = [
            'allowed' => $user->can('view prescriptions'),
            'data' => null,
        ];
        if ($sections['prescriptions']['allowed']) {
            $sections['prescriptions']['data'] = Prescription::with(['items.medicine', 'doctor'])
                ->where('patient_id', $patient->id)
                ->latest()->limit(10)->get();
        }

        $sections['laboratory'] = [
            'allowed' => $user->can('view test results') || $user->can('add test requests') || $user->can('print lab reports'),
            'data' => null,
        ];
        if ($sections['laboratory']['allowed']) {
            $sections['laboratory']['data'] = LabRequest::with(['items.labTest'])
                ->where('patient_id', $patient->id)
                ->latest()->limit(10)->get();
        }

        $sections['radiology'] = [
            'allowed' => $user->can('view test results') || $user->can('manage radiology worklist') || $user->can('approve radiology reports'),
            'data' => null,
        ];
        if ($sections['radiology']['allowed']) {
            $sections['radiology']['data'] = RadiologyRequest::with(['items.radiologyTest'])
                ->where('patient_id', $patient->id)
                ->latest()->limit(10)->get();
        }

        $sections['billing'] = [
            'allowed' => $user->can('view invoices') || $user->can('view billing') || $user->can('create invoices') || $user->can('view payment reports'),
            'data' => null,
        ];
        if ($sections['billing']['allowed'] && \Schema::hasTable('invoices')) {
            $invoiceCols = \Schema::getColumnListing('invoices');
            $amountCol = collect(['total', 'amount', 'grand_total', 'net_amount', 'total_amount'])
                ->first(fn ($c) => in_array($c, $invoiceCols, true));
            $select = array_values(array_intersect(['id', 'invoice_number', 'status', 'created_at', $amountCol], $invoiceCols));
            $sections['billing']['data'] = \App\Models\Invoice::where('patient_id', $patient->id)
                ->latest()->limit(10)->get($select ?: ['id']);
        }

        return view('hms.patients.360', [
            'patient' => $patient,
            'sections' => $sections,
        ]);
    }
}
