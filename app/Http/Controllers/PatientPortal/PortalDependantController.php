<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use App\Models\PortalDependant;
use App\Models\PatientPortalAccount;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PortalDependantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $accountId = session('patient_portal_user');

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

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'relationship' => 'required|string|in:spouse,child,parent,other',
            'is_primary' => 'boolean',
        ]);

        $dependant = PortalDependant::create([
            'portal_account_id' => $accountId,
            'patient_id' => $data['patient_id'],
            'relationship' => $data['relationship'],
            'is_primary' => $data['is_primary'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $dependant,
            'message' => 'Dependant added successfully'
        ], 201);
    }
}
