<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Patient;
use App\Models\PortalDependant;
use App\Models\PatientPortalAccount;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PortalDependantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $accountId = session('patient_portal_user');
        if (!$accountId) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $dependants = PortalDependant::with('patient')
            ->where('portal_account_id', $accountId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $dependants
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $accountId = session('patient_portal_user');
        if (!$accountId) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $account = PatientPortalAccount::with('patient')->find($accountId);
        if (!$account || !$account->is_active) {
            return response()->json(['success' => false, 'message' => 'Account not found or inactive'], 401);
        }

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'relationship' => 'required|string|in:spouse,child,parent,other',
            'is_primary' => 'boolean',
        ]);

        $patient = Patient::find($data['patient_id']);

        // Own-records rule: only link own patient record or patients matching verified contact details.
        $isOwn = $account->patient_id === $patient->id;
        $contactMatch = filled($account->email)
            && (($patient->email && strcasecmp((string) $patient->email, (string) $account->email) === 0)
                || ($patient->phone && $account->phone && $patient->phone === $account->phone));

        if (!$isOwn && !$contactMatch) {
            AuditLog::log(
                'portal',
                $accountId,
                'portal.dependant.denied',
                'Patient',
                $patient->id,
                null,
                ['portal_account_id' => $accountId, 'patient_id' => $patient->id],
                'Portal dependant link denied: contact mismatch'
            );

            return response()->json([
                'success' => false,
                'message' => 'You can only link patients that match your verified contact details.'
            ], 403);
        }

        $exists = PortalDependant::where('portal_account_id', $accountId)
            ->where('patient_id', $patient->id)
            ->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Dependant already linked.'
            ], 422);
        }

        $dependant = PortalDependant::create([
            'portal_account_id' => $accountId,
            'patient_id' => $patient->id,
            'relationship' => $data['relationship'],
            'is_primary' => $data['is_primary'] ?? false,
        ]);

        AuditLog::log(
            'portal',
            $accountId,
            'portal.dependant.linked',
            'Patient',
            $patient->id,
            null,
            ['relationship' => $data['relationship']],
            'Portal dependant linked'
        );

        return response()->json([
            'success' => true,
            'data' => $dependant,
            'message' => 'Dependant added successfully'
        ], 201);
    }
}
